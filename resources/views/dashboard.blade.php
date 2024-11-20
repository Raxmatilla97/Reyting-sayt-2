<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg shadow-lg">
            <h2 class="py-6 text-2xl sm:text-3xl font-bold text-center text-white leading-tight">
                Salom {{ ucfirst(strtolower($auth->first_name)) }}!
                <span class="block text-xl sm:text-2xl mt-2 font-medium text-blue-100">
                    {{ __('OTM Ilmiy tadqiqot faoliyatini tashkil etish saytiga hush kelibsiz!') }}
                </span>
            </h2>
        </div>
    </x-slot>

    @include('texnik')

    <div class="py-6 sm:py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl">
                <div class="p-6 sm:p-8">
                    <div class="w-full">
                        <div
                            class="max-w-xxl w-full space-y-8 p-6 sm:p-10 bg-white rounded-xl shadow-lg border border-gray-100">
                            <!-- Title Section -->
                            <div class="flex flex-col">
                                <div class="flex flex-col sm:flex-row items-center mb-6">
                                    <h2 class="font-bold text-2xl text-gray-800 text-center sm:text-left mb-4 sm:mb-0">
                                        Reyting ma'lumotini joylash uchun kerakli yo'nalishni tanlang
                                    </h2>
                                </div>

                                <!-- Alert Messages with enhanced styling -->
                                <div class="space-y-4">
                                    <div
                                        class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 rounded-lg shadow-sm">
                                        <svg class="flex-shrink-0 w-5 h-5 text-blue-600 mr-3" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path
                                                d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                                        </svg>
                                        <div>
                                            <span class="font-semibold text-blue-800">Etibor bering! </span>
                                            <span class="text-blue-700">Ma'lumot yuklashdan oldin uning yo'nalishini
                                                belgilang.</span>
                                        </div>
                                    </div>

                                    <div
                                        class="flex items-center p-4 bg-gradient-to-r from-green-50 to-green-50 border-l-4 border-green-500 rounded-lg shadow-sm">
                                        <svg class="flex-shrink-0 w-5 h-5 text-green-600 mr-3" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path
                                                d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                                        </svg>
                                        <div>
                                            <span class="font-semibold text-green-800">Diqqat! </span>
                                            <span class="text-green-700">Sayt mobil qurilmalar uchun
                                                moslashtirildi.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Cards Section -->
                            <div class="mt-8">
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                    <!-- O'qituvchilar Card -->
                                    <a href="{{ route('dashboard.employee_form_chose') }}"
                                        class="group relative flex flex-col sm:flex-row items-center bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden border border-gray-100 hover:border-indigo-200 transform hover:-translate-y-1">
                                        <!-- Gradient overlay -->
                                        <div
                                            class="absolute inset-0 bg-gradient-to-r from-blue-50 via-indigo-50 to-purple-50 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                        </div>

                                        <!-- Glowing effect -->
                                        <div class="absolute inset-0 opacity-0 group-hover:opacity-20 transition-opacity duration-300"
                                            style="background: radial-gradient(circle at center, rgba(99, 102, 241, 0.5) 0%, transparent 70%);">
                                        </div>

                                        <!-- Image container -->
                                        <div class="relative w-full sm:w-48 lg:w-56 overflow-hidden">
                                            <img class="object-cover w-full h-48 sm:h-full rounded-t-lg sm:rounded-none sm:rounded-l-xl transform group-hover:scale-110 transition-transform duration-500"
                                                src="{{ asset('assets/images/logo3.png') }}"
                                                alt="Kafedra">
                                            <!-- Image overlay -->

                                        </div>

                                        <!-- Content -->
                                        <div class="flex flex-col justify-between p-6 sm:p-8 relative z-10">
                                            <!-- Icon -->
                                            <div class="mb-4">
                                                <span
                                                    class="inline-block p-3 bg-indigo-100 rounded-lg group-hover:bg-indigo-200 transition-colors duration-300">
                                                    <svg class="w-6 h-6 text-indigo-600" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                                        </path>
                                                    </svg>
                                                </span>
                                            </div>

                                            <h5
                                                class="mb-3 text-2xl font-bold text-gray-900 group-hover:text-indigo-600 transition-colors duration-300">
                                                O'qituvchilar tomonidan yuboriladigan ma'lumotlar uchun
                                            </h5>
                                            <p
                                                class="text-gray-600 group-hover:text-gray-700 transition-colors duration-300 mb-6">
                                                Universitet o'qituvchi hodimlari tomonidan ilmiy yo'nalishdagi
                                                ma'lumotlarni yuborishlari uchun
                                            </p>

                                            <!-- Action button -->
                                            <div
                                                class="inline-flex items-center text-indigo-600 group-hover:text-indigo-700 transition-colors duration-300">
                                                <span class="font-semibold">Kirish</span>
                                                <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-2 transition-transform duration-300"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                                </svg>
                                            </div>
                                        </div>
                                    </a>

                                    <!-- Kafedra Card -->
                                    <a href="{{ route('dashboard.department_form_chose') }}"
                                        class="group relative flex flex-col sm:flex-row items-center bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden border border-gray-100 hover:border-indigo-200 transform hover:-translate-y-1">
                                        <!-- Gradient overlay -->
                                        <div
                                            class="absolute inset-0 bg-gradient-to-r from-blue-50 via-indigo-50 to-purple-50 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                        </div>

                                        <!-- Glowing effect -->
                                        <div class="absolute inset-0 opacity-0 group-hover:opacity-20 transition-opacity duration-300"
                                            style="background: radial-gradient(circle at center, rgba(99, 102, 241, 0.5) 0%, transparent 70%);">
                                        </div>

                                        <!-- Image container -->
                                        <div class="relative w-full sm:w-48 lg:w-56 overflow-hidden">
                                            <img class="object-cover w-full h-48 sm:h-full rounded-t-lg sm:rounded-none sm:rounded-l-xl transform group-hover:scale-110 transition-transform duration-500"
                                                src="{{ asset('assets/images/logo3.png') }}"
                                                alt="Kafedra">
                                        </div>

                                        <!-- Content -->
                                        <div class="flex flex-col justify-between p-6 sm:p-8 relative z-10">
                                            <!-- Icon -->
                                            <div class="mb-4">
                                                <span
                                                    class="inline-block p-3 bg-indigo-100 rounded-lg group-hover:bg-indigo-200 transition-colors duration-300">
                                                    <svg class="w-6 h-6 text-indigo-600" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                                        </path>
                                                    </svg>
                                                </span>
                                            </div>

                                            <h5
                                                class="mb-3 text-2xl font-bold text-gray-900 group-hover:text-indigo-600 transition-colors duration-300">
                                                Kafedra ma'lumotlarini yuborish uchun
                                            </h5>
                                            <p
                                                class="text-gray-600 group-hover:text-gray-700 transition-colors duration-300 mb-6">
                                                Universitet o'qituvchi hodimlari tomonidan ilmiy yo'nalishdagi
                                                ma'lumotlarni yuborishlari uchun
                                            </p>

                                            <!-- Action button -->
                                            <div
                                                class="inline-flex items-center text-indigo-600 group-hover:text-indigo-700 transition-colors duration-300">
                                                <span class="font-semibold">Kirish</span>
                                                <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-2 transition-transform duration-300"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                                </svg>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <!-- KPI Card - Featured -->
                            <div class="relative mt-8 mb-4">
                                <!-- Featured badge -->
                                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 z-10">
                                    <span
                                        class="px-4 py-1 bg-gradient-to-r from-indigo-400 to-indigo-500 text-white text-sm font-bold rounded-full shadow-lg">
                                        New
                                    </span>
                                </div>

                                <a href="{{ route('kpi.index')}}"
                                    class="group relative flex flex-col sm:flex-row items-center bg-white rounded-xl shadow-2xl hover:shadow-2xl transition-all duration-300 overflow-hidden border-2 border-indigo-100 hover:border-indigo-300">
                                    <!-- Animated gradient background -->
                                    <div
                                        class="absolute inset-0 bg-gradient-to-r from-blue-50 via-indigo-50 to-purple-50 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                    </div>

                                    <!-- Glowing effect on hover -->
                                    <div class="absolute inset-0 opacity-0 group-hover:opacity-20 transition-opacity duration-300"
                                        style="background: radial-gradient(circle at center, rgba(99, 102, 241, 0.5) 0%, transparent 70%);">
                                    </div>

                                    <!-- Image container with enhanced hover effects -->
                                    <div class="relative w-full sm:w-64 overflow-hidden">
                                        <img class="object-cover w-full h-48 sm:h-64 rounded-t-lg sm:rounded-none sm:rounded-l-xl transform group-hover:scale-110 transition-transform duration-500"
                                            src="https://mir-s3-cdn-cf.behance.net/project_modules/fs/1fcafd93322439.5e6164ef9bb7f.jpg"
                                            alt="KPI">
                                        <!-- Overlay gradient on image -->
                                        <div
                                            class="absolute inset-0 bg-gradient-to-t from-indigo-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        </div>
                                    </div>

                                    <!-- Content with enhanced styling -->
                                    <div class="flex flex-col justify-between p-8 relative z-10 w-full">
                                        <!-- Icon -->
                                        <div class="mb-4">
                                            <span
                                                class="inline-block p-3 bg-indigo-100 rounded-lg group-hover:bg-indigo-200 transition-colors duration-300">
                                                <svg class="w-6 h-6 text-indigo-600" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                                    </path>
                                                </svg>
                                            </span>
                                        </div>

                                        <!-- Title with enhanced typography -->
                                        <h5
                                            class="mb-4 text-3xl font-bold text-gray-900 group-hover:text-indigo-600 transition-colors duration-300">
                                            KPI reytingini aniqlash uchun
                                        </h5>

                                        <!-- Description with better contrast -->
                                        <p
                                            class="mb-6 text-lg text-gray-600 group-hover:text-gray-700 transition-colors duration-300">
                                            Universitet o'qituvchi hodimlari tomonidan ilmiy yo'nalishdagi ma'lumotlarni
                                            yuborishlari uchun
                                        </p>

                                        <!-- Action button -->
                                        <div
                                            class="inline-flex items-center text-indigo-600 group-hover:text-indigo-700 transition-colors duration-300">
                                            <span class="font-semibold">Kirish</span>
                                            <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-2 transition-transform duration-300"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                            </svg>
                                        </div>
                                    </div>
                                </a>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .hover-gradient:hover {
                background: linear-gradient(to right, rgba(59, 130, 246, 0.1), rgba(99, 102, 241, 0.1));
            }
        </style>
    @endpush
</x-app-layout>
