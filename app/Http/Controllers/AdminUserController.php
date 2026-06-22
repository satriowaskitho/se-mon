<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pcl;
use App\Models\Pml;
use App\Models\SubSls;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $role = $request->input('role');

        $query = User::query()->with(['pcl', 'pml']);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (!empty($role)) {
            $query->where('role', $role);
        }

        $users = $query->orderBy('name')->paginate(15)->withQueryString();

        if ($request->ajax()) {
            return view('admin.users.list', compact('users', 'search', 'role'));
        }

        return view('admin.users.index', compact('users', 'search', 'role'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $pmls = Pml::orderBy('nama')->get();
        return view('admin.users.create', compact('pmls'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'string', 'in:admin,pml,pcl,provinsi'],
        ];

        // Conditional validation for PCL / PML
        if (in_array($request->input('role'), ['pcl', 'pml'])) {
            $rules['id_petugas'] = ['required', 'integer', 'min:1'];
            if ($request->input('role') === 'pcl') {
                $rules['pml_id'] = ['required', 'exists:pmls,id'];
            }
        }

        $validated = $request->validate($rules);

        // Check uniqueness of ID Petugas
        if ($request->input('role') === 'pcl') {
            if (Pcl::where('id', $request->input('id_petugas'))->exists()) {
                return back()->withErrors(['id_petugas' => 'ID Petugas PCL sudah terdaftar.'])->withInput();
            }
        } elseif ($request->input('role') === 'pml') {
            if (Pml::where('id', $request->input('id_petugas'))->exists()) {
                return back()->withErrors(['id_petugas' => 'ID Petugas PML sudah terdaftar.'])->withInput();
            }
        }

        DB::transaction(function () use ($validated, $request) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
            ]);

            if ($validated['role'] === 'pml') {
                Pml::create([
                    'id' => $validated['id_petugas'],
                    'nama' => $validated['name'],
                    'user_id' => $user->id,
                ]);
            } elseif ($validated['role'] === 'pcl') {
                Pcl::create([
                    'id' => $validated['id_petugas'],
                    'nama' => $validated['name'],
                    'user_id' => $user->id,
                ]);
            }
        });

        // Clear cache
        Cache::forget('landing_stats');
        Cache::forget('kabupaten_stats');
        Cache::forget('map_progress');

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $pmls = Pml::orderBy('nama')->get();
        $user->load(['pcl.assignments.subsls.sls.village.district', 'pml']);

        // Fetch all subsls for assignment option
        // To make it lightweight, we fetch idsubsls, plus related names
        $subslsList = SubSls::with('sls.village')
            ->orderBy('idsubsls')
            ->get();

        return view('admin.users.edit', compact('user', 'pmls', 'subslsList'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', 'string', 'in:admin,pml,pcl,provinsi'],
        ];

        // Conditional validation for PCL / PML
        if (in_array($request->input('role'), ['pcl', 'pml'])) {
            $rules['id_petugas'] = ['required', 'integer', 'min:1'];
            if ($request->input('role') === 'pcl') {
                $rules['pml_id'] = ['required', 'exists:pmls,id'];
            }
        }

        $validated = $request->validate($rules);

        // Check uniqueness of ID Petugas if changed
        if ($validated['role'] === 'pcl') {
            $existingPcl = Pcl::where('id', $validated['id_petugas'])->first();
            if ($existingPcl && (!$user->pcl || $existingPcl->id !== $user->pcl->id)) {
                return back()->withErrors(['id_petugas' => 'ID Petugas PCL sudah terdaftar oleh pengguna lain.'])->withInput();
            }
        } elseif ($validated['role'] === 'pml') {
            $existingPml = Pml::where('id', $validated['id_petugas'])->first();
            if ($existingPml && (!$user->pml || $existingPml->id !== $user->pml->id)) {
                return back()->withErrors(['id_petugas' => 'ID Petugas PML sudah terdaftar oleh pengguna lain.'])->withInput();
            }
        }

        DB::transaction(function () use ($validated, $user, $request) {
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
            ];

            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $user->update($userData);

            // Handle PCL and PML record updates
            if ($validated['role'] === 'pcl') {
                // Delete PML record if role changed from PML
                if ($user->pml) {
                    $user->pml->delete();
                }

                if ($user->pcl) {
                    // Update PCL ID and Name
                    $oldPclId = $user->pcl->id;
                    $user->pcl->update([
                        'id' => $validated['id_petugas'],
                        'nama' => $validated['name'],
                    ]);

                    // Update assignments PML supervisor
                    Assignment::where('pcl_id', $validated['id_petugas'])
                        ->update(['pml_id' => $validated['pml_id']]);
                } else {
                    Pcl::create([
                        'id' => $validated['id_petugas'],
                        'nama' => $validated['name'],
                        'user_id' => $user->id,
                    ]);
                }
            } elseif ($validated['role'] === 'pml') {
                // Delete PCL record if role changed from PCL
                if ($user->pcl) {
                    $user->pcl->delete();
                }

                if ($user->pml) {
                    $user->pml->update([
                        'id' => $validated['id_petugas'],
                        'nama' => $validated['name'],
                    ]);
                } else {
                    Pml::create([
                        'id' => $validated['id_petugas'],
                        'nama' => $validated['name'],
                        'user_id' => $user->id,
                    ]);
                }
            } else {
                // Clean up PCL/PML records if role is now admin/provinsi
                if ($user->pcl) {
                    $user->pcl->delete();
                }
                if ($user->pml) {
                    $user->pml->delete();
                }
            }
        });

        // Clear cache
        Cache::forget('landing_stats');
        Cache::forget('kabupaten_stats');
        Cache::forget('map_progress');

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        DB::transaction(function () use ($user) {
            if ($user->pcl) {
                $user->pcl->delete(); // Cascades delete to assignments & daily_reports
            }
            if ($user->pml) {
                $user->pml->delete(); // Cascades delete to assignments
            }
            $user->delete();
        });

        // Clear cache
        Cache::forget('landing_stats');
        Cache::forget('kabupaten_stats');
        Cache::forget('map_progress');

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }

    /**
     * Assign a SLS (SubSLS) to PCL.
     */
    public function assignSls(Request $request, User $user)
    {
        $request->validate([
            'idsubsls' => ['required', 'exists:subsls,idsubsls'],
            'target_usaha' => ['required', 'integer', 'min:0'],
        ]);

        $pcl = $user->pcl;
        if (!$pcl) {
            return back()->with('error', 'User ini bukan seorang PCL.');
        }

        // Determine PML supervisor
        // Find existing pml supervisor for this PCL from other assignments, or get first PML
        $pmlId = Assignment::where('pcl_id', $pcl->id)->value('pml_id');
        if (!$pmlId) {
            $pmlId = Pml::value('id');
        }

        if (!$pmlId) {
            return back()->with('error', 'Tidak ada supervisor PML yang tersedia di database. Buat PML terlebih dahulu.');
        }

        // Check if already assigned
        $assignment = Assignment::where('idsubsls', $request->input('idsubsls'))->first();

        if ($assignment) {
            // Reassign
            $assignment->update([
                'pcl_id' => $pcl->id,
                'pml_id' => $pmlId,
                'target_usaha' => $request->input('target_usaha'),
            ]);
        } else {
            // Create new
            Assignment::create([
                'idsubsls' => $request->input('idsubsls'),
                'pcl_id' => $pcl->id,
                'pml_id' => $pmlId,
                'target_usaha' => $request->input('target_usaha'),
            ]);
        }

        // Clear cache
        Cache::forget('landing_stats');
        Cache::forget('kabupaten_stats');
        Cache::forget('map_progress');

        return back()->with('success', 'Penugasan SLS berhasil ditambahkan.');
    }

    /**
     * Remove a SLS assignment.
     */
    public function removeSls(User $user, Assignment $assignment)
    {
        $assignment->delete();

        // Clear cache
        Cache::forget('landing_stats');
        Cache::forget('kabupaten_stats');
        Cache::forget('map_progress');

        return back()->with('success', 'Penugasan SLS berhasil dihapus.');
    }
}
