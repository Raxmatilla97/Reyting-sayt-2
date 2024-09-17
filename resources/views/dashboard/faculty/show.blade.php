<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl sm:text-3xl text-gray-900 leading-tight text-center sm:text-left">
            {{ __("Fakultet ma'lumotlarini to'liq ko'rish") }}
        </h2>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                <div class="p-6 sm:p-8 bg-white border-b border-gray-200">
                    <div class="mb-8">
                        <div x-data="{ activeTab: 'yuborilgan' }" class="container mx-auto">
                            <!-- Mobile Tab Dropdown -->
                            <div class="sm:hidden mb-6">
                                <select x-model="activeTab"
                                    class="block w-full px-4 py-3 text-base bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="about_us">Fakultet haqida</option>
                                    <option value="department_list">Fakultet kafedralari</option>
                                    <option value="yuborilgan">Yuborilgan ma'lumotlar</option>
                                </select>
                            </div>

                            <!-- Desktop Tabs -->
                            <div class="hidden sm:block border-b border-gray-200 mb-8">
                                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                                    <a href="#" @click.prevent="activeTab = 'about_us'"
                                        :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'about_us' }"
                                        class="border-transparent text-gray-600 hover:text-gray-800 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-lg">
                                        Fakultet haqida
                                    </a>
                                    <a href="#" @click.prevent="activeTab = 'department_list'"
                                        :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'department_list' }"
                                        class="border-transparent text-gray-600 hover:text-gray-800 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-lg">
                                        Fakultet kafedralari
                                    </a>
                                    <a href="#" @click.prevent="activeTab = 'yuborilgan'"
                                        :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'yuborilgan' }"
                                        class="border-transparent text-gray-600 hover:text-gray-800 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-lg">
                                        Yuborilgan ma'lumotlar
                                    </a>
                                </nav>
                            </div>

                            <!-- Tab Contents -->
                            <div class="mt-6">
                                <div x-show="activeTab === 'about_us'" class="space-y-6">
                                    <h2 class="text-2xl font-bold text-gray-900">Fakultet haqida</h2>
                                    <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
                                        <span class="font-medium">INFO!</span> {{$pointsCalculationExplanation}}
                                      </div>
                                    <div
                                        class="flex flex-col sm:flex-row items-center bg-gray-50 rounded-lg p-6 shadow-sm">
                                        <div class="w-full sm:w-1/3 flex justify-center mb-6 sm:mb-0">
                                            <img src="https://cdn1.iconfinder.com/data/icons/got-idea-vol-2/128/branches-1024.png"
                                                alt="Logo"
                                                class="w-40 h-40 object-cover rounded-full border-4 border-indigo-300">
                                        </div>
                                        <div class="w-full sm:w-2/3 sm:pl-8">
                                            <h1 class="text-3xl sm:text-4xl font-bold text-indigo-700 mb-4">
                                                {{ $faculty->name }}</h1>
                                            <p class="text-xl text-gray-700 mb-6">
                                                Assalomu alaykum, {{ $faculty->name }}ga oid ma'lumotlar keyinchalik
                                                to'ldirilib boyitilib borilishi mumkin!
                                            </p>
                                            <ul class="space-y-3 text-lg text-gray-600">
                                                <li><span class="font-semibold">Fakultet o'qituvchilar soni:</span>
                                                    {{ $totalEmployees }} nafar</li>
                                                <li><span class="font-semibold">Fakultet to'plangan umumiy
                                                        ballar:</span> {{ $averagePoints }}</li>
                                                <li><span class="font-semibold">Fakultet hisobidagi yuborilgan
                                                        ma'lumotlar soni:</span> {{ $totalInfos }} ta</li>
                                                <li><span class="font-semibold">Oxirgi yuborilgan ma'lumot vaqti:</span>
                                                    {{ $timeAgo }}</li>
                                                <li><span class="font-semibold">Oxirgi yuborgan ma'lumot egasi
                                                        nomi:</span> {{ $fullName }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div x-show="activeTab === 'department_list'" class="space-y-6">
                                    <h2 class="text-2xl font-bold text-gray-900">Fakultet kafedralari ro'yxati</h2>
                                    <div class="overflow-x-auto bg-white rounded-lg shadow">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Kafedra nomi</th>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        O'qituvchilar soni</th>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Yuborilgan ma'lumotlar</th>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        To'plagan ballari</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @php
                                                    $departmentCounts = config('departament_tichers_count');
                                                    if ($departmentCounts === null) {
                                                        $departmentCounts = include config_path(
                                                            'departament_tichers_count.php',
                                                        );
                                                    }
                                                @endphp

                                                @foreach ($faculty->departments as $department)
                                                    @php
                                                        $configTeacherCount = 0;
                                                        foreach ($departmentCounts as $facultyDepartments) {
                                                            if (isset($facultyDepartments[$department->id])) {
                                                                $configTeacherCount =
                                                                    $facultyDepartments[$department->id];
                                                                break;
                                                            }
                                                        }

                                                        $departmentPointTotal = 0;
                                                        foreach (
                                                            $department->point_user_deportaments->where('status', 1)
                                                            as $pointEntry
                                                        ) {
                                                            $departmentPointTotal += $pointEntry->point;
                                                            if ($pointEntry->departPoint) {
                                                                $departmentPointTotal +=
                                                                    $pointEntry->departPoint->point;
                                                            }
                                                        }

                                                        $averagePoints =
                                                            $configTeacherCount > 0
                                                                ? round($departmentPointTotal / $configTeacherCount, 2)
                                                                : 'N/A';
                                                    @endphp
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="flex items-center">
                                                                <div class="flex-shrink-0 h-12 w-12">
                                                                    <span
                                                                        class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100">
                                                                        <span
                                                                            class="text-xl font-medium text-indigo-800">{{ substr($department->name, 0, 2) }}</span>
                                                                    </span>
                                                                </div>
                                                                <div class="ml-4">
                                                                    <a href="{{ route('dashboard.departmentShow', ['slug' => $department->slug]) }}"
                                                                        class="text-lg font-medium text-gray-900 hover:text-indigo-600">{{ $department->name }}</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        {{-- <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-500">
                                                            {{ $department->user->count() }}
                                                        </td> --}}
                                                        <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-500">
                                                            {{ $configTeacherCount }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-lg text-gray-500">
                                                            {{ $department->point_user_deportaments->count() }}
                                                        </td>
                                                        {{-- <td class="px-6 py-4 whitespace-nowrap">
                                                            <span
                                                                class="px-3 py-1 inline-flex text-lg leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                                {{ $totalPoints }}
                                                            </span>
                                                        </td> --}}
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span
                                                                class="px-3 py-1 inline-flex text-lg leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                                {{ $averagePoints }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div x-show="activeTab === 'yuborilgan'" class="space-y-6">
                                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Fakultet o'qituvchilari tomonidan
                                        yuborilgan ma'lumotlar</h3>
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
            // Your existing JavaScript remains unchanged
        </script>
    @endpush
</x-app-layout>
