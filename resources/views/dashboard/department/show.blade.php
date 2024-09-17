<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-center sm:text-left text-gray-800 leading-tight">
            {{ __("Kafedra ma'lumotlarini to'liq ko'rish") }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div x-data="{ activeTab: 'yuborilgan' }" class="container mx-auto">
                        <!-- Mobile Tab Dropdown -->
                        <div class="sm:hidden mb-6">
                            <label for="tabs" class="sr-only">Tanlang</label>
                            <select id="tabs" x-model="activeTab"
                                class="block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="about_us">Kafedra haqida</option>
                                <option value="department_list">O'qituvchilar ro'yxati</option>
                                <option value="yuborilgan">Yuborilgan ma'lumotlar</option>
                            </select>
                        </div>

                        <!-- Desktop Tabs -->
                        <div class="hidden sm:block mb-6">
                            <nav class="flex space-x-4" aria-label="Tabs">
                                <button @click="activeTab = 'about_us'"
                                    :class="{ 'bg-indigo-100 text-indigo-700': activeTab === 'about_us' }"
                                    class="px-3 py-2 font-medium text-sm rounded-md">Kafedra haqida</button>
                                <button @click="activeTab = 'department_list'"
                                    :class="{ 'bg-indigo-100 text-indigo-700': activeTab === 'department_list' }"
                                    class="px-3 py-2 font-medium text-sm rounded-md">O'qituvchilar ro'yxati</button>
                                <button @click="activeTab = 'yuborilgan'"
                                    :class="{ 'bg-indigo-100 text-indigo-700': activeTab === 'yuborilgan' }"
                                    class="px-3 py-2 font-medium text-sm rounded-md">Yuborilgan ma'lumotlar</button>
                            </nav>
                        </div>

                        <!-- Tab Contents -->
                        <div class="mt-8">
                            <div x-show="activeTab === 'about_us'" class="space-y-6">
                                <h2 class="text-2xl font-bold text-gray-900">Kafedra haqida</h2>

                                <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
                                    <span class="font-medium">INFO!</span> {{$pointsCalculationExplanation}}
                                  </div>

                                <div class="flex flex-col sm:flex-row items-center bg-gray-50 rounded-lg p-6 shadow-sm">
                                    <div class="w-full sm:w-1/3 flex justify-center mb-6 sm:mb-0">
                                        <img src="https://cdn1.iconfinder.com/data/icons/got-idea-vol-2/128/branches-1024.png"
                                            alt="Logo"
                                            class="w-40 h-40 object-cover rounded-full border-4 border-indigo-300">
                                    </div>
                                    <div class="w-full sm:w-2/3 sm:pl-8">
                                        <h1 class="text-3xl font-bold text-indigo-700 mb-4">{{ $department->name }}</h1>
                                        <p class="text-xl text-gray-700 mb-6">
                                            Assalomu alaykum, {{ $department->name }}ga oid ma'lumotlar keyinchalik
                                            to'ldirilib boyitilib borilishi mumkin!
                                        </p>
                                        <ul class="space-y-3 text-lg text-gray-600">
                                            <li><span class="font-semibold">Kafedra o'qituvchilar soni:</span>
                                                <span
                                                    class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                                    Saytga kirgan: {{ $totalEmployees }} nafar
                                                </span>

                                                <span
                                                    class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                                    Hemis bo'yicha: {{ $unregisteredEmployees }} nafar
                                                </span>
                                            </li>
                                            <li><span class="font-semibold">Kafedra to'plangan umumiy ballar:</span>
                                                <span
                                                    class="bg-green-100 text-green-800 text-md font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">
                                                    {{ $totalPoints }} ball
                                                </span>
                                            </li>
                                            <li><span class="font-semibold">Kafedraga o'tgan ballar:</span>
                                                <span
                                                    class="bg-indigo-100 text-indigo-800 text-sm font-sm me-2 px-2 py-0.5 rounded dark:bg-green-900 dark:text-green-300">
                                                    {{ $departmentExtraPoints }} ball
                                                </span>
                                            </li>
                                            <li><span class="font-semibold">Kafedra hisobidagi yuborilgan ma'lumotlar
                                                    soni:</span> {{ $totalInfos }} ta</li>
                                            <li><span class="font-semibold">Oxirgi yuborilgan ma'lumot vaqti:</span>
                                                {{ $timeAgo }}</li>
                                            <li><span class="font-semibold">Oxirgi yuborgan ma'lumot egasi:</span>
                                                <span
                                                    class="bg-indigo-100 text-indigo-800 text-lg font-medium me-2 px-2.5 py-0.5 rounded dark:bg-indigo-900 dark:text-indigo-300">
                                                    {{ $fullName }}
                                                </span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div x-show="activeTab === 'department_list'" class="space-y-6">
                                <h2 class="text-2xl font-bold text-gray-900">O'qituvchilar ro'yxati</h2>
                                <div class="overflow-x-auto bg-white rounded-lg shadow">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    O'qituvchi F.I.SH</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Yuborilgan ma'lumotlar soni</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    To'plagan ballari</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($department->employee as $employee)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="flex-shrink-0 h-10 w-10">
                                                                <span
                                                                    class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-indigo-100 text-indigo-800 font-medium text-lg">
                                                                    {{ substr($employee->FullName, 0, 2) }}
                                                                </span>
                                                            </div>
                                                            <div class="ml-4">
                                                                <a href="{{ route('dashboard.employeeShow', ['id_employee' => $employee->employee_id_number]) }}"
                                                                    class="text-sm font-medium text-gray-900 hover:text-indigo-600">
                                                                    {{ ucwords(strtolower($employee->FullName)) }}
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $employee->department->point_user_deportaments()->where('user_id', $employee->id)->count() }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                            {{ $employee->department->point_user_deportaments()->where('status', 1)->where('user_id', $employee->id)->sum('point') }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div x-show="activeTab === 'yuborilgan'" class="space-y-6">
                                <h3 class="text-2xl font-bold text-gray-900 mb-6">Kafedra o'qituvchilari tomonidan
                                    yuborilgan ma'lumotlar</h3>
                                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                                    <div class="p-6 text-gray-900">
                                        @include('dashboard.item_list_component')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const urlParams = new URLSearchParams(window.location.search);
                const page = urlParams.get('page');
                const storedTab = localStorage.getItem('activeTab');

                let initialTab = 'about_us';

                if (page || urlParams.has('yuborilgan')) {
                    initialTab = 'yuborilgan';
                } else if (storedTab) {
                    initialTab = storedTab;
                }

                Alpine.data('tabs', () => ({
                    activeTab: initialTab,
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
