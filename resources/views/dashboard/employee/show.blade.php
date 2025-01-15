<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl shadow-lg">
            <h2 class="py-6 text-2xl font-bold text-center text-white">
                {{ ucwords(strtolower($employee->FullName ?? 'Kuzatuvchi')) }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <!-- Profile Header Section -->
                <div class="relative">
                    <!-- Background Pattern -->

                    <div class="absolute inset-0 h-48 bg-cover bg-center bg-no-repeat bg-fixed"
                        style="background-image: url('{{ '/assets/images/surat_profile.webp' }}'); background-position: center 25%;">
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-900/50 to-blue-600/50 backdrop-blur-sm">
                        </div>
                    </div>
                    <!-- Profile Content -->
                    <div class="relative px-8">
                        <!-- Profile Image and Basic Info -->
                        <div class="flex flex-col md:flex-row items-center gap-8">
                            <!-- Profile Image -->
                            <div class="shrink-0 mt-6">
                                <img class="w-40  rounded-xl shadow-lg border-4 border-white object-cover"
                                    src="{{ $employee->image ? asset('storage/users/image/' . $employee->image) : 'https://otm.cspu.uz/storage/users/image/image_1729094435_670fe3233b26d.jpg' }}"
                                    alt="{{ $employee->FullName }}">
                            </div>

                            <!-- Basic Stats -->
                            <div class="flex-1 text-center md:text-left">
                                <!-- Rating Card -->
                                <div class="inline-flex items-center bg-white rounded-xl shadow-md p-4 mb-4">
                                    <div class="mr-4">
                                        <div class="text-sm text-gray-500">Umumiy ball</div>
                                        <div class="text-2xl font-bold text-blue-600">
                                            {{ round($employee->department->point_user_deportaments()->where('user_id', $employee->id)->where('status', 1)->sum('point'),2) }}
                                        </div>
                                    </div>
                                    <div class="w-px h-12 bg-gray-200 mx-4"></div>
                                    <div>
                                        <div class="text-sm text-gray-500">Kafedraga o'tgan</div>
                                        <div class="text-2xl font-bold text-indigo-600">{{ $departamentPointTotal }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Detailed Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                            <!-- Personal Information Card -->
                            <div class="bg-white rounded-xl shadow-md p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Shaxsiy ma'lumotlar</h3>
                                <div class="space-y-3">
                                    <div class="flex items-center py-2">
                                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="m6.072 10.072 2 2 6-4m3.586 4.314.9-.9a2 2 0 0 0 0-2.828l-.9-.9a2 2 0 0 1-.586-1.414V5.072a2 2 0 0 0-2-2H13.8a2 2 0 0 1-1.414-.586l-.9-.9a2 2 0 0 0-2.828 0l-.9.9a2 2 0 0 1-1.414.586H5.072a2 2 0 0 0-2 2v1.272a2 2 0 0 1-.586 1.414l-.9.9a2 2 0 0 0 0 2.828l.9.9a2 2 0 0 1 .586 1.414v1.272a2 2 0 0 0 2 2h1.272a2 2 0 0 1 1.414.586l.9.9a2 2 0 0 0 2.828 0l.9-.9a2 2 0 0 1 1.414-.586h1.272a2 2 0 0 0 2-2V13.8a2 2 0 0 1 .586-1.414Z" />
                                        </svg>
                                        <span class="text-gray-600"> {{ $employee->FullName }}</span>
                                    </div>
                                    <div class="flex items-center py-2">
                                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        <span
                                            class="text-gray-600">{{ $employee->phone ? $employee->phone : 'Nomalum!' }}</span>
                                    </div>
                                    <div class="flex items-center py-2">
                                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <span class="text-gray-600">{{ $employee->department->name }} kafedrasi</span>
                                    </div>
                                    <div class="flex items-center py-2">
                                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 1v3m5-3v3m5-3v3M1 7h18M5 11h10M2 3h16a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1Z" />
                                        </svg>
                                        <span class="text-gray-600">OTMda <b>{{ $employee->year_of_enter }}</b> yildan
                                            beri ishlaydi</span>
                                    </div>
                                    <div class="flex items-center py-2">
                                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 16.5A2.493 2.493 0 0 1 6.51 18H6.5a2.468 2.468 0 0 1-2.4-3.154 2.98 2.98 0 0 1-.85-5.274 2.468 2.468 0 0 1 .921-3.182 2.477 2.477 0 0 1 1.875-3.344 2.5 2.5 0 0 1 3.41-1.856A2.5 2.5 0 0 1 11 3.5m0 13v-13m0 13a2.492 2.492 0 0 0 4.49 1.5h.01a2.467 2.467 0 0 0 2.403-3.154 2.98 2.98 0 0 0 .847-5.274 2.468 2.468 0 0 0-.921-3.182 2.479 2.479 0 0 0-1.875-3.344A2.5 2.5 0 0 0 13.5 1 2.5 2.5 0 0 0 11 3.5m-8 5a2.5 2.5 0 0 1 3.48-2.3m-.28 8.551a3 3 0 0 1-2.953-5.185M19 8.5a2.5 2.5 0 0 0-3.481-2.3m.28 8.551a3 3 0 0 0 2.954-5.185" />
                                        </svg>
                                        <span class="text-gray-600">Ilmiy unvoni:
                                            <b>{{ $employee->academicDegree_name ? $employee->academicDegree_name : 'Nomalum!' }}</b></span>
                                    </div>
                                    <div class="flex items-center py-2">
                                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-gray-600">{{ $employee->birth_date }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Statistics Card -->
                            <div class="bg-white rounded-xl shadow-md p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistika</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div class="text-sm text-gray-500">Yuborilgan ma'lumotlar</div>
                                        <div class="text-2xl font-bold text-gray-900">
                                            {{ $employee->department->point_user_deportaments->where('user_id', $employee->id)->count() }}
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div class="text-sm text-gray-500">Qabul qilingan</div>
                                        <div class="text-2xl font-bold text-gray-900">
                                            {{ $employee->department->point_user_deportaments->where('user_id', $employee->id)->where('status', 1)->count() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg overflow-hidden">

                    <!-- Line Chart -->
                    <div class="max-w-full w-full bg-white rounded-xl shadow-lg p-6">
                        <div class="flex justify-between items-start w-full mb-4">
                            <h5 class="text-lg font-bold text-gray-900">Kunlik ma'lumotlar statistikasi</h5>
                        </div>
                        <div id="line-chart" class="w-full" style="min-height: 400px;"></div>
                    </div>


                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            if (document.getElementById('line-chart')) {
                                const lineChartOptions = {
                                    series: [{
                                        name: 'Jami yuborilgan',
                                        data: @json($dailyStats->pluck('total')->values())
                                    }, {
                                        name: 'Maqullangan',
                                        data: @json($dailyStats->pluck('accepted')->values())
                                    }, {
                                        name: 'Rad etilgan',
                                        data: @json($dailyStats->pluck('rejected')->values())
                                    }],
                                    chart: {
                                        type: 'bar',
                                        height: 400,
                                        toolbar: {
                                            show: true,
                                            tools: {
                                                download: true,
                                                selection: false,
                                                zoom: true,
                                                zoomin: true,
                                                zoomout: true,
                                                pan: true,
                                            }
                                        }
                                    },
                                    plotOptions: {
                                        bar: {
                                            horizontal: false,
                                            columnWidth: '55%',
                                            borderRadius: 2,
                                            dataLabels: {
                                                position: 'top'
                                            }
                                        },
                                    },
                                    colors: ['#4F46E5', '#22C55E', '#EF4444'],
                                    dataLabels: {
                                        enabled: true,
                                        formatter: function(val) {
                                            return val > 0 ? val : '';
                                        },
                                        style: {
                                            fontSize: '12px',
                                            fontWeight: 600,
                                            colors: ["#333"]
                                        },
                                        offsetY: -20
                                    },
                                    xaxis: {
                                        categories: @json($dailyStats->keys()),
                                        labels: {
                                            rotate: -45,
                                            rotateAlways: true,
                                            style: {
                                                fontSize: '12px',
                                                fontWeight: 500
                                            },
                                            formatter: function(value) {
                                                const date = new Date(value);
                                                const day = String(date.getDate()).padStart(2, '0');
                                                const month = String(date.getMonth() + 1).padStart(2, '0');
                                                return `${day}.${month}`;
                                            }
                                        },
                                        tickAmount: 10
                                    },
                                    yaxis: {
                                        title: {
                                            text: "Ma'lumotlar soni",
                                            style: {
                                                fontSize: '14px',
                                                fontWeight: 500
                                            }
                                        },
                                        labels: {
                                            formatter: function(val) {
                                                return Math.round(val);
                                            }
                                        },
                                        min: 0
                                    },
                                    tooltip: {
                                        shared: true,
                                        intersect: false,
                                        y: {
                                            formatter: function(val) {
                                                return val + " ta";
                                            }
                                        },
                                        x: {
                                            formatter: function(value) {
                                                const date = new Date(value);
                                                return date.toLocaleDateString('uz-UZ', {
                                                    year: 'numeric',
                                                    month: '2-digit',
                                                    day: '2-digit'
                                                });
                                            }
                                        }
                                    },
                                    legend: {
                                        position: 'top',
                                        horizontalAlign: 'center',
                                        offsetY: 0,
                                        fontSize: '14px'
                                    }
                                };

                                const lineChart = new ApexCharts(document.getElementById('line-chart'), lineChartOptions);
                                lineChart.render();
                            }
                        });
                    </script>
                </div>

                <!-- Submitted Documents Section -->
                <div class="mt-8 px-8 pb-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Yuklangan hujjatlar</h3>
                    @include('dashboard.item_list_component')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<style>
    /* Gradient Animation */
    .gradient-animation {
        background-size: 200% 200%;
        animation: gradient 15s ease infinite;
    }

    @keyframes gradient {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }
</style>
