<x-app-layout>
    <x-slot name="title">Edit User - {{ $user->name }}</x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header Card -->
        <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-900 dark:border-gray-800">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.users.index') }}" class="p-2 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-xl transition duration-150 dark:hover:bg-gray-800 dark:hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">Edit User</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Ubah profil akun, role akses, atau kelola wilayah tugas SLS.</p>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                <span class="font-medium">Berhasil!</span> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                <span class="font-medium">Gagal!</span> {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Edit Profile & Role Form (Left Column) -->
            <div class="lg:col-span-1 space-y-6">
                <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-900 dark:border-gray-800" x-data="{ role: '{{ old('role', $user->role) }}' }">
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-4 uppercase tracking-wider">Profil Akun</h2>
                    
                    <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <!-- Nama -->
                        <div>
                            <label for="name" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Nama Lengkap</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="w-full px-3 py-2 text-sm bg-gray-50 border border-gray-300 rounded-xl focus:ring-bps-500 focus:border-bps-500 dark:bg-gray-850 dark:border-gray-700 dark:text-white" required>
                            @error('name')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="w-full px-3 py-2 text-sm bg-gray-50 border border-gray-300 rounded-xl focus:ring-bps-500 focus:border-bps-500 dark:bg-gray-850 dark:border-gray-700 dark:text-white" required>
                            @error('email')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password (Optional) -->
                        <div>
                            <label for="password" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Password Baru (Opsional)</label>
                            <input type="password" name="password" id="password" placeholder="Kosongkan jika tidak diubah" class="w-full px-3 py-2 text-sm bg-gray-50 border border-gray-300 rounded-xl focus:ring-bps-500 focus:border-bps-500 dark:bg-gray-850 dark:border-gray-700 dark:text-white">
                            @error('password')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Role -->
                        <div>
                            <label for="role" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Role Akses</label>
                            <select name="role" id="role" x-model="role" class="w-full px-3 py-2 text-sm bg-gray-50 border border-gray-300 rounded-xl focus:ring-bps-500 focus:border-bps-500 dark:bg-gray-850 dark:border-gray-700 dark:text-white" required>
                                <option value="pcl">PCL (Petugas Lapangan / PPL)</option>
                                <option value="pml">PML (Pemeriksa / Supervisor)</option>
                                <option value="admin">Admin</option>
                                <option value="provinsi">Provinsi (Hanya View Dashboard)</option>
                            </select>
                            @error('role')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- ID Petugas (Visible if PCL/PML) -->
                        <div x-show="role === 'pcl' || role === 'pml'" x-cloak class="p-3 bg-orange-50/50 dark:bg-gray-800/30 border border-orange-100 dark:border-gray-800 rounded-xl space-y-3">
                            <div>
                                <label for="id_petugas" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">ID Petugas</label>
                                @php
                                    $currentId = $user->role === 'pcl' ? $user->pcl?->id : ($user->role === 'pml' ? $user->pml?->id : '');
                                @endphp
                                <input type="number" name="id_petugas" id="id_petugas" value="{{ old('id_petugas', $currentId) }}" class="w-full px-3 py-2 text-sm bg-white border border-gray-300 rounded-xl focus:ring-bps-500 focus:border-bps-500 dark:bg-gray-850 dark:border-gray-700 dark:text-white" :required="role === 'pcl' || role === 'pml'">
                                @error('id_petugas')
                                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- PML Supervisor Dropdown (Visible if PCL) -->
                            <div x-show="role === 'pcl'" x-cloak>
                                <label for="pml_id" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">PML Pengawas / Supervisor</label>
                                @php
                                    $currentPmlId = \App\Models\Assignment::where('pcl_id', $user->pcl?->id)->value('pml_id') ?? ($pmls->first()?->id);
                                @endphp
                                <select name="pml_id" id="pml_id" class="w-full px-3 py-2 text-sm bg-white border border-gray-300 rounded-xl focus:ring-bps-500 focus:border-bps-500 dark:bg-gray-850 dark:border-gray-700 dark:text-white" :required="role === 'pcl'">
                                    <option value="">Pilih PML Pengawas...</option>
                                    @foreach($pmls as $pmlOption)
                                        <option value="{{ $pmlOption->id }}" {{ old('pml_id', $currentPmlId) == $pmlOption->id ? 'selected' : '' }}>{{ $pmlOption->nama }} (ID: {{ $pmlOption->id }})</option>
                                    @endforeach
                                </select>
                                @error('pml_id')
                                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100 dark:border-gray-800">
                            <button type="submit" class="w-full px-4 py-2 text-sm font-semibold text-white bg-bps-600 hover:bg-bps-700 rounded-xl focus:ring-4 focus:ring-bps-300 dark:bg-bps-500 dark:hover:bg-bps-600 transition duration-150">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- SLS Assignment Management (Right Column) -->
            <div class="lg:col-span-2 space-y-6">
                @if($user->role === 'pcl')
                    <!-- Add Assignment Section -->
                    <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-900 dark:border-gray-800">
                        <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-4 uppercase tracking-wider">Tambah Penugasan SLS</h2>
                        
                        <form method="POST" action="{{ route('admin.users.assign-sls', $user->id) }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            @csrf
                            <div class="md:col-span-2">
                                <label for="idsubsls_input" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-2">ID SLS / SubSLS</label>
                                <input list="subsls_options" name="idsubsls" id="idsubsls_input" placeholder="Ketik/cari kode SubSLS..." class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-300 rounded-xl focus:ring-bps-500 focus:border-bps-500 dark:bg-gray-850 dark:border-gray-700 dark:text-white" required>
                                <datalist id="subsls_options">
                                    @foreach($subslsList as $subsls)
                                        <option value="{{ $subsls->idsubsls }}">Kec. {{ $subsls->sls?->village?->district?->nmkec ?? 'Tidak Diketahui' }} - Des. {{ $subsls->sls?->village?->nmdesa ?? 'Tidak Diketahui' }} - {{ $subsls->sls?->nmsls ?? 'Tidak Diketahui' }} (ID: {{ $subsls->idsubsls }})</option>
                                    @endforeach
                                </datalist>
                            </div>
                            <div>
                                <label for="target_usaha" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-2">Target Usaha</label>
                                <input type="number" name="target_usaha" id="target_usaha" value="0" min="0" class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-300 rounded-xl focus:ring-bps-500 focus:border-bps-500 dark:bg-gray-850 dark:border-gray-700 dark:text-white" required>
                            </div>
                            <div class="md:col-span-3 pt-2">
                                <button type="submit" class="w-full px-4 py-2.5 text-sm font-semibold text-white bg-bps-600 hover:bg-bps-700 rounded-xl transition duration-150">
                                    Tugaskan SLS
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Current Assignments List -->
                    <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-900 dark:border-gray-800">
                        <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-4 uppercase tracking-wider">Daftar SLS Saat Ini ({{ $user->pcl?->assignments->count() ?? 0 }})</h2>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-800 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-4 py-3">ID SubSLS</th>
                                        <th scope="col" class="px-4 py-3">Kecamatan</th>
                                        <th scope="col" class="px-4 py-3">Desa</th>
                                        <th scope="col" class="px-4 py-3">Nama SLS</th>
                                        <th scope="col" class="px-4 py-3 text-right">Target</th>
                                        <th scope="col" class="px-4 py-3 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-150 dark:divide-gray-800">
                                    @forelse($user->pcl?->assignments ?? [] as $assignmentItem)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-850 transition duration-150">
                                            <td class="px-4 py-3 font-mono text-xs">{{ $assignmentItem->idsubsls }}</td>
                                            <td class="px-4 py-3 text-xs">{{ $assignmentItem->subsls?->sls?->village?->district?->nmkec ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs">{{ $assignmentItem->subsls?->sls?->village?->nmdesa ?? '-' }}</td>
                                            <td class="px-4 py-3 text-xs">{{ $assignmentItem->subsls?->sls?->nmsls ?? '-' }}</td>
                                            <td class="px-4 py-3 text-right font-bold text-gray-800 dark:text-gray-200">{{ number_format($assignmentItem->target_usaha) }}</td>
                                            <td class="px-4 py-3 text-center">
                                                <form method="POST" action="{{ route('admin.users.remove-sls', ['user' => $user->id, 'assignment' => $assignmentItem->id]) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus penugasan SLS ini?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-xs font-semibold">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-4 py-6 text-center text-gray-400 dark:text-gray-500">
                                                Belum ada penugasan wilayah SLS untuk PCL ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <!-- Non-PCL Notice -->
                    <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-900 dark:border-gray-800 flex flex-col items-center justify-center text-center h-48">
                        <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-semibold">Pengelolaan tugas SLS hanya tersedia untuk pengguna dengan role PCL (Petugas/PPL).</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
