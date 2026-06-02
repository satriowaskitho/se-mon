<x-app-layout>
    <x-slot name="title">Dashboard PCL</x-slot>

    <div class="space-y-6">
        <!-- Welcome Card -->
        <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-900 dark:border-gray-800">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Selamat Datang, {{ Auth::user()->name }}!</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Anda masuk sebagai Petugas Pencacah Lapangan (PCL) Sensus Ekonomi 2026. Pantau capaian harian Anda di bawah ini.</p>
                </div>
                <div>
                    <a href="{{ route('daily-reports.create') }}" class="inline-flex items-center px-4 py-2.5 text-sm font-semibold text-white bg-bps-600 hover:bg-bps-700 focus:ring-4 focus:ring-bps-300 rounded-xl dark:bg-bps-500 dark:hover:bg-bps-600 transition duration-150">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Input Capaian Hari Ini
                    </a>
                </div>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Target Card -->
            <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-900 dark:border-gray-800 flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-gray-500 uppercase dark:text-gray-400">Total Target Usaha</span>
                    <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white mt-1">{{ number_format($stats['total_target']) }}</h3>
                </div>
                <div class="p-3 bg-bps-50 text-bps-600 rounded-xl dark:bg-bps-950/40 dark:text-bps-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
            </div>

            <!-- Realisasi Usaha Card -->
            <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-900 dark:border-gray-800 flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-gray-500 uppercase dark:text-gray-400">Realisasi Usaha</span>
                    <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white mt-1">{{ number_format($stats['total_realisasi_usaha']) }}</h3>
                </div>
                <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl dark:bg-emerald-950/40 dark:text-emerald-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 13.255A11.968 11.968 0 0112 21c-6.627 0-12-5.373-12-12 0-3.314 1.343-6.314 3.515-8.485L12 9l9 4.255z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 9L3.515.515"></path></svg>
                </div>
            </div>

            <!-- Realisasi Ruta Card -->
            <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-900 dark:border-gray-800 flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-gray-500 uppercase dark:text-gray-400">Realisasi Ruta</span>
                    <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white mt-1">{{ number_format($stats['total_realisasi_ruta']) }}</h3>
                </div>
                <div class="p-3 bg-purple-50 text-purple-600 rounded-xl dark:bg-purple-950/40 dark:text-purple-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                </div>
            </div>

            <!-- Progress % Card -->
            <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-900 dark:border-gray-800 flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-gray-500 uppercase dark:text-gray-400">Progres Capaian</span>
                    <div class="flex items-baseline gap-2 mt-1">
                        <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ $stats['percentage'] }}%</h3>
                    </div>
                </div>
                <div class="p-3 rounded-xl flex items-center justify-center
                    {{ $stats['progress_color'] === 'red' ? 'bg-red-50 text-red-600 dark:bg-red-950/40 dark:text-red-400' : '' }}
                    {{ $stats['progress_color'] === 'yellow' ? 'bg-amber-50 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400' : '' }}
                    {{ $stats['progress_color'] === 'green' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400' : '' }}">
                    <span class="font-extrabold text-sm px-2.5 py-1.5 rounded-lg border
                        {{ $stats['progress_color'] === 'red' ? 'border-red-200 dark:border-red-900' : '' }}
                        {{ $stats['progress_color'] === 'yellow' ? 'border-amber-200 dark:border-amber-900' : '' }}
                        {{ $stats['progress_color'] === 'green' ? 'border-emerald-200 dark:border-emerald-900' : '' }}">
                        {{ strtoupper($stats['progress_color']) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Areas of Duty -->
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-900 dark:border-gray-800 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Daftar Wilayah Tugas Anda (SubSLS)</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-850 dark:text-gray-300">
                        <tr>
                            <th scope="col" class="px-6 py-3.5">ID SubSLS</th>
                            <th scope="col" class="px-6 py-3.5">Kecamatan</th>
                            <th scope="col" class="px-6 py-3.5">Desa</th>
                            <th scope="col" class="px-6 py-3.5">SLS</th>
                            <th scope="col" class="px-6 py-3.5">PML Supervisor</th>
                            <th scope="col" class="px-6 py-3.5 text-center">Target Usaha</th>
                            <th scope="col" class="px-6 py-3.5 text-center">Realisasi Usaha</th>
                            <th scope="col" class="px-6 py-3.5 text-center">Realisasi Ruta</th>
                            <th scope="col" class="px-6 py-3.5 text-center">Progress (%)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @forelse($assignments as $assignment)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">{{ $assignment->subsls->idsubsls }}</td>
                                <td class="px-6 py-4">{{ $assignment->subsls->sls->village->district->nmkec }}</td>
                                <td class="px-6 py-4">{{ $assignment->subsls->sls->village->nmdesa }}</td>
                                <td class="px-6 py-4">{{ $assignment->subsls->sls->nmsls }}</td>
                                <td class="px-6 py-4 font-medium text-bps-600 dark:text-bps-400">{{ $assignment->pml->nama }}</td>
                                <td class="px-6 py-4 font-bold text-center text-gray-900 dark:text-white">{{ $assignment->target_usaha }}</td>
                                <td class="px-6 py-4 font-bold text-center text-gray-900 dark:text-white">{{ $assignment->usaha_realisasi }}</td>
                                <td class="px-6 py-4 font-bold text-center text-gray-900 dark:text-white">{{ $assignment->ruta_realisasi }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full font-bold text-xs
                                        {{ $assignment->progress_pct < 50 ? 'bg-red-50 text-red-600 dark:bg-red-950/40 dark:text-red-400' : '' }}
                                        {{ $assignment->progress_pct >= 50 && $assignment->progress_pct < 80 ? 'bg-amber-50 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400' : '' }}
                                        {{ $assignment->progress_pct >= 80 ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400' : '' }}">
                                        <span class="w-1.5 h-1.5 rounded-full
                                            {{ $assignment->progress_pct < 50 ? 'bg-red-500' : '' }}
                                            {{ $assignment->progress_pct >= 50 && $assignment->progress_pct < 80 ? 'bg-amber-500' : '' }}
                                            {{ $assignment->progress_pct >= 80 ? 'bg-emerald-500' : '' }}"></span>
                                        {{ round($assignment->progress_pct, 1) }}%
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-8 text-center text-gray-500">Belum ada wilayah tugas yang didelegasikan untuk Anda.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent History -->
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-900 dark:border-gray-800 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Riwayat Input Terakhir Anda</h3>
                <a href="{{ route('daily-reports.index') }}" class="text-sm font-semibold text-bps-600 hover:text-bps-700 dark:text-bps-400 dark:hover:text-bps-500">Lihat Semua Riwayat</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-850 dark:text-gray-300">
                        <tr>
                            <th scope="col" class="px-6 py-3.5">Tanggal</th>
                            <th scope="col" class="px-6 py-3.5">ID SubSLS</th>
                            <th scope="col" class="px-6 py-3.5">SLS</th>
                            <th scope="col" class="px-6 py-3.5 text-center">Tambahan Usaha</th>
                            <th scope="col" class="px-6 py-3.5 text-center">Tambahan Ruta</th>
                            <th scope="col" class="px-6 py-3.5">Catatan</th>
                            <th scope="col" class="px-6 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @forelse($recentReports as $report)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">{{ $report->report_date->format('d-m-Y') }}</td>
                                <td class="px-6 py-4">{{ $report->assignment->subsls->idsubsls }}</td>
                                <td class="px-6 py-4">{{ $report->assignment->subsls->sls->nmsls }}</td>
                                <td class="px-6 py-4 font-bold text-center text-emerald-600 dark:text-emerald-400">+{{ $report->usaha_today }}</td>
                                <td class="px-6 py-4 font-bold text-center text-purple-600 dark:text-purple-400">+{{ $report->ruta_today }}</td>
                                <td class="px-6 py-4 max-w-xs truncate">{{ $report->notes ?? '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('daily-reports.edit', $report) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-bps-700 bg-bps-50 hover:bg-bps-100 rounded-lg dark:bg-bps-950/30 dark:text-bps-400 dark:hover:bg-bps-950/60 transition duration-150">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">Anda belum menginput capaian harian pencacahan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
