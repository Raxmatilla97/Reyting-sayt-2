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
                    <div x-data="{ activeTab: 'yuborilgan' }" class="container mx-auto">
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
                                                <div
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
                                                                {{ $totalPoints }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

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
                                                                {{ $totalInfos }} ta
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
                                                {{-- <!-- Kafedraga o'tgan ballar -->
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
                                                            <p class="text-sm text-gray-500">Fakultet ballari</p>
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
                                                </div> --}}

                                            </div>
                                        </div>
                                    </div>
                                </div>


                              {{-- {!! $pointsCalculationExplanation !!} --}}
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
                                                            <span class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500 text-white">
                                                                <span class="text-xl font-medium">{{ substr($department->name, 0, 2) }}</span>
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
                                                    <span class="px-3 py-1 inline-flex text-lg leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                        {{ $points['teacher_count'] }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-3 py-1 inline-flex text-lg leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        {{ $department->point_user_deportaments->count() }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-3 py-1 inline-flex text-lg leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                                        {{ $points['total_points'] }}
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
            document.addEventListener('DOMContentLoaded', function() {
                let initialTab = window.location.search.includes('page=') ? 'yuborilgan' : 'about_us';

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
