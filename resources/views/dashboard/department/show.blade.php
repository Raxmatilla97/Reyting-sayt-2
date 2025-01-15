<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg shadow-lg">
            <h2 class="py-6 text-3xl font-bold text-center text-white leading-tight">
                {{ $department->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl">
                <div class="p-6 bg-white">
                    <div x-data="{
                        activeTab: {{ request()->has('page') ? "'yuborilgan'" : "'about_us'" }}
                    }" class="container mx-auto">
                        <!-- Mobile Tab Dropdown with better styling -->
                        <div class="sm:hidden mb-6">
                            <select x-model="activeTab"
                                class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white shadow-sm">
                                <option value="about_us">📊 Kafedra haqida</option>
                                <option value="department_list">👥 O'qituvchilar ro'yxati</option>
                                <option value="yuborilgan">📝 Yuborilgan ma'lumotlar</option>
                            </select>
                        </div>

                        <!-- Desktop Tabs with animation -->
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
                                    Kafedra haqida
                                </button>
                                <button @click="activeTab = 'department_list'"
                                    :class="{ 'bg-white shadow-md transform scale-105': activeTab === 'department_list' }"
                                    class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition duration-200 ease-in-out hover:bg-white hover:shadow-md">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                    O'qituvchilar ro'yxati
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

                        <!-- Improved Tab Contents with transitions -->
                        <div class="mt-8" x-cloak>
                            <!-- About Us Tab -->
                            <div x-show="activeTab === 'about_us'" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform translate-x-4"
                                x-transition:enter-end="opacity-100 transform translate-x-0">
                                <!-- Existing about_us content with enhanced shadows and animations -->
                                <div
                                    class="bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-shadow duration-300">
                                    <div x-show="activeTab === 'about_us'" class="space-y-6">
                                        <h2 class="text-2xl font-bold text-gray-900">Kafedra haqida</h2>

                                        <!-- Kafedra asosiy ma'lumotlari -->
                                        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                                            <div class="flex flex-col lg:flex-row">
                                                <!-- Chap taraf - Rasm va nom -->
                                                <div
                                                    class="lg:w-1/3 p-8 bg-gradient-to-br from-blue-600 to-indigo-600 text-white">
                                                    <div
                                                        class="flex flex-col items-center justify-center h-full space-y-4">
                                                        <div class="relative">
                                                            <div
                                                                class="absolute inset-0 bg-white rounded-full opacity-10 animate-pulse">
                                                            </div>
                                                            <img src="{{ asset('assets/images/logo3.png') }}"
                                                                alt="Kafedra Logo"
                                                                class="w-48 h-48 object-cover rounded-full border-4 border-white shadow-2xl">
                                                        </div>
                                                        <h1 class="text-3xl font-bold text-center">
                                                            {{ $department->name }}</h1>
                                                    </div>
                                                </div>

                                                <!-- O'ng taraf - Statistika -->
                                                <div class="lg:w-2/3 p-8 bg-gray-50">
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                        <!-- O'qituvchilar soni -->
                                                        <div
                                                            class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                                                            <div class="flex items-center gap-4">
                                                                <div class="p-3 bg-blue-100 rounded-full">
                                                                    <svg class="w-6 h-6 text-blue-600" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                                                        </path>
                                                                    </svg>
                                                                </div>
                                                                <div>
                                                                    <p class="text-sm text-gray-500">O'qituvchilar soni
                                                                    </p>
                                                                    <div class="flex gap-2 mt-1">
                                                                        <span
                                                                            class="bg-blue-100 text-blue-800 text-sm font-medium px-2.5 py-0.5 rounded">
                                                                            Saytda ro'yxatdan o'tganlar:
                                                                            {{ $totalEmployees }}
                                                                        </span>
                                                                    </div>
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
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                                                        </path>
                                                                    </svg>
                                                                </div>
                                                                <div>
                                                                    <p class="text-sm text-gray-500">Kafedra reytingi balli
                                                                    </p>
                                                                    <span
                                                                        class="bg-green-100 text-green-800 text-lg font-medium px-2.5 py-0.5 rounded">
                                                                        {{ $totalPoints }} ball
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div> --}}

                                                        @include('dashboard.department.custom_points')

                                                        <!-- Kafedraga o'tgan ballar -->
                                                        <div
                                                            class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                                                            <div class="flex items-center gap-4">
                                                                <div class="p-3 bg-indigo-100 rounded-full">
                                                                    <svg class="w-6 h-6 text-indigo-600" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                        </path>
                                                                    </svg>
                                                                </div>
                                                                <div>
                                                                    <p class="text-sm text-gray-500">Kafedra
                                                                        o'qituvchilari ballari</p>
                                                                    <span
                                                                        class="bg-indigo-100 mr-2 text-indigo-800 text-sm font-medium px-1 py-0.5 rounded">
                                                                        {{ $departmentExtraPoints }}
                                                                    </span>
                                                                    +
                                                                    <span
                                                                        class="bg-green-100 ml-2 mr-2 text-green-800 text-sm font-medium px-1 py-0.5 rounded">
                                                                        {{ $teacherTotalPoints }}
                                                                    </span>
                                                                    =
                                                                    <span
                                                                        class="bg-green-100 ml-2 text-green-800 text-sm font-medium px-1 py-0.5 rounded">
                                                                        {{ $totalWithExtra }} ball
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Ma'lumotlar soni -->
                                                        <div
                                                            class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                                                            <div class="flex items-center gap-4">
                                                                <div class="p-3 bg-yellow-100 rounded-full">
                                                                    <svg class="w-6 h-6 text-yellow-600"
                                                                        fill="none" stroke="currentColor"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                                        </path>
                                                                    </svg>
                                                                </div>
                                                                <div>
                                                                    <p class="text-sm text-gray-500">Yuborilgan
                                                                        ma'lumotlar</p>
                                                                    <span
                                                                        class="bg-yellow-100 text-yellow-800 text-lg font-medium px-2.5 py-0.5 rounded">
                                                                        {{ $totalInfos }} ta qabul qilingan
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Oxirgi ma'lumotlar -->
                                                    <div class="mt-6 bg-white p-6 rounded-lg shadow-sm">
                                                        <div class="flex items-center justify-between">
                                                            <div>
                                                                <p class="text-sm text-gray-500">Oxirgi ma'lumot</p>
                                                                <p class="text-lg font-medium text-gray-800">
                                                                    {{ $timeAgo }}
                                                                </p>
                                                            </div>
                                                            <div class="text-right">
                                                                <p class="text-sm text-gray-500">Ma'lumot egasi</p>
                                                                <span
                                                                    class="bg-indigo-100 text-indigo-800 text-lg font-medium px-2.5 py-0.5 rounded">
                                                                    {{ $fullName }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>


                                            </div>
                                            <!-- Hisob-kitob ma'lumotlari -->
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
                                    </div>
                                </div>
                            </div>

                            <!-- Department List Tab -->
                            <div x-show="activeTab === 'department_list'"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform translate-x-4"
                                x-transition:enter-end="opacity-100 transform translate-x-0">
                                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gradient-to-r from-blue-600 to-indigo-600">
                                            <tr>
                                                <th scope="col"
                                                    class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">
                                                    O'qituvchi F.I.SH
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">
                                                    Yuborilgan ma'lumotlar soni
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">
                                                    To'plagan ballari
                                                </th>

                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($department->employee->where('status', 1) as $employee)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="flex-shrink-0 h-10 w-10">
                                                                <span
                                                                    class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-indigo-100 text-indigo-800 font-medium text-lg">
                                                                    {{ substr($employee->FullName, 0, 2) }}
                                                                </span>
                                                            </div>
                                                            <div class="ml-4">
                                                                <a href="{{ route('dashboard.employeeShow', ['id_employee' => $employee->employee_id_number]) }}"
                                                                    class="text-sm font-medium text-gray-900 hover:text-indigo-600">
                                                                    {{ ucwords(strtolower($employee->FullName)) }}
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $employee->department->point_user_deportaments()->where('status', 1)->where('user_id', $employee->id)->count() }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                            {{ $employee->department->point_user_deportaments()
                                                                ->whereHas('employee', function($q) {
                                                                    $q->where('status', 1);  // Faqat aktiv o'qituvchilar
                                                                })
                                                                ->where('status', 1)  // Tasdiqlangan ma'lumotlar
                                                                ->where('user_id', $employee->id)  // Shu o'qituvchining ma'lumotlari
                                                                ->sum('point') }}
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
                                <div x-show="activeTab === 'yuborilgan'" class="space-y-6">
                                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Kafedra o'qituvchilari tomonidan
                                        yuborilgan ma'lumotlar</h3>
                                    <div class="bg-white overflow-hidden shadow-lg rounded-lg">

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
            document.addEventListener('DOMContentLoaded', function() {
                // Agar URL da 'page' parametri bo'lsa, 'yuborilgan' tabini tanlash
                let initialTab = window.location.search.includes('page=') ? 'yuborilgan' : 'about_us';

                // Alpine.js data
                window.Alpine.data('tabs', () => ({
                    activeTab: initialTab,
                    init() {
                        this.$watch('activeTab', value => {
                            localStorage.setItem('activeTab', value);
                        })
                    }
                }));
            });
        </script>
    @endpush
</x-app-layout>
