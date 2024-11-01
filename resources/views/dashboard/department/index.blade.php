<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg shadow-lg">
            <h2 class="py-6 text-2xl font-bold text-center text-white leading-tight">
                {{ __("Kafedralar ro'yxati") }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="p-6">
                    <!-- Qidiruv qismi -->
                    <form action="{{ route('dashboard.departments') }}" method="get" class="mb-8">
                        <div class="relative">
                            <input type="search" name="name"
                                class="w-full pl-12 pr-20 py-4 text-gray-700 bg-gray-50 border border-gray-200 rounded-xl focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-colors duration-200"
                                placeholder="Kafedralarni qidirish...">
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <button type="submit"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-colors duration-200">
                                Qidirish
                            </button>
                        </div>
                    </form>

                    <!-- Kafedralar ro'yxati -->
                    <div class="bg-white rounded-xl shadow-sm">
                        @if (count($departments) > 0)
                            <!-- Mobile versiya -->
                            <div class="grid gap-4 sm:hidden">
                                @foreach ($departments as $index => $item)
                                    <a href="{{ route('dashboard.departmentShow', ['slug' => $item->slug]) }}">
                                        <div
                                            class="bg-white p-4 rounded-xl border border-gray-100 hover:border-blue-200 hover:shadow-lg transition-all duration-200">
                                            <div class="flex items-center space-x-4">
                                                <div class="flex-shrink-0">
                                                    <img class="w-16 rounded-full"
                                                        src="{{ asset('assets/images/logo1.png') }}"
                                                        alt="">
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-md font-semibold text-gray-900">
                                                        {{ $item->name }}
                                                    </p>
                                                    <div class="mt-2 text-base text-gray-500">
                                                        <span class="font-medium">{{ $item->user->count() }}</span> ta
                                                        o'qituvchi ro'yxatdan o'tgan
                                                    </div>
                                                    <div class="flex items-center mt-2 space-x-2">
                                                        <span
                                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $item->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                            {{ $item->status ? 'Aktiv' : 'Aktiv emas' }}
                                                        </span>
                                                        <span class=" text-lg text-right font-semibold text-blue-600">
                                                            {{ $item->totalPoints }} ball
                                                        </span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>

                            <!-- Desktop versiya -->
                            <table class="w-full hidden sm:table">
                                <thead>
                                    <tr class="bg-gradient-to-r from-gray-50 to-gray-100">
                                        <th
                                            class="px-6 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">
                                            №</th>
                                        <th
                                            class="px-6 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">
                                            Kafedra</th>
                                        <th
                                            class="px-6 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">
                                            Holati</th>
                                        <th
                                            class="px-6 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">
                                            Ball</th>
                                        <th
                                            class="px-6 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">
                                            Amal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($departments as $index => $item)
                                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ ($departments->currentPage() - 1) * $departments->perPage() + $loop->iteration }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 w-10">
                                                        <img class="w-10 rounded-full"
                                                            src="{{ asset('assets/images/logo2.png') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-xl font-medium text-gray-900">
                                                        {{ \Str::limit($item->name . " kafedrasi", 60, '...') }}</div>
                                                        <div class="text-base text-gray-600">
                                                            <span
                                                                class="inline-flex items-center bg-blue-50 px-3 py-1 rounded-lg">
                                                                <svg class="w-3 h-3 text-blue-600 mr-2" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                </svg>
                                                                <span class="font-bold text-blue-600 text-sm">
                                                                    {{ $item->user->count() }}
                                                                </span>
                                                                <span class="ml-2 font-medium text-gray-700">
                                                                    ta o'qituvchi ro'yxatdan o'tgan
                                                                </span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $item->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $item->status ? 'Aktiv' : 'Aktiv emas' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-xl font-semibold text-blue-600">
                                                    {{ $item->totalPoints }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <a href="{{ route('dashboard.departmentShow', ['slug' => $item->slug]) }}"
                                                    class="inline-flex items-center px-3 py-1.5 text-base font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-all duration-300 transform hover:scale-105">
                                                    <span>Ko'rish</span>
                                                    <svg class="w-5 h-5 ml-2 -mr-1" fill="none" stroke="currentColor"
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
                        @else
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01" />
                                </svg>
                                <h3 class="mt-4 text-lg font-medium text-gray-900">Kafedralar topilmadi!</h3>
                                <p class="mt-2 text-sm text-gray-500">Kafedralar ro'yxati bo'sh yoki qidiruv so'rovi
                                    bo'yicha hech narsa topilmadi.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $departments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
