<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("O'qituvchilar ro'yxati sahifasi") }}
        </h2>

    </x-slot>


    {{-- Sahifada yangilanish qilganda o'ng tarafda chiqadigan bildirishnoma --}}
    {{-- @include('reyting.dashboard.professor.frogments.edit.toaster') --}}


    <div class="py-1 mt-6">

        <div class="max-w-8xl mx-auto sm:px-1 lg:px-1">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-1 bg-white border-b border-gray-200">


                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                        <div class="p-6 text-gray-900 mb-8">
                            <form class="mb-6" action="{{ route('dashboard.employees') }}" method="get">

                                <label for="default-search"
                                    class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Qidirish</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                        </svg>
                                    </div>
                                    <input type="search" name="name" id="default-search"
                                        class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="O'qituvchilarni qidirish...">
                                    <button type="submit"
                                        class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Qidirish</button>
                                </div>
                            </form>



                            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                @if (count($employee) > 0)
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
                                                    O'qituvchi F.I.SH
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Kafedrasi
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
                                            @foreach ($employee as $item)
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
                                                            src="{{ $item->image ? asset('storage/users/image/' . $item->image) : 'https://www.svgrepo.com/show/192244/man-user.svg' }}"
                                                            alt="">
                                                        <div class="ps-3" style="    width: 300px;">
                                                            <div class="text-base font-semibold"
                                                                style="max-width: 260px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                                {{ ucwords(strtolower($item->FullName ?? 'Kuzatuvchi')) }}
                                                            </div>
                                                            <div class="font-normal text-gray-500">
                                                                {{-- Fakultet ichidagi kafedralar soni <b class="text-blue-600">{{$item->department->count()}}</b> ta --}}
                                                            </div>
                                                        </div>
                                                    </th>

                                                    <td class="px-6 py-4">

                                                        <div class="my-1.2">

                                                            <span
                                                                class="bg-blue-100 text-blue-800 text-xs font-md me-3 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
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

                                                                {{ $item->department ? $item->department->name : 'N/A' }}</span>

                                                        </div>

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
                                                        @php
                                                        $totalPoints = $item->department
                                                            ? $item->department->point_user_deportaments()
                                                                ->where('status', 1)
                                                                ->where('user_id', $item->id)
                                                                ->sum('point')
                                                            : 0;
                                                    @endphp
                                                    {{ $item->department ? round($totalPoints, 2) : 'N/A' }}
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <a href="{{ route('dashboard.employeeShow', ['id_employee' => $item->employee_id_number]) }}"
                                                            class="font-medium text-blue-600  hover:underline">Ko'rish</a>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                @else
                                    <h1 class="text-center text-xl font-medium mb-4 mt-2 text-gray-400">
                                        O'qituvchilar topilmadi!</h1>
                                    @include('frogments.skeletonTable')
                                @endif

                            </div>
                            <div class=" items-center justify-between mt-6">
                                {{ $employee->links() }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
