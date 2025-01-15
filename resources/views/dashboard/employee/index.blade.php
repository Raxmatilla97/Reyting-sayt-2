<x-app-layout>

    <x-slot name="header">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg shadow-lg">
            <h2 class="py-6 text-2xl sm:text-3xl font-bold text-center text-white leading-tight">
                <span class="block text-xl sm:text-2xl mt-2 font-medium text-blue-100">
                    {{ __("O'qituvchilar ro'yxati sahifasi") }}
                </span>
            </h2>
        </div>
    </x-slot>



    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="flex justify-between items-center p-6 border-b border-gray-200">

                    <div class="flex items-center space-x-4">
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('dashboard.employees.export') }}"
                               class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Excelga yuklash
                            </a>
                        @endif
                    </div>

                    <div class="flex items-center space-x-6">
                        <div class="flex items-center">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                                Aktiv: {{ \App\Models\Employee::where('status', 1)->count() }}
                            </span>
                        </div>

                        <div class="flex items-center">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                <span class="w-2 h-2 rounded-full bg-red-500 mr-2"></span>
                                Aktiv emas: {{ \App\Models\Employee::where('status', 0)->count() }}
                            </span>
                        </div>
                    </div>
                </div>
                <!-- Search Bar -->
                <div class="p-6 border-b border-gray-200">
                    <form action="{{ route('dashboard.employees') }}" method="get">
                        <div class="relative">
                            <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="search" name="name"
                                class="block w-full pl-12 pr-32 py-4 text-gray-900 border border-gray-200 rounded-xl bg-gray-50 focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-colors"
                                placeholder="O'qituvchilarni qidirish...">
                            <button type="submit"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-colors">
                                Qidirish
                            </button>
                        </div>
                    </form>
                </div>

                @if (count($employee) > 0)
                    <!-- Mobile Cards -->
                    <div class="sm:hidden">
                        <div class="divide-y divide-gray-200">
                            @foreach ($employee as $index => $item)
                                <div class="p-6 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <img class="h-16 w-16 rounded-xl shadow object-cover"
                                                src="{{ $item->image ? asset('storage/users/image/' . $item->image) : 'https://otm.cspu.uz/storage/users/image/image_1729094435_670fe3233b26d.jpg' }}"
                                                alt="">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-lg font-semibold text-gray-900 truncate">
                                                {{ ucwords(strtolower($item->FullName ?? 'Kuzatuvchi')) }}
                                            </p>
                                            <p class="text-sm text-gray-500 mb-2">
                                                {{ $item->department ? $item->department->name : 'N/A' }}
                                            </p>
                                            <div class="flex items-center space-x-4">
                                                @if ($item->status)
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <span
                                                            class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                                                        Aktiv
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span>
                                                        Aktiv emas
                                                    </span>
                                                @endif
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ round($item->department? $item->department->point_user_deportaments()->where('status', 1)->where('user_id', $item->id)->sum('point'): 0,2) }}
                                                    ball
                                                </span>
                                            </div>
                                        </div>
                                        <a href="{{ route('dashboard.employeeShow', ['id_employee' => $item->employee_id_number]) }}"
                                            class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-gray-50 hover:bg-gray-100 text-gray-500 transition-colors">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Desktop Table -->
                    <div class="hidden sm:block">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        №</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        O'qituvchi</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kafedra</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Holat</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Ball</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($employee as $index => $item)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ ($employee->currentPage() - 1) * $employee->perPage() + $loop->iteration }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <img class="h-10 w-10 rounded-lg object-cover"
                                                    src="{{ $item->image ? asset('storage/users/image/' . $item->image) : 'https://www.svgrepo.com/show/192244/man-user.svg' }}"
                                                    alt="">
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ ucwords(strtolower($item->FullName ?? 'Kuzatuvchi')) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-md text-sm font-medium bg-blue-50 text-blue-700">
                                                {{ $item->department ? $item->department->name : 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($item->status)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                                                    Aktiv
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span>
                                                    Aktiv emas
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm font-medium">
                                                {{ round($item->department? $item->department->point_user_deportaments()->where('status', 1)->where('user_id', $item->id)->sum('point'): 0,2) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <a href="{{ route('dashboard.employeeShow', ['id_employee' => $item->employee_id_number]) }}"
                                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                                Ko'rish
                                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">O'qituvchilar topilmadi</h3>
                        <p class="mt-1 text-sm text-gray-500">Qidiruv natijasida hech qanday o'qituvchi topilmadi.</p>
                    </div>
                @endif

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $employee->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
