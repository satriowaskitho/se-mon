<div class="space-y-6" x-data="{ showFilters: true }">
    <!-- Top Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <!-- Target Card -->
        <div class="p-5 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-900 dark:border-gray-800 flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-gray-500 uppercase dark:text-gray-400">Total Target Usaha</span>
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
                <span class="text-xs font-semibold text-gray-500 uppercase dark:text-gray-400">Total Realisasi Usaha</span>
                <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white mt-1">{{ number_format($stats['total_realisasi_usaha']) }}</h3>
                <span class="text-[10px] text-emerald-500 font-semibold">{{ $stats['percentage'] }}% dari Target</span>
            </div>
            <div class="p-3.5 bg-emerald-50 text-emerald-600 rounded-xl dark:bg-emerald-950/40 dark:text-emerald-400">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"></path></svg>
            </div>
        </div>

        <!-- Realisasi Ruta Card -->
        <div class="p-5 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-900 dark:border-gray-800 flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-gray-500 uppercase dark:text-gray-400">Total Realisasi Ruta</span>
                <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white mt-1">{{ number_format($stats['total_realisasi_ruta']) }}</h3>
                <span class="text-[10px] text-purple-500 font-semibold">Cacah Rumah Tangga</span>
            </div>
            <div class="p-3.5 bg-purple-50 text-purple-600 rounded-xl dark:bg-purple-950/40 dark:text-purple-400">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            </div>
        </div>

        <!-- Progress Percentage Card -->
        <div class="p-5 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-900 dark:border-gray-800 flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-gray-500 uppercase dark:text-gray-400">Progres SE2026</span>
                <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white mt-1">{{ $stats['percentage'] }}%</h3>
                <span class="text-[10px] font-bold uppercase
                    {{ $stats['progress_color'] === 'red' ? 'text-red-500' : '' }}
                    {{ $stats['progress_color'] === 'yellow' ? 'text-amber-500' : '' }}
                    {{ $stats['progress_color'] === 'green' ? 'text-emerald-500' : '' }}">
                    Status: {{ $stats['progress_color'] === 'red' ? 'Kritis' : ($stats['progress_color'] === 'yellow' ? 'Waspada' : 'Aman') }}
                </span>
            </div>
            <div class="p-3.5 rounded-xl flex items-center justify-center
                {{ $stats['progress_color'] === 'red' ? 'bg-red-50 text-red-600 dark:bg-red-950/40 dark:text-red-400' : '' }}
                {{ $stats['progress_color'] === 'yellow' ? 'bg-amber-50 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400' : '' }}
                {{ $stats['progress_color'] === 'green' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400' : '' }}">
                <span class="text-lg font-bold">{{ $stats['percentage'] }}%</span>
            </div>
        </div>
    </div>

    <!-- PCL Perlu Perhatian Alert Widget (Section 3) -->
    @if(count($attentionPcls) > 0)
        <div class="p-5 bg-red-50 border border-red-200 rounded-2xl dark:bg-red-950/10 dark:border-red-900/40">
            <div class="flex items-center gap-2 mb-3">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <h4 class="text-sm font-bold text-red-800 dark:text-red-400">Petugas Lapangan (PCL) Perlu Perhatian / Pendampingan Segera</h4>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                @foreach($attentionPcls->take(4) as $attPcl)
                    <div class="p-3 bg-white border border-red-100 rounded-xl dark:bg-gray-900 dark:border-red-900/20 shadow-xs">
                        <div class="font-bold text-xs text-gray-900 dark:text-white">{{ $attPcl['name'] }}</div>
                        <div class="flex items-center justify-between mt-1 text-[10px]">
                            <span class="text-gray-500">Progres Kemajuan</span>
                            <span class="font-bold text-red-600 dark:text-red-400">{{ $attPcl['progress'] }}%</span>
                        </div>
                        <div class="flex items-center justify-between mt-0.5 text-[10px]">
                            <span class="text-gray-500">Aktivitas Terakhir</span>
                            <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $attPcl['last_activity'] }}</span>
                        </div>
                        <span class="mt-1.5 inline-block text-[8px] font-extrabold bg-red-100 text-red-700 dark:bg-red-950/40 dark:text-red-400 px-1.5 py-0.5 rounded uppercase">
                            {{ $attPcl['reason'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Progress Monitoring Filters Panel -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-900 dark:border-gray-800">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
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

            <!-- Action buttons (Reset & Exports) -->
            <div class="flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-gray-100 dark:border-gray-800">
                <div class="flex items-center gap-2">
                    <button wire:click="$set('monitoringLevel', 'kec'); $set('dateFilter', ''); $set('kecFilter', ''); $set('desaFilter', ''); $set('slsFilter', ''); $set('pclFilter', ''); $set('pmlFilter', ''); $set('keywordFilter', ''); resetPage();" class="text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 px-4 py-2 rounded-xl dark:bg-red-950/20 dark:text-red-400 dark:hover:bg-red-950/40 transition">
                        Reset Semua Filter
                    </button>
                </div>
                <div class="flex items-center gap-2">
                    <button wire:click="exportExcel" class="inline-flex items-center px-4 py-2 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded-xl dark:bg-emerald-950/20 dark:text-emerald-400 dark:hover:bg-emerald-950/40 transition">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Unduh Excel
                    </button>
                    <button wire:click="exportCsv" class="inline-flex items-center px-4 py-2 text-xs font-bold text-purple-700 bg-purple-50 hover:bg-purple-100 rounded-xl dark:bg-purple-950/20 dark:text-purple-400 dark:hover:bg-purple-950/40 transition">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Unduh CSV
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

        <!-- Histogram Progress Monitoring (Always Visible) -->
        <div class="lg:col-span-2 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-900 dark:border-gray-800 p-6 flex flex-col h-[520px]"
             wire:key="bar-chart-container-{{ $monitoringLevel }}"
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

                <!-- Column Chart Kecamatan container -->
                <div class="flex-1 relative flex items-center justify-center">
                    @if($chartProgress->isEmpty())
                        <div class="flex flex-col items-center justify-center text-center p-8 bg-gray-50 dark:bg-gray-800/30 rounded-2xl border border-dashed border-gray-200 dark:border-gray-850 w-full h-[320px]">
                            <svg class="w-12 h-12 text-gray-400 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Tidak ada data untuk filter yang dipilih.</p>
                        </div>
                    @else
                        <div class="w-full h-full" x-ref="kecChartCanvas"></div>
                    @endif
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
         wire:key="timeline-chart-container-{{ $trendEntityType }}-{{ $trendEntityId }}"
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
                    <select wire:model.live="trendEntityType" class="text-xs font-bold bg-gray-50 border border-gray-200 rounded-lg p-2 dark:bg-gray-800 dark:border-gray-700 dark:text-white">
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
                        <select wire:model.live="trendEntityId" class="text-xs font-semibold bg-gray-50 border border-gray-200 rounded-lg p-2 dark:bg-gray-800 dark:border-gray-700 dark:text-white max-w-[180px]">
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
                    <input type="date" wire:model.live="trendStartDate" class="text-xs bg-gray-50 border border-gray-200 rounded-lg p-1.5 dark:bg-gray-800 dark:border-gray-700 dark:text-white" />
                </div>

                <!-- End Date -->
                <div>
                    <label class="block mb-1 text-[10px] font-bold text-gray-400 uppercase">Tanggal Akhir</label>
                    <input type="date" wire:model.live="trendEndDate" class="text-xs bg-gray-50 border border-gray-200 rounded-lg p-1.5 dark:bg-gray-800 dark:border-gray-700 dark:text-white" />
                </div>
            </div>
        </div>

        <!-- Line Chart Daily Timeline Canvas -->
        <div class="relative min-h-[320px] flex items-center justify-center">
            @if(empty($trendTimeline['categories']))
                <div class="flex flex-col items-center justify-center text-center p-8 bg-gray-50 dark:bg-gray-800/30 rounded-2xl border border-dashed border-gray-200 dark:border-gray-850 w-full h-[320px]">
                    <svg class="w-12 h-12 text-gray-400 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Tidak ada data untuk filter yang dipilih.</p>
                </div>
            @else
                <div class="w-full h-full" x-ref="timelineChartCanvas"></div>
            @endif
        </div>
    </div>

    <!-- Monitoring Table -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-900 dark:border-gray-800 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-gray-50/50 dark:bg-gray-900/50">
            <div>
                <h3 class="text-base font-bold text-gray-900 dark:text-white">Tabel Rincian Monitoring Petugas & Target</h3>
                <p class="text-xs text-gray-400 mt-0.5">Daftar wilayah tugas terkecil (SubSLS) beserta realisasi usaha dan ruta.</p>
            </div>
            <div class="flex items-center gap-1.5 text-xs text-gray-400">
                Menampilkan <span class="font-bold text-gray-700 dark:text-white">{{ $tableData->firstItem() ?? 0 }}-{{ $tableData->lastItem() ?? 0 }}</span> dari <span class="font-bold text-gray-700 dark:text-white">{{ $tableData->total() }}</span> SubSLS
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-850 dark:text-gray-300">
                    <tr>
                        <th scope="col" class="px-6 py-3.5">ID SubSLS</th>
                        <th scope="col" class="px-6 py-3.5">Kecamatan</th>
                        <th scope="col" class="px-6 py-3.5">Desa</th>
                        <th scope="col" class="px-6 py-3.5">SLS</th>
                        <th scope="col" class="px-6 py-3.5">PCL Pencacah</th>
                        <th scope="col" class="px-6 py-3.5">PML Supervisor</th>
                        <th scope="col" class="px-6 py-3.5 text-center">Target Usaha</th>
                        <th scope="col" class="px-6 py-3.5 text-center">Realisasi Usaha</th>
                        <th scope="col" class="px-6 py-3.5 text-center">Realisasi Ruta</th>
                        <th scope="col" class="px-6 py-3.5 text-center">Progres (%)</th>
                        <th scope="col" class="px-6 py-3.5 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse($tableData as $row)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition">
                            <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">{{ $row->subsls->idsubsls }}</td>
                            <td class="px-6 py-4">{{ $row->subsls->sls->village->district->nmkec ?? 'N/A' }}</td>
                            <td class="px-6 py-4">{{ $row->subsls->sls->village->nmdesa ?? 'N/A' }}</td>
                            <td class="px-6 py-4">{{ $row->subsls->sls->nmsls ?? 'N/A' }}</td>
                            <td class="px-6 py-4 font-medium text-gray-800 dark:text-gray-200">{{ $row->pcl->nama ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-bps-600 dark:text-bps-400 font-medium">{{ $row->pml->nama ?? 'N/A' }}</td>
                            <td class="px-6 py-4 font-bold text-center text-gray-900 dark:text-white">{{ number_format($row->target_usaha) }}</td>
                            <td class="px-6 py-4 font-bold text-center text-emerald-600 dark:text-emerald-400">{{ number_format($row->usaha_realisasi) }}</td>
                            <td class="px-6 py-4 font-bold text-center text-purple-600 dark:text-purple-400">{{ number_format($row->ruta_realisasi) }}</td>
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
                            <td class="px-6 py-4 text-center">
                                <span class="px-2.5 py-1 text-[10px] font-bold rounded-full border uppercase
                                    {{ $row->progress_pct < 50 ? 'bg-red-50 text-red-600 border-red-200 dark:bg-red-950/20 dark:text-red-400 dark:border-red-900' : '' }}
                                    {{ $row->progress_pct >= 50 && $row->progress_pct < 80 ? 'bg-amber-50 text-amber-600 border-amber-200 dark:bg-amber-950/20 dark:text-amber-400 dark:border-amber-900' : '' }}
                                    {{ $row->progress_pct >= 80 ? 'bg-emerald-50 text-emerald-600 border-emerald-200 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900' : '' }}">
                                    {{ $row->progress_pct < 50 ? 'KRITIS' : ($row->progress_pct < 80 ? 'WASPAD' : 'AMAN') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-6 py-12 text-center text-gray-500 font-semibold">Tidak ditemukan data monitoring yang sesuai dengan kriteria filter.</td>
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
