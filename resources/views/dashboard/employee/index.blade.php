<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl sm:text-3xl text-gray-900 leading-tight text-center sm:text-left">
            {{ __("O'qituvchilar ro'yxati") }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form class="mb-6" action="{{ route('dashboard.employees') }}" method="get">
                        <div class="relative">
                            <input type="search" name="name" id="default-search"
                                class="block w-full p-4 pl-10 text-sm sm:text-base text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="O'qituvchilarni qidirish...">
                            <button type="submit"
                                class="text-white absolute right-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm sm:text-base px-4 py-2">
                                Qidirish
                            </button>
                        </div>
                    </form>

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        @if (count($employee) > 0)
                            <!-- Mobile view -->
                            <div class="sm:hidden">
                                @foreach ($employee as $index => $item)
                                    <div class="bg-white border-b border-gray-200 rounded-lg shadow-sm mb-4 p-4">
                                        <div class="flex items-center mb-3">
                                            <img class="w-12 h-12 rounded-full mr-4"
                                                src="{{ $item->image ? asset('storage/users/image/' . $item->image) : 'https://www.svgrepo.com/show/192244/man-user.svg' }}"
                                                alt="">
                                            <div>
                                                <div class="font-semibold text-lg text-gray-900">
                                                    {{ ucwords(strtolower($item->FullName ?? 'Kuzatuvchi')) }}
                                                </div>
                                                <div class="text-sm text-gray-600">
                                                    {{ $item->department ? $item->department->name : 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex justify-between items-center text-sm">
                                            <div class="flex items-center">
                                                @if ($item->status)
                                                    <div class="h-2.5 w-2.5 rounded-full bg-green-500 mr-2"></div>
                                                    <span class="text-green-600">Aktiv</span>
                                                @else
                                                    <div class="h-2.5 w-2.5 rounded-full bg-red-500 mr-2"></div>
                                                    <span class="text-red-600">Aktiv emas</span>
                                                @endif
                                            </div>
                                            <div>
                                                Umumiy ball: <span class="font-semibold">
                                                    @php
                                                        $totalPoints = $item->department
                                                            ? $item->department->point_user_deportaments()
                                                                ->where('status', 1)
                                                                ->where('user_id', $item->id)
                                                                ->sum('point')
                                                            : 0;
                                                    @endphp
                                                    {{ $item->department ? round($totalPoints, 2) : 'N/A' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <a href="{{ route('dashboard.employeeShow', ['id_employee' => $item->employee_id_number]) }}"
                                                class="text-blue-600 hover:underline">Ko'rish</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Tablet and Desktop view -->
                            <table class="w-full text-sm sm:text-base text-left text-gray-500 hidden sm:table">
                                <thead class="text-xs sm:text-sm text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" class="p-4">№</th>
                                        <th scope="col" class="px-6 py-3">O'qituvchi F.I.SH</th>
                                        <th scope="col" class="px-6 py-3">Kafedrasi</th>
                                        <th scope="col" class="px-6 py-3">Holati</th>
                                        <th scope="col" class="px-6 py-3">Umumiy ball</th>
                                        <th scope="col" class="px-6 py-3">Bajarish</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employee as $index => $item)
                                        <tr class="bg-white border-b hover:bg-gray-50">
                                            <td class="w-4 p-4 font-medium">{{ $index + 1 }}</td>
                                            <th scope="row" class="flex items-center px-6 py-4 whitespace-nowrap">
                                                <img class="w-10 h-10 rounded-full"
                                                    src="{{ $item->image ? asset('storage/users/image/' . $item->image) : 'https://www.svgrepo.com/show/192244/man-user.svg' }}"
                                                    alt="">
                                                <div class="pl-3">
                                                    <div class="text-base font-semibold text-gray-900">
                                                        {{ ucwords(strtolower($item->FullName ?? 'Kuzatuvchi')) }}
                                                    </div>
                                                </div>
                                            </th>
                                            <td class="px-6 py-4">
                                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                                    {{ $item->department ? $item->department->name : 'N/A' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    @if ($item->status)
                                                        <div class="h-2.5 w-2.5 rounded-full bg-green-500 mr-2"></div>
                                                        Aktiv
                                                    @else
                                                        <div class="h-2.5 w-2.5 rounded-full bg-red-500 mr-2"></div>
                                                        Aktiv emas
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 font-medium">
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
                                                    class="font-medium text-blue-600 hover:underline">Ko'rish</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <h1 class="text-center text-xl font-medium my-8 text-gray-400">
                                O'qituvchilar topilmadi!
                            </h1>
                            @include('frogments.skeletonTable')
                        @endif
                    </div>
                    <div class="mt-6">
                        {{ $employee->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
