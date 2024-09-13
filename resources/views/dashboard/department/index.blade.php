<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("Kafedralar ro'yxati sahifasi") }}
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
                            <form class="mb-6" action="{{route('dashboard.departments')}}" method="get">

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
                                        placeholder="Kafedralarni qidirish...">
                                    <button type="submit"
                                        class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Qidirish</button>
                                </div>
                            </form>



                            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                @if (count($departments) > 0)
                                    <!-- Mobile and Tablet View -->
                                    <div class="sm:hidden">
                                        @foreach ($departments as $index => $item)
                                            <div class="mb-4 bg-white border rounded-lg shadow-sm p-4">
                                                <div class="flex items-center mb-2">
                                                    <img class="w-10 h-10 rounded-full mr-3" src="https://image.pngaaa.com/419/1509419-middle.png" alt="">
                                                    <div>
                                                        <div class="font-semibold text-lg">{{ $item->name }}</div>
                                                        <div class="text-sm text-gray-500">O'qituvchilar soni: <b class="text-blue-600">{{$item->user->count()}}</b> ta</div>
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
                                                        <span class="bg-indigo-100 text-indigo-800 text-sm font-medium ml-1 px-2.5 py-0.5 rounded">
                                                            {{ round($item->point_user_deportaments()->where('status', 1)->sum('point'), 2) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <a href="{{ route('dashboard.departmentShow', ['slug' => $item->slug]) }}" class="text-blue-600 hover:underline">
                                                    Ko'rish
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Desktop View -->
                                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 hidden sm:table">
                                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                            <tr>
                                                <th scope="col" class="p-4">№</th>
                                                <th scope="col" class="px-6 py-3">Kafedra nomi</th>
                                                <th scope="col" class="px-6 py-3">Holati</th>
                                                <th scope="col" class="px-6 py-3">Umumiy ball</th>
                                                <th scope="col" class="px-6 py-3">Bajarish</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($departments as $index => $item)
                                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                    <td class="w-4 p-4">
                                                        <div class="flex items-center font-bold">
                                                            {{ $index + 1 }}
                                                        </div>
                                                    </td>
                                                    <th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                                        <img class="w-10 h-10 rounded-full" style="object-fit: cover;" src="https://image.pngaaa.com/419/1509419-middle.png" alt="">
                                                        <div class="ps-3">
                                                            <div class="text-base font-semibold" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                                {{ $item->name }}
                                                            </div>
                                                            <div class="font-normal text-gray-500">
                                                                O'qituvchilar soni <b class="text-blue-600">{{$item->user->count()}}</b> ta
                                                            </div>
                                                        </div>
                                                    </th>
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
                                                        {{ round($item->totalPoints, 2) }}
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <a href="{{ route('dashboard.departmentShow', ['slug' => $item->slug]) }}" class="font-medium text-blue-600 hover:underline">
                                                            Ko'rish
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <h1 class="text-center text-xl font-medium mb-4 mt-2 text-gray-400">
                                        Kafedralar topilmadi!
                                    </h1>
                                    @include('frogments.skeletonTable')
                                @endif
                            </div>
                            <div class=" items-center justify-between mt-6">
                                {{ $departments->links() }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
