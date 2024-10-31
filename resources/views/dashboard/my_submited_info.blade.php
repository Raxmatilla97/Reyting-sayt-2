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
                    ->orderBy('created_at', 'desc')
                    ->get();

                $maqullanganCount = $pointUserInformations2->where('status', '1')->count();
                $kutushdaCount = $pointUserInformations2->where('status', '3')->count();
                $radEtilganCount = $pointUserInformations2->where('status', '0')->count();

                $totalCount = $maqullanganCount + $kutushdaCount + $radEtilganCount;
            @endphp
            <div class="bg-white rounded-xl shadow-lg overflow-hidden" style="z-index: 0;">
                <!-- Pie Chart -->
                <div class="max-w-full w-full bg-white rounded-xl shadow-lg p-6 mb-6">
                    <div class="flex justify-between items-start w-full mb-4">
                        <h5 class="text-lg font-bold text-gray-900">Ma'lumotlar holati</h5>
                        <div class="flex gap-4">
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
                    <div id="pie-chart" class="w-full" style="min-height: 400px;"></div>
                </div>

                <!-- Line Chart -->
                <div class="max-w-full w-full bg-white rounded-xl shadow-lg p-6">
                    <div class="flex justify-between items-start w-full mb-4">
                        <h5 class="text-lg font-bold text-gray-900">Kunlik ma'lumotlar statistikasi</h5>
                    </div>
                    <div id="line-chart" class="w-full" style="min-height: 400px;"></div>
                </div>

                @php
                    // Kunlik ma'lumotlarni tayyorlash
                    $dailyStats = $pointUserInformations
                        ->groupBy(function ($item) {
                            return $item->created_at->format('Y-m-d');
                        })
                        ->map(function ($items) {
                            return [
                                'total' => $items->count(),
                                'accepted' => $items->where('status', '1')->count(),
                                'pending' => $items->where('status', '3')->count(),
                                'rejected' => $items->where('status', '0')->count(),
                                                ];
                                            });
                @endphp

                <script>
                    // Pie Chart
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
                            formatter: function(val, opts) {
                                return Math.round(val) + '%'
                            }
                        },
                        legend: {
                            position: 'bottom',
                            offsetY: 0,
                            height: 40
                        }
                    };

                    // Line Chart
                    const dates = @json($dailyStats->keys());
                    const lineChartOptions = {
                        series: [{
                            name: 'Jami yuborilgan',
                            data: @json($dailyStats->pluck('total'))
                        }, {
                            name: 'Maqullangan',
                            data: @json($dailyStats->pluck('accepted'))
                        }, {
                            name: 'Kutilmoqda',
                            data: @json($dailyStats->pluck('pending'))
                        }, {
                            name: 'Rad etilgan',
                            data: @json($dailyStats->pluck('rejected'))
                        }],
                        chart: {
                            height: 400,
                            type: 'line',
                            toolbar: {
                                show: true
                            },
                            zoom: {
                                enabled: true
                            }
                        },
                        colors: ['#6B7280', '#22C55E', '#3B82F6', '#EF4444'],
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            curve: 'smooth',
                            width: 3
                        },
                        xaxis: {
                            categories: dates,
                            labels: {
                                rotate: -45,
                                rotateAlways: true
                            }
                        },
                        yaxis: {
                            title: {
                                text: "Ma'lumotlar soni"
                            }
                        },
                        legend: {
                            position: 'top'
                        },
                        grid: {
                            borderColor: '#f1f1f1'
                        },
                        tooltip: {
                            shared: true,
                            intersect: false
                        }
                    };

                    // Render Charts
                    document.addEventListener("DOMContentLoaded", function() {
                        if (document.getElementById('pie-chart')) {
                            const pieChart = new ApexCharts(document.getElementById('pie-chart'), pieChartOptions);
                            pieChart.render();
                        }

                        if (document.getElementById('line-chart')) {
                            const lineChart = new ApexCharts(document.getElementById('line-chart'), lineChartOptions);
                            lineChart.render();
                        }
                    });
                </script>
            </div>
            

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
