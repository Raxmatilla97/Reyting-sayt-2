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
                                            {{ round($employee->department->point_user_deportaments()->sum('point'), 2) }}</span>
                                    </div>
                                </div>

                            </div>
                            <img class="rounded w-4/5 sm:w-2/4 md:w-1/3 lg:w-1/6"
                                src="{{ '/storage/users/image' }}/{{ $employee->image }}" alt="Extra large avatar"
                                style="width: 189px; height: 200px; object-fit: cover;">
                        </div>

                        {{-- dddd --}}

                        <div class="mt-6 border-t border-gray-100">
                            <dl class="divide-y divide-gray-100">
                                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-md font-medium leading-6 text-gray-900">To'liq F.I.SH</dt>
                                    <dd class="mt-1 text-xl leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                        {{ ucfirst(strtolower($employee->second_name)) }}
                                        {{ ucfirst(strtolower($employee->first_name)) }}
                                        {{ ucfirst(strtolower($employee->third_name)) }}</dd>
                                </div>
                                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-md font-medium leading-6 text-gray-900">Foydalanuvchining shaxsiy
                                        ma'lumotlari
                                    </dt>
                                    <dd class="mt-1 text-xl leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                        <span
                                            class="bg-blue-100 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">
                                           Mobil telefon raqami: {{$employee->phone}}
                                        </span>


                                        <br>
                                        <span
                                            class="bg-blue-100 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">

                                           Kafedrasi nomi: {{$employee->department ->name}}
                                        </span>

                                        <br>
                                        <span
                                            class="bg-blue-100 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">
                                           Tug'ulgan kuni:  {{$employee->birth_date}}

                                        </span>


                                        <br>
                                        <span
                                            class="bg-blue-100 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">
                                           Umumiy yuborgan ma'lumotlar soni:  {{$employee->department->point_user_deportaments->count()}} ta

                                        </span>


                                    </dd>
                                </div>


                            </dl>
                        </div>
                    </div>
                    <hr class="mb-8 mt-3">
                    <p class="text-gray-500 dark:text-gray-400 pl-4 m-auto text-center" style="width: 90%">
                        <span
                            class="bg-gray-100 text-gray-800 text-md font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
                            Foydalanuvchi tomonidan yuklangan fayllar va ularga qo'yilgan ballar:</span>
                    </p>
                    <div class="px-0 py-6  sm:px-0">
                        <div class="p-4 tab-content">
                            <h3 class="text-2xl font-bold mb-5">Fakultet o'qituvchilari tomonidan yuborilgan
                                ma'lumotlar</h3>
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                                <div class="text-gray-900 mb-8">

                                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                        @if (count($pointUserInformations) > 0)
                                            <table
                                                class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
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

                                                        <th scope="col" class="px-6 py-3"
                                                            style="    min-width: 150px;">
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
                                                                    src="{{ '/storage/users/image' }}/{{ $item->employee->image }}"
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

                                                                    <div
                                                                        class="font-normal text-gray-500 ">
                                                                        {{ $item->murojaat_nomi ?? 'Noma’lum' }}

                                                                    </div>

                                                                </div>
                                                            </th>


                                                            <td class="px-6 py-4">
                                                                <div class="flex items-center">
                                                                    @if ($item->status == 1)
                                                                        <div
                                                                            class="h-2.5 w-2.5 rounded-full bg-green-500 me-2">
                                                                        </div>
                                                                        Maqullangan!
                                                                    @elseif($item->status == 0)
                                                                        <div
                                                                            class="h-2.5 w-2.5 rounded-full bg-red-500 me-2">
                                                                        </div>
                                                                        Rad etilgan
                                                                    @else
                                                                        <div
                                                                            class="h-2.5 w-2.5 rounded-full bg-indigo-500 me-2">
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
                                                                    <button
                                                                        data-modal-target="default-modal-{{ $item->id }}"
                                                                        data-modal-toggle="default-modal-{{ $item->id }}"
                                                                        class="view-details-btn block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-sm rounded-lg text-sm px-3 py-1.5 text-center"
                                                                        type="button"
                                                                        data-id="{{ $item->id }}">
                                                                        KO'RISH
                                                                    </button>


                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                        @else
                                            <h1
                                                class="text-center text-xl font-medium mb-4 mt-2 text-gray-400">
                                                Murojaatlar kelib tushmagan!</h1>
                                            {{-- @include('reyting.frontend.frogments.skeletonTable') --}}
                                        @endif

                                    </div>
                                    <div class=" items-center justify-between mt-6">
                                        {{ $pointUserInformations->links() }}
                                    </div>
                                </div>

                            </div>
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
                    <svg class="w-3 h-3" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2"
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
