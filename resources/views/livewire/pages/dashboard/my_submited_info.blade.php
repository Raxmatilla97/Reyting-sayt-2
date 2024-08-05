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
                    Umumiy to'plagan ballaringiz: <span
                        class="bg-blue-100 text-blue-800 text-md font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">{{ $totalPoints }}</span>
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
                                                    <div class="mr-3 flex-shrink-0 item">
                                                        <button data-modal-target="default-modal-{{ $item->id }}"
                                                            data-modal-toggle="default-modal-{{ $item->id }}"
                                                            class="view-details-btn block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-sm rounded-lg text-sm px-3 py-1.5 text-center"
                                                            type="button" data-id="{{ $item->id }}">
                                                            KO'RISH
                                                        </button>


                                                    </div>
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


    <div id="default-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50">
        <div class="relative p-4 w-full max-w-3xl h-full md:h-auto">
            <!-- Modal content -->
            <div class=" bg-white rounded-lg shadow overflow-y-auto">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 border-b rounded-t">
                    <h3 class="text-xl font-semibold text-gray-900">
                        To'liq ma'lumot
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 flex justify-center items-center"
                        data-modal-hide="default-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="modal-body pb-4 pl-4 pr-4 pt-2">
                    <!-- AJAX bilan yangilanadi -->
                </div>
                <!-- Modal footer -->
                {{-- <div class="flex items-center p-4 border-t rounded-b">

                <button data-modal-hide="default-modal" type="button"
                    class="text-gray-900 bg-white border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:ring-4 focus:ring-gray-100 rounded-lg text-sm px-5 py-2.5 ms-3">Yopish</button>
            </div> --}}
            </div>
        </div>
    </div>

    {{-- Ko'rish tugmasi bosilganda AJAX so'rovi yuboriladigan js kodi --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const viewButtons = document.querySelectorAll('.view-details-btn');

            viewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = button.getAttribute('data-id');
                    const modalId = 'default-modal'; // Faqat bitta modal mavjud

                    // AJAX so'rovini yuborish
                    fetch(`/getItemDetails/${id}`)
                        .then(response => response.json())
                        .then(data => {
                            // Modalni yangilash
                            const modal = document.querySelector(`#${modalId}`);
                            if (modal) {
                                console.log(`Updating modal with id ${modalId}`); // Debug log
                                modal.querySelector('.modal-body').innerHTML = data.html;
                                modal.classList.remove('hidden');
                            } else {
                                console.error(`Modal with id ${modalId} does not exist.`);
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });
            });

            // Modalni yopish uchun kod
            document.querySelectorAll('[data-modal-hide]').forEach(button => {
                button.addEventListener('click', function() {
                    const modalId = button.getAttribute('data-modal-hide');
                    const modal = document.querySelector(`#${modalId}`);
                    if (modal) {
                        console.log(`Hiding modal with id ${modalId}`); // Debug log
                        modal.classList.add('hidden');
                    } else {
                        console.error(`Modal with id ${modalId} does not exist.`);
                    }
                });
            });

            // Modal tashqarisiga bosilganda yopish uchun kod
            const modal = document.querySelector('#default-modal');
            if (modal) {
                modal.addEventListener('click', function(event) {
                    if (event.target === modal) {
                        modal.classList.add('hidden');
                    }
                });
            }
        });
    </script>


</x-app-layout>
