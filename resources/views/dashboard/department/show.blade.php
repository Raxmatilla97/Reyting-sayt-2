<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("Kafedra ma'lumotlarini to'liq ko'rish") }}
        </h2>

    </x-slot>


    <div class="py-1 mt-6">

        <div class="max-w-8xl mx-auto sm:px-1 lg:px-1">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-1 bg-white border-b border-gray-200">


                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                        <div class="p-6 text-gray-900 mb-8">

                            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">

                                {{--  --}}

                                <style>
                                    /* Custom tab styles */
                                    .tab {
                                        transition: color 0.3s, border-color 0.3s, font-weight 0.3s;
                                    }

                                    .tab:hover,
                                    .tab.active {
                                        color: #2563EB;
                                        /* Tailwind's blue-600 color */
                                        border-bottom-color: #2563EB;
                                        font-weight: 600;
                                        /* Semi-bold font */
                                    }

                                    .tab svg {
                                        transition: color 0.3s;
                                    }

                                    .tab:hover svg,
                                    .tab.active svg {
                                        color: #2563EB;
                                        /* Tailwind's blue-600 color */
                                    }

                                    .tab-content {
                                        padding: 1rem;
                                        border: 1px solid #e5e7eb;
                                        /* Tailwind's border color */
                                        border-radius: 0.375rem;
                                        /* Tailwind's rounded-md */
                                    }
                                </style>

                                <div class="container mx-auto mt-5">
                                    <div class="border-b border-gray-200 dark:border-gray-700">
                                        <ul
                                            class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400">

                                            <li class="me-2">
                                                <a href="#yuborilgan"
                                                    class="inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg tab"
                                                    data-tab="yuborilgan">
                                                    <svg class="w-4 h-4 me-2 text-gray-400" aria-hidden="true"
                                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                        viewBox="0 0 18 18">
                                                        <path
                                                            d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z" />
                                                    </svg>Yuborilgan ma'lumotlar
                                                </a>
                                            </li>

                                            <li class="me-2">
                                                <a href="#about_us"
                                                    class="inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg tab"
                                                    data-tab="about_us">
                                                    <svg class="w-4 h-4 me-2 text-gray-400" aria-hidden="true"
                                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path
                                                            d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z" />
                                                    </svg>Kafedra haqida
                                                </a>
                                            </li>
                                            <li class="me-2">
                                                <a href="#department_list"
                                                    class="inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg tab"
                                                    data-tab="department_list">
                                                    <svg class="w-4 h-4 me-2 text-gray-400" aria-hidden="true"
                                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                        viewBox="0 0 18 18">
                                                        <path
                                                            d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z" />
                                                    </svg>O'qituvchilar ro'yxati kafedralari
                                                </a>
                                            </li>


                                        </ul>
                                    </div>

                                    <div id="about_us" class="p-4 tab-content hidden">
                                        <h2 class="text-2xl font-bold">Kafedra haqida</h2>

                                        <div class="flex items-center">
                                            <!-- Logo -->
                                            <div class="w-1/3 flex justify-center">
                                                <img src="https://cdn1.iconfinder.com/data/icons/got-idea-vol-2/128/branches-1024.png"
                                                    alt="Logo"
                                                    class="w-32 h-32 object-cover rounded-full border-2 border-gray-300">
                                            </div>

                                            <!-- Info -->
                                            <div class="w-2/3 p-6">
                                                <h1 class="text-3xl font-bold text-blue-700 mb-4">
                                                    {{ $department->name }}</h1>
                                                <p class="text-lg text-blue-700 mb-4">
                                                    Assalomu alaykum, {{ $department->name }}ga oid ma'lumotlar
                                                    keyinchalik to'ldirilib boyitilib borilishi mumkin!
                                                </p>
                                                <ul class="list-disc list-inside text-gray-700">
                                                    <li class="mb-2">Fakultet o'qituvchilar soni:
                                                        {{ $totalEmployees }} nafar</li>
                                                    <li class="mb-2">Fakultet to'plangan umumiy ballar:
                                                        {{ $totalPoints }}</li>
                                                    <li class="mb-2">Fakultet hisobidagi yuborilgan ma'lumotlar
                                                        soni: {{ $totalInfos }} ta</li>
                                                    <li class="mb-2">Oxirgi yuborilgan ma'lumot vaqti:
                                                        {{ $timeAgo }}</li>
                                                    <li class="mb-2">Oxirgi yuborgan ma'lumot egasi nomi:
                                                        {{ $fullName }}</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="department_list" class="p-4 tab-content hidden">
                                        <h2 class="text-2xl font-bold">Fakultet kafedralari ro'yxati</h2>

                                        <div class="bg-white  rounded-lg p-6">
                                            <table class="w-full table-auto">
                                                <thead>
                                                    <tr class="bg-gray-200 border-b">

                                                        <th class="px-4 py-2 text-left text-gray-600">O'qituvchi F.I.SH
                                                        </th>
                                                        <th class="px-4 py-2 text-left text-gray-600">Yuborilgan
                                                            ma'lumotlari soni
                                                        </th>
                                                        <th class="px-4 py-2 text-left text-gray-600">To'plagan
                                                            ballari
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($department->employee as $employee)
                                                        <tr class="border-b">

                                                            <td class="px-4 py-4">

                                                                <span
                                                                    class="inline-flex items-center justify-center w-5 h-5 me-2 text-md font-semibold text-blue-800 bg-blue-100 rounded-full dark:bg-gray-700 dark:text-blue-400">
                                                                    <svg class="w-3 h-3" aria-hidden="true"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill="currentColor"
                                                                            d="m18.774 8.245-.892-.893a1.5 1.5 0 0 1-.437-1.052V5.036a2.484 2.484 0 0 0-2.48-2.48H13.7a1.5 1.5 0 0 1-1.052-.438l-.893-.892a2.484 2.484 0 0 0-3.51 0l-.893.892a1.5 1.5 0 0 1-1.052.437H5.036a2.484 2.484 0 0 0-2.48 2.481V6.3a1.5 1.5 0 0 1-.438 1.052l-.892.893a2.484 2.484 0 0 0 0 3.51l.892.893a1.5 1.5 0 0 1 .437 1.052v1.264a2.484 2.484 0 0 0 2.481 2.481H6.3a1.5 1.5 0 0 1 1.052.437l.893.892a2.484 2.484 0 0 0 3.51 0l.893-.892a1.5 1.5 0 0 1 1.052-.437h1.264a2.484 2.484 0 0 0 2.481-2.48V13.7a1.5 1.5 0 0 1 .437-1.052l.892-.893a2.484 2.484 0 0 0 0-3.51Z">
                                                                        </path>
                                                                        <path fill="#fff"
                                                                            d="M8 13a1 1 0 0 1-.707-.293l-2-2a1 1 0 1 1 1.414-1.414l1.42 1.42 5.318-3.545a1 1 0 0 1 1.11 1.664l-6 4A1 1 0 0 1 8 13Z">
                                                                        </path>
                                                                    </svg>
                                                                </span>

                                                                <span
                                                                    class="bg-blue-100 text-blue-800 text-md font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                                                    <a
                                                                        href="{{ route('dashboard.employeeShow', ['id_employee' => $employee->employee_id_number]) }}">
                                                                        {{ ucwords(strtolower($employee->FullName)) }}
                                                                    </a>

                                                                </span>
                                                            </td>

                                                            <td class="px-4 py-2 text-center">

                                                                @php
                                                                    $totalPoints = $employee->department->point_user_deportaments->count();
                                                                @endphp

                                                                {{ $totalPoints }}
                                                            </td>
                                                            <td class="px-4 py-2 text-center">
                                                                @php
                                                                    $point_full = 0.0;
                                                                @endphp

                                                                @php
                                                                    $point_full = 0;
                                                                    if ($employee->department) {
                                                                        $points = $employee->department
                                                                            ->point_user_deportaments()
                                                                            ->where('status', 1)
                                                                            ->where('user_id', $employee->id)
                                                                            ->get();

                                                                        foreach ($points as $point) {
                                                                            $point_full += $point->point;
                                                                        }
                                                                    }
                                                                @endphp

                                                                {{ $point_full }}


                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    <!-- Qo'shimcha satrlar qo'shishingiz mumkin -->
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                    <div id="yuborilgan" class="p-4 tab-content hidden">
                                        <h3 class="text-2xl font-bold mb-5">Kafedra o'qituvchilari tomonidan yuborilgan
                                            ma'lumotlar</h3>
                                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                            <div class="text-gray-900 mb-8">

                                                @include('dashboard.item_list_component')

                                            </div>
                                        </div>

                                    </div>

                                    {{-- Tab funksiyasini ishga tushurish kodi --}}
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            // Hammasi tablarga
                                            const tabs = document.querySelectorAll('.tab');
                                            const tabContents = document.querySelectorAll('.tab-content');

                                            tabs.forEach(tab => {
                                                tab.addEventListener('click', function(event) {
                                                    event.preventDefault();

                                                    // Barcha tablar va kontentlardan active sinflarini olib tashlash
                                                    tabs.forEach(item => item.classList.remove('active', 'border-blue-500',
                                                        'text-blue-600'));
                                                    tabContents.forEach(content => content.classList.add('hidden'));

                                                    // Joriy tabni active qilish
                                                    const target = document.querySelector(tab.getAttribute('href'));
                                                    tab.classList.add('active', 'border-blue-500', 'text-blue-600');
                                                    if (target) {
                                                        target.classList.remove('hidden');
                                                    }
                                                });
                                            });

                                            // Default active tabni aniqlash
                                            if (tabs.length > 0) {
                                                tabs[0].click();
                                            }
                                        });
                                    </script>

                                    {{-- Paginatsiya bo'lganda active tabni saqlaydigan js codi --}}

                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const tabs = document.querySelectorAll('.tab');
                                            const tabContents = document.querySelectorAll('.tab-content');

                                            function showTab(tabId) {
                                                tabContents.forEach(content => {
                                                    content.classList.add('hidden');
                                                });
                                                document.getElementById(tabId).classList.remove('hidden');
                                            }

                                            tabs.forEach(tab => {
                                                tab.addEventListener('click', function(event) {
                                                    event.preventDefault();
                                                    const tabId = this.getAttribute('data-tab');
                                                    localStorage.setItem('activeTab', tabId);
                                                    showTab(tabId);
                                                });
                                            });

                                            // Function to get URL parameters
                                            function getUrlParameter(name) {
                                                name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
                                                const regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
                                                const results = regex.exec(location.search);
                                                return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
                                            }

                                            // Determine which tab to show
                                            const page = getUrlParameter('page');
                                            const activeTab = localStorage.getItem('activeTab');

                                            if (page) {
                                                showTab('yuborilgan');
                                            } else if (activeTab) {
                                                showTab(activeTab);
                                            } else {
                                                showTab('about_us'); // Default tab
                                            }
                                        });
                                    </script>

                                    {{--  --}}
                                </div>
                                {{-- <div class=" items-center justify-between mt-6">
                                    paginate
                                </div> --}}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

</x-app-layout>
