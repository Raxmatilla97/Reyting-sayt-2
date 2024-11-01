<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl shadow-lg">
            <h2 class="py-6 text-2xl font-bold text-center text-white">
                {{ __("Mening yuborgan ma'lumotlarim") }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Total Points Card -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-500">Umumiy to'plagan ballar</h3>
                                <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $totalPoints }} ball</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Department Points Card -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-indigo-100">
                                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-500">Kafedraga o'tgan ballar</h3>
                                <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $departamentPointTotal }} ball
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Alert -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
                <div class="p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">
                                Ma'lumot!
                            </h3>
                            <div class="mt-2 text-sm text-gray-600">
                                <p class="leading-relaxed">
                                    Agar siz yuborgan ma'lumotlarga ballar faqat kafedra hisobiga o'tayotgan bo'lsa,
                                    bu sizning shu yo'nalishda maksimal ballni yiqqaningizni bildiradi. Bunday holatda,
                                    ortiqcha yig'ilgan ballar kafedra hisobiga o'tadi.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @php
            $user = auth()->user();
            $pointUserInformations2 = \App\Models\PointUserDeportament::where('user_id', $user->id)
                ->orderBy('created_at', 'asc')
                ->get();

            $maqullanganCount = $pointUserInformations2->where('status', '1')->count();
            $kutushdaCount = $pointUserInformations2->where('status', '3')->count();
            $radEtilganCount = $pointUserInformations2->where('status', '0')->count();

            // Kunlik statistika
            $dailyStats = $pointUserInformations2
                ->groupBy(function($item) {
                    return $item->created_at->format('Y-m-d');
                })
                ->map(function($items) {
                    return [
                        'total' => $items->count(),
                        'accepted' => $items->where('status', '1')->count(),
                        'rejected' => $items->where('status', '0')->count()
                    ];
                });
        @endphp
            <div class="bg-white rounded-xl shadow-lg overflow-hidden p-6">
                <!-- Grid layout for charts -->
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <!-- Left side - Profile section -->
                    <div class="relative rounded-xl shadow-lg overflow-hidden" style="min-height: 450px;">
                        <!-- Background image with gradient -->
                        <div class="absolute inset-0">
                            <div class="absolute inset-0 h-48 bg-cover bg-center bg-no-repeat"
                                style="background-image: url('{{asset('assets/images/surat_profile.webp')}}');">
                            </div>
                            <div class="absolute inset-0 h-48 bg-gradient-to-r from-blue-900/50 to-blue-600/50 backdrop-blur-sm"></div>
                        </div>

                        <!-- Profile content -->
                        <div class="relative pt-52 pb-6 px-6 flex flex-col items-center justify-start h-full">
                            <!-- Profile image container -->
                            <div class="relative w-32 h-32 rounded-full overflow-hidden border-4 border-white shadow-lg mb-4"
                                 style="margin-top: -64px;">
                                <img src="{{ $user->image ? asset('storage/users/image/' . $user->image) : 'https://www.svgrepo.com/show/192244/man-user.svg' }}"
                                     alt="Profile"
                                     class="w-full h-full object-cover"/>
                            </div>

                            <!-- Profile info -->
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $user->name }}</h3>
                            <p class="text-gray-600 mb-6">Statistika ma'lumotlari</p>

                            <!-- Stats -->
                            <div class="grid grid-cols-3 gap-4 w-full text-center">
                                <div class="bg-white shadow-md rounded-lg p-4">
                                    <div class="font-bold text-green-500 text-lg">{{ $maqullanganCount }}</div>
                                    <div class="text-sm text-gray-600">Maqullangan</div>
                                </div>
                                <div class="bg-white shadow-md rounded-lg p-4">
                                    <div class="font-bold text-blue-500 text-lg">{{ $kutushdaCount }}</div>
                                    <div class="text-sm text-gray-600">Kutilmoqda</div>
                                </div>
                                <div class="bg-white shadow-md rounded-lg p-4">
                                    <div class="font-bold text-red-500 text-lg">{{ $radEtilganCount }}</div>
                                    <div class="text-sm text-gray-600">Rad etilgan</div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Right side - Pie Chart -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex justify-between items-start w-full mb-4">
                            <h5 class="text-lg font-bold text-gray-900">Ma'lumotlar holati</h5>
                            <div class="flex gap-4 flex-wrap">
                                <span class="flex items-center">
                                    <span class="w-3 h-3 rounded-full bg-green-500 mr-2"></span>
                                    <span class="text-sm text-gray-600">Maqullangan ({{ $maqullanganCount }})</span>
                                </span>
                                <span class="flex items-center">
                                    <span class="w-3 h-3 rounded-full bg-blue-500 mr-2"></span>
                                    <span class="text-sm text-gray-600">Kutilmoqda ({{ $kutushdaCount }})</span>
                                </span>
                                <span class="flex items-center">
                                    <span class="w-3 h-3 rounded-full bg-red-500 mr-2"></span>
                                    <span class="text-sm text-gray-600">Rad etilgan ({{ $radEtilganCount }})</span>
                                </span>
                            </div>
                        </div>
                        <div id="pie-chart" style="min-height: 400px;"></div>
                    </div>
                </div>
                <!-- Bottom - Stacked Area Chart -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex justify-between items-start w-full mb-4">
                        <h5 class="text-lg font-bold text-gray-900">Kunlik ma'lumotlar statistikasi</h5>
                    </div>
                    <div id="stacked-chart" style="min-height: 400px;"></div>
                </div>
            </div>

            <script>
                // Pie Chart Configuration
                const pieChartOptions = {
                    series: [{{ $maqullanganCount }}, {{ $kutushdaCount }}, {{ $radEtilganCount }}],
                    chart: {
                        type: 'donut',
                        height: 400,
                        toolbar: {
                            show: true
                        }
                    },
                    colors: ['#22C55E', '#3B82F6', '#EF4444'],
                    labels: ['Maqullangan', 'Kutish jarayonida', 'Rad etilgan'],
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '70%',
                                labels: {
                                    show: true,
                                    total: {
                                        show: true,
                                        label: 'Jami',
                                        formatter: function(w) {
                                            return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                                        }
                                    }
                                }
                            }
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: function(val) {
                            return Math.round(val) + '%'
                        }
                    },
                    legend: {
                        position: 'bottom',
                        offsetY: 0,
                        height: 40
                    }
                };
