<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6">KPI Ma'lumot Yuborish</h2>

                    <form action="{{ route('kpi.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700">Kategoriya</label>
                            <select id="category" name="category" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Kategoriyani tanlang</option>
                                @foreach($categories as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="criteria_id" class="block text-sm font-medium text-gray-700">Mezon</label>
                            <select id="criteria_id" name="criteria_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Avval kategoriyani tanlang</option>
                            </select>
                            @error('criteria_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <p id="proof_info" class="text-sm text-gray-600 mt-1"></p>
                            <p id="max_points_info" class="text-sm text-gray-600 mt-1"></p>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Tavsif</label>
                            <textarea id="description" name="description" rows="4" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="proof_file" class="block text-sm font-medium text-gray-700">Tasdiqlovchi hujjat</label>
                            <input type="file" id="proof_file" name="proof_file" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('proof_file')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Yuborish
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const categorySelect = document.getElementById('category');
        const criteriaSelect = document.getElementById('criteria_id');
        const proofInfo = document.getElementById('proof_info');
        const maxPointsInfo = document.getElementById('max_points_info');

        categorySelect.addEventListener('change', function() {
            const category = this.value;

            if (!category) {
                criteriaSelect.innerHTML = '<option value="">Avval kategoriyani tanlang</option>';
                proofInfo.textContent = '';
                maxPointsInfo.textContent = '';
                return;
            }

            fetch(`/kpi/criteria/${category}`)
                .then(response => response.json())
                .then(criteria => {
                    criteriaSelect.innerHTML = '<option value="">Mezonni tanlang</option>';
                    criteria.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.id;
                        option.textContent = item.name;
                        option.dataset.proof = item.required_proof;
                        option.dataset.maxPoints = item.max_points;
                        criteriaSelect.appendChild(option);
                    });
                });
        });

        criteriaSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];

            if (selectedOption.dataset.proof) {
                proofInfo.textContent = `Kerakli hujjatlar: ${selectedOption.dataset.proof}`;
                maxPointsInfo.textContent = `Maksimal ball: ${selectedOption.dataset.maxPoints}`;
            } else {
                proofInfo.textContent = '';
                maxPointsInfo.textContent = '';
            }
        });
    </script>
    @endpush
</x-app-layout>
