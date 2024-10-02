<div class="max-w-full w-full bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6">

    <div class="flex justify-between items-start w-full">
        <div class="flex-col items-center">
            <div class="flex items-center mb-1">
                <h5 class="text-md font-bold leading-none text-gray-900 dark:text-white me-1">Ma'lumotlar holati</h5>

            </div>

        </div>

    </div>

    <!-- Line Chart -->
    <div class="py-6 w-full " id="pie-chart"></div>


    <div class="grid grid-cols-1 items-center border-gray-200 border-t dark:border-gray-700 justify-between">
        <div class="flex justify-between items-center pt-5">
            <!-- Button -->


        </div>
    </div>
</div>
@php
    $user = auth()->user();
    $pointUserInformations = \App\Models\PointUserDeportament::where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->get();

    $maqullanganCount = $pointUserInformations->where('status', '1')->count();
    $kutushdaCount = $pointUserInformations->where('status', '3')->count();
    $radEtilganCount = $pointUserInformations->where('status', '0')->count();

    $totalCount = $maqullanganCount + $kutushdaCount + $radEtilganCount;
@endphp

<script>
    window.maqullanganCount = @json($maqullanganCount);
    window.kutushdaCount = @json($kutushdaCount);
    window.radEtilganCount = @json($radEtilganCount);
    window.totalCount = @json($totalCount);

    const calculatePercentage = (count, total) => {
        return total > 0 ? parseFloat((count / total * 100).toFixed(2)) : 0;
    }

    // ApexCharts options and config
    window.addEventListener("load", function() {
        const getChartOptions = () => {
            return {
                series: [
                    calculatePercentage(window.maqullanganCount, window.totalCount),
                    calculatePercentage(window.kutushdaCount, window.totalCount),
                    calculatePercentage(window.radEtilganCount, window.totalCount)
                ],
                colors: ["#1C64F2", "#16BDCA", "#9061F9"],
                chart: {
                    height: 420,
                    width: "100%",
                    type: "pie",
                    toolbar: {
                        show: true,
                    },
                },
                stroke: {
                    colors: ["white"],
                    lineCap: "",
                },
                plotOptions: {
                    pie: {
                        labels: {
                            show: true,
                        },
                        size: "100%",
                        dataLabels: {
                            offset: -25
                        }
                    },
                },
                labels: ["Maqullangan", "Kutush jarayonida", "Rad etilgan"],
                dataLabels: {
                    enabled: true,
                    formatter: function (val, opts) {
                        return opts.w.config.series[opts.seriesIndex] + '%';
                    },
                    style: {
                        fontFamily: "Inter, sans-serif",
                    },
                },
                legend: {
                    position: "bottom",
                    fontFamily: "Inter, sans-serif",
                },
                tooltip: {
                    y: {
                        formatter: function(value) {
                            return value + "%";
                        }
                    }
                },
            }
        }

        if (document.getElementById("pie-chart") && typeof ApexCharts !== 'undefined') {
            if (window.totalCount > 0) {
                const chart = new ApexCharts(document.getElementById("pie-chart"), getChartOptions());
                chart.render();
            } else {
                document.getElementById("pie-chart").innerHTML = "Ma'lumot mavjud emas";
            }
        }
    });
</script>
