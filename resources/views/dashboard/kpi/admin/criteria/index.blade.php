<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800">KPI Mezonlari</h2>
                        <a href="{{ route('admin.criteria.create') }}" 
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Yangi mezon
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tartib
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kategoriya
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nomi
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Maksimal ball
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Amallar
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($criteria as $criterion)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $criterion->sort_order }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ \App\Models\KpiCriteria::categories()[$criterion->category] }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $criterion->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $criterion->max_points }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('admin.criteria.edit', $criterion) }}" 
                                               class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                Tahrirlash
                                            </a>
                                            <form action="{{ route('admin.criteria.destroy', $criterion) }}" 
                                                  method="POST" 
                                                  class="inline"
                                                  onsubmit="return confirm('Rostdan ham o\'chirmoqchimisiz?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-900">
                                                    O'chirish
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $criteria->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
