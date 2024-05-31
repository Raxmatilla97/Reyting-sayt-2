<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("Men yuborgan ma'lumotlar ro'yxati") }}
        </h2>

    </x-slot>



    <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">

            <div id="alert-additional-content-1"
                class="p-4 mb-4 text-blue-800 border border-blue-300 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-800"
                role="alert">
                <div class="flex items-center">
                    <svg class="flex-shrink-0 w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                    </svg>
                    <span class="sr-only">Info</span>
                    <h3 class="text-lg font-medium">Siz yuborgan ma'lumotlarni to'liq ro'yxatini shu sahifadan
                        topishingiz mumkin!</h3>
                       
                   
                </div>
                <p class="text-right">
                    Umumiy to'plagan ballaringiz: <span class="bg-blue-100 text-blue-800 text-md font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">{{ $totalPoints }}</span>
                </p>

            </div>


        </div>

       {{-- Search joyi --}}
        <div class="py-12">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                    <div class="p-6 text-gray-900 mb-8">

                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            @if (count($pointUserInformations) > 0)
                                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    <thead
                                        class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="p-4">
                                                <div class="flex items-center">
                                                    №
                                                </div>
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                F.I.SH
                                            </th>

                                            <th scope="col" class="px-6 py-3" style="    min-width: 150px;">
                                                Ma'lumot holati
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Berilgan ball
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Vaqti
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Bajarish
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 1;
                                        @endphp


                                        @foreach ($pointUserInformations as $item)
                                            <tr
                                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                <td class="w-4 p-4">
                                                    <div class="flex items-center font-bold">
                                                        {{ $i++ }}
                                                    </div>
                                                </td>
                                                <th scope="row"
                                                    class="flex items-center px-6 py-4 text-gray-900 whitespace-normal dark:text-white">
                                                    <img class="hidden sm:block w-10 h-10 rounded-full"
                                                        style="object-fit: cover;"
                                                        src="{{ '/storage/users/image' }}/{{ auth()->user()->image }}"
                                                        alt="">
                                                    <div class="ps-3">
                                                        <div class="text-base font-semibold"
                                                            style=" overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                            Maxsus forma kodi:
                                                            <span
                                                                class="bg-blue-100 text-blue-800 text-md font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                                                {{ $item->murojaat_codi ?? 'Noma’lum' }}
                                                            </span>
                                                        </div>

                                                        <div class="font-normal text-gray-500 ">

                                                            {{ $item->murojaat_nomi ?? 'Noma’lum' }}

                                                        </div>

                                                    </div>
                                                </th>


                                                <td class="px-6 py-4">
                                                    <div class="flex items-center">
                                                        @if ($item->status == 1)
                                                            <div class="h-2.5 w-2.5 rounded-full bg-green-500 me-2">
                                                            </div>
                                                            Maqullangan!
                                                        @elseif($item->status == 0)
                                                            <div class="h-2.5 w-2.5 rounded-full bg-red-500 me-2">
                                                            </div>
                                                            Rad etilgan
                                                        @else
                                                            <div class="h-2.5 w-2.5 rounded-full bg-indigo-500 me-2">
                                                            </div>
                                                            Tekshiruvda!
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    @if ($item->status == 1)
                                                        <span
                                                            class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">{{ $item->points }}
                                                            - ball</span>
                                                    @elseif($item->status == 0)
                                                        <span
                                                            class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Hisoblanmadi!</span>
                                                    @else
                                                        <span
                                                            class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">Baholanmagan!</span>
                                                    @endif

                                                </td>
                                                <td class="px-6 py-4">
                                                    {{ $item->created_at }}
                                                </td>
                                                <td class="px-6 py-4">
                                                    <a href=""
                                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Ko'rish</a>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            @else
                                <h1 class="text-center text-xl font-medium mb-4 mt-2 text-gray-400">
                                    Murojaatlar kelib tushmagan!</h1>
                                {{-- @include('reyting.frontend.frogments.skeletonTable') --}}
                            @endif

                        </div>
                        <div class=" items-center justify-between mt-6">
                            {{-- {{ $murojatlar->appends(['category' => $form_info['category'], 'sort' => $form_info['sort'], 'name'
                    => $form_info['name']])->links() }} --}}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>

</x-app-layout>