// Stacked Column Chart Configuration
const stackedColumnChartOptions = {
    series: [{
        name: 'Jami yuborilgan',
        data: @json($dailyStats->pluck('total'))
    }, {
        name: 'Maqullangan',
        data: @json($dailyStats->pluck('accepted'))
    }, {
        name: 'Rad etilgan',
        data: @json($dailyStats->pluck('rejected'))
    }],
    chart: {
        type: 'bar',
        height: 400,
        stacked: true,
        toolbar: {
            show: true
        },
        zoom: {
            enabled: true
        }
    },
    colors: ['#FFB547', '#22C55E', '#EF4444'],  // Sariq, Yashil, Qizil
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: '20%',  // Ustun kengligi kamaytirildi
            borderRadius: 0,
            distributed: false,
            rangeBarOverlap: true,
            rangeBarGroupRows: false,
            barHeight: '70%',    // Bar balandligi
            isDumbbell: false,
            isFunnel: false,
            isCylinder: false,
            isVertical: true,
            dataLabels: {
                position: 'top'
            }
        },
    },
    dataLabels: {
        enabled: true,
        formatter: function(val) {
            return val > 0 ? val : '';
        },
        style: {
            fontSize: '12px'
        }
    },
    stroke: {
        show: true,
        width: 1,
        colors: ['transparent']
    },
    grid: {
        show: true,
        xaxis: {
            lines: {
                show: false
            }
        },
        yaxis: {
            lines: {
                show: true
            }
        }
    },
    xaxis: {
        categories: @json($dailyStats->keys()),
        labels: {
            rotate: -45,
            rotateAlways: true,
            style: {
                fontSize: '12px'
            }
        },
        tickPlacement: 'on',
        axisTicks: {
            show: true
        },
        axisBorder: {
            show: true
        }
    },
    yaxis: {
        title: {
            text: "Ma'lumotlar soni"
        },
        min: 0,
        max: function(max) {
            return max + 1;  // Y o'qining maksimal qiymatini bir birlikka oshirish
        }
    },
    fill: {
        opacity: 1
    },
    legend: {
        position: 'top',
        horizontalAlign: 'center'
    },
    tooltip: {
        shared: true,
        intersect: false,
        y: {
            formatter: function(val) {
                return val + " ta"
            }
        }
    }
};

// Render Charts
document.addEventListener("DOMContentLoaded", function() {
    if (document.getElementById('pie-chart')) {
        const pieChart = new ApexCharts(document.getElementById('pie-chart'), pieChartOptions);
        pieChart.render();
    }

    if (document.getElementById('stacked-chart')) {
        const stackedColumnChart = new ApexCharts(document.getElementById('stacked-chart'), stackedColumnChartOptions);
        stackedColumnChart.render();
    }
});
            </script>


            {{-- <!-- Ma'lumotlar ro'yxati -->
            <div class="bg-white rounded-xl shadow-lg">
                @include('dashboard.item_list_component')
            </div> --}}
            <div class="mt-2 mb-4">
                @include('dashboard.item_list_component')
            </div>

        </div>
    </div>
</x-app-layout>

<style>
    /* Card hover effects */
    .rounded-xl:hover {
        transform: translateY(-2px);
        transition: transform 0.2s ease-in-out;
    }

    /* Stats number animation */
    @keyframes countUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .text-3xl {
        animation: countUp 0.5s ease-out forwards;
    }
</style>
<style>
    /* Modal va chart z-index fix */
    .apexcharts-canvas {
        z-index: 0 !important;
    }

    .apexcharts-tooltip {
        z-index: 1 !important;
    }

    .apexcharts-toolbar {
        z-index: 2 !important;
    }

    #default-modal {
        z-index: 9999 !important;
    }

    /* Existing styles... */
</style>
