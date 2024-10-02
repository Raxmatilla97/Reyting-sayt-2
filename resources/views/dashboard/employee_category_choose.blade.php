<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ma'lumot bo'limini tanlang
        </h2>
    </x-slot>

    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-center hide-on-mobile">
                                        Yo'nalish KODI
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        Yo'nalish nomi
                                    </th>

                                </tr>
                            </thead>
                            <tbody>
                                {{-- @foreach ($jadvallar_codlari as $key => $volume)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <th scope="row"
                                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white hide-on-mobile">
                                            <span
                                                class="bg-blue-100 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                                {{ $key }}</span>

                                        </th>
                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ route('dashboard.show_employee_form', $key) }}">
                                                <button type="button"
                                                    class="text-gray-900 bg-gray-100 py-3 px-5  me-2 mb-4 text-md font-medium text-gray-900 focus:outline-none rounded-lg     border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">{{ $volume }}</button>
                                            </a>
                                        </td>

                                    </tr>
                                @endforeach --}}

                                <div class="flex items-center p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                                    <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                      <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                                    </svg>
                                    <span class="sr-only">Info</span>
                                    <div>
                                      <span class="font-medium">Diqqat!</span> Ma'lumot yuklash muddati o'z nihoyasiga yetdi! 
                                    </div>
                                  </div>
                                  
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <style>
        /* Mobil qurilmalar uchun max-width: 768px dan kichik bo'lganda ko'rinmaydigan qoida */
        @media (max-width: 768px) {
            .hide-on-mobile {
                display: none;
            }
        }
    </style>


</x-app-layout>
