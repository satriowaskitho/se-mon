<style>
    @media (max-width: 767px) {
        .desktop-only-view {
            display: none !important;
        }
        .mobile-only-view {
            display: block !important;
        }
    }
    @media (min-width: 768px) {
        .desktop-only-view {
            display: block !important;
        }
        .mobile-only-view {
            display: none !important;
        }
    }
</style>

<!-- Users Table Card (Desktop) -->
<div class="desktop-only-view bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-900 dark:border-gray-800 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-800 dark:text-gray-400 border-b border-gray-200 dark:border-gray-800">
                <tr>
                    <th scope="col" class="px-6 py-4">Nama</th>
                    <th scope="col" class="px-6 py-4">Email</th>
                    <th scope="col" class="px-6 py-4">Role</th>
                    <th scope="col" class="px-6 py-4">ID Petugas</th>
                    <th scope="col" class="px-6 py-4">PML Pengawas</th>
                    <th scope="col" class="px-6 py-4">SLS Ditugaskan</th>
                    <th scope="col" class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                @forelse($users as $userItem)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-855 transition duration-150">
                        <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                            {{ $userItem->name }}
                            @if(Auth::id() === $userItem->id)
                                <span class="ml-1.5 px-2 py-0.5 text-[10px] font-bold text-bps-600 bg-bps-50 rounded-full uppercase dark:bg-bps-950/40 dark:text-bps-400">Anda</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">{{ $userItem->email }}</td>
                        <td class="px-6 py-4">
                            @if($userItem->role === 'admin')
                                <span class="px-2.5 py-1 text-xs font-semibold text-indigo-800 bg-indigo-50 rounded-lg dark:bg-indigo-950/40 dark:text-indigo-400">Admin</span>
                            @elseif($userItem->role === 'pml')
                                <span class="px-2.5 py-1 text-xs font-semibold text-emerald-800 bg-emerald-50 rounded-lg dark:bg-emerald-950/40 dark:text-emerald-400">PML</span>
                            @elseif($userItem->role === 'pcl')
                                <span class="px-2.5 py-1 text-xs font-semibold text-orange-800 bg-orange-50 rounded-lg dark:bg-orange-950/40 dark:text-orange-400">PCL / PPL</span>
                            @elseif($userItem->role === 'provinsi')
                                <span class="px-2.5 py-1 text-xs font-semibold text-purple-800 bg-purple-50 rounded-lg dark:bg-purple-950/40 dark:text-purple-400">Provinsi</span>
                            @else
                                <span class="px-2.5 py-1 text-xs font-semibold text-gray-800 bg-gray-50 rounded-lg dark:bg-gray-800 dark:text-gray-400">{{ $userItem->role }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-mono text-xs">
                            @if($userItem->role === 'pcl')
                                {{ $userItem->pcl?->id ?? '-' }}
                            @elseif($userItem->role === 'pml')
                                {{ $userItem->pml?->id ?? '-' }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs">
                            @if($userItem->role === 'pcl')
                                @php
                                    $pmlName = \App\Models\Assignment::where('pcl_id', $userItem->pcl?->id)->with('pml')->first()?->pml?->nama;
                                @endphp
                                {{ $pmlName ?? 'Belum Ditentukan' }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs">
                            @if($userItem->role === 'pcl')
                                @php
                                    $slsCount = $userItem->pcl?->assignments()->count() ?? 0;
                                @endphp
                                <span class="font-bold text-gray-850 dark:text-gray-200">{{ $slsCount }} SLS</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center flex items-center justify-center gap-2.5">
                            <a href="{{ route('admin.users.edit', $userItem->id) }}" class="text-bps-600 hover:text-bps-800 dark:text-bps-400 dark:hover:text-bps-300 text-sm font-semibold transition duration-150">
                                Edit
                            </a>
                            @if(Auth::id() !== $userItem->id)
                                <span class="text-gray-300 dark:text-gray-700">|</span>
                                <form method="POST" action="{{ route('admin.users.destroy', $userItem->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini? Peringatan: Menghapus PPL/PML akan menghapus seluruh data penugasan SLS dan laporan harian yang terkait.');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-sm font-semibold transition duration-150">
                                        Hapus
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-400 dark:text-gray-500">
                            Tidak ada pengguna ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Desktop -->
    @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/50">
            {{ $users->links() }}
        </div>
    @endif
</div>

<!-- Users Card Grid (Mobile View) -->
<div class="mobile-only-view space-y-4">
    @forelse($users as $userItem)
        <div class="p-5 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-900 dark:border-gray-800 space-y-3">
            <div class="flex items-center justify-between">
                <div class="font-semibold text-gray-900 dark:text-white text-base">
                    {{ $userItem->name }}
                    @if(Auth::id() === $userItem->id)
                        <span class="ml-1.5 px-2 py-0.5 text-[10px] font-bold text-bps-600 bg-bps-50 rounded-full uppercase dark:bg-bps-950/40 dark:text-bps-400">Anda</span>
                    @endif
                </div>
                <div>
                    @if($userItem->role === 'admin')
                        <span class="px-2.5 py-1 text-xs font-semibold text-indigo-800 bg-indigo-50 rounded-lg dark:bg-indigo-950/40 dark:text-indigo-400">Admin</span>
                    @elseif($userItem->role === 'pml')
                        <span class="px-2.5 py-1 text-xs font-semibold text-emerald-800 bg-emerald-50 rounded-lg dark:bg-emerald-950/40 dark:text-emerald-400">PML</span>
                    @elseif($userItem->role === 'pcl')
                        <span class="px-2.5 py-1 text-xs font-semibold text-orange-800 bg-orange-50 rounded-lg dark:bg-orange-950/40 dark:text-orange-400">PCL / PPL</span>
                    @elseif($userItem->role === 'provinsi')
                        <span class="px-2.5 py-1 text-xs font-semibold text-purple-800 bg-purple-50 rounded-lg dark:bg-purple-950/40 dark:text-purple-400">Provinsi</span>
                    @endif
                </div>
            </div>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                <span class="font-semibold text-gray-700 dark:text-gray-300">Email:</span> {{ $userItem->email }}
            </div>
            
            @if(in_array($userItem->role, ['pcl', 'pml']))
                <div class="text-sm text-gray-500 dark:text-gray-400 flex justify-between">
                    <div>
                        <span class="font-semibold text-gray-700 dark:text-gray-300">ID Petugas:</span>
                        <span class="font-mono">{{ $userItem->role === 'pcl' ? ($userItem->pcl?->id ?? '-') : ($userItem->pml?->id ?? '-') }}</span>
                    </div>
                    @if($userItem->role === 'pcl')
                        <div>
                            <span class="font-semibold text-gray-700 dark:text-gray-300">SLS:</span>
                            {{ $userItem->pcl?->assignments()->count() ?? 0 }} SLS
                        </div>
                    @endif
                </div>
                @if($userItem->role === 'pcl')
                    @php
                        $pmlName = \App\Models\Assignment::where('pcl_id', $userItem->pcl?->id)->with('pml')->first()?->pml?->nama;
                    @endphp
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <span class="font-semibold text-gray-700 dark:text-gray-300">PML Pengawas:</span> {{ $pmlName ?? 'Belum Ditentukan' }}
                    </div>
                @endif
            @endif

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100 dark:border-gray-800 text-sm">
                <a href="{{ route('admin.users.edit', $userItem->id) }}" class="text-bps-600 hover:text-bps-800 dark:text-bps-400 dark:hover:text-bps-300 font-semibold transition duration-150">
                    Edit
                </a>
                @if(Auth::id() !== $userItem->id)
                    <span class="text-gray-300 dark:text-gray-700">|</span>
                    <form method="POST" action="{{ route('admin.users.destroy', $userItem->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini? Peringatan: Menghapus PPL/PML akan menghapus seluruh data penugasan SLS dan laporan harian yang terkait.');" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 font-semibold transition duration-150">
                            Hapus
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-900 dark:border-gray-800 text-center text-gray-400 dark:text-gray-500">
            Tidak ada pengguna ditemukan.
        </div>
    @endforelse

    <!-- Pagination Mobile -->
    @if($users->hasPages())
        <div class="p-4 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-900 dark:border-gray-800 flex justify-center">
            {{ $users->links() }}
        </div>
    @endif
</div>
