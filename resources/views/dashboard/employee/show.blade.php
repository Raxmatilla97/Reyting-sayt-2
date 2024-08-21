<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("O'qituvchi ma'lumotlarini ko'rish") }}
        </h2>

    </x-slot>
    <style>
        @media only screen and (max-width: 768px) {}

        /* Mobil qurilmalar uchun (masalan, 600px dan kichikroq ekranlar uchun) */
        @media only screen and (max-width: 600px) {
            .image_moder {
                margin-top: 20px;
            }

            .operator_name {
                max-width: 200px;
            }
        }

        /* Tablet qurilmalar uchun (masalan, 600px dan 1024px gacha) */
        @media only screen and (min-width: 601px) and (max-width: 1024px) {}

        /* Desktop qurilmalar uchun (1025px dan yuqori) */
        @media only screen and (min-width: 1025px) {

            .operator_name {
                width: 370px;
            }

        }
    </style>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <div>

                        <div class="flex flex-col items-center px-4 sm:px-0 lg:flex-row lg:justify-between">
                            <div class="mb-4 lg:mb-0">
                                <h3 class="text-base font-semibold leading-7 text-gray-900">Foydalanuvchi haqida
                                    ma'lumotlar
                                </h3>
                                <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">Reyting ballari o'zgarib
                                    turushi
                                    mumkin!</p>
                            </div>
                            <div class="mb-4 lg:mb-0">
                                <div class="flex items-center p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400"
                                    role="alert">
                                    <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                                    </svg>
                                    <span class="sr-only">Info</span>
                                    <div>
                                        <span class="font-medium"></span> <span
                                            class=" text-lg font-medium me-2 px-2.5 py-0.5 rounded-full ">Umumiy ball:
                                            {{ round($employee->department->point_user_deportaments()->where('status', 1)->sum('point'), 2) }}</span>
                                    </div>
                                </div>

                            </div>
                            <img class="rounded w-4/5 sm:w-2/4 md:w-1/3 lg:w-1/6"
                            src="{{ $employee->image ? asset('storage/users/image/' . $employee->image) : 'https://www.svgrepo.com/show/192244/man-user.svg' }}" alt="Extra large avatar"
                                style="width: 189px; height: 200px; object-fit: cover;">
                        </div>

                        {{-- dddd --}}

                        <div class="mt-6 border-t border-gray-100">
                            <dl class="divide-y divide-gray-100">
                                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-md font-medium leading-6 text-gray-900">To'liq F.I.SH</dt>
                                    <dd class="mt-1 text-xl leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                        {{ ucwords(strtolower($employee->FullName ?? $employee->name)) }}
                                       </dd>
                                </div>
                                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-md font-medium leading-6 text-gray-900">Foydalanuvchining shaxsiy
                                        ma'lumotlari
                                    </dt>
                                    <dd class="mt-1 text-xl leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                        <span
                                            class="bg-blue-100 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">
                                            Mobil telefon raqami: {{ $employee->phone }}
                                        </span>


                                        <br>
                                        <span
                                            class="bg-blue-100 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">

                                            Kafedrasi nomi: {{ $employee->department->name }}
                                        </span>

                                        <br>
                                        <span
                                            class="bg-blue-100 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">
                                            Tug'ulgan kuni: {{ $employee->birth_date }}

                                        </span>


                                        <br>
                                        <span
                                            class="bg-blue-100 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">
                                            Umumiy yuborgan ma'lumotlar soni:
                                            {{ $employee->department->point_user_deportaments->count() }} ta

                                        </span>


                                    </dd>
                                </div>


                            </dl>
                        </div>
                    </div>
                    <hr class="mb-8 mt-3">
                    <p class="text-gray-500 dark:text-gray-400 pl-4 m-auto text-center" style="width: 90%">
                        {{-- <span
                            class="bg-gray-100 text-gray-800 text-md font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
                            Foydalanuvchi tomonidan yuklangan fayllar va ularga qo'yilgan ballar:</span> --}}
                    </p>

                    <div  class="p-4 px-0">
                        <h3 class="text-2xl font-bold mb-7 ml-3"> Foydalanuvchi tomonidan yuklangan fayllar va ularga qo'yilgan ballar:</h3>
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="text-gray-900 mb-8">

                                @include('dashboard.item_list_component')

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>




</x-app-layout>
