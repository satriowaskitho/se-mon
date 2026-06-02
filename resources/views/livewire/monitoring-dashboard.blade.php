<div class="space-y-6" x-data="{ showFilters: true }">
    <!-- Top Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <!-- Target Card -->
        <div class="p-5 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-900 dark:border-gray-800 flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-gray-500 uppercase dark:text-gray-400 font-bold">Total Target Usaha</span>
                <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white mt-1">{{ number_format($stats['total_target']) }}</h3>
                <span class="text-[10px] text-gray-400">Semua Wilayah Tugas</span>
            </div>
            <div class="p-3.5 bg-bps-50 text-bps-600 rounded-xl dark:bg-bps-950/40 dark:text-bps-400">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
        </div>

        <!-- Realisasi Usaha Card -->
        <div class="p-5 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-900 dark:border-gray-800 flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-gray-500 uppercase dark:text-gray-400 font-bold">Total Realisasi Usaha</span>
                <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white mt-1">{{ number_format($stats['total_realisasi_usaha']) }}</h3>
                <span class="text-[10px] text-gray-400">Realisasi Lapangan</span>
            </div>
            <div class="p-3.5 bg-emerald-50 text-emerald-600 rounded-xl dark:bg-emerald-950/40 dark:text-emerald-400">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"></path></svg>
            </div>
        </div>

        <!-- Realisasi Ruta Card -->
        <div class="p-5 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-900 dark:border-gray-800 flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-gray-500 uppercase dark:text-gray-400 font-bold">Total Realisasi Ruta</span>
                <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white mt-1">{{ number_format($stats['total_realisasi_ruta']) }}</h3>
                <span class="text-[10px] text-gray-400">Rumah Tangga Tercacah</span>
            </div>
            <div class="p-3.5 bg-purple-50 text-purple-600 rounded-xl dark:bg-purple-950/40 dark:text-purple-400">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            </div>
        </div>

        <!-- Progress Percentage Card -->
        <div class="p-5 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-900 dark:border-gray-800 flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-gray-500 uppercase dark:text-gray-400 font-bold">Progres SE2026</span>
                <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white mt-1">{{ $stats['percentage'] }}%</h3>
                <span class="text-[10px] text-gray-400">Persentase Kumulatif</span>
            </div>
            <div class="p-3.5 bg-bps-50 text-bps-600 rounded-xl dark:bg-bps-950/40 dark:text-bps-400">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
            </div>
        </div>
    </div>



    <!-- Progress Monitoring Filters Panel -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-900 dark:border-gray-800">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                Filter Progress Pemantauan (Histogram)
            </h3>
            <button @click="showFilters = !showFilters" class="text-xs font-semibold text-bps-600 hover:text-bps-700 dark:text-bps-400 dark:hover:text-bps-500 flex items-center">
                <span x-show="showFilters">Sembunyikan</span>
                <span x-show="!showFilters">Tampilkan</span>
            </button>
        </div>
        <div class="p-6 space-y-4" x-show="showFilters" x-transition>
            <div class="grid grid-cols-1 sm:grid-cols-5 gap-4">
                <!-- Level Monitoring -->
                <div>
                    <label class="block mb-1.5 text-xs font-semibold text-gray-500 dark:text-gray-400 font-bold">Level Monitoring</label>
                    <select wire:model.live="monitoringLevel" class="w-full text-sm bg-gray-50 border border-bps-300 rounded-xl p-2.5 dark:bg-gray-800 dark:border-gray-750 dark:text-white focus:ring-bps-500 focus:border-bps-500 font-bold text-bps-600 dark:text-bps-400">
                        <option value="kec">Kecamatan</option>
                        <option value="desa">Desa</option>
                        <option value="subsls">SubSLS</option>
                        <option value="pcl">PCL</option>
                        <option value="pml">PML</option>
                    </select>
                </div>
                <!-- Date Filter -->
                <div>
                    <label class="block mb-1.5 text-xs font-semibold text-gray-500 dark:text-gray-400">Filter Tanggal</label>
                    <input type="date" wire:model.live="dateFilter" class="w-full text-sm bg-gray-50 border border-gray-300 rounded-xl p-2.5 dark:bg-gray-800 dark:border-gray-750 dark:text-white focus:ring-bps-500 focus:border-bps-500" />
                </div>
                <!-- Kecamatan Filter -->
                <div>
                    <label class="block mb-1.5 text-xs font-semibold text-gray-500 dark:text-gray-400">Filter Kecamatan</label>
                    <select wire:model.live="kecFilter" class="w-full text-sm bg-gray-50 border border-gray-300 rounded-xl p-2.5 dark:bg-gray-800 dark:border-gray-750 dark:text-white focus:ring-bps-500 focus:border-bps-500">
                        <option value="">Semua Kecamatan</option>
                        @foreach($districtsList as $dist)
                            <option value="{{ $dist->idkec }}">{{ $dist->nmkec }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Desa Filter -->
                <div>
                    <label class="block mb-1.5 text-xs font-semibold text-gray-500 dark:text-gray-400">Filter Desa</label>
                    <select wire:model.live="desaFilter" class="w-full text-sm bg-gray-50 border border-gray-300 rounded-xl p-2.5 dark:bg-gray-800 dark:border-gray-750 dark:text-white focus:ring-bps-500 focus:border-bps-500" {{ !$kecFilter ? 'disabled' : '' }}>
                        <option value="">Semua Desa</option>
                        @foreach($villagesList as $v)
                            <option value="{{ $v->iddesa }}">{{ $v->nmdesa }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- SLS/SubSLS Filter -->
                <div>
                    <label class="block mb-1.5 text-xs font-semibold text-gray-500 dark:text-gray-400">Filter SLS</label>
                    <select wire:model.live="slsFilter" class="w-full text-sm bg-gray-50 border border-gray-300 rounded-xl p-2.5 dark:bg-gray-800 dark:border-gray-750 dark:text-white focus:ring-bps-500 focus:border-bps-500" {{ !$desaFilter ? 'disabled' : '' }}>
                        <option value="">Semua SLS</option>
                        @foreach($slsList as $s)
                            <option value="{{ $s->idsls }}">{{ $s->nmsls }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <!-- PCL Filter -->
                <div>
                    <label class="block mb-1.5 text-xs font-semibold text-gray-500 dark:text-gray-400">Filter PCL</label>
                    <select wire:model.live="pclFilter" class="w-full text-sm bg-gray-50 border border-gray-300 rounded-xl p-2.5 dark:bg-gray-800 dark:border-gray-750 dark:text-white focus:ring-bps-500 focus:border-bps-500">
                        <option value="">Semua PCL</option>
                        @foreach($pclList as $p)
                            <option value="{{ $p->id }}">{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- PML Filter -->
                <div>
                    <label class="block mb-1.5 text-xs font-semibold text-gray-500 dark:text-gray-400">Filter PML</label>
                    <select wire:model.live="pmlFilter" class="w-full text-sm bg-gray-50 border border-gray-300 rounded-xl p-2.5 dark:bg-gray-800 dark:border-gray-750 dark:text-white focus:ring-bps-500 focus:border-bps-500">
                        <option value="">Semua PML</option>
                        @foreach($pmlList as $pm)
                            <option value="{{ $pm->id }}">{{ $pm->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Keyword Input -->
                <div class="sm:col-span-2">
                    <label class="block mb-1.5 text-xs font-semibold text-gray-500 dark:text-gray-400">Pencarian Kata Kunci</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" wire:model.live.debounce.300ms="keywordFilter" class="w-full text-sm bg-gray-50 border border-gray-300 rounded-xl ps-10 p-2.5 dark:bg-gray-800 dark:border-gray-750 dark:text-white focus:ring-bps-500 focus:border-bps-500" placeholder="Ketik ID SubSLS, SLS, Desa, PCL, atau PML..." />
                    </div>
                </div>
            </div>

            <!-- Action buttons (Reset) -->
            <div class="flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-gray-100 dark:border-gray-800">
                <div class="flex items-center gap-2">
                    <button wire:click="$set('monitoringLevel', 'kec'); $set('dateFilter', ''); $set('kecFilter', ''); $set('desaFilter', ''); $set('slsFilter', ''); $set('pclFilter', ''); $set('pmlFilter', ''); $set('keywordFilter', '');" class="text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 px-4 py-2.5 rounded-xl dark:bg-red-950/20 dark:text-red-400 dark:hover:bg-red-950/40 transition">
                        Reset Semua Filter Histogram
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Administrative Metrics Row -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl dark:bg-gray-900 dark:border-gray-800 flex items-center justify-between">
            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Petugas PCL</span>
            <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $stats['count_pcl'] }}</span>
        </div>
        <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl dark:bg-gray-900 dark:border-gray-800 flex items-center justify-between">
            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Supervisor PML</span>
            <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $stats['count_pml'] }}</span>
        </div>
        <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl dark:bg-gray-900 dark:border-gray-800 flex items-center justify-between">
            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Total SLS</span>
            <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $stats['count_sls'] }}</span>
        </div>
        <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl dark:bg-gray-900 dark:border-gray-800 flex items-center justify-between">
            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Total SubSLS</span>
            <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $stats['count_subsls'] }}</span>
        </div>
    </div>

    <!-- SECTION 1: Progress Monitoring & Rankings Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Interactive Drill Down panel -->
        <div class="lg:col-span-1 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-900 dark:border-gray-800 flex flex-col h-[520px]">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
                <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-1.5">
                    <svg class="w-5 h-5 text-bps-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    Drill-Down Wilayah
                </h3>
                <span class="text-[10px] bg-bps-50 dark:bg-bps-950/40 text-bps-600 dark:text-bps-400 font-bold px-2 py-0.5 rounded uppercase">{{ $drillLevel }}</span>
            </div>

            <!-- Breadcrumbs -->
            <div class="px-6 py-2.5 bg-gray-50 dark:bg-gray-850 border-b border-gray-200 dark:border-gray-800 flex flex-wrap items-center gap-1.5 text-xs">
                @foreach($drillBreadcrumbs as $index => $crumb)
                    @if($index > 0)
                        <span class="text-gray-400">/</span>
                    @endif
                    @if($crumb['action'])
                        <button wire:click="{{ $crumb['action'] }}" class="font-semibold text-bps-600 hover:text-bps-700 dark:text-bps-400 dark:hover:text-bps-500">{{ $crumb['label'] }}</button>
                    @else
                        <span class="text-gray-500 font-medium">{{ $crumb['label'] }}</span>
                    @endif
                @endforeach
            </div>

            <!-- Drill down Rows list -->
            <div class="flex-1 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-800 px-4">
                @forelse($drillData as $item)
                    <div class="py-3 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-800/40 px-2 rounded-xl transition duration-75">
                        <div class="flex-1 min-w-0 pr-3">
                            <div class="flex items-center justify-between">
                                @if($drillLevel === 'kec')
                                    <button wire:click="selectDrillKec('{{ $item['id'] }}')" class="text-sm font-bold text-gray-900 dark:text-white hover:underline text-left">{{ $item['name'] }}</button>
                                @elseif($drillLevel === 'desa')
                                    <button wire:click="selectDrillDesa('{{ $item['id'] }}')" class="text-sm font-bold text-gray-900 dark:text-white hover:underline text-left">{{ $item['name'] }}</button>
                                @elseif($drillLevel === 'sls')
                                    <button wire:click="selectDrillSls('{{ $item['id'] }}')" class="text-sm font-bold text-gray-900 dark:text-white hover:underline text-left">{{ $item['name'] }}</button>
                                @else
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $item['name'] }}</span>
                                @endif
                                <span class="text-xs font-bold text-gray-500 dark:text-gray-400">{{ $item['percentage'] }}%</span>
                            </div>
                            <!-- Mini Progress Bar -->
                            <div class="w-full bg-gray-100 rounded-full h-1.5 mt-2 dark:bg-gray-850 overflow-hidden">
                                <div class="h-1.5 rounded-full
                                    {{ $item['color'] === 'red' ? 'bg-red-500' : '' }}
                                    {{ $item['color'] === 'yellow' ? 'bg-amber-400' : '' }}
                                    {{ $item['color'] === 'green' ? 'bg-emerald-500' : '' }}"
                                     style="width: {{ min(100, $item['percentage']) }}%"></div>
                            </div>
                            <div class="flex items-center justify-between mt-1 text-[10px] text-gray-400">
                                <span>Realisasi: {{ number_format($item['realisasi']) }}</span>
                                <span>Target: {{ number_format($item['target']) }}</span>
                            </div>
                        </div>
                        <div class="ml-2">
                            @if($drillLevel !== 'subsls')
                                <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-sm text-gray-500">Tidak ada wilayah di bawah level ini.</div>
                @endforelse
            </div>
        </div>

        <!-- Histogram Progress Monitoring (Always Visible & In-Place updates) -->
        <div class="lg:col-span-2 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-900 dark:border-gray-800 p-6 flex flex-col h-[520px]"
             wire:key="bar-chart-container"
             x-data="dashboardCharts(
                 @js($chartProgress->pluck('name')->toArray()),
                 @js($chartProgress->pluck('percentage')->toArray()),
                 [],
                 @js($chartProgress->pluck('target')->toArray()),
                 @js($chartProgress->pluck('realisasi')->toArray()),
                 @js($monitoringLevel === 'kec' ? 'Kecamatan' : ($monitoringLevel === 'desa' ? 'Desa' : ($monitoringLevel === 'subsls' ? 'SubSLS' : ($monitoringLevel === 'pcl' ? 'PCL' : 'PML'))))
             )"
             @chart-data-updated.window="updateCharts($event.detail)">
            
            <div class="flex-1 flex flex-col">
                <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-3 mb-4">
                    <div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-white">Histogram Progres Kemajuan Wilayah/Petugas</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                            Level Monitoring Aktif: <span class="font-bold text-bps-600 dark:text-bps-400 uppercase">{{ $monitoringLevel === 'kec' ? 'Kecamatan' : ($monitoringLevel === 'desa' ? 'Desa' : ($monitoringLevel === 'subsls' ? 'SubSLS' : ($monitoringLevel === 'pcl' ? 'PCL' : 'PML'))) }}</span>
                        </p>
                    </div>
                </div>

                <!-- Column Chart Kecamatan container with wire:ignore and Alpine empty overlays -->
                <div class="flex-1 relative flex items-center justify-center w-full h-[320px]">
                    <!-- Alpine Empty State Overlay -->
                    <div x-show="isEmpty" x-cloak class="absolute inset-0 flex flex-col items-center justify-center text-center p-8 bg-gray-50 dark:bg-gray-800/30 rounded-2xl border border-dashed border-gray-200 dark:border-gray-850 w-full h-full z-10">
                        <svg class="w-12 h-12 text-gray-400 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Tidak ada data untuk filter yang dipilih.</p>
                    </div>
                    <!-- Chart canvas wrapped in wire:ignore -->
                    <div class="w-full h-full" x-show="!isEmpty" wire:ignore>
                        <div class="w-full h-full" x-ref="kecChartCanvas"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Ranking Panel (Top 10 Best vs Lowest) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Top 10 Best Progress -->
        <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-900 dark:border-gray-800">
            <h4 class="text-sm font-bold text-emerald-700 dark:text-emerald-400 flex items-center gap-1.5 border-b border-gray-100 dark:border-gray-800 pb-3 mb-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                Top 10 Progress Tertinggi (Level: {{ strtoupper($monitoringLevel) }})
            </h4>
            <div class="space-y-2">
                @forelse($topProgress as $index => $rank)
                    <div class="flex items-center justify-between text-xs py-1.5 px-3 bg-emerald-50/50 hover:bg-emerald-50 rounded-xl dark:bg-emerald-950/10 dark:hover:bg-emerald-950/20 transition">
                        <span class="font-bold text-emerald-800 dark:text-emerald-400">#{{ $index + 1 }} {{ $rank['name'] }}</span>
                        <div class="flex items-center gap-2">
                            <span class="text-gray-400">Realisasi: {{ number_format($rank['realisasi']) }}/{{ number_format($rank['target']) }}</span>
                            <span class="font-extrabold text-emerald-600 dark:text-emerald-400">{{ $rank['percentage'] }}%</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-xs text-gray-400">Tidak ada data peringkat.</div>
                @endforelse
            </div>
        </div>

        <!-- Top 10 Lowest Progress -->
        <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-900 dark:border-gray-800">
            <h4 class="text-sm font-bold text-red-700 dark:text-red-400 flex items-center gap-1.5 border-b border-gray-100 dark:border-gray-800 pb-3 mb-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"></path></svg>
                Top 10 Progress Terendah (Level: {{ strtoupper($monitoringLevel) }})
            </h4>
            <div class="space-y-2">
                @forelse($lowestProgress as $index => $rank)
                    <div class="flex items-center justify-between text-xs py-1.5 px-3 bg-red-50/50 hover:bg-red-50 rounded-xl dark:bg-red-950/10 dark:hover:bg-red-950/20 transition">
                        <span class="font-bold text-red-800 dark:text-red-400">#{{ $index + 1 }} {{ $rank['name'] }}</span>
                        <div class="flex items-center gap-2">
                            <span class="text-gray-400">Realisasi: {{ number_format($rank['realisasi']) }}/{{ number_format($rank['target']) }}</span>
                            <span class="font-extrabold text-red-600 dark:text-red-400">{{ $rank['percentage'] }}%</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-xs text-gray-400">Tidak ada data peringkat.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- SECTION 2: Daily Trend Analysis (100% Independent) -->
    <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-900 dark:border-gray-800"
         wire:key="timeline-chart-container"
         x-data="dashboardCharts(@js($trendTimeline['categories']), @js($trendTimeline['usaha_series']), @js($trendTimeline['ruta_series']))"
         @chart-data-updated.window="updateCharts($event.detail)">
        
        <!-- Independent Header & Filter Controls -->
        <div class="flex flex-col md:flex-row md:items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-4 mb-4 gap-4">
            <div>
                <h3 class="text-base font-bold text-gray-900 dark:text-white">Analisis Tren Perkembangan Harian (Linimasa)</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Analisis realisasi tambahan harian non-kumulatif secara linier dari waktu ke waktu.</p>
            </div>
            
            <!-- Independent Filters Panel -->
            <div class="flex flex-wrap items-center gap-3">
                <!-- Trend By -->
                <div>
                    <label class="block mb-1 text-[10px] font-bold text-gray-400 uppercase">Trend By</label>
                    <select wire:model.live="trendEntityType" class="text-xs font-bold bg-gray-50 border border-gray-200 rounded-lg p-2 dark:bg-gray-800 dark:border-gray-750 dark:text-white">
                        <option value="kab">Kabupaten</option>
                        <option value="kec">Kecamatan</option>
                        <option value="desa">Desa</option>
                        <option value="subsls">SubSLS</option>
                        <option value="pcl">PCL</option>
                        <option value="pml">PML</option>
                    </select>
                </div>

                <!-- Dynamic Target Selector (Only show if NOT Kabupaten) -->
                @if($trendEntityType !== 'kab')
                    <div>
                        <label class="block mb-1 text-[10px] font-bold text-gray-400 uppercase">Target Entitas</label>
                        <select wire:model.live="trendEntityId" class="text-xs font-semibold bg-gray-50 border border-gray-200 rounded-lg p-2 dark:bg-gray-800 dark:border-gray-750 dark:text-white max-w-[180px]">
                            <option value="">Pilih Target...</option>
                            @foreach($this->trendSelectorOptions as $opt)
                                <option value="{{ $opt['id'] }}">{{ $opt['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- Start Date -->
                <div>
                    <label class="block mb-1 text-[10px] font-bold text-gray-400 uppercase">Tanggal Mulai</label>
                    <input type="date" wire:model.live="trendStartDate" class="text-xs bg-gray-50 border border-gray-250 rounded-lg p-1.5 dark:bg-gray-800 dark:border-gray-700 dark:text-white" />
                </div>

                <!-- End Date -->
                <div>
                    <label class="block mb-1 text-[10px] font-bold text-gray-400 uppercase">Tanggal Akhir</label>
                    <input type="date" wire:model.live="trendEndDate" class="text-xs bg-gray-50 border border-gray-250 rounded-lg p-1.5 dark:bg-gray-800 dark:border-gray-700 dark:text-white" />
                </div>
            </div>
        </div>

        <!-- Line Chart Daily Timeline Canvas with wire:ignore and Alpine empty overlays -->
        <div class="relative min-h-[320px] flex items-center justify-center">
            <!-- Alpine Empty State Overlay -->
            <div x-show="isEmpty" x-cloak class="absolute inset-0 flex flex-col items-center justify-center text-center p-8 bg-gray-50 dark:bg-gray-800/30 rounded-2xl border border-dashed border-gray-200 dark:border-gray-850 w-full h-[320px] z-10">
                <svg class="w-12 h-12 text-gray-400 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Tidak ada data untuk filter yang dipilih.</p>
            </div>
            <!-- Timeline Chart Canvas wrapped in wire:ignore -->
            <div class="w-full h-full" x-show="!isEmpty" wire:ignore>
                <div class="w-full h-full" x-ref="timelineChartCanvas"></div>
            </div>
        </div>
    </div>
</div>
