<x-app-layout>


    <x-slot name="header">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg shadow-lg">
            <h2 class="py-6 text-2xl sm:text-3xl font-bold text-center text-white leading-tight">
                <span class="block text-xl sm:text-2xl mt-2 font-medium text-blue-100">
                    {{ __("Fakultetlar ro'yxati sahifasi") }}
                </span>
            </h2>
        </div>
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
                    <div class="p-4 sm:p-6 text-gray-900 mb-8">
                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            @if (count($faculties) > 0)
                                <!-- Mobile and Tablet View -->
                                <div class="sm:hidden">
                                    @foreach ($faculties as $item)
                                        <div class="mb-4 bg-white border rounded-lg shadow-sm">
                                            <div class="p-4">
                                                <div class="flex items-center mb-2">
                                                    <img class="w-10 h-10 rounded-full mr-3" src="https://cdn1.iconfinder.com/data/icons/got-idea-vol-2/128/branches-1024.png" alt="">
                                                    <div>
                                                        <div class="font-semibold text-lg">{{ $item->name }}</div>
                                                        <div class="text-sm text-gray-500">Kafedralar soni: {{ $item->departments->count() }}</div>
                                                    </div>
                                                </div>
                                                <div class="mb-2">
                                                    <div class="font-semibold mb-1">Kafedralar:</div>
                                                    <div class="flex flex-wrap">
                                                        @foreach ($item->departments->take(3) as $department)
                                                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold mr-2 mb-2 px-2.5 py-0.5 rounded">{{ $department->name }}</span>
                                                        @endforeach
                                                        @if ($item->departments->count() > 3)
                                                            <span class="text-sm text-gray-500">+{{ $item->departments->count() - 3 }} ko'p</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex justify-between items-center mb-2">
                                                    <div>
                                                        <span class="font-semibold">Holati:</span>
                                                        @if ($item->status)
                                                            <span class="text-green-500 ml-1">Aktiv</span>
                                                        @else
                                                            <span class="text-red-500 ml-1">Aktiv emas</span>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <span class="font-semibold">Umumiy ball:</span>
                                                        <span class="bg-indigo-100 text-indigo-800 text-sm font-medium ml-1 px-2.5 py-0.5 rounded">{{ $item->total_points }}</span>
                                                    </div>
                                                </div>
                                                <a href="{{ route('dashboard.facultyShow', ['slug' => $item->slug]) }}" class="text-blue-600 hover:underline">Ko'rish</a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Desktop View -->
                                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 hidden sm:table">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="p-4">№</th>
                                            <th scope="col" class="px-6 py-3">Fakultet nomi</th>
                                            <th scope="col" class="px-6 py-3">Kafedralar</th>
                                            <th scope="col" class="px-6 py-3">Holati</th>
                                            <th scope="col" class="px-6 py-3">Umumiy ball</th>
                                            <th scope="col" class="px-6 py-3">Bajarish</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($faculties as $index => $item)
                                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                <td class="w-4 p-4">
                                                    <div class="flex items-center font-bold">
                                                        {{ $index + 1 }}
                                                    </div>
                                                </td>
                                                <th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                                    <img class="w-10 h-10 rounded-full" style="object-fit: cover;" src="https://cdn1.iconfinder.com/data/icons/got-idea-vol-2/128/branches-1024.png" alt="">
                                                    <div class="ps-3" style="width: 300px;">
                                                        <div class="text-base font-semibold" style="max-width: 260px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                            {{ $item->name }}
                                                        </div>
                                                        <div class="font-normal text-gray-500">
                                                            Fakultet ichidagi kafedralar soni <b class="text-blue-600">{{ $item->departments->count() }}</b> ta
                                                        </div>
                                                    </div>
                                                </th>
                                                <td class="px-6 py-4">
                                                    <div class="flex flex-wrap">
                                                        @foreach ($item->departments->take(4) as $department)
                                                            <span class="bg-blue-100 text-blue-800 text-xs font-sm me-2 mb-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">{{ $department->name }}</span>
                                                        @endforeach
                                                        @if ($item->departments->count() > 4)
                                                            <span class="text-xs  text-gray-500">+{{ $item->departments->count() - 3 }} ko'p</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center">
                                                        @if ($item->status)
                                                            <div class="h-2.5 w-2.5 rounded-full bg-green-500 me-2"></div>
                                                            Aktiv
                                                        @else
                                                            <div class="h-2.5 w-2.5 rounded-full bg-red-500 me-2"></div>
                                                            Aktiv emas
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    Jami ball: <span class="bg-indigo-100 text-indigo-800 text-md font-medium me-2 px-2.5 py-0.5 rounded dark:bg-indigo-900 dark:text-indigo-300">
                                                        {{ $item->total_points }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <a href="{{ route('dashboard.facultyShow', ['slug' => $item->slug]) }}" class="font-medium text-blue-600 hover:underline">
                                                        Ko'rish
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <h1 class="text-center text-xl font-medium mb-4 mt-2 text-gray-400">
                                    Fakultet topilmadi!
                                </h1>
                                @include('frogments.skeletonTable')
                            @endif
                        </div>
                        <div class="items-center justify-between mt-6">
                            {{ $faculties->links() }}
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
