<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("Kelib tushgan murojaatni ko'rish") }}
        </h2>

    </x-slot>

    <div class="py-1 mt-6 mb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <div>

                        <div class="flex flex-col items-center px-4 sm:px-0 lg:flex-row lg:justify-between">
                            <div class="mb-4 lg:mb-0">
                                <h3 class="text-base font-semibold leading-7 text-gray-900">Ma'lumot yuboruvchi haqida
                                    ma'lumotlar
                                </h3>
                                <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">Reyting ballari o'zgarib
                                    turushi
                                    mumkin!</p>
                            </div>
                            <div class="mb-4 lg:mb-0">
                                <div class="flex items-center p-4 mb-4 text-sm
                                @if ($information->status == 0) text-red-800 bg-red-50
                                @elseif($information->status == 1) text-green-800 bg-green-50
                                @else text-blue-800 bg-blue-50 @endif
                                rounded-lg dark:bg-gray-800 dark:text-blue-400"
                                    role="alert">
                                    <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                                    </svg>
                                    <span class="sr-only">Info</span>
                                    <div>
                                        @if ($information->status == 0)
                                            <span class="font-medium"></span> <span
                                                class=" text-lg font-medium me-2 px-2.5 py-0.5 rounded-full ">Hisoblanmagan!</span>
                                        @elseif($information->status == 1)
                                            <span class="font-medium"></span> <span
                                                class=" text-lg font-medium me-2 px-2.5 py-0.5 rounded-full ">Ushbu
                                                yuborilgan ma'lumot uchun olgan bali:
                                                {{ $information->point }}</span>
                                        @else
                                            <span class="font-medium"></span> <span
                                                class=" text-lg font-medium me-2 px-2.5 py-0.5 rounded-full ">Baholanmagan!</span>
                                        @endif

                                    </div>
                                </div>

                            </div>
                            <img class="w-36 h-36  rounded" style=" object-fit: cover;"
                                src="@if ($information->employee->image == null) {{ $default_image }} @else{{ '/storage/users/image' }}/{{ $information->employee->image }} @endif"
                                alt="">
                        </div>



                        <div class="mt-6 border-t border-gray-100">
                            <dl class="divide-y divide-gray-100">
                                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-md font-medium leading-6 text-gray-900">To'liq F.I.SH</dt>
                                    <dd class="mt-1 text-xl leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                        {{ ucwords(strtolower($information->employee->FullName)) }}
                                    </dd>
                                </div>
                                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-md font-medium leading-6 text-gray-900">Umumiy to'plagan ballari
                                    </dt>
                                    <dd class="mt-1 text-md leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                        <span
                                            class="bg-blue-100 text-blue-800 text-md font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">
                                            {{ $totalPoints }} ball
                                        </span>
                                    </dd>
                                </div>
                                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-md font-medium leading-6 text-gray-900">Foydalanuvchining kafedrasi
                                        nomi</dt>
                                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                        <blockquote class="text-xl italic font-semibold text-gray-900 dark:text-white">
                                            <svg class="w-8 h-8 text-gray-400 dark:text-gray-600 mb-4"
                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                fill="currentColor" viewBox="0 0 18 14">
                                                <path
                                                    d="M6 0H2a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h4v1a3 3 0 0 1-3 3H2a1 1 0 0 0 0 2h1a5.006 5.006 0 0 0 5-5V2a2 2 0 0 0-2-2Zm10 0h-4a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h4v1a3 3 0 0 1-3 3h-1a1 1 0 0 0 0 2h1a5.006 5.006 0 0 0 5-5V2a2 2 0 0 0-2-2Z" />
                                            </svg>
                                            <p>{{ $information->employee->department->name }}</p>
                                        </blockquote>
                                    </dd>
                                </div>

                            </dl>
                        </div>
                    </div>
                    <hr class="mb-8 mt-3">
                    <p class="text-gray-500 dark:text-gray-400">

                        Foydalanuvchi tomonidan yuborilgan ma'lumotlar va fayllar va ularga qo'yilgan ballar:
                    </p>
                    <form action="{{ route('murojatlar.murojatniTasdiqlash') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $information->id }}">
                        <div class="px-4 py-6 ml-8 sm:grid sm:grid-cols-2 sm:gap-4 sm:px-0 ">
                            <dd class="mt-2 text-sm text-gray-900 sm:col-span-2 sm:mt-0">


                                <ol
                                    class="relative text-gray-500 border-s border-gray-200 dark:border-gray-700 dark:text-gray-400">
                                    <li class="mb-10 ms-6">
                                        <span
                                            class="absolute flex items-center justify-center w-8 h-8 bg-green-200 rounded-full -start-4 ring-4 ring-white dark:ring-gray-900 dark:bg-green-900">
                                            <svg class="w-3.5 h-3.5 text-green-500 dark:text-green-400"
                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 16 12">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M1 5.917 5.724 10.5 15 1.5" />
                                            </svg>
                                        </span>
                                        <h3 class="font-medium leading-tight">Foydalanuvchi ma'lumotlarni yuborgan!</h3>
                                        <p class="text-sm">Ma'lumotlar serverga kelib tushgan.</p>
                                    </li>
                                    <li class="mb-10 ms-6">
                                        <span
                                            class="absolute flex items-center justify-center w-8 h-8 bg-green-100 rounded-full -start-4 ring-4 ring-white dark:ring-gray-900 dark:bg-gray-700">
                                            <svg class="w-3.5 h-3.5 text-green-500 dark:text-gray-400"
                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                fill="currentColor" viewBox="0 0 20 16">
                                                <path
                                                    d="M18 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2ZM6.5 3a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5ZM3.014 13.021l.157-.625A3.427 3.427 0 0 1 6.5 9.571a3.426 3.426 0 0 1 3.322 2.805l.159.622-6.967.023ZM16 12h-3a1 1 0 0 1 0-2h3a1 1 0 0 1 0 2Zm0-3h-3a1 1 0 1 1 0-2h3a1 1 0 1 1 0 2Zm0-3h-3a1 1 0 1 1 0-2h3a1 1 0 1 1 0 2Z" />
                                            </svg>
                                        </span>
                                        <h3 class="font-medium leading-tight">Ma'lumotni ko'rish va tekshirish
                                        </h3>
                                        <p class="text-sm">Yuborilgan ma'lumotlarni tekshiring</p>
                                        <style>
                                            .top-bottom-shadow {
                                                box-shadow: 0 -10px 15px -3px rgba(0, 0, 0, 0.1), 0 10px 15px -3px rgba(0, 0, 0, 0.1);
                                            }

                                            .label-box {
                                                width: 200px;
                                            }
                                        </style>
                                        <div
                                            class="max-w-5xl mt-6 mx-auto bg-white rounded-xl top-bottom-shadow overflow-hidden">
                                            <div class="p-6">
                                                <h2 class="text-2xl mb-6 font-bold text-gray-800 text-center">
                                                    {{ ucwords(strtolower($information->employee->FullName)) }}ning
                                                    yuborgan ma'lumoti</h2>

                                                <!-- Category Item -->
                                                <div class="mt-4 flex">
                                                    <div class="label-box">
                                                        <i class="fas fa-tags text-blue-500"></i>
                                                        <span class="ml-2 text-lg font-medium">Faklultet &
                                                            kafedra:</span>
                                                    </div>
                                                    <div>

                                                        <!-- Breadcrumb -->
                                                        <nav class="flex px-5 py-3 text-gray-700 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700"
                                                            aria-label="Breadcrumb">
                                                            <ol
                                                                class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                                                                <li class="inline-flex items-center">
                                                                    <a href="#"
                                                                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                                                                        <svg class="w-3 h-3 me-2.5" aria-hidden="true"
                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                            fill="currentColor" viewBox="0 0 20 20">
                                                                            <path
                                                                                d="M8 1V0v1Zm4 0V0v1Zm2 4v1h1V5h-1ZM6 5H5v1h1V5Zm2-3h4V0H8v2Zm4 0a1 1 0 0 1 .707.293L14.121.879A3 3 0 0 0 12 0v2Zm.707.293A1 1 0 0 1 13 3h2a3 3 0 0 0-.879-2.121l-1.414 1.414ZM13 3v2h2V3h-2Zm1 1H6v2h8V4ZM7 5V3H5v2h2Zm0-2a1 1 0 0 1 .293-.707L5.879.879A3 3 0 0 0 5 3h2Zm.293-.707A1 1 0 0 1 8 2V0a3 3 0 0 0-2.121.879l1.414 1.414ZM2 6h16V4H2v2Zm16 0h2a2 2 0 0 0-2-2v2Zm0 0v12h2V6h-2Zm0 12v2a2 2 0 0 0 2-2h-2Zm0 0H2v2h16v-2ZM2 18H0a2 2 0 0 0 2 2v-2Zm0 0V6H0v12h2ZM2 6V4a2 2 0 0 0-2 2h2Zm16.293 3.293C16.557 11.029 13.366 12 10 12c-3.366 0-6.557-.97-8.293-2.707L.293 10.707C2.557 12.971 6.366 14 10 14c3.634 0 7.444-1.03 9.707-3.293l-1.414-1.414ZM10 9v2a2 2 0 0 0 2-2h-2Zm0 0H8a2 2 0 0 0 2 2V9Zm0 0V7a2 2 0 0 0-2 2h2Zm0 0h2a2 2 0 0 0-2-2v2Z" />
                                                                        </svg>
                                                                        {{ $information->employee->department->faculty->name }}
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <div class="flex items-center">
                                                                        <svg class="rtl:rotate-180 block w-3 h-3 mx-1 text-gray-400 "
                                                                            aria-hidden="true"
                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                            fill="none" viewBox="0 0 6 10">
                                                                            <path stroke="currentColor"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2" d="m1 9 4-4-4-4" />
                                                                        </svg>

                                                                        <a href="#"
                                                                            class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">
                                                                            {{ $information->employee->department->name }}</a>

                                                                    </div>
                                                                </li>
                                                                <li aria-current="page">
                                                                    <div class="flex items-center">
                                                                        <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400"
                                                                            aria-hidden="true"
                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                            fill="none" viewBox="0 0 6 10">
                                                                            <path stroke="currentColor"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2" d="m1 9 4-4-4-4" />
                                                                        </svg>
                                                                        <span
                                                                            class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">O'qituvchi</span>
                                                                    </div>
                                                                </li>
                                                            </ol>
                                                        </nav>
                                                    </div>
                                                </div>

                                                <div class="mt-4 ">
                                                    {{-- <div class="label-box">
                                                        <i class="fas fa-link text-purple-500"></i>
                                                        <span class="ml-2 text-lg font-medium"> Muommolar
                                                            haqida:</span>
                                                    </div> --}}
                                                    <div>
                                                        <div class="p-4 mb-4 text-md text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 w-full "
                                                            role="alert">
                                                            <span class="font-medium w-full">
                                                                @foreach ($relatedData as $table => $data)
                                                                    @if ($data)
                                                                        @php
                                                                            $configKey = "employee_form_fields.{$table}_";
                                                                            $fields = config($configKey);
                                                                            $fieldsByName = collect($fields)->keyBy(
                                                                                'name',
                                                                            );
                                                                            $currentTable = $table;
                                                                        @endphp
                                                                        <h4 class="text-lg font-semibold mb-4 mt-4">
                                                                            <span
                                                                                class="bg-blue-100 text-blue-800 text-lg font-medium px-3 py-1 rounded">
                                                                                Maxsus forma kodi: {{ $table }}
                                                                                ma'lumotlari
                                                                            </span>
                                                                        </h4>
                                                                        <div class="flex p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400"
                                                                            role="alert">
                                                                            <svg class="flex-shrink-0 inline w-4 h-4 me-3 mt-[2px]"
                                                                                aria-hidden="true"
                                                                                xmlns="http://www.w3.org/2000/svg"
                                                                                fill="currentColor"
                                                                                viewBox="0 0 20 20">
                                                                                <path
                                                                                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                                                                            </svg>
                                                                            <span class="sr-only">Info</span>
                                                                            <div class="text-lg">
                                                                                <span class="font-medium">Ushbu
                                                                                    ma'lumotlar qaysi yilda yaratilgan
                                                                                    yoki tegishli bo'lishi
                                                                                    mumkinligi:</span>
                                                                                <ul
                                                                                    class="mt-1.5 list-disc list-inside">
                                                                                    <span
                                                                                        class="text-gray-900">{{ $year }}</span>
                                                                                    yilda yaratilgan yoki tegishli.
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                        @foreach ($data->toArray() as $column => $value)
                                                                            @php

                                                                                $label =
                                                                                    $fieldsByName[$column]['label'] ??
                                                                                    ucfirst(
                                                                                        str_replace('_', ' ', $column),
                                                                                    );
                                                                                $isFile =
                                                                                    strpos($value, 'documents/') === 0;

                                                                                // Maxsus nomlar uchun
                                                                                if ($column === 'created_at') {
                                                                                    $label = 'Yaratilgan sana';
                                                                                    $value = \Carbon\Carbon::parse(
                                                                                        $value,
                                                                                    )->format('d-M-Y H:i');
                                                                                } elseif ($column === 'updated_at') {
                                                                                    $label = 'Tekshirilgan sana';
                                                                                    if (
                                                                                        $information->updated_at ==
                                                                                        $information->created_at
                                                                                    ) {
                                                                                        $value =
                                                                                            "Siz ma'lumotni ko'rib chiqqanizdan so'ng sana qo'yiladi!";
                                                                                    } else {
                                                                                        $value = \Carbon\Carbon::parse(
                                                                                            $information->updated_at,
                                                                                        )->format('d-M-Y H:i');
                                                                                    }
                                                                                }

                                                                            @endphp
                                                                            @if ($column !== 'id')
                                                                                <div class="flex p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400"
                                                                                    role="alert">
                                                                                    <svg class="flex-shrink-0 inline w-4 h-4 me-3 mt-[2px]"
                                                                                        aria-hidden="true"
                                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                                        fill="currentColor"
                                                                                        viewBox="0 0 20 20">
                                                                                        <path
                                                                                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                                                                                    </svg>
                                                                                    <span class="sr-only">Info</span>
                                                                                    <div class="text-lg">
                                                                                        <span
                                                                                            class="font-medium">{{ $label }}:</span>
                                                                                        <ul
                                                                                            class="mt-1.5 list-disc list-inside">
                                                                                            @if ($isFile)
                                                                                                <a href="{{ asset('storage/' . $value) }}"
                                                                                                    download
                                                                                                    class="text-blue-600 hover:text-blue-800 underline">
                                                                                                    <button
                                                                                                        type="button"
                                                                                                        class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                                                                        Yuklab olish
                                                                                                    </button>
                                                                                                </a>
                                                                                            @else
                                                                                                <span
                                                                                                    class="text-gray-900 block break-words overflow-wrap-anywhere"
                                                                                                    style="word-break: break-word; hyphens: auto;">
                                                                                                    {{ $value }}
                                                                                                </span>
                                                                                            @endif
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                            <hr
                                                                                class="border-t-2 border-gray-500 opacity-20 border-dashed">
                                                                        @endforeach
                                                                    @endif
                                                                @endforeach

                                                            </span>
                                                        </div>

                                                    </div>

                                                </div>


                                            </div>
                                        </div>



                                    </li>

                                    <li class="mb-10 ms-6">
                                        <span
                                            class="absolute flex items-center justify-center w-8 h-8 @if ($information->ariza_holati == 'maqullandi') bg-green-100 @else bg-gray-100 @endif  rounded-full -start-4 ring-4 ring-white dark:ring-gray-900 dark:bg-gray-700">
                                            <svg class="w-4 h-4 @if ($information->ariza_holati == 'maqullandi') text-green-500  @else text-gray-500 @endif dark:text-gray-400"
                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                fill="currentColor" viewBox="0 0 18 20">
                                                <path
                                                    d="M10 3.464V1.1m0 2.365a5.338 5.338 0 0 1 5.133 5.368v1.8c0 2.386 1.867 2.982 1.867 4.175C17 15.4 17 16 16.462 16H3.538C3 16 3 15.4 3 14.807c0-1.193 1.867-1.789 1.867-4.175v-1.8A5.338 5.338 0 0 1 10 3.464ZM4 3 3 2M2 7H1m15-4 1-1m1 5h1M6.54 16a3.48 3.48 0 0 0 6.92 0H6.54Z" />
                                            </svg>
                                        </span>
                                        <h3 class="font-medium leading-tight">Bu yo'nalish bo'yicha olgan ballari
                                            haqida</h3>
                                        <p class="text-sm">Ball berishdan oldin ko'rib chiqing</p>
                                        <div class="flex items-center p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400"
                                            role="alert">
                                            <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                                            </svg>
                                            <span class="sr-only">Info</span>
                                            @php
                                                $extraPoints = max(
                                                    0,
                                                    $userPointInfo['total_points'] - $userPointInfo['max_point'],
                                                );
                                                $isDisabled =
                                                    $userPointInfo['total_points'] <= $userPointInfo['max_point'];
                                                $truePoint =
                                                    max($userPointInfo['total_points'], $userPointInfo['max_point']) -
                                                    $userPointInfo['total_points'];
                                                $initialInputValue = old('point', $information->point);
                                                $initialInputValueDepartament = '';
                                            @endphp

                                            <div id="pointInfo">
                                                <span class="font-medium">DIQQAT!</span> Foydalanuvchi <b></b>
                                                yo'nalishidan ball olgan bo'lishi mumkin!.
                                                <ul class="mb-4 mt-3">
                                                    <li>Bu yo'nalish kodi: <b>{{ $userPointInfo['table_name'] }}!</b>
                                                    </li>
                                                    <li>Bu yo'nalish uchun belgilangan maksimal ball: <b
                                                            id="maxPoint">{{ $userPointInfo['max_point'] }}</b>
                                                        ballni tashkil etadi!</li>
                                                    <li><b class="text-green-600" id="remainingPoints">Bu yerdagi gap
                                                            JS</b></li>
                                                    <li id="teacherPoints"
                                                        class="@if ($userPointInfo['total_points'] > $userPointInfo['max_point']) text-red-600 font-bold @endif">
                                                        Bu yo'nalish uchun o'qituvchi olgan ball:
                                                        <b>{{ $userPointInfo['total_points'] }}</b> ballni tashkil
                                                        etadi!
                                                        <span id="exceedWarning"
                                                            class="ml-2 text-red-600 @if ($userPointInfo['total_points'] <= $userPointInfo['max_point']) hidden @endif">
                                                            (Diqqat: Olingan ball maksimal balldan oshib ketdi!)
                                                        </span>
                                                    </li>
                                                    <li id="extraPointsInfo"
                                                        class="text-indigo-600 font-semibold @if ($extraPoints <= 0) hidden @endif">
                                                        Ortiqcha <span
                                                            id="extraPointsValue">{{ $extraPoints }}</span> ballni
                                                        kafedra hisobiga o'tkazish kerak! (Buning uchun "Ballni kafedra
                                                        hisobiga o'tqazish") ga belgilang va ortiqcha balni yozing!
                                                    </li>
                                                    <li
                                                        class="text-indigo-600 font-semibold @if ($userPointInfo['user_point_this_item'] <= 0) hidden @endif">
                                                        Ushbu ma'lumot uchun <span
                                                            id="extraPointsValue">{{ $userPointInfo['user_point_this_item'] }}</span>
                                                        ball kafedra hisobiga o'tqazilgan.
                                                    </li>
                                                    <hr class="my-4">
                                                    <li
                                                        class="@if ($hasSimilarData) text-red-600 @else text-green-600 @endif font-semibold">
                                                        @if ($hasSimilarData)
                                                            O'xshash ma'lumot mavjud!
                                                            <a href="{{ route('murojatlar.show', $similarDataId) }}"
                                                                class="btn btn-primary ml-2 text-blue-500">
                                                                Ko'rish
                                                            </a>
                                                        @else
                                                            O'xshash ma'lumot mavjud emas!
                                                        @endif
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="mb-10 ms-6">
                                        <span
                                            class="absolute flex items-center justify-center w-8 h-8 @if ($information->ariza_holati == 'maqullandi') bg-green-100 @else bg-gray-100 @endif  rounded-full -start-4 ring-4 ring-white dark:ring-gray-900 dark:bg-gray-700">
                                            <svg class="w-3.5 h-3.5 @if ($information->ariza_holati == 'maqullandi') text-green-500  @else text-gray-500 @endif dark:text-gray-400"
                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                fill="currentColor" viewBox="0 0 18 20">
                                                <path
                                                    d="M16 1h-3.278A1.992 1.992 0 0 0 11 0H7a1.993 1.993 0 0 0-1.722 1H2a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2Zm-3 14H5a1 1 0 0 1 0-2h8a1 1 0 0 1 0 2Zm0-4H5a1 1 0 0 1 0-2h8a1 1 0 1 1 0 2Zm0-5H5a1 1 0 0 1 0-2h2V2h4v2h2a1 1 0 1 1 0 2Z" />
                                            </svg>
                                        </span>
                                        <h3 class="font-medium leading-tight">Baholash va natija haqida yozish</h3>
                                        <p class="text-sm">Yuborilgan ma'lumot bo'yicha baholash va natijani yozib
                                            qo'yish
                                        </p>

                                        <div
                                            class="max-w-5xl mt-6 mx-auto bg-white rounded-xl top-bottom-shadow overflow-hidden">
                                            <div class="p-6">
                                                @if ($errors->any())
                                                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                                                        role="alert">
                                                        <strong class="font-bold">Xatolar mavjud!</strong>
                                                        <span class="block sm:inline">
                                                            Iltimos, quyidagi xatolarni to'g'rilang:
                                                        </span>
                                                        <ul class="list-disc pl-5 mt-3">
                                                            @foreach ($errors->all() as $error)
                                                                <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                                @if (session('success'))
                                                    <div class="fixed top-3 mb-4 right-3 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                                                        role="alert">
                                                        <strong class="font-bold">Muvaffaqiyat!</strong>
                                                        <span class="block sm:inline">{{ session('success') }}</span>
                                                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                                            <svg class="fill-current h-6 w-6 text-green-500"
                                                                role="button"
                                                                onclick="this.parentElement.parentElement.remove();"
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                viewBox="0 0 20 20">
                                                                <title>Yopish</title>
                                                                <path
                                                                    d="M14.348 14.859l-4.708-4.708 4.708-4.708a1 1 0 0 0-1.414-1.414l-4.708 4.708-4.708-4.708a1 1 0 1 0-1.414 1.414l4.708 4.708-4.708 4.708a1 1 0 1 0 1.414 1.414l-4.708-4.708 4.708 4.708a1 1 0 0 0 1.414-1.414z" />
                                                            </svg>
                                                        </span>
                                                    </div>
                                                @endif


                                                <!-- Category Item -->
                                                <div class="mt-4 flex mb-4">
                                                    <div class="label-box" style="width: 300px;">
                                                        <i class="fas fa-tags text-blue-500"></i>
                                                        <span class="ml-2 text-lg font-medium">Ma'lumot holati:</span>
                                                    </div>
                                                    <div class="w-full">
                                                        <label for="countries"
                                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ariza
                                                            holatini tanlang</label>
                                                        <select id="murojaat-holati" name="murojaat_holati"
                                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                            <option value="3"
                                                                {{ $information->status == '3' ? 'selected' : '' }}>
                                                                Ariza hali ko'rib chiqilmagan!</option>
                                                            <option value="1"
                                                                {{ $information->status == '1' ? 'selected' : '' }}>
                                                                Ariza maqullandi!</option>
                                                            <option value="0"
                                                                {{ $information->status == '0' ? 'selected' : '' }}>
                                                                Ariza rad etildi!</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- URL Site -->
                                                <div class="mt-4 flex flex-col mb-4" id="hidden-div"
                                                    style="display: none;">
                                                    <div class="flex items-center mb-4">
                                                        <div class="label-box flex items-center"
                                                            style="width: 300px;">
                                                            <i class="fas fa-link text-purple-500"></i>
                                                            <span class="ml-2 text-lg font-medium">Ma'lumotga reyting
                                                                bali:</span>
                                                        </div>
                                                        <div class="flex items-center space-x-4">
                                                            <div class="relative" style="width: 150px;">
                                                                <div
                                                                    class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                                        aria-hidden="true"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        fill="none" viewBox="0 0 18 20">
                                                                        <path stroke="currentColor"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="m6.072 10.072 2 2 6-4m3.586 4.314.9-.9a2 2 0 0 0 0-2.828l-.9-.9a2 2 0 0 1-.586-1.414V5.072a2 2 0 0 0-2-2H13.8a2 2 0 0 1-1.414-.586l-.9-.9a2 2 0 0 0-2.828 0l-.9.9a2 2 0 0 1-1.414.586H5.072a2 2 0 0 0-2 2v1.272a2 2 0 0 1-.586 1.414l-.9.9a2 2 0 0 0 0 2.828l.9.9a2 2 0 0 1 .586 1.414v1.272a2 2 0 0 0 2 2h1.272a2 2 0 0 1 1.414.586l.9.9a2 2 0 0 0 2.828 0l.9-.9a2 2 0 0 1 1.414-.586h1.272a2 2 0 0 0 2-2V13.8a2 2 0 0 1 .586-1.414Z" />
                                                                    </svg>
                                                                </div>
                                                                <input type="text" id="murojaatBali"
                                                                    value="{{ $initialInputValue }}"
                                                                    name="murojaat_bali"
                                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                                    placeholder="O'qituvchi bali" required
                                                                    pattern="[0-9]*[.,]?[0-9]+"
                                                                    title="O'qituvchiga yo'nalish maximal baligacha bo'lgan ballarni berish uchun">
                                                            </div>

                                                            <div x-show="true" class="flex items-center">
                                                                <div class="relative" style="width: 150px;">
                                                                    <div
                                                                        class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                                            aria-hidden="true"
                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                            fill="none" viewBox="0 0 18 20">
                                                                            <path stroke="currentColor"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M3 5v10M3 5a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm0 10a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm12 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm0 0V6a3 3 0 0 0-3-3H9m1.5-2-2 2 2 2" />
                                                                        </svg>
                                                                    </div>
                                                                    <input type="text" id="smallInput"
                                                                        value="{{ $userPointInfo['user_point_this_item'] > 0 ? $userPointInfo['user_point_this_item'] : '' }}"
                                                                        name="kafedra_uchun"
                                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                                        placeholder="Kafedra bali"
                                                                        pattern="[0-9]*[.,]?[0-9]+"
                                                                        title="O'qituvchining yo'nalish maximal ballaridan ortiq ballarni berish uchun"
                                                                        {{ $userPointInfo['user_point_this_item'] > 0 ? '' : 'disabled' }}>
                                                                </div>

                                                                <div
                                                                    class="relative inline-block w-10 ml-5 mr-2 align-middle select-none transition duration-200 ease-in">
                                                                    <input type="checkbox" id="toggle"
                                                                        class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer"
                                                                        {{ $userPointInfo['user_point_this_item'] > 0 ? 'checked' : '' }}>
                                                                    <label for="toggle"
                                                                        class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                                                                </div>
                                                                <label for="toggle" class="text-sm text-gray-700">
                                                                    Ballni kafedra hisobiga o'tqazish
                                                                </label>
                                                            </div>

                                                            <script>
                                                                document.addEventListener('DOMContentLoaded', function() {
                                                                    const murojaatBaliInput = document.getElementById('murojaatBali');
                                                                    const remainingPointsElement = document.getElementById('remainingPoints');
                                                                    const teacherPointsElement = document.getElementById('teacherPoints');
                                                                    const exceedWarningElement = document.getElementById('exceedWarning');
                                                                    const extraPointsInfoElement = document.getElementById('extraPointsInfo');
                                                                    const extraPointsValueElement = document.getElementById('extraPointsValue');
                                                                    const kafedraHisobiCheckbox = document.getElementById('kafedra-hisobi');
                                                                    const toggleCheckbox = document.getElementById('toggle');
                                                                    const extraPointsInput = document.getElementById('extraPointsInput');
                                                                    const smallInput = document.getElementById('smallInput');

                                                                    const maxPoint = {{ $userPointInfo['max_point'] }};
                                                                    const initialTotalPoints = {{ $userPointInfo['total_points'] }};
                                                                    const initialInputValue = {{ $initialInputValue }};
                                                                    const userPointThisItem = {{ $userPointInfo['user_point_this_item'] }};

                                                                    function updatePointInfo() {
                                                                        const currentInputValue = parseFloat(murojaatBaliInput.value) || 0;
                                                                        const newTotalPoints = initialTotalPoints + (currentInputValue - initialInputValue);
                                                                        const truePoint = Math.max(0, maxPoint - newTotalPoints);
                                                                        const extraPoints = Math.max(0, newTotalPoints - maxPoint);

                                                                        remainingPointsElement.textContent =
                                                                            `Siz yana ${truePoint.toFixed(2)} bal bera olasiz! Qolgan ortiqcha ballar kafedra hisobiga o'tadi.`;

                                                                        teacherPointsElement.querySelector('b').textContent = newTotalPoints.toFixed(2);
                                                                        teacherPointsElement.classList.toggle('text-red-600', newTotalPoints > maxPoint);
                                                                        teacherPointsElement.classList.toggle('font-bold', newTotalPoints > maxPoint);

                                                                        exceedWarningElement.classList.toggle('hidden', newTotalPoints <= maxPoint);

                                                                        extraPointsInfoElement.classList.toggle('hidden', extraPoints <= 0);
                                                                        extraPointsValueElement.textContent = extraPoints.toFixed(2);

                                                                        extraPointsInput.value = extraPoints.toFixed(2);

                                                                        updateSmallInputState();
                                                                    }

                                                                    murojaatBaliInput.addEventListener('input', updatePointInfo);

                                                                    smallInput.addEventListener('input', function() {
                                                                        if (this.value === "" || isNaN(parseFloat(this.value))) {
                                                                            this.value = "";
                                                                        }
                                                                    });

                                                                    // Boshlang'ich holatni o'rnatish
                                                                    updatePointInfo();
                                                                });
                                                            </script>

                                                            <script>
                                                                document.addEventListener('DOMContentLoaded', function() {
                                                                    const toggleCheckbox = document.getElementById('toggle');
                                                                    const smallInput = document.getElementById('smallInput');
                                                                    const extraPointsInput = document.getElementById('extraPointsInput');

                                                                    const userPointThisItem = {{ $userPointInfo['user_point_this_item'] }};

                                                                    function updateSmallInputState() {
                                                                        smallInput.disabled = !toggleCheckbox.checked;

                                                                        if (toggleCheckbox.checked) {
                                                                            if (smallInput.value === "" || parseFloat(smallInput.value) === 0) {
                                                                                const extraPoints = parseFloat(extraPointsInput.value) || 0;
                                                                                smallInput.value = Math.max(extraPoints, userPointThisItem).toFixed(2);
                                                                            }
                                                                        } else {
                                                                            smallInput.value = "";
                                                                        }
                                                                    }

                                                                    // Toggle o'zgarishini kuzatish
                                                                    toggleCheckbox.addEventListener('change', updateSmallInputState);

                                                                    // Boshlang'ich holatni o'rnatish
                                                                    if (userPointThisItem > 0) {
                                                                        toggleCheckbox.checked = true;
                                                                        smallInput.disabled = false;
                                                                        smallInput.value = userPointThisItem.toFixed(2);
                                                                    } else {
                                                                        toggleCheckbox.checked = false;
                                                                        smallInput.disabled = true;
                                                                        smallInput.value = "";
                                                                    }

                                                                    // Boshlang'ich holatni yangilash
                                                                    updateSmallInputState();
                                                                });
                                                            </script>
                                                            <style>
                                                                .toggle-checkbox:checked {
                                                                    @apply: right-0 border-green-400;
                                                                    right: 0;
                                                                    border-color: #68D391;
                                                                }

                                                                .toggle-checkbox:checked+.toggle-label {
                                                                    @apply: bg-green-400;
                                                                    background-color: #68D391;
                                                                }
                                                            </style>
                                                        </div>
                                                    </div>


                                                </div>
                                                <!-- File Upload Info -->


                                                <div class="flex mt-4">
                                                    <div class="label-box" style="width: 300px;">
                                                        <i class="fas fa-file-upload text-red-500"></i>
                                                        <span class="ml-2 text-lg font-medium">Ma'lumot holati izohi:</span>
                                                    </div>
                                                    <div class="w-full">
                                                        <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                            Agar foydalanuvchi ma'lumotiga izoh yozmoqchi bo'lsangiz yoki maqullanmagan bo'lsa nima uchunligini yozing.
                                                        </label>
                                                        <textarea id="message" rows="4" name="murojaat_izohi"
                                                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                            placeholder="Assalomu alaykum, sizning ....">{{ old('arizaga_javob', $information->arizaga_javob) }}</textarea>

                                                            <div class="my-4">
                                                                <div class="flex items-center mb-4">
                                                                    <input id="radio-1" type="radio" value="Giper xavola noto'g'ri kiritilgan: Ma'lumot uchun berilgan havola ishlamayapti yoki noto'g'ri manzilga yo'naltirilgan." name="default-radio"
                                                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                                    <label for="radio-1" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                        Giper xavola noto'g'ri kiritilgan: Ma'lumot uchun berilgan havola ishlamayapti yoki noto'g'ri manzilga yo'naltirilgan.
                                                                    </label>
                                                                </div>
                                                                <div class="flex items-center mb-4">
                                                                    <input id="radio-2" type="radio" value="Asos ma'lumotga mos emas: Taqdim etilgan hujjat yoki ma'lumot so'ralgan ma'lumotni tasdiqlamaydi yoki unga to'g'ri kelmaydi." name="default-radio"
                                                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                                    <label for="radio-2" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                        Asos ma'lumotga mos emas: Taqdim etilgan hujjat yoki ma'lumot so'ralgan ma'lumotni tasdiqlamaydi yoki unga to'g'ri kelmaydi.
                                                                    </label>
                                                                </div>
                                                                <div class="flex items-center">
                                                                    <input id="radio-3" type="radio" value="Ma'lumotlar to'liq kiritilmagan: Zarur bo'lgan barcha ma'lumotlar to'ldirilmagan yoki qisman kiritilgan." name="default-radio"
                                                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                                    <label for="radio-3" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                                                        Ma'lumotlar to'liq kiritilmagan: Zarur bo'lgan barcha ma'lumotlar to'ldirilmagan yoki qisman kiritilgan.
                                                                    </label>
                                                                </div>
                                                            </div>
                                                    </div>
                                                </div>

                                                <script>
                                                document.addEventListener('DOMContentLoaded', function() {
                                                    const radioButtons = document.querySelectorAll('input[name="default-radio"]');
                                                    const textarea = document.getElementById('message');

                                                    radioButtons.forEach(radio => {
                                                        radio.addEventListener('change', function() {
                                                            if (this.checked) {
                                                                textarea.value = this.value;
                                                            }
                                                        });
                                                    });
                                                });
                                                </script>

                                                <script>
                                                    document.querySelector('input[name="murojaat_bali"]').addEventListener('input', function(event) {
                                                        const value = event.target.value;
                                                        event.target.value = value.replace(/[^0-9.]/g, '');
                                                    });
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        const selectElement = document.getElementById('murojaat-holati');
                                                        const hiddenDiv = document.getElementById('hidden-div');

                                                        function toggleDivVisibility() {
                                                            if (selectElement.value === '1') {
                                                                hiddenDiv.style.display = 'flex';
                                                            } else {
                                                                hiddenDiv.style.display = 'none';
                                                            }
                                                        }

                                                        toggleDivVisibility();

                                                        selectElement.addEventListener('change', toggleDivVisibility);
                                                    });
                                                </script>
                                            </div>
                                    </li>
                                    <li class="ms-6">
                                        <span
                                            class="absolute flex items-center justify-center w-8 h-8 @if ($information->ariza_holati == 'maqullandi') bg-green-100 @else bg-gray-100 @endif  rounded-full -start-4 ring-4 ring-white dark:ring-gray-900 dark:bg-gray-700">
                                            <svg class="w-3.5 h-3.5 @if ($information->ariza_holati == 'maqullandi') text-green-500 @else text-gray-500 @endif  dark:text-gray-400"
                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                fill="currentColor" viewBox="0 0 18 20">
                                                <path
                                                    d="M16 1h-3.278A1.992 1.992 0 0 0 11 0H7a1.993 1.993 0 0 0-1.722 1H2a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2ZM7 2h4v3H7V2Zm5.7 8.289-3.975 3.857a1 1 0 0 1-1.393 0L5.3 12.182a1.002 1.002 0 1 1 1.4-1.436l1.328 1.289 3.28-3.181a1 1 0 1 1 1.392 1.435Z" />
                                            </svg>
                                        </span>
                                        <h3 class="font-medium leading-tight">Natijani saqalash</h3>
                                        <p class="text-sm">Yozilgan va belgilangan ma'lumotlarni saqlash</p>

                                        <div class="my-4">
                                            <div class="flex p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400"
                                                role="alert">
                                                <svg class="flex-shrink-0 inline w-4 h-4 me-3 mt-[2px]"
                                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                                                </svg>
                                                <span class="sr-only">Info</span>
                                                <div style="width: 100%;">
                                                    <span class="font-medium">Saqlashdan oldin bir bor tekshirib
                                                        ko'ring:</span>
                                                    <ul class="mt-1.5 list-disc list-inside">
                                                        <li>Yuborilgan ma'lumotlar tog'riligiga</li>
                                                        <li>Ariza holatini tog'ri belgilaganizga</li>
                                                        <li>Tog'ri reyting balini yozganizga</li>
                                                        <li>Tog'ri murozat izohini yozganizga</li>

                                                    </ul>
                                                    <div class="flex justify-center">
                                                        <button type="submit"
                                                            class="mt-4  text-white bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-sm rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Barcha
                                                            ma'lumotlar tog'ri va saqlash mumkin</button>

                                                    </div>
                                                </div>

                                            </div>
                                            <button data-modal-target="popup-modal" data-modal-toggle="popup-modal"
                                                type="button"
                                                class=" flex justify-between focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-small rounded-lg text-xs px-3 py-1 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                                <svg class="w-4 h-4 mr-2 text-white dark:text-white"
                                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                                </svg>

                                                O'chirish</button>


                                        </div>
                                    </li>
                                </ol>

                            </dd>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="popup-modal" tabindex="-1"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <button type="button"
                    class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-hide="popup-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Oynani yopish</span>
                </button>
                <form action="{{ route('murojaat.destroy', $information->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="p-4 md:p-5 text-center">
                        <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Ma'lumotni o'chirishni
                            istaysizmi?
                            <p class="mt-3 text-sm">Agarda Murojaatni o'chirsangiz ma'lumotlarni qaytib tiklab
                                bo'lmaydi!</p>
                        </h3>

                        <button data-modal-hide="popup-modal" type="submit"
                            class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center me-2">
                            Ha, o'chirilsin
                        </button>

                        <button data-modal-hide="popup-modal" type="button"
                            class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Yo'q,
                            oynani yopish</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    </div>

</x-app-layout>
