<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg shadow-lg">
            <h2 class="py-6 text-3xl font-bold text-center text-white leading-tight">
                {{ __("Fakultet ma'lumotlarini to'liq ko'rish") }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl">
                <div class="p-6 bg-white">
                    <div x-data="{
                        activeTab: window.location.search.includes('page=') ? 'yuborilgan' : localStorage.getItem('activeTab') || 'about_us'
                    }"
                    class="container mx-auto"
                    @load.window="() => {
                        $watch('activeTab', value => localStorage.setItem('activeTab', value))
                    }">
                        <!-- Mobile Tab Dropdown -->
                        <div class="sm:hidden mb-6">
                            <select x-model="activeTab"
                                class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white shadow-sm">
                                <option value="about_us">📊 Fakultet haqida</option>
                                <option value="department_list">👥 Fakultet kafedralari</option>
                                <option value="yuborilgan">📝 Yuborilgan ma'lumotlar</option>
                            </select>
                        </div>

                        <!-- Desktop Tabs -->
                        <div class="hidden sm:block mb-6">
                            <nav class="flex space-x-4 p-1 bg-gray-100 rounded-xl" aria-label="Tabs">
                                <button @click="activeTab = 'about_us'"
                                    :class="{ 'bg-white shadow-md transform scale-105': activeTab === 'about_us' }"
                                    class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition duration-200 ease-in-out hover:bg-white hover:shadow-md">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                        </path>
                                    </svg>
                                    Fakultet haqida
                                </button>
                                <button @click="activeTab = 'department_list'"
                                    :class="{ 'bg-white shadow-md transform scale-105': activeTab === 'department_list' }"
                                    class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition duration-200 ease-in-out hover:bg-white hover:shadow-md">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                    Fakultet kafedralari
                                </button>
                                <button @click="activeTab = 'yuborilgan'"
                                    :class="{ 'bg-white shadow-md transform scale-105': activeTab === 'yuborilgan' }"
                                    class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition duration-200 ease-in-out hover:bg-white hover:shadow-md">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    Yuborilgan ma'lumotlar
                                </button>
                            </nav>
                        </div>

                        <!-- Tab Contents with transitions -->
                        <div class="mt-8" x-cloak>
                            <!-- About Us Tab -->
                            <div x-show="activeTab === 'about_us'" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform translate-x-4"
                                x-transition:enter-end="opacity-100 transform translate-x-0">
                                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                                    <div class="flex flex-col lg:flex-row">
                                        <!-- Left side - Image and title -->
                                        <div
                                            class="lg:w-1/3 p-8 bg-gradient-to-br from-blue-600 to-indigo-600 text-white">
                                            <div class="flex flex-col items-center justify-center h-full space-y-4">
                                                <div class="relative">
                                                    <div
                                                        class="absolute inset-0 bg-white rounded-full opacity-10 animate-pulse">
                                                    </div>
                                                    <img src="https://cdn1.iconfinder.com/data/icons/got-idea-vol-2/128/branches-1024.png"
                                                        alt="Logo"
                                                        class="w-48 h-48 object-cover rounded-full border-4 border-white shadow-2xl">
                                                </div>
                                                <h1 class="text-3xl font-bold text-center">{{ $faculty->name }}</h1>
                                            </div>
                                        </div>

                                        <!-- Right side - Statistics -->
                                        <div class="lg:w-2/3 p-8 bg-gray-50">
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <!-- O'qituvchilar soni -->
                                                <div
                                                    class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                                                    <div class="flex items-center gap-4">
                                                        <div class="p-3 bg-blue-100 rounded-full">
                                                            <svg class="w-6 h-6 text-blue-600" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                                                </path>
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm text-gray-500">O'qituvchilar soni</p>
                                                            <span
                                                                class="bg-blue-100 text-blue-800 text-lg font-medium px-2.5 py-0.5 rounded">
                                                                {{ $totalTeachers }} nafar
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Umumiy ballar -->
                                                {{-- <div
                                                    class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                                                    <div class="flex items-center gap-4">
                                                        <div class="p-3 bg-green-100 rounded-full">
                                                            <svg class="w-6 h-6 text-green-600" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                                                </path>
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm text-gray-500">Umumiy ballar</p>
                                                            <span
                                                                class="bg-green-100 text-green-800 text-lg font-medium px-2.5 py-0.5 rounded">
                                                                {{ $totalPoints }} ball
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div> --}}

                                                @include('dashboard.faculty.custom_points')

                                                <!-- Ma'lumotlar soni -->
                                                <div
                                                    class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                                                    <div class="flex items-center gap-4">
                                                        <div class="p-3 bg-yellow-100 rounded-full">
                                                            <svg class="w-6 h-6 text-yellow-600" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                                </path>
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm text-gray-500">Yuborilgan ma'lumotlar</p>
                                                            <span
                                                                class="bg-yellow-100 text-yellow-800 text-lg font-medium px-2.5 py-0.5 rounded">
                                                                {{ $totalInfos }} ta qabul qilingan
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Oxirgi ma'lumot -->
                                                <div
                                                    class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                                                    <div class="flex items-center gap-4">
                                                        <div class="p-3 bg-indigo-100 rounded-full">
                                                            <svg class="w-6 h-6 text-indigo-600" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                </path>
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm text-gray-500">Oxirgi ma'lumot</p>
                                                            <span
                                                                class="bg-indigo-100 text-indigo-800 text-sm font-medium px-2.5 py-0.5 rounded">
                                                                {{ $timeAgo }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Hisob-kitob ma'lumotlari -->
                                            <div class="p-4 mt-4 text-sm rounded-lg bg-blue-50 shadow-md"
                                                role="alert">
                                                <!-- Kafedraga o'tgan ballar -->
                                                <div
                                                    class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                                                    <div class="p-6">
                                                        <!-- Header qismi -->
                                                        <div class="flex items-start space-x-4">
                                                            <!-- Icon container -->
                                                            <div class="flex-shrink-0">
                                                                <div
                                                                    class="p-3 bg-gradient-to-br from-indigo-100 to-indigo-200 rounded-xl shadow-sm">
                                                                    <svg class="w-6 h-6 text-indigo-600"
                                                                        fill="none" stroke="currentColor"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                        </path>
                                                                    </svg>
                                                                </div>
                                                            </div>

                                                            <!-- Content container -->
                                                            <div class="flex-1">
                                                                <h3 class="text-sm font-medium text-gray-500 mb-2.5">
                                                                    Oxirgi ma'lumotni yuborgan o'qituvchi
                                                                </h3>
                                                                <div class="flex flex-wrap items-center gap-2">
                                                                    <!-- O'qituvchi ismi -->
                                                                    <div
                                                                        class="inline-flex items-center bg-gradient-to-r from-indigo-50 to-indigo-100
                                                                                rounded-lg px-3 py-1.5 shadow-sm">
                                                                        <svg class="w-4 h-4 text-indigo-500 mr-2"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                                            </path>
                                                                        </svg>
                                                                        <span
                                                                            class="text-sm font-medium text-indigo-700">
                                                                            {{ $fullName }}
                                                                        </span>
                                                                    </div>

                                                                    <!-- Kafedra nomi -->
                                                                    <div
                                                                        class="inline-flex items-center bg-gradient-to-r from-indigo-50 to-indigo-100
                                                                                rounded-lg px-3 py-1.5 shadow-sm">
                                                                        <svg class="w-4 h-4 text-indigo-500 mr-2"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                                                            </path>
                                                                        </svg>
                                                                        <span
                                                                            class="text-sm font-medium text-indigo-700">
                                                                            {{ $departEmployee }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>


                                        </div>
                                    </div>
                                </div>


                                            <!-- Chartlar uchun container -->
                                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                                                <!-- Bar Chart -->
                                                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                                                    <div class="p-6">
                                                        <h2 class="text-lg font-semibold text-gray-700 mb-4">Kafedralar reytingi
                                                        </h2>
                                                        <div id="barChart" class="h-[400px]"></div>
                                                    </div>
                                                </div>
            
                                                <!-- Radar Chart -->
                                                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                                                    <div class="p-6">
                                                        <h2 class="text-lg font-semibold text-gray-700 mb-4">Yo'nalishlar bo'yicha
                                                            ma'lumotlar</h2>
                                                        <div id="radarChart" class="h-[400px]"></div>
                                                    </div>
                                                </div>
                                            </div>
            

                                <!-- HTML qismi -->
                                <div class="glitch-container relative mt-2">
                                    <!-- Asosiy kontent -->
                                    <div class="p-4 mt-4 text-sm rounded-lg bg-blue-50 shadow-md glitch-effect main-content" role="alert">

                                        {!! $pointsCalculationExplanation !!}
                                    </div>

                                    <!-- Harakatlanuvchi chiziqlar -->
                                    <div class="moving-lines"></div>
                                    <div class="moving-lines-reverse"></div>

                                    <!-- Glitch overlay -->
                                    <div class="glitch-overlay"></div>

                                    <!-- Alert overlay -->
                                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-20">
                                        <div class="cyber-alert">
                                            <div class="alert-content">
                                                <div class="alert-icon">⚠️</div>
                                                <div class="alert-text">Avtomatik hisob kitob funksiyasi o'chirildi!</div>
                                                <div class="alert-scanner"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- CSS qismi -->
                                <style>
                                .glitch-container {
                                    position: relative;
                                    overflow: hidden;
                                }

                                .main-content {
                                    animation: glitch 1s infinite;
                                    opacity: 0.5;
                                    filter: blur(3px);
                                    background: rgba(0, 0, 255, 0.1);
                                    position: relative;
                                    z-index: 1;
                                    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
                                }

                                .moving-lines, .moving-lines-reverse {
                                    position: absolute;
                                    top: 0;
                                    left: -100%;
                                    right: -100%;
                                    bottom: 0;
                                    width: 200%;
                                    z-index: 2;
                                    pointer-events: none;
                                }

                                .moving-lines {
                                    background: repeating-linear-gradient(
                                        -60deg,
                                        transparent,
                                        transparent 20px,
                                        rgba(255, 0, 0, 0.2) 20px,
                                        rgba(255, 0, 0, 0.2) 40px
                                    );
                                    animation: moveLines 4s linear infinite;
                                }

                                .moving-lines-reverse {
                                    background: repeating-linear-gradient(
                                        -60deg,
                                        transparent,
                                        transparent 20px,
                                        rgba(0, 255, 255, 0.1) 20px,
                                        rgba(0, 255, 255, 0.1) 40px
                                    );
                                    animation: moveLines 5s linear infinite;
                                }

                                .glitch-overlay {
                                    position: absolute;
                                    top: 0;
                                    left: 0;
                                    right: 0;
                                    bottom: 0;
                                    background: linear-gradient(90deg,
                                        rgba(0, 0, 255, 0.1),
                                        rgba(0, 255, 255, 0.1)
                                    );
                                    mix-blend-mode: overlay;
                                    animation: colorShift 3s infinite alternate;
                                    z-index: 3;
                                }

                                .cyber-alert {
                                    background: rgba(0, 100, 255, 0.9);
                                    padding: 1rem 2rem;
                                    border-radius: 8px;
                                    box-shadow:
                                        0 0 10px #0066ff,
                                        0 0 20px rgba(0, 100, 255, 0.5),
                                        0 0 30px rgba(0, 100, 255, 0.3);
                                    animation: alertPulse 2s infinite;
                                    position: relative;
                                    overflow: hidden;
                                }

                                .alert-content {
                                    display: flex;
                                    align-items: center;
                                    gap: 10px;
                                    position: relative;
                                }

                                .alert-icon {
                                    font-size: 1.5rem;
                                    animation: shake 0.5s infinite;
                                }

                                .alert-text {
                                    color: white;
                                    font-weight: bold;
                                    text-shadow: 0 0 5px rgba(255, 255, 255, 0.5);
                                    animation: textGlow 1s infinite alternate;
                                }

                                .alert-scanner {
                                    position: absolute;
                                    top: 0;
                                    left: -100%;
                                    width: 50%;
                                    height: 100%;
                                    background: linear-gradient(
                                        to right,
                                        transparent,
                                        rgba(255, 255, 255, 0.4),
                                        transparent
                                    );
                                    animation: scan 2s linear infinite;
                                }

                                @keyframes moveLines {
                                    0% {
                                        transform: translateX(0);
                                    }
                                    100% {
                                        transform: translateX(50%);
                                    }
                                }

                                @keyframes glitch {
                                    0% {
                                        transform: translate(0);
                                        filter: blur(3px);
                                    }
                                    20% {
                                        transform: translate(-2px, 2px);
                                        filter: blur(4px) hue-rotate(5deg);
                                    }
                                    40% {
                                        transform: translate(-2px, -2px);
                                        opacity: 0.6;
                                        filter: blur(5px) hue-rotate(-5deg);
                                    }
                                    60% {
                                        transform: translate(2px, 2px);
                                        filter: blur(6px) hue-rotate(5deg);
                                    }
                                    80% {
                                        transform: translate(2px, -2px);
                                        opacity: 0.4;
                                        filter: blur(5px) hue-rotate(-5deg);
                                    }
                                    100% {
                                        transform: translate(0);
                                        filter: blur(3px);
                                    }
                                }

                                @keyframes colorShift {
                                    0% {
                                        opacity: 0.3;
                                        background-position: 0% 50%;
                                    }
                                    100% {
                                        opacity: 0.5;
                                        background-position: 100% 50%;
                                    }
                                }

                                @keyframes alertPulse {
                                    0%, 100% {
                                        transform: scale(1);
                                        box-shadow:
                                            0 0 10px #0066ff,
                                            0 0 20px rgba(0, 100, 255, 0.5),
                                            0 0 30px rgba(0, 100, 255, 0.3);
                                    }
                                    50% {
                                        transform: scale(1.05);
                                        box-shadow:
                                            0 0 15px #0066ff,
                                            0 0 25px rgba(0, 100, 255, 0.5),
                                            0 0 35px rgba(0, 100, 255, 0.3);
                                    }
                                }

                                @keyframes shake {
                                    0%, 100% { transform: translateX(0); }
                                    25% { transform: translateX(-1px) rotate(-5deg); }
                                    75% { transform: translateX(1px) rotate(5deg); }
                                }

                                @keyframes textGlow {
                                    0% {
                                        text-shadow: 0 0 5px rgba(255, 255, 255, 0.5);
                                    }
                                    100% {
                                        text-shadow: 0 0 10px rgba(255, 255, 255, 0.8),
                                                    0 0 15px rgba(255, 255, 255, 0.5);
                                    }
                                }

                                @keyframes scan {
                                    0% {
                                        left: -100%;
                                    }
                                    100% {
                                        left: 200%;
                                    }
                                }
                                </style>


                            </div>

                            <!-- Department List Tab -->
                            <!-- Department List Tab -->
                            <div x-show="activeTab === 'department_list'"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform translate-x-4"
                                x-transition:enter-end="opacity-100 transform translate-x-0">
                                <div
                                    class="bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-shadow duration-300">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gradient-to-r from-blue-600 to-indigo-600">
                                            <tr>
                                                <th scope="col"
                                                    class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">
                                                    Kafedra nomi
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">
                                                    O'qituvchilar soni
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">
                                                    Yuborilgan ma'lumotlar
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">
                                                    To'plagan ballari
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($departments as $item)
                                                @php
                                                    $department = $item['department'];
                                                    $points = $item['points'];
                                                @endphp
                                                <tr class="hover:bg-blue-50 transition-colors duration-200">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="flex-shrink-0 h-12 w-12">
                                                                <span
                                                                    class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500 text-white">
                                                                    <span
                                                                        class="text-xl font-medium">{{ substr($department->name, 0, 2) }}</span>
                                                                </span>
                                                            </div>
                                                            <div class="ml-4">
                                                                <a href="{{ route('dashboard.departmentShow', ['slug' => $department->slug]) }}"
                                                                    class="text-lg font-medium text-gray-900 hover:text-indigo-600 transition-colors duration-200">
                                                                    {{ $department->name }}
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span
                                                            class="px-3 py-1 inline-flex text-lg leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                            {{ $points['teacher_count'] }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span
                                                            class="px-3 py-1 inline-flex text-lg leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                            {{ $points['approved_count'] }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span
                                                            class="px-3 py-1 inline-flex text-lg leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">

                                                            {{ $department->custom_points ?? $points['total_points']  }} ball
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Yuborilgan Tab -->
                            <div x-show="activeTab === 'yuborilgan'"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform translate-x-4"
                                x-transition:enter-end="opacity-100 transform translate-x-0">
                                <div class="space-y-6">
                                    <h3 class="text-2xl font-bold text-gray-900 mb-6">
                                        Fakultet o'qituvchilari tomonidan yuborilgan ma'lumotlar
                                    </h3>
                                    <div class="bg-white overflow-hidden shadow-xl rounded-2xl">
                                        @include('dashboard.item_list_component')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            [x-cloak] {
                display: none !important;
            }

            .tab-transition {
                transition: all 0.3s ease-in-out;
            }

            .hover-scale:hover {
                transform: scale(1.02);
                transition: transform 0.2s ease-in-out;
            }
        </style>
    @endpush


    @push('scripts')
    <script>
        // URL dan page parametrini tekshirish
        const hasPageParam = window.location.search.includes('page=');

        // Agar URL da page parametri bo'lsa activeTab ni yuborilgan ga o'zgartirish
        if (hasPageParam) {
            localStorage.setItem('activeTab', 'yuborilgan');
        }

        // Sahifa yuklanganda
        window.addEventListener('load', () => {
            const activeTab = hasPageParam ? 'yuborilgan' : (localStorage.getItem('activeTab') || 'about_us');

            // Alpine.js state ni yangilash
            if (window.Alpine) {
                const component = document.querySelector('[x-data]').__x;
                if (component) {
                    component.$data.activeTab = activeTab;
                }
            }
        });
    </script>

    <script>
        // Bar Chart
        var barOptions = {
            series: [{
                name: 'Reyting bali',
                data: @json($barChartData)
            }],
            chart: {
                type: 'bar',
                height: 350,
                fontFamily: 'Inter, sans-serif',
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                        selection: true,
                        zoom: true,
                        zoomin: true,
                        zoomout: true,
                        pan: true,
                        reset: true
                    }
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    borderRadius: 4,
                    columnWidth: '70%',
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return val.toFixed(1);
                },
                style: {
                    fontSize: '12px'
                }
            },
            colors: ['#4F46E5'],
            xaxis: {
                type: 'category',
                labels: {
                    style: {
                        fontSize: '12px',
                        fontWeight: 500,
                    },
                    rotate: -45,
                    rotateAlways: true,
                    trim: false,
                    maxHeight: 120
                }
            },
            yaxis: {
                title: {
                    text: 'Reyting bali',
                    style: {
                        fontSize: '13px',
                        fontWeight: 500
                    }
                },
                labels: {
                    formatter: function(val) {
                        return val.toFixed(1);
                    }
                },
                // Ballar orasidagi farqni normallashtirish
                min: function(min) {
                    return min * 0.8;
                },
                max: function(max) {
                    return max * 1.2;
                }
            },
            tooltip: {
                custom: function({
                    series,
                    seriesIndex,
                    dataPointIndex,
                    w
                }) {
                    const data = w.config.series[seriesIndex].data[dataPointIndex];
                    return '<div class="p-2">' +
                        '<div class="font-semibold">' + data.full_name + '</div>' +
                        '<div>Ball: ' + data.y.toFixed(2) + '</div>' +
                        '</div>';
                }
            }
        };

        // Chartlarni render qilish
        var barChart = new ApexCharts(document.querySelector("#barChart"), barOptions);


        barChart.render();
    </script>

    <script>
        // Radar Chart
        var radarOptions = {
            series: [{
                name: "Ma'lumotlar soni",
                data: @json($radarData['series'])
            }],
            chart: {
                type: 'radar',
                height: 350,
                fontFamily: 'Inter, sans-serif',
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                        zoom: true,
                        zoomin: true,
                        zoomout: true,
                        pan: true,
                        reset: true
                    },
                    export: {
                        csv: {
                            filename: 'Yo\'nalishlar bo\'yicha ma\'lumotlar',
                        },
                        svg: {
                            filename: 'Yo\'nalishlar bo\'yicha ma\'lumotlar',
                        },
                        png: {
                            filename: 'Yo\'nalishlar bo\'yicha ma\'lumotlar',
                        }
                    }
                }
            },
            plotOptions: {
                radar: {
                    size: 140,
                    polygons: {
                        strokeColor: '#e9e9e9',
                        fill: {
                            colors: ['#f8f8f8', '#fff']
                        }
                    }
                }
            },
            colors: ['#4F46E5'],
            stroke: {
                width: 2
            },
            fill: {
                opacity: 0.5
            },
            markers: {
                size: 5,
                hover: {
                    size: 7
                }
            },
            xaxis: {
                categories: @json($radarData['categories']),
                labels: {
                    style: {
                        fontSize: '11px',
                        fontWeight: 500
                    },
                    formatter: function(value) {
                        return value.toString();
                    }
                }
            },
            yaxis: {
                show: true,
                tickAmount: 6,
                labels: {
                    formatter: function(val) {
                        return val.toFixed(0);
                    }
                }
            },
            tooltip: {
                enabled: true,
                y: {
                    formatter: function(val) {
                        return val.toFixed(0) + ' ta';
                    }
                }
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        height: 300
                    }
                }
            }]
        };


        // Radar chartni render qilish
        var radarChart = new ApexCharts(document.querySelector("#radarChart"), radarOptions);
        radarChart.render();

        // Event listenerlarni qo'shish
        document.addEventListener("DOMContentLoaded", function() {
            const chartElement = document.querySelector("#radarChart");
            let scale = 1;
            const ZOOM_SPEED = 0.15; // Zoom tezligini oshirdik
            const MAX_ZOOM = 5; // Maksimal zoomni oshirdik
            const MIN_ZOOM = 0.5; // Minimal zoom

            chartElement.addEventListener('wheel', function(e) {
                e.preventDefault();

                const delta = e.deltaY > 0 ? -ZOOM_SPEED : ZOOM_SPEED;
                scale = Math.min(Math.max(MIN_ZOOM, scale + delta), MAX_ZOOM);

                // Chartni yangilash
                radarChart.updateOptions({
                    plotOptions: {
                        radar: {
                            size: 140 * scale
                        }
                    }
                });
            }, {
                passive: false
            });

            // Touch eventlar uchun
            let touchStartDistance = 0;

            chartElement.addEventListener('touchstart', function(e) {
                if (e.touches.length === 2) {
                    touchStartDistance = Math.hypot(
                        e.touches[0].pageX - e.touches[1].pageX,
                        e.touches[0].pageY - e.touches[1].pageY
                    );
                }
            });

            chartElement.addEventListener('touchmove', function(e) {
                if (e.touches.length === 2) {
                    e.preventDefault();

                    const distance = Math.hypot(
                        e.touches[0].pageX - e.touches[1].pageX,
                        e.touches[0].pageY - e.touches[1].pageY
                    );

                    const delta = (distance - touchStartDistance) * 0.015; // Touch zoom tezligini oshirdik
                    scale = Math.min(Math.max(MIN_ZOOM, scale + delta), MAX_ZOOM);

                    radarChart.updateOptions({
                        plotOptions: {
                            radar: {
                                size: 140 * scale
                            }
                        }
                    });

                    touchStartDistance = distance;
                }
            }, {
                passive: false
            });
        });
    </script>
@endpush
</x-app-layout>
