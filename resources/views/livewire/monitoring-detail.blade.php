<div class="space-y-6" x-data="{ showFilters: true }">
    <!-- Header Title Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-gray-900 dark:text-white flex items-center gap-2">
                <svg class="w-6 h-6 text-bps-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                Detail Pemantauan Sensus Ekonomi 2026
            </h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Halaman operasional khusus untuk detail wilayah tugas SubSLS, pencarian data petugas, status progres kemajuan, dan ekspor laporan.</p>
        </div>
    </div>

    <!-- Filters Panel -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-900 dark:border-gray-800">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                Filter Pencarian & Kriteria
            </h3>
            <button @click="showFilters = !showFilters" class="text-xs font-semibold text-bps-600 hover:text-bps-700 dark:text-bps-400 dark:hover:text-bps-500 flex items-center">
                <span x-show="showFilters">Sembunyikan Filter</span>
                <span x-show="!showFilters">Tampilkan Filter</span>
            </button>
        </div>

        <div class="p-6 space-y-4" x-show="showFilters" x-transition>
            <div class="grid grid-cols-1 sm:grid-cols-3 md:grid-cols-6 gap-4">
                <!-- Search Input -->
                <div class="sm:col-span-2">
                    <label class="block mb-1.5 text-xs font-semibold text-gray-500 dark:text-gray-400">Pencarian Kata Kunci</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" wire:model.live.debounce.300ms="search" class="w-full text-sm bg-gray-50 border border-gray-300 rounded-xl ps-10 p-2.5 dark:bg-gray-800 dark:border-gray-750 dark:text-white focus:ring-bps-500 focus:border-bps-500" placeholder="ID SubSLS, SLS, Desa, PCL, atau PML..." />
                    </div>
                </div>

                <!-- Kecamatan Filter -->
                <div>
                    <label class="block mb-1.5 text-xs font-semibold text-gray-500 dark:text-gray-400">Kecamatan</label>
                    <select wire:model.live="kecFilter" class="w-full text-sm bg-gray-50 border border-gray-300 rounded-xl p-2.5 dark:bg-gray-800 dark:border-gray-750 dark:text-white focus:ring-bps-500 focus:border-bps-500">
                        <option value="">Semua Kecamatan</option>
                        @foreach($districtsList as $dist)
                            <option value="{{ $dist->idkec }}">{{ $dist->nmkec }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Desa Filter -->
                <div>
                    <label class="block mb-1.5 text-xs font-semibold text-gray-500 dark:text-gray-400">Desa</label>
                    <select wire:model.live="desaFilter" class="w-full text-sm bg-gray-50 border border-gray-300 rounded-xl p-2.5 dark:bg-gray-800 dark:border-gray-750 dark:text-white focus:ring-bps-500 focus:border-bps-500" {{ !$kecFilter ? 'disabled' : '' }}>
                        <option value="">Semua Desa</option>
                        @foreach($villagesList as $v)
                            <option value="{{ $v->iddesa }}">{{ $v->nmdesa }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- PML Filter -->
                <div>
                    <label class="block mb-1.5 text-xs font-semibold text-gray-500 dark:text-gray-400">PML Supervisor</label>
                    <select wire:model.live="pmlFilter" class="w-full text-sm bg-gray-50 border border-gray-300 rounded-xl p-2.5 dark:bg-gray-800 dark:border-gray-750 dark:text-white focus:ring-bps-500 focus:border-bps-500" {{ auth()->user()->role === 'pml' ? 'disabled' : '' }}>
                        @if(auth()->user()->role !== 'pml')
                            <option value="">Semua PML</option>
                        @endif
                        @foreach($pmlList as $pm)
                            <option value="{{ $pm->id }}">{{ $pm->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- PCL Filter -->
                <div>
                    <label class="block mb-1.5 text-xs font-semibold text-gray-500 dark:text-gray-400">PCL Pencacah</label>
                    <select wire:model.live="pclFilter" class="w-full text-sm bg-gray-50 border border-gray-300 rounded-xl p-2.5 dark:bg-gray-800 dark:border-gray-750 dark:text-white focus:ring-bps-500 focus:border-bps-500">
                        <option value="">Semua PCL</option>
                        @foreach($pclList as $p)
                            <option value="{{ $p->id }}">{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Status Progress Filter -->
                <div>
                    <label class="block mb-1.5 text-xs font-semibold text-gray-500 dark:text-gray-400">Kategori Progres</label>
                    <select wire:model.live="statusFilter" class="w-full text-sm bg-gray-50 border border-gray-300 rounded-xl p-2.5 dark:bg-gray-800 dark:border-gray-750 dark:text-white focus:ring-bps-500 focus:border-bps-500 font-semibold">
                        <option value="">Semua Kategori</option>
                        <option value="perlu_perhatian" class="text-red-600 font-semibold">Perlu Perhatian (0% - 24%)</option>
                        <option value="rendah" class="text-orange-600 font-semibold">Rendah (25% - 49%)</option>
                        <option value="waspada" class="text-amber-600 font-semibold">Waspada (50% - 79%)</option>
                        <option value="baik" class="text-emerald-600 font-semibold">Baik (80%+)</option>
                    </select>
                </div>

                <div class="sm:col-span-2 grid grid-cols-1 gap-2.5 sm:flex sm:flex-wrap sm:items-end sm:justify-end sm:gap-3 pt-2">
                    <button wire:click="resetTable" class="text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 px-4 py-2.5 rounded-xl dark:bg-red-950/20 dark:text-red-400 dark:hover:bg-red-950/40 transition w-full sm:w-auto text-center">
                        Reset Semua Filter
                    </button>
                    <button wire:click="exportExcel" class="inline-flex items-center justify-center px-4 py-2.5 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded-xl dark:bg-emerald-950/20 dark:text-emerald-400 dark:hover:bg-emerald-950/40 transition w-full sm:w-auto">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Unduh Excel
                    </button>
                    <button wire:click="exportCsv" class="inline-flex items-center justify-center px-4 py-2.5 text-xs font-bold text-purple-700 bg-purple-50 hover:bg-purple-100 rounded-xl dark:bg-purple-950/20 dark:text-purple-400 dark:hover:bg-purple-950/40 transition w-full sm:w-auto">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Unduh CSV
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Monitoring Table Card -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-900 dark:border-gray-800 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-gray-50/50 dark:bg-gray-900/50">
            <div>
                <h3 class="text-base font-bold text-gray-900 dark:text-white">Rincian Monitoring Wilayah & Kinerja Petugas</h3>
                <p class="text-xs text-gray-400 mt-0.5">Menampilkan status pencacahan SubSLS secara real-time, diurutkan terkecil ke terbesar berdasarkan progres targets.</p>
            </div>
            <div class="flex items-center gap-1.5 text-xs text-gray-400">
                Menampilkan <span class="font-bold text-gray-700 dark:text-white">{{ $tableData->firstItem() ?? 0 }}-{{ $tableData->lastItem() ?? 0 }}</span> dari <span class="font-bold text-gray-700 dark:text-white">{{ $tableData->total() }}</span> SubSLS
            </div>
        </div>

        <div class="overflow-x-auto relative min-h-[300px]">
            <!-- Target-specific Skeleton Loader (Case C) -->
            <div wire:loading wire:target="search, kecFilter, desaFilter, pmlFilter, pclFilter, statusFilter, sortField, sortDirection, resetTable, gotoPage, previousPage, nextPage" class="absolute inset-0 bg-white/95 dark:bg-gray-900/95 backdrop-blur-[2px] z-20 flex flex-col p-6 space-y-4 animate-pulse">
                <span class="text-xs font-bold text-gray-400">Memproses filter...</span>
                <div class="space-y-3.5 mt-2">
                    <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded-lg w-full"></div>
                    @for($i = 0; $i < 6; $i++)
                        <div class="flex gap-4">
                            <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-1/12"></div>
                            <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-2/12"></div>
                            <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-2/12"></div>
                            <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-3/12"></div>
                            <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-2/12"></div>
                            <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-2/12"></div>
                        </div>
                    @endfor
                </div>
            </div>

            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-850 dark:text-gray-300 select-none">
                    <tr>
                        <!-- ID SubSLS -->
                        <th scope="col" class="px-6 py-3.5 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 transition" wire:click="sortBy('idsubsls')">
                            <div class="flex items-center gap-1.5">
                                ID SubSLS
                                @if($sortField === 'idsubsls')
                                    <span>{!! $sortDirection === 'asc' ? '&#9652;' : '&#9662;' !!}</span>
                                @endif
                            </div>
                        </th>
                        <!-- Kecamatan -->
                        <th scope="col" class="px-6 py-3.5 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 transition" wire:click="sortBy('kecamatan')">
                            <div class="flex items-center gap-1.5">
                                Kecamatan
                                @if($sortField === 'kecamatan')
                                    <span>{!! $sortDirection === 'asc' ? '&#9652;' : '&#9662;' !!}</span>
                                @endif
                            </div>
                        </th>
                        <!-- Desa -->
                        <th scope="col" class="px-6 py-3.5 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 transition" wire:click="sortBy('desa')">
                            <div class="flex items-center gap-1.5">
                                Desa
                                @if($sortField === 'desa')
                                    <span>{!! $sortDirection === 'asc' ? '&#9652;' : '&#9662;' !!}</span>
                                @endif
                            </div>
                        </th>
                        <!-- SLS -->
                        <th scope="col" class="px-6 py-3.5 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 transition" wire:click="sortBy('sls')">
                            <div class="flex items-center gap-1.5">
                                SLS
                                @if($sortField === 'sls')
                                    <span>{!! $sortDirection === 'asc' ? '&#9652;' : '&#9662;' !!}</span>
                                @endif
                            </div>
                        </th>
                        <!-- PCL -->
                        <th scope="col" class="px-6 py-3.5 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 transition" wire:click="sortBy('pcl')">
                            <div class="flex items-center gap-1.5">
                                PCL
                                @if($sortField === 'pcl')
                                    <span>{!! $sortDirection === 'asc' ? '&#9652;' : '&#9662;' !!}</span>
                                @endif
                            </div>
                        </th>
                        <!-- PML -->
                        <th scope="col" class="px-6 py-3.5 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 transition" wire:click="sortBy('pml')">
                            <div class="flex items-center gap-1.5">
                                PML
                                @if($sortField === 'pml')
                                    <span>{!! $sortDirection === 'asc' ? '&#9652;' : '&#9662;' !!}</span>
                                @endif
                            </div>
                        </th>
                        <!-- Target -->
                        <th scope="col" class="px-6 py-3.5 text-center cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 transition" wire:click="sortBy('target_usaha')">
                            <div class="flex items-center justify-center gap-1.5">
                                Target Usaha
                                @if($sortField === 'target_usaha')
                                    <span>{!! $sortDirection === 'asc' ? '&#9652;' : '&#9662;' !!}</span>
                                @endif
                            </div>
                        </th>
                        <!-- Realisasi Usaha -->
                        <th scope="col" class="px-6 py-3.5 text-center cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 transition" wire:click="sortBy('usaha_realisasi')">
                            <div class="flex items-center justify-center gap-1.5">
                                Realisasi Usaha
                                @if($sortField === 'usaha_realisasi')
                                    <span>{!! $sortDirection === 'asc' ? '&#9652;' : '&#9662;' !!}</span>
                                @endif
                            </div>
                        </th>
                        <!-- Realisasi Ruta -->
                        <th scope="col" class="px-6 py-3.5 text-center cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 transition" wire:click="sortBy('ruta_realisasi')">
                            <div class="flex items-center justify-center gap-1.5">
                                Realisasi Ruta
                                @if($sortField === 'ruta_realisasi')
                                    <span>{!! $sortDirection === 'asc' ? '&#9652;' : '&#9662;' !!}</span>
                                @endif
                            </div>
                        </th>
                        <!-- Progress -->
                        <th scope="col" class="px-6 py-3.5 text-center cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 transition" wire:click="sortBy('progress_pct')">
                            <div class="flex items-center justify-center gap-1.5">
                                Progres
                                @if($sortField === 'progress_pct')
                                    <span>{!! $sortDirection === 'asc' ? '&#9652;' : '&#9662;' !!}</span>
                                @endif
                            </div>
                        </th>
                        <!-- Last Activity -->
                        <th scope="col" class="px-6 py-3.5">Aktivitas Terakhir</th>
                        <!-- Status -->
                        <th scope="col" class="px-6 py-3.5 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse($tableData as $row)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition">
                            <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">{{ $row->idsubsls }}</td>
                            <td class="px-6 py-4 text-xs">{{ $row->subsls->sls->village->district->nmkec ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-xs">{{ $row->subsls->sls->village->nmdesa ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-xs truncate max-w-[120px]">{{ $row->subsls->sls->nmsls ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-xs font-semibold text-gray-800 dark:text-gray-200">{{ $row->pcl->nama ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-xs text-bps-600 dark:text-bps-400 font-semibold">{{ $row->pml->nama ?? 'N/A' }}</td>
                            <td class="px-6 py-4 font-bold text-center text-gray-900 dark:text-white">{{ number_format($row->target_usaha) }}</td>
                            <td class="px-6 py-4 font-bold text-center text-emerald-600 dark:text-emerald-400">{{ number_format($row->usaha_realisasi) }}</td>
                            <td class="px-6 py-4 font-bold text-center text-purple-600 dark:text-purple-400">{{ number_format($row->ruta_realisasi) }}</td>
                            
                            <!-- Progress Bar & Text -->
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-1.5 font-bold text-xs">
                                    <div class="w-12 bg-gray-200 rounded-full h-1.5 dark:bg-gray-700 overflow-hidden">
                                        <div class="h-1.5 rounded-full
                                            {{ $row->progress_pct < 50 ? 'bg-red-500' : '' }}
                                            {{ $row->progress_pct >= 50 && $row->progress_pct < 80 ? 'bg-amber-400' : '' }}
                                            {{ $row->progress_pct >= 80 ? 'bg-emerald-500' : '' }}"
                                             style="width: {{ min(100, $row->progress_pct) }}%"></div>
                                    </div>
                                    <span class="w-8 text-right">{{ round($row->progress_pct, 1) }}%</span>
                                </div>
                            </td>

                            <!-- Last Activity Tracking -->
                            <td class="px-6 py-4">
                                @if($row->last_report_date)
                                    @php
                                        $days = \Carbon\Carbon::parse($row->last_report_date)->startOfDay()->diffInDays(\Carbon\Carbon::now()->startOfDay());
                                    @endphp
                                    @if($days === 0)
                                        <span class="text-xs font-semibold text-gray-900 dark:text-white">Hari ini</span>
                                    @elseif($days === 1)
                                        <span class="text-xs font-semibold text-gray-900 dark:text-white">1 hari lalu</span>
                                    @else
                                        <span class="text-xs font-semibold text-gray-900 dark:text-white">{{ $days }} hari lalu</span>
                                    @endif
                                    <span class="block text-[9px] text-gray-400 mt-0.5">({{ \Carbon\Carbon::parse($row->last_report_date)->format('d-m-Y') }})</span>
                                @else
                                    <span class="text-xs text-gray-400 italic">Belum ada</span>
                                @endif
                            </td>

                            <!-- Status Badge -->
                            <td class="px-6 py-4 text-center align-middle min-w-[130px]">
                                @if($row->progress_pct < 25)
                                    <span class="px-3 py-1.5 text-[10px] md:text-[11px] font-bold rounded-full border uppercase inline-flex items-center justify-center whitespace-nowrap leading-none shrink-0 bg-red-50 text-red-600 border-red-200 dark:bg-red-950/20 dark:text-red-400 dark:border-red-900">Perlu Perhatian</span>
                                @elseif($row->progress_pct < 50)
                                    <span class="px-3 py-1.5 text-[10px] md:text-[11px] font-bold rounded-full border uppercase inline-flex items-center justify-center whitespace-nowrap leading-none shrink-0 bg-orange-50 text-orange-600 border-orange-200 dark:bg-orange-950/20 dark:text-orange-400 dark:border-orange-900">Rendah</span>
                                @elseif($row->progress_pct < 80)
                                    <span class="px-3 py-1.5 text-[10px] md:text-[11px] font-bold rounded-full border uppercase inline-flex items-center justify-center whitespace-nowrap leading-none shrink-0 bg-amber-50 text-amber-600 border-amber-200 dark:bg-amber-950/20 dark:text-amber-400 dark:border-amber-900">Waspada</span>
                                @else
                                    <span class="px-3 py-1.5 text-[10px] md:text-[11px] font-bold rounded-full border uppercase inline-flex items-center justify-center whitespace-nowrap leading-none shrink-0 bg-emerald-50 text-emerald-600 border-emerald-200 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900">Baik</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="px-6 py-12 text-center text-gray-500 font-semibold bg-gray-50/20">
                                @if(!$hasOperationalData)
                                    <div class="flex flex-col items-center justify-center p-6 text-center">
                                        <svg class="w-10 h-10 text-orange-500 dark:text-orange-400 mb-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        <p class="text-xs font-bold text-gray-500 dark:text-gray-400">Belum ada progres pencacahan yang masuk.</p>
                                    </div>
                                @else
                                    <div class="flex flex-col items-center justify-center p-6 text-center">
                                        <svg class="w-10 h-10 text-gray-400 dark:text-gray-655 mb-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                        </svg>
                                        <p class="text-xs font-bold text-gray-500 dark:text-gray-400 mb-2.5">Tidak ada data yang sesuai filter.</p>
                                        <button wire:click="resetTable" class="px-3.5 py-1.5 bg-bps-600 hover:bg-bps-700 text-[10px] font-bold text-white rounded-lg shadow-sm transition">
                                            Reset Table
                                        </button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Monitoring Table Pagination -->
        @if($tableData->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/50">
                {{ $tableData->links() }}
            </div>
        @endif
    </div>
</div>
