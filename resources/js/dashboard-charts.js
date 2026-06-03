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
        kecChart: null,
        chartTimeline: null,
        isEmpty: initSer.length === 0,

        // Stored state for Trend Chart
        trendCategories: initCat,
        trendUsaha: initSer,
        trendRuta: initRuta,

        init() {
            console.log('INIT CALLED');

            // 4. Prevent Duplicate Event Listener
            if (window.dashboardUpdateHandler) {
                window.removeEventListener('updateCharts', window.dashboardUpdateHandler);
            }
            window.dashboardUpdateHandler = (e) => {
                this.updateCharts(e.detail || e);
            };
            window.addEventListener('updateCharts', window.dashboardUpdateHandler);

            this.$nextTick(() => {
                // 1. Initialize Column Chart if ref exists
                if (this.$refs.kecChartCanvas) {
                    this.initChartKecWithData(initSer, initCat, initTargets, initRealisasis, initLevelLabel);
                }

                // 2. Initialize Daily progress timeline if container exists
                if (document.querySelector('#trend-chart')) {
                    this.initChartTimelineWithData(initCat, initSer, initRuta);
                }
            });
        },

        initChartKecWithData(series, categories, targets, realisasis, levelLabel) {
            if (this.chartKec) {
                try { this.chartKec.destroy(); } catch (e) {}
                this.chartKec = null;
            }
            if (this.kecChart) {
                try { this.kecChart.destroy(); } catch (e) {}
                this.kecChart = null;
            }

            const canvas = this.$refs.kecChartCanvas;
            if (!canvas) return;
            canvas.innerHTML = '';

            const render = () => {
                if (this.chartKec || this.kecChart) return;
                
                // Verify offset dimensions
                if (canvas.offsetWidth === 0 || canvas.offsetHeight === 0) {
                    console.log('chartKec has 0 dimensions, skipping render.');
                    return;
                }

                console.log('Initializing chartKec with data');
                const chartInstance = new ApexCharts(canvas, {
                    chart: {
                        type: 'bar',
                        height: '100%',
                        toolbar: { show: false },
                        fontFamily: 'Inter, sans-serif',
                        animations: { enabled: true }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '45%',
                            borderRadius: 6
                        }
                    },
                    colors: ['#f97316'],
                    dataLabels: {
                        enabled: true,
                        formatter: function (val) { return val + '%'; },
                        style: { fontSize: '10px', colors: ['#ffffff'] }
                    },
                    series: [{
                        name: 'Progress',
                        data: series
                    }],
                    xaxis: {
                        categories: categories,
                        labels: { rotate: -15, style: { fontSize: '10px' } }
                    },
                    yaxis: {
                        min: 0,
                        max: function(max) {
                            if (max <= 100) return 100;
                            return Math.ceil(max / 10) * 10;
                        },
                        labels: { formatter: function (val) { return Math.round(val) + '%'; } }
                    },
                    grid: { borderColor: '#f1f1f1' },
                    customTargets: targets || [],
                    customRealisasis: realisasis || [],
                    customLevelLabel: levelLabel || 'Entitas',
                    tooltip: {
                        custom: function({ series, seriesIndex, dataPointIndex, w }) {
                            const tgts = w.config.customTargets || [];
                            const reals = w.config.customRealisasis || [];
                            const lvl = w.config.customLevelLabel || 'Entitas';
                            
                            const target = tgts[dataPointIndex] !== undefined ? tgts[dataPointIndex] : 0;
                            const realisasi = reals[dataPointIndex] !== undefined ? reals[dataPointIndex] : 0;
                            const progress = series[seriesIndex][dataPointIndex];
                            const name = w.config.xaxis.categories[dataPointIndex] || '';

                            return '<div class="p-3 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl shadow-xl text-xs font-semibold text-gray-700 dark:text-gray-300">' +
                                   '<div class="font-extrabold text-gray-900 dark:text-white mb-1.5 border-b border-gray-100 dark:border-gray-800 pb-1.5">' + lvl + ': ' + name + '</div>' +
                                   '<div class="flex justify-between gap-4 mb-1"><span>Target Usaha:</span><span class="font-bold text-gray-900 dark:text-white">' + Number(target).toLocaleString('id-ID') + '</span></div>' +
                                   '<div class="flex justify-between gap-4 mb-1"><span>Realisasi Usaha:</span><span class="font-bold text-gray-900 dark:text-white">' + Number(realisasi).toLocaleString('id-ID') + '</span></div>' +
                                   '<div class="flex justify-between gap-4"><span>Progress:</span><span class="font-extrabold text-bps-600 dark:text-bps-400">' + Number(progress).toFixed(2) + '%</span></div>' +
                                   '</div>';
                        }
                    }
                });

                chartInstance.render();
                this.chartKec = chartInstance;
                this.kecChart = chartInstance;
            };

            if (canvas.offsetWidth > 0 && canvas.offsetHeight > 0) {
                render();
            } else {
                // Retry once using requestAnimationFrame
                requestAnimationFrame(() => {
                    if (canvas.offsetWidth > 0 && canvas.offsetHeight > 0) {
                        render();
                    } else {
                        console.log('chartKec second attempt has 0 dimensions, aborting.');
                    }
                });
            }
        },

        initChartTimelineWithData(categories, usahaSeries, rutaSeries) {
            const el = document.querySelector('#trend-chart');
            if (!el) return;

            if (this.chartTimeline && this.chartTimeline.el !== el) {
                try { this.chartTimeline.destroy(); } catch (e) {}
                this.chartTimeline = null;
                window.trendChart = null;
            }

            const render = () => {
                if (this.chartTimeline) return;
                
                // Verify offset dimensions
                if (el.offsetWidth === 0 || el.offsetHeight === 0) {
                    console.log('chartTimeline has 0 dimensions, skipping render.');
                    return;
                }

                console.log('Initializing chartTimeline with data');
                el.innerHTML = '';
                const chartInstance = new ApexCharts(el, {
                    chart: {
                        type: 'area',
                        height: '100%',
                        toolbar: { show: false },
                        fontFamily: 'Inter, sans-serif',
                        animations: { enabled: true }
                    },
                    colors: ['#10b981', '#a855f7'],
                    dataLabels: { enabled: false },
                    stroke: { curve: 'smooth', width: 3 },
                    fill: {
                        type: 'gradient',
                        gradient: { shadeIntensity: 1, opacityFrom: 0.3, opacityTo: 0.05 }
                    },
                    series: [
                        { name: 'Tambahan Usaha', data: usahaSeries },
                        { name: 'Tambahan Ruta', data: rutaSeries }
                    ],
                    xaxis: {
                        categories: categories,
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
                
                chartInstance.render();
                this.chartTimeline = chartInstance;
                window.trendChart = chartInstance;
            };

            if (el.offsetWidth > 0 && el.offsetHeight > 0) {
                render();
            } else {
                // Retry once using requestAnimationFrame
                requestAnimationFrame(() => {
                    if (el.offsetWidth > 0 && el.offsetHeight > 0) {
                        render();
                    } else {
                        console.log('chartTimeline second attempt has 0 dimensions, aborting.');
                    }
                });
            }
        },

        updateCharts(data) {
            console.log('updateCharts received', data);
            
            // Livewire v3 events wrap parameters inside an array
            if (Array.isArray(data)) {
                data = data[0];
            }
            if (!data) return;
            
            // Handle Histogram (Kec chart) updates
            if (this.$refs.kecChartCanvas) {
                this.isEmpty = !data.kecSeries || data.kecSeries.length === 0;
                if (!this.isEmpty) {
                    if (!this.chartKec && !this.kecChart) {
                        // Defer initialization if not created yet
                        this.initChartKecWithData(data.kecSeries, data.kecCategories, data.kecTargets, data.kecRealisasis, data.levelLabel);
                    } else {
                        // Safe update without recreating
                        const activeChart = this.chartKec || this.kecChart;
                        activeChart.updateOptions({
                            series: [{ name: 'Progress', data: data.kecSeries }],
                            xaxis: { categories: data.kecCategories },
                            customTargets: data.kecTargets || [],
                            customRealisasis: data.kecRealisasis || [],
                            customLevelLabel: data.levelLabel || 'Entitas'
                        });
                    }
                }
            }

            // Handle Timeline updates
            const el = document.querySelector('#trend-chart');
            if (el) {
                this.isEmpty = !data.timelineUsaha || data.timelineUsaha.length === 0;
                if (!this.isEmpty) {
                    this.trendCategories = data.timelineCategories;
                    this.trendUsaha = data.timelineUsaha;
                    this.trendRuta = data.timelineRuta;
                    
                    // Recreate ONLY if container actually changes
                    if (this.chartTimeline && this.chartTimeline.el !== el) {
                        console.log('destroy');
                        try { this.chartTimeline.destroy(); } catch (e) {}
                        this.chartTimeline = null;
                        window.trendChart = null;
                    }

                    if (this.chartTimeline) {
                        console.log('chartTimeline.updateSeries()');
                        this.chartTimeline.updateSeries([
                            { name: 'Tambahan Usaha', data: data.timelineUsaha },
                            { name: 'Tambahan Ruta', data: data.timelineRuta }
                        ]);
                        this.chartTimeline.updateOptions({
                            xaxis: {
                                categories: data.timelineCategories
                            }
                        }, false, true);
                    } else {
                        this.initChartTimelineWithData(data.timelineCategories, data.timelineUsaha, data.timelineRuta);
                    }
                }
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
                this.chartKec = null;
            }
            if (this.kecChart) {
                try {
                    this.kecChart.destroy();
                } catch (e) {
                    console.log('Safe kecChart destroy caught:', e.message);
                }
                this.kecChart = null;
            }
            if (this.chartTimeline) {
                try {
                    this.chartTimeline.destroy();
                } catch (e) {
                    console.log('Safe chartTimeline destroy caught:', e.message);
                }
                this.chartTimeline = null;
            }
            if (window.trendChart) {
                try {
                    window.trendChart.destroy();
                } catch (e) {
                    console.log('Safe window.trendChart destroy caught:', e.message);
                }
                window.trendChart = null;
            }
        }
    }));
};

if (window.Alpine) {
    registerCharts();
} else {
    document.addEventListener('alpine:init', registerCharts);
}

