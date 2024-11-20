<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6">KPI Tekshiruv</h2>

                    <!-- Filter Panel -->
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                        <form action="{{ route('admin.kpi.index') }}" method="GET" class="flex gap-4">
                            <div class="flex-1">
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Barchasi</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Kutilmoqda</option>
                                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Tasdiqlangan</option>
                                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rad etilgan</option>
                                </select>
                            </div>

                            <div class="flex-1">
                                <label for="category" class="block text-sm font-medium text-gray-700">Kategoriya</label>
                                <select name="category" id="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Barchasi</option>
                                    @foreach($categories as $key => $value)
                                        <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex items-end">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Filtrlash
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Data Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        O'qituvchi
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kategoriya
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Mezon
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Ball
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Yuborilgan sana
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Amallar
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($submissions as $submission)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="font-medium text-gray-900">{{ $submission->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $submission->user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $categories[$submission->category] }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-900">{{ $submission->criteria->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $submission->description }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $submission->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                   ($submission->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                                {{ $submission->status === 'pending' ? 'Kutilmoqda' : 
                                                   ($submission->status === 'approved' ? 'Tasdiqlangan' : 'Rad etilgan') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $submission->points ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $submission->created_at->format('d.m.Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            @if($submission->status === 'pending')
                                                <button type="button" 
                                                        onclick="openReviewModal('{{ $submission->id }}', '{{ $submission->criteria->max_points }}')"
                                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">
                                                    Baholash
                                                </button>
                                            @else
                                                <button type="button" 
                                                        onclick="openReviewModal('{{ $submission->id }}', '{{ $submission->criteria->max_points }}')"
                                                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-1 px-3 rounded text-sm">
                                                    Ko'rish
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                            Ma'lumotlar topilmadi
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $submissions->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Review Modal -->
    <div id="reviewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <form id="reviewForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700">Maksimal ball</label>
                    <p id="maxPointsInfo" class="text-sm text-gray-500"></p>
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select id="status" name="status" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="approved">Tasdiqlash</option>
                        <option value="rejected">Rad etish</option>
                    </select>
                </div>

                <div>
                    <label for="points" class="block text-sm font-medium text-gray-700">Ball</label>
                    <input type="number" id="points" name="points" step="0.1" min="0"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="admin_comment" class="block text-sm font-medium text-gray-700">Izoh</label>
                    <textarea id="admin_comment" name="admin_comment" rows="3" required
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeReviewModal()"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Bekor qilish
                    </button>
                    <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Saqlash
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        let currentMaxPoints = 0;

        function openReviewModal(submissionId, maxPoints) {
            const modal = document.getElementById('reviewModal');
            const form = document.getElementById('reviewForm');
            const maxPointsInfo = document.getElementById('maxPointsInfo');
            const pointsInput = document.getElementById('points');
            
            currentMaxPoints = maxPoints;
            maxPointsInfo.textContent = `Maksimal ball: ${maxPoints}`;
            pointsInput.max = maxPoints;
            
            form.action = `/admin/kpi/${submissionId}/review`;
            modal.classList.remove('hidden');

            // Points validation
            pointsInput.addEventListener('input', function() {
                if (this.value > currentMaxPoints) {
                    this.value = currentMaxPoints;
                }
            });
            
            // Status change handling
            document.getElementById('status').addEventListener('change', function() {
                const pointsField = document.getElementById('points');
                if (this.value === 'approved') {
                    pointsField.required = true;
                    pointsField.disabled = false;
                } else {
                    pointsField.required = false;
                    pointsField.disabled = true;
                    pointsField.value = '';
                }
            });
        }

        function closeReviewModal() {
            const modal = document.getElementById('reviewModal');
            modal.classList.add('hidden');
        }

        // Close modal on outside click
        document.getElementById('reviewModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeReviewModal();
            }
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeReviewModal();
            }
        });
    </script>
    @endpush
</x-app-layout>