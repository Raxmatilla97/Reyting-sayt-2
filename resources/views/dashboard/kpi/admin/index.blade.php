<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6">KPI Tekshirish</h2>

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
                                        Hujjat
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Amallar
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($submissions as $submission)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $submission->user->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $submission->criteria->category }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="font-medium">{{ $submission->criteria->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $submission->description }}</p>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($submission->proof_file)
                                                <a href="{{ Storage::url($submission->proof_file) }}" target="_blank"
                                                   class="text-blue-600 hover:text-blue-900">
                                                    Hujjatni ko'rish
                                                </a>
                                            @else
                                                <span class="text-gray-500">Hujjat yo'q</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button type="button"
                                                    onclick="openReviewModal('{{ $submission->id }}')"
                                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                                Tekshirish
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                            Tekshirish uchun ma'lumotlar yo'q
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Review Modal -->
    <div id="reviewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <form id="reviewForm" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select id="status" name="status" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="approved">Tasdiqlash</option>
                        <option value="rejected">Rad etish</option>
                    </select>
                </div>

                <div>
                    <label for="points" class="block text-sm font-medium text-gray-700">Ball</label>
                    <input type="number" id="points" name="points" step="0.1" min="0"
                           class="mt-1 block w-full py-2 px-3   border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="admin_comment" class="block text-sm font-medium text-gray-700">Izoh</label>
                    <textarea id="admin_comment" name="admin_comment" rows="3"
                              class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
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
        function openReviewModal(submissionId) {
            const modal = document.getElementById('reviewModal');
            const form = document.getElementById('reviewForm');

            // Formni yangilash
            form.action = `/admin/kpi/${submissionId}/review`;

            // Modalni ko'rsatish
            modal.classList.remove('hidden');

            // Escape tugmasi bilan yopish
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeReviewModal();
                }
            });

            // Modal tashqarisini bosish bilan yopish
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeReviewModal();
                }
            });
        }

        function closeReviewModal() {
            const modal = document.getElementById('reviewModal');
            modal.classList.add('hidden');
        }

        // Status o'zgarganida ballni majburiy/ixtiyoriy qilish
        document.getElementById('status').addEventListener('change', function() {
            const pointsInput = document.getElementById('points');
            if (this.value === 'approved') {
                pointsInput.required = true;
            } else {
                pointsInput.required = false;
                pointsInput.value = '';
            }
        });
    </script>
    @endpush
</x-app-layout>
