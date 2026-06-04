<x-app-layout>
    <x-slot name="title">Riwayat Input</x-slot>

    <div class="space-y-6">
        <!-- Header Card -->
        <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-900 dark:border-gray-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-6 h-6 text-bps-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Riwayat Laporan Capaian Pencacahan
                </h1>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    @if($isPcl)
                        Menampilkan seluruh riwayat pengiriman capaian harian yang telah Anda input.
                    @elseif($isPml)
                        Menampilkan seluruh riwayat pengiriman capaian harian dari PCL di bawah pengawasan Anda.
                    @elseif($isAdmin)
                        Menampilkan seluruh riwayat pengiriman capaian harian dari seluruh PCL.
                    @endif
                </p>
            </div>
            @if($isPcl)
            <div>
                <a href="{{ route('daily-reports.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-bps-600 hover:bg-bps-700 focus:ring-4 focus:ring-bps-300 rounded-xl dark:bg-bps-500 dark:hover:bg-bps-600 transition duration-150">
                    <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    Input Laporan Baru
                </a>
            </div>
            @endif
        </div>

        <!-- History Table Card -->
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-900 dark:border-gray-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-850 dark:text-gray-300">
                        <tr>
                            <th scope="col" class="px-6 py-3.5">Tanggal Laporan</th>
                            @if($isPml || $isAdmin)
                                <th scope="col" class="px-6 py-3.5">Nama PCL</th>
                            @endif
                            <th scope="col" class="px-6 py-3.5">ID SubSLS</th>
                            <th scope="col" class="px-6 py-3.5">Kecamatan</th>
                            <th scope="col" class="px-6 py-3.5">Desa</th>
                            <th scope="col" class="px-6 py-3.5">SLS</th>
                            <th scope="col" class="px-6 py-3.5 text-center">Tambahan Usaha</th>
                            <th scope="col" class="px-6 py-3.5 text-center">Tambahan Ruta</th>
                            <th scope="col" class="px-6 py-3.5">Catatan</th>
                            <th scope="col" class="px-6 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @forelse($reports as $report)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">{{ $report->report_date->translatedFormat('d F Y') }}</td>
                                @if($isPml || $isAdmin)
                                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">{{ $report->assignment->pcl->nama ?? 'Unknown' }}</td>
                                @endif
                                <td class="px-6 py-4 font-medium">{{ $report->assignment->subsls->idsubsls }}</td>
                                <td class="px-6 py-4">{{ $report->assignment->subsls->sls->village->district->nmkec }}</td>
                                <td class="px-6 py-4">{{ $report->assignment->subsls->sls->village->nmdesa }}</td>
                                <td class="px-6 py-4">{{ $report->assignment->subsls->sls->nmsls }}</td>
                                <td class="px-6 py-4 font-extrabold text-center text-emerald-600 dark:text-emerald-400">+{{ number_format($report->usaha_today) }}</td>
                                <td class="px-6 py-4 font-extrabold text-center text-purple-600 dark:text-purple-400">+{{ number_format($report->ruta_today) }}</td>
                                <td class="px-6 py-4 max-w-xs truncate">{{ $report->notes ?? '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        @if($isPcl || $isAdmin)
                                            <!-- Edit Link -->
                                            @can('update', $report)
                                                <a href="{{ route('daily-reports.edit', $report) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-bps-700 bg-bps-50 hover:bg-bps-100 rounded-lg dark:bg-bps-950/30 dark:text-bps-400 dark:hover:bg-bps-950/60 transition duration-150">
                                                    Edit
                                                </a>
                                            @endcan
                                            <!-- Delete Form -->
                                            @can('delete', $report)
                                                <form method="POST" action="{{ route('daily-reports.destroy', $report) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-700 bg-red-50 hover:bg-red-100 rounded-lg dark:bg-red-950/20 dark:text-red-400 dark:hover:bg-red-950/40 transition duration-150">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @endcan
                                        @endif
                                        @if($isPml)
                                            <span class="text-xs text-gray-400 italic">Read-Only</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ ($isPml || $isAdmin) ? 10 : 9 }}" class="px-6 py-12 text-center text-gray-500">Belum ada riwayat pengiriman laporan capaian harian.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination Section -->
            @if($reports->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/50">
                    {{ $reports->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
