import ApexCharts from 'apexcharts';

const formatIndonesianDate = (dateStr) => {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    if (isNaN(date.getTime())) return dateStr;
    const months = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    return `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
};

const registerCharts = () => {
    if (!window.Alpine) return;

    window.Alpine.data('dashboardCharts', (initCat = [], initSer = [], initRuta = [], initTargets = [], initRealisasis = [], initLevelLabel = 'Entitas') => ({
        chartKec: null,
        chartTimeline: null,

        init() {
            console.log('INIT CALLED - dashboardCharts initialized');

            this.$nextTick(() => {
                console.log('nextTick execution');
                console.log('kec ref:', this.$refs.kecChartCanvas);
                console.log('timeline ref:', this.$refs.timelineChartCanvas);

                // 1. Initialize Column Chart (only if ref exists in this DOM instance)
                if (this.$refs.kecChartCanvas) {
                    console.log('Initializing chartKec with initial data');
                    this.chartKec = new ApexCharts(this.$refs.kecChartCanvas, {
                        chart: {
                            type: 'bar',
                            height: 320,
                            toolbar: { show: false },
                            fontFamily: 'Inter, sans-serif'
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: '45%',
                                borderRadius: 6
                            }
                        },
                        colors: ['#0c82eb'],
                        dataLabels: {
                            enabled: true,
                            formatter: function (val) { return val + '%'; },
                            style: { fontSize: '10px', colors: ['#ffffff'] }
                        },
                        series: [{
                            name: 'Progress',
                            data: initSer
                        }],
                        xaxis: {
                            categories: initCat,
                            labels: { rotate: -15, style: { fontSize: '10px' } }
                        },
                        yaxis: {
                            max: function(max) {
                                return Math.max(100, Math.ceil(max * 1.15));
                            },
                            labels: { formatter: function (val) { return Math.round(val) + '%'; } }
                        },
                        grid: { borderColor: '#f1f1f1' },
                        // Store custom target, realisasi, and levelLabel in config to read inside custom tooltip
                        customTargets: initTargets || [],
                        customRealisasis: initRealisasis || [],
                        customLevelLabel: initLevelLabel || 'Entitas',
                        tooltip: {
                            custom: function({ series, seriesIndex, dataPointIndex, w }) {
                                const targets = w.config.customTargets || [];
                                const realisasis = w.config.customRealisasis || [];
                                const levelLabel = w.config.customLevelLabel || 'Entitas';
                                
                                const target = targets[dataPointIndex] !== undefined ? targets[dataPointIndex] : 0;
                                const realisasi = realisasis[dataPointIndex] !== undefined ? realisasis[dataPointIndex] : 0;
                                const progress = series[seriesIndex][dataPointIndex];
                                const name = w.config.xaxis.categories[dataPointIndex] || '';

                                return '<div class="p-3 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl shadow-xl text-xs font-semibold text-gray-700 dark:text-gray-300">' +
                                       '<div class="font-extrabold text-gray-900 dark:text-white mb-1.5 border-b border-gray-100 dark:border-gray-800 pb-1.5">' + levelLabel + ': ' + name + '</div>' +
                                       '<div class="flex justify-between gap-4 mb-1"><span>Target Usaha:</span><span class="font-bold text-gray-900 dark:text-white">' + Number(target).toLocaleString('id-ID') + '</span></div>' +
                                       '<div class="flex justify-between gap-4 mb-1"><span>Realisasi Usaha:</span><span class="font-bold text-gray-900 dark:text-white">' + Number(realisasi).toLocaleString('id-ID') + '</span></div>' +
                                       '<div class="flex justify-between gap-4"><span>Progress:</span><span class="font-extrabold text-bps-600 dark:text-bps-400">' + Number(progress).toFixed(2) + '%</span></div>' +
                                       '</div>';
                            }
                        }
                    });
                    this.chartKec.render();
                }

                // 2. Initialize Daily progress timeline (only if ref exists in this DOM instance)
                if (this.$refs.timelineChartCanvas) {
                    console.log('Initializing chartTimeline with initial data');
                    this.chartTimeline = new ApexCharts(this.$refs.timelineChartCanvas, {
                        chart: {
                            type: 'area',
                            height: 320,
                            toolbar: { show: false },
                            fontFamily: 'Inter, sans-serif'
                        },
                        colors: ['#10b981', '#a855f7'],
                        dataLabels: { enabled: false },
                        stroke: { curve: 'smooth', width: 3 },
                        fill: {
                            type: 'gradient',
                            gradient: { shadeIntensity: 1, opacityFrom: 0.3, opacityTo: 0.05 }
                        },
                        series: [
                            { name: 'Tambahan Usaha', data: initSer },
                            { name: 'Tambahan Ruta', data: initRuta }
                        ],
                        xaxis: {
                            categories: initCat,
                            labels: { style: { fontSize: '9px' } }
                        },
                        grid: { borderColor: '#f1f1f1' },
                        legend: {
                            show: true,
                            position: 'top',
                            horizontalAlign: 'right',
                            fontSize: '11px',
                            fontFamily: 'Inter, sans-serif',
                            fontWeight: 600,
                            labels: { colors: '#4b5563' },
                            markers: { radius: 12 }
                        },
                        tooltip: {
                            custom: function({ series, seriesIndex, dataPointIndex, w }) {
                                const dateStr = w.config.xaxis.categories[dataPointIndex] || '';
                                const indonesianDate = formatIndonesianDate(dateStr);
                                
                                const usaha = series[0][dataPointIndex] !== undefined ? series[0][dataPointIndex] : 0;
                                const ruta = series[1][dataPointIndex] !== undefined ? series[1][dataPointIndex] : 0;
                                
                                return '<div class="p-3 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl shadow-xl text-xs font-semibold text-gray-700 dark:text-gray-300">' +
                                       '<div class="font-extrabold text-gray-900 dark:text-white mb-1.5 border-b border-gray-100 dark:border-gray-800 pb-1.5">' + indonesianDate + '</div>' +
                                       '<div class="flex justify-between gap-4 mb-1"><span>Tambahan Usaha:</span><span class="font-bold text-emerald-600 dark:text-emerald-400">' + Number(usaha).toLocaleString('id-ID') + '</span></div>' +
                                       '<div class="flex justify-between gap-4"><span>Tambahan Ruta:</span><span class="font-bold text-purple-600 dark:text-purple-400">' + Number(ruta).toLocaleString('id-ID') + '</span></div>' +
                                       '</div>';
                            }
                        }
                    });
                    this.chartTimeline.render();
                }
            });
        },

        updateCharts(data) {
            console.log('updateCharts called with raw data:', data);
            
            // Livewire v3 events wrap parameters inside an array
            if (Array.isArray(data)) {
                data = data[0];
            }
            if (!data) return;

            console.log('updateCharts parsed payload:', data);
            
            if (this.chartKec && data.kecSeries && data.kecCategories && this.$refs.kecChartCanvas) {
                console.log('Updating chartKec...');
                this.chartKec.updateOptions({
                    series: [{ name: 'Progress', data: data.kecSeries }],
                    xaxis: { categories: data.kecCategories },
                    customTargets: data.kecTargets || [],
                    customRealisasis: data.kecRealisasis || [],
                    customLevelLabel: data.levelLabel || 'Entitas'
                });
            }
            if (this.chartTimeline && data.timelineUsaha && data.timelineCategories && this.$refs.timelineChartCanvas) {
                console.log('Updating chartTimeline...');
                this.chartTimeline.updateOptions({
                    series: [
                        { name: 'Tambahan Usaha', data: data.timelineUsaha },
                        { name: 'Tambahan Ruta', data: data.timelineRuta }
                    ],
                    xaxis: { categories: data.timelineCategories }
                });
            }
        },

        destroy() {
            console.log('DESTROY CALLED - dashboardCharts cleanup');
            if (this.chartKec) {
                try {
                    this.chartKec.destroy();
                } catch (e) {
                    console.log('Safe chartKec destroy caught:', e.message);
                }
            }
            if (this.chartTimeline) {
                try {
                    this.chartTimeline.destroy();
                } catch (e) {
                    console.log('Safe chartTimeline destroy caught:', e.message);
                }
            }
        }
    }));
};

if (window.Alpine) {
    registerCharts();
} else {
    document.addEventListener('alpine:init', registerCharts);
}
