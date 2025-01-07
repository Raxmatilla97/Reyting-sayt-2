<x-app-layout>
    <div class="py-12">

        <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Section -->
            <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100">
                            <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Jami arizalar</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $statistics['total'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100">
                            <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Kutilmoqda</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $statistics['pending'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100">
                            <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Tasdiqlangan</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $statistics['approved'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-red-100">
                            <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Rad etilgan</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $statistics['rejected'] }}</p>
                        </div>
                    </div>
                </div>
            </div>




            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6">KPI Tekshiruv</h2>

                    <!-- Filter Panel -->
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                        <form action="{{ route('admin.kpi.index') }}" method="GET" class="flex flex-wrap gap-4">
                            <div class="flex-1 min-w-[200px]">
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Barchasi</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>
                                        Kutilmoqda</option>
                                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>
                                        Tasdiqlangan</option>
                                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>
                                        Rad etilgan</option>
                                </select>
                            </div>

                            <div class="flex-1 min-w-[200px]">
                                <label for="category" class="block text-sm font-medium text-gray-700">Kategoriya</label>
                                <select name="category" id="category"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Barchasi</option>
                                    @foreach ($categories as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ request('category') === $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex-1 min-w-[200px]">
                                <label for="date_range" class="block text-sm font-medium text-gray-700">Sana
                                    oralig'i</label>
                                <input type="date" name="date_range" id="date_range"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    value="{{ request('date_range') }}">
                            </div>

                            <div class="flex items-end">
                                <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
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
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        O'qituvchi
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kategoriya
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Mezon
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Ball
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Yuborilgan sana
                                    </th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
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
                                            <div class="font-medium text-gray-900">{{ $submission->criteria->name }}
                                            </div>
                                            <div class="text-sm text-gray-500">{{ $submission->description }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $submission->status === 'pending'
                                                    ? 'bg-yellow-100 text-yellow-800'
                                                    : ($submission->status === 'approved'
                                                        ? 'bg-green-100 text-green-800'
                                                        : 'bg-red-100 text-red-800') }}">
                                                {{ $submission->status === 'pending'
                                                    ? 'Kutilmoqda'
                                                    : ($submission->status === 'approved'
                                                        ? 'Tasdiqlangan'
                                                        : 'Rad etilgan') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $submission->points ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $submission->created_at->format('d.m.Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            @if ($submission->status === 'pending')
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
