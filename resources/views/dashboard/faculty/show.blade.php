<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center sm:text-left">
            {{ __("Fakultet ma'lumotlarini to'liq ko'rish") }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <div x-data="{ activeTab: 'about_us' }" class="container mx-auto">
                            <!-- Mobile Tab Dropdown -->
                            <div class="sm:hidden mb-4">
                                <select x-model="activeTab" class="block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="about_us">Fakultet haqida</option>
                                    <option value="department_list">Fakultet kafedralari</option>
                                    <option value="yuborilgan">Yuborilgan ma'lumotlar</option>
                                </select>
                            </div>

                            <!-- Desktop Tabs -->
                            <div class="hidden sm:block border-b border-gray-200">
                                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                                    <a href="#" @click.prevent="activeTab = 'about_us'" :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'about_us' }" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                        Fakultet haqida
                                    </a>
                                    <a href="#" @click.prevent="activeTab = 'department_list'" :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'department_list' }" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                        Fakultet kafedralari
                                    </a>
                                    <a href="#" @click.prevent="activeTab = 'yuborilgan'" :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'yuborilgan' }" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                        Yuborilgan ma'lumotlar
                                    </a>
                                </nav>
                            </div>

                            <!-- Tab Contents -->
                            <div class="mt-4">
                                <div x-show="activeTab === 'about_us'" class="space-y-4">
                                    <h2 class="text-2xl font-bold">Fakultet haqida</h2>
                                    <div class="flex flex-col sm:flex-row items-center">
                                        <div class="w-full sm:w-1/3 flex justify-center mb-4 sm:mb-0">
                                            <img src="https://cdn1.iconfinder.com/data/icons/got-idea-vol-2/128/branches-1024.png" alt="Logo" class="w-32 h-32 object-cover rounded-full border-2 border-gray-300">
                                        </div>
                                        <div class="w-full sm:w-2/3 p-4">
                                            <h1 class="text-2xl sm:text-3xl font-bold text-blue-700 mb-4">{{ $faculty->name }}</h1>
                                            <p class="text-lg text-blue-700 mb-4">
                                                Assalomu alaykum, {{ $faculty->name }}ga oid ma'lumotlar keyinchalik to'ldirilib boyitilib borilishi mumkin!
                                            </p>
                                            <ul class="list-disc list-inside text-gray-700 space-y-2">
                                                <li>Fakultet o'qituvchilar soni: {{ $totalEmployees }} nafar</li>
                                                <li>Fakultet to'plangan umumiy ballar: {{ $totalPoints }}</li>
                                                <li>Fakultet hisobidagi yuborilgan ma'lumotlar soni: {{ $totalInfos }} ta</li>
                                                <li>Oxirgi yuborilgan ma'lumot vaqti: {{ $timeAgo }}</li>
                                                <li>Oxirgi yuborgan ma'lumot egasi nomi: {{ $fullName }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div x-show="activeTab === 'department_list'" class="space-y-4">
                                    <h2 class="text-2xl font-bold">Fakultet kafedralari ro'yxati</h2>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kafedra nomi</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">O'qituvchilar soni</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Yuborilgan ma'lumotlar</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">To'plagan ballari</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach ($faculty->departments as $department)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="flex-shrink-0 h-10 w-10">
                                                                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-blue-100">
                                                                    <span class="text-blue-800 font-medium">{{ substr($department->name, 0, 2) }}</span>
                                                                </span>
                                                            </div>
                                                            <div class="ml-4">
                                                                <a href="{{ route('dashboard.departmentShow', ['slug' => $department->slug]) }}" class="text-sm font-medium text-gray-900 hover:text-blue-600">{{ $department->name }}</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $department->user->count() }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $department->point_user_deportaments->count() }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $department->point_user_deportaments->where('status', 1)->sum('point') }}
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div x-show="activeTab === 'yuborilgan'" class="space-y-4">
                                    <h3 class="text-2xl font-bold mb-5">Fakultet o'qituvchilari tomonidan yuborilgan ma'lumotlar</h3>
                                    @include('dashboard.item_list_component')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sahifa yuklanganda yoki pagination bo'lganda aktiv tabni saqlash
            const urlParams = new URLSearchParams(window.location.search);
            const page = urlParams.get('page');
            const storedTab = localStorage.getItem('activeTab');

            if (page) {
                Alpine.store('activeTab', 'yuborilgan');
            } else if (storedTab) {
                Alpine.store('activeTab', storedTab);
            }

            // Tablar o'zgarganda
            Alpine.data('tabs', () => ({
                activeTab: Alpine.store('activeTab') || 'about_us',
                init() {
                    this.$watch('activeTab', value => {
                        localStorage.setItem('activeTab', value);
                    })
                }
            }))
        });
    </script>
    @endpush
</x-app-layout>
