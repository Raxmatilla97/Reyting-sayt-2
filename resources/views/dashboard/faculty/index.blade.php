<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("Fakultetlar ro'yxati sahifasi") }}
        </h2>

    </x-slot>


    {{-- Sahifada yangilanish qilganda o'ng tarafda chiqadigan bildirishnoma --}}
    {{-- @include('reyting.dashboard.professor.frogments.edit.toaster') --}}


    <div class="py-1 mt-6">

        <div class="max-w-8xl mx-auto sm:px-1 lg:px-1">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-1 bg-white border-b border-gray-200">

                    {{-- <button id="updateButton" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Fakultetlarni yangilash
                    </button> --}}

                    <div id="updateModal" tabindex="-1" aria-hidden="true"
                        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative w-full max-w-2xl max-h-full">
                            <!-- Modal content -->
                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                <!-- Modal header -->
                                <div
                                    class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                                    <h3 id="modalTitle" class="text-xl font-semibold text-gray-900 dark:text-white">
                                        Natija
                                    </h3>
                                    <button type="button"
                                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                        data-modal-hide="updateModal">
                                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="sr-only">Yopish</span>
                                    </button>
                                </div>
                                <!-- Modal body -->
                                <div id="modalBody" class="p-6 space-y-6">
                                    <!-- Natijalar shu yerda ko'rsatiladi -->
                                </div>
                                <!-- Modal footer -->
                                <div
                                    class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                                    <button data-modal-hide="updateModal" type="button"
                                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">OK</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const updateButton = document.getElementById('updateButton');
                            const modalTitle = document.getElementById('modalTitle');
                            const modalBody = document.getElementById('modalBody');
                            const modal = new Modal(document.getElementById('updateModal'));

                            updateButton.addEventListener('click', async function() {
                                updateButton.disabled = true;
                                updateButton.textContent = 'Yangilanmoqda...';

                                try {
                                    const response = await fetch('/update-faculties', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                                .getAttribute('content')
                                        },
                                    });
                                    const result = await response.json();

                                    if (result.error) {
                                        modalTitle.textContent = 'Xatolik';
                                        modalBody.innerHTML = `<p class="text-red-500">${result.error}</p>`;
                                    } else {
                                        modalTitle.textContent = 'Natija';
                                        modalBody.innerHTML = `
                            <p>${result.message}</p>
                            ${result.updated > 0 ? `<p>Yangilangan: ${result.updated}</p>` : ''}
                            ${result.new > 0 ? `<p>Yangi qo'shilgan: ${result.new}</p>` : ''}
                            ${result.deactivated > 0 ? `<p>O'chirilgan: ${result.deactivated}</p>` : ''}
                        `;
                                    }

                                    modal.show();
                                } catch (error) {
                                    console.error('Error:', error);
                                    modalTitle.textContent = 'Xatolik';
                                    modalBody.innerHTML = '<p class="text-red-500">Serverda xatolik yuz berdi</p>';
                                    modal.show();
                                } finally {
                                    updateButton.disabled = false;
                                    updateButton.textContent = 'Fakultetlarni yangilash';
                                }
                            });
                        });
                    </script>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                        <div class="p-6 text-gray-900 mb-8">

                            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                @if (count($faculties) > 0)
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
                                                    Fakultet nomi
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Kafedralar
                                                </th>

                                                <th scope="col" class="px-6 py-3">
                                                    Holati
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Umumiy ball
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
                                            @foreach ($faculties as $item)
                                                <tr
                                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                    <td class="w-4 p-4">
                                                        <div class="flex items-center font-bold">
                                                            {{ $i++ }}
                                                        </div>
                                                    </td>
                                                    <th scope="row"
                                                        class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                                        <img class="hidden sm:block w-10 h-10 rounded-full"
                                                            style="object-fit: cover;"
                                                            src="https://cdn1.iconfinder.com/data/icons/got-idea-vol-2/128/branches-1024.png"
                                                            alt="">
                                                        <div class="ps-3" style="    width: 300px;">
                                                            <div class="text-base font-semibold"
                                                                style="max-width: 260px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                                {{ $item->name }}
                                                            </div>
                                                            <div class="font-normal text-gray-500">
                                                                Fakultet ichidagi kafedralar soni <b
                                                                    class="text-blue-600">{{ $item->departments->count() }}</b>
                                                                ta
                                                            </div>
                                                        </div>
                                                    </th>

                                                    <td class="px-6 py-4">
                                                        @foreach ($item->departments as $department)
                                                            <div class="my-1.2">
                                                                <span
                                                                    class="inline-flex items-center justify-center w-5 h-5 me-2 text-sm font-semibold text-blue-800 bg-blue-100 rounded-full dark:bg-gray-700 dark:text-blue-400">
                                                                    <svg class="w-3 h-3" aria-hidden="true"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill="currentColor"
                                                                            d="m18.774 8.245-.892-.893a1.5 1.5 0 0 1-.437-1.052V5.036a2.484 2.484 0 0 0-2.48-2.48H13.7a1.5 1.5 0 0 1-1.052-.438l-.893-.892a2.484 2.484 0 0 0-3.51 0l-.893.892a1.5 1.5 0 0 1-1.052.437H5.036a2.484 2.484 0 0 0-2.48 2.481V6.3a1.5 1.5 0 0 1-.438 1.052l-.892.893a2.484 2.484 0 0 0 0 3.51l.892.893a1.5 1.5 0 0 1 .437 1.052v1.264a2.484 2.484 0 0 0 2.481 2.481H6.3a1.5 1.5 0 0 1 1.052.437l.893.892a2.484 2.484 0 0 0 3.51 0l.893-.892a1.5 1.5 0 0 1 1.052-.437h1.264a2.484 2.484 0 0 0 2.481-2.48V13.7a1.5 1.5 0 0 1 .437-1.052l.892-.893a2.484 2.484 0 0 0 0-3.51Z" />
                                                                        <path fill="#fff"
                                                                            d="M8 13a1 1 0 0 1-.707-.293l-2-2a1 1 0 1 1 1.414-1.414l1.42 1.42 5.318-3.545a1 1 0 0 1 1.11 1.664l-6 4A1 1 0 0 1 8 13Z" />
                                                                    </svg>

                                                                </span>
                                                                <span
                                                                    class="bg-blue-100 text-blue-800 text-xs font-sm me-3 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">{{ $department->name }}</span>

                                                            </div>
                                                        @endforeach
                                                    </td>

                                                    <td class="px-6 py-4">
                                                        <div class="flex items-center">
                                                            @if ($item->status)
                                                                <div class="h-2.5 w-2.5 rounded-full bg-green-500 me-2">
                                                                </div>
                                                                Aktive
                                                            @else
                                                                <div class="h-2.5 w-2.5 rounded-full bg-red-500 me-2">
                                                                </div>
                                                                No aktive!
                                                            @endif
                                                        </div>
                                                    </td>

                                                    <td class="px-6 py-4">
                                                        Jami ball: <span
                                                            class="bg-indigo-100 text-indigo-800 text-md font-medium me-2 px-2.5 py-0.5 rounded dark:bg-indigo-900 dark:text-indigo-300">
                                                            {{ $item->totalPoints }}</span>

                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <a href="{{ route('dashboard.facultyShow', ['slug' => $item->slug]) }}"
                                                            class="font-medium text-blue-600 hover:underline">
                                                            Ko'rish
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                @else
                                    <h1 class="text-center text-xl font-medium mb-4 mt-2 text-gray-400">
                                        Fakultet topilmadi!</h1>
                                    @include('frogments.skeletonTable')
                                @endif

                            </div>
                            <div class=" items-center justify-between mt-6">
                                {{ $faculties->links() }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
