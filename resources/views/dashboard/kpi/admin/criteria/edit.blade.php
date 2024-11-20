{{-- resources/views/admin/criteria/edit.blade.php --}}
<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6">KPI Mezonini tahrirlash</h2>

                    <form action="{{ route('admin.criteria.update', $criterion) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700">Kategoriya</label>
                            <select id="category" name="category" required
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @foreach($categories as $key => $value)
                                    <option value="{{ $key }}" {{ $criterion->category === $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nomi</label>
                            <input type="text" id="name" name="name" required
                                   value="{{ old('name', $criterion->name) }}"
                                   class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Tavsif</label>
                            <textarea id="description" name="description" rows="3" required
                                      class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('description', $criterion->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="max_points" class="block text-sm font-medium text-gray-700">Maksimal ball</label>
                            <input type="number" id="max_points" name="max_points" required
                                   value="{{ old('max_points', $criterion->max_points) }}" step="0.1" min="0"
                                   class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('max_points')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="calculation_method" class="block text-sm font-medium text-gray-700">Hisoblash metodikasi</label>
                            <textarea id="calculation_method" name="calculation_method" rows="3" required
                                      class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('calculation_method', $criterion->calculation_method) }}</textarea>
                            @error('calculation_method')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="evaluation_period" class="block text-sm font-medium text-gray-700">Baholash davri</label>
                            <input type="text" id="evaluation_period" name="evaluation_period" required
                                   value="{{ old('evaluation_period', $criterion->evaluation_period) }}"
                                   class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('evaluation_period')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="required_proof" class="block text-sm font-medium text-gray-700">Talab qilinadigan hujjatlar</label>
                            <textarea id="required_proof" name="required_proof" rows="2"
                                      class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('required_proof', $criterion->required_proof) }}</textarea>
                            @error('required_proof')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-gray-700">Tartib raqami</label>
                            <input type="number" id="sort_order" name="sort_order" required
                                   value="{{ old('sort_order', $criterion->sort_order) }}" min="0"
                                   class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('sort_order')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('admin.criteria.index') }}"
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Bekor qilish
                            </a>
                            <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Saqlash
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>