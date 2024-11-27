<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800">KPI Yuborilgan Ma'lumotlar</h2>
                        <a href="{{ route('kpi.create') }}"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Yangi Qo'shish
                        </a>
                    </div>

                    <div
                        class="flex items-center p-4 mb-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 rounded-lg shadow-sm">
                        <svg class="flex-shrink-0 w-5 h-5 text-blue-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                        </svg>
                        <div>
                            <span class="font-semibold text-blue-800">Etibor bering! </span>
                            <span class="text-blue-700">Bu bo'lim hozir test rejimida. Shu sababli, hozircha bu yerga
                                hech narsa yuklashingiz shart emas.</span>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
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
                                        Amallar
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($submissions as $submission)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $submission->criteria->name }}</div>
                                            <div class="text-xs text-gray-400 mt-1">
                                                Kategoriya:
                                                {{ \App\Models\KpiCriteria::categories()[$submission->criteria->category] ?? 'Noma\'lum kategoriya' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $submission->status === 'pending'
                                                    ? 'bg-yellow-100 text-yellow-800'
                                                    : ($submission->status === 'approved'
                                                        ? 'bg-green-100 text-green-800'
                                                        : ($submission->status === 'apilation'
                                                            ? 'bg-purple-100 text-purple-800'
                                                            : 'bg-red-100 text-red-800')) }}">
                                                {{ $submission->status === 'pending'
                                                    ? 'Kutilmoqda'
                                                    : ($submission->status === 'approved'
                                                        ? 'Tasdiqlandi'
                                                        : ($submission->status === 'apilation'
                                                            ? 'Apellyatsiyada'
                                                            : 'Rad etildi')) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $submission->points ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <button type="button"
                                                class="text-blue-600 hover:text-blue-900 view-details"
                                                data-submission-id="{{ $submission->id }}">
                                                Ko'rish
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                            Ma'lumotlar mavjud emas
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
    <!-- Enhanced Modal -->
    <div id="detailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-8 border max-w-2xl shadow-lg rounded-lg bg-white">
            <div class="flex justify-between items-start">
                <h3 class="text-2xl font-bold text-gray-900">Batafsil ma'lumot</h3>
                <div class="flex space-x-4">
                    <button id="apilationBtn"
                        class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition duration-200">
                        Apellyatsiyaga berish
                    </button>
                    <button class="close-modal text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="mt-6">
                <div id="modalContent" class="space-y-6">
                    <!-- Ajax content will be loaded here -->
                </div>
                <div class="mt-8 flex justify-end">
                    <button type="button"
                        class="close-modal px-6 py-2.5 bg-gray-100 text-gray-800 font-semibold rounded-lg hover:bg-gray-200 transition duration-200">
                        Yopish
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Apellyatsiya Modal -->
    <div id="apilationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-8 border max-w-xl shadow-lg rounded-lg bg-white">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-xl font-bold text-gray-900">Apellyatsiyaga berish</h3>
                <button class="close-apilation-modal text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="apilationForm" class="space-y-6">
                <input type="hidden" id="submissionId" name="submission_id">
                <div>
                    <label for="apilation_message" class="block text-sm font-medium text-gray-700 mb-2">
                        Apellyatsiya sababi
                    </label>
                    <textarea id="apilation_message" name="apilation_message" rows="4"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Apellyatsiya sababini yozing..." required></textarea>
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button"
                        class="close-apilation-modal px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Bekor qilish
                    </button>
                    <button type="submit"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                        Yuborish
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modal = document.getElementById('detailsModal');
                const apilationModal = document.getElementById('apilationModal');
                const modalContent = document.getElementById('modalContent');
                const closeButtons = document.querySelectorAll('.close-modal');
                const closeApilationButtons = document.querySelectorAll('.close-apilation-modal');
                const apilationBtn = document.getElementById('apilationBtn');
                const apilationForm = document.getElementById('apilationForm');
                let currentSubmissionId = null;

                // Get category name from code
                function getCategoryName(category) {
                    const categories = {
                        'teaching': "O'quv va o'quv-uslubiy ishlar",
                        'research': "Ilmiy va innovatsiyalarga oid ishlar",
                        'international': "Xalqaro hamkorlikka oid ishlar",
                        'spiritual': "Ma'naviy-ma'rifiy ishlar"
                    };
                    return categories[category] || 'Noma\'lum kategoriya';
                }

                // Status badges styling function
                function getStatusBadge(status) {
                    const statusClasses = {
                        'pending': 'bg-yellow-100 text-yellow-800',
                        'approved': 'bg-green-100 text-green-800',
                        'rejected': 'bg-red-100 text-red-800',
                        'apilation': 'bg-purple-100 text-purple-800'
                    };

                    const statusNames = {
                        'pending': 'Kutilmoqda',
                        'approved': 'Tasdiqlandi',
                        'rejected': 'Rad etildi',
                        'apilation': 'Apellyatsiyada'
                    };

                    return `<span class="px-3 py-1 text-sm font-semibold rounded-full ${statusClasses[status] || 'bg-gray-100 text-gray-800'}">
                ${statusNames[status] || status}
            </span>`;
                }

                // Ko'rish tugmasini bosilganda
                document.querySelectorAll('.view-details').forEach(button => {
                    button.addEventListener('click', function() {
                        const submissionId = this.getAttribute('data-submission-id');
                        currentSubmissionId = submissionId;
                        document.getElementById('submissionId').value = submissionId;

                        // AJAX request
                        fetch(`/kpi-submissions/${submissionId}/details`)
                            .then(response => response.json())
                            .then(data => {
                                modalContent.innerHTML = `
                            <div class="grid gap-6">
                                   <div class="flex items-center space-x-2">
                                    <span class="text-lg font-semibold text-gray-700">Status:</span>
                                    ${getStatusBadge(data.status)}
                                </div>

                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h4 class="text-lg font-semibold text-gray-700 mb-2">KPI Mezoni va yo'nalishi </h4>
                                    <p class="text-gray-700">${data.criteria_name || 'Mezon nomi ko\'rsatilmagan'}</p>
                                      <p class="text-sm text-gray-600 mt-2">Yo'nalish: ${getCategoryName(data.category)}</p>
                                </div>


                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h4 class="text-lg font-semibold text-gray-700 mb-2">Tavsif</h4>
                                    <p class="text-gray-700">${data.description || 'Tavsif yozilmagan'}</p>
                                </div>

                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h4 class="text-lg font-semibold text-gray-700 mb-2">Ball</h4>
                                    <p class="text-gray-700 text-xl">${data.points ? data.points + ' ball' : 'Ball belgilanmagan'}</p>
                                </div>

                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h4 class="text-lg font-semibold text-gray-700 mb-2">Tekshiruvchi izohi
                                        <div class="relative inline-block group">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5 text-gray-400 hover:text-gray-600 cursor-help"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 w-64 bg-gray-900 text-white text-sm rounded-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                                Tekshiruvchi tomonidan berilgan izoh. Bu yerda ball qo'yish sabablari va boshqa muhim ma'lumotlar ko'rsatiladi.

                                                <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-full">
                                                    <div class="border-8 border-transparent border-t-gray-900"></div>
                                                </div>
                                            </div>
                                        </div>
                                        </h4>
                                    <p class="text-gray-700">${data.admin_comment || 'Izoh yozilmagan'}</p>
                                </div>

                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h4 class="text-lg font-semibold text-gray-700 mb-2">Yuklangan fayl</h4>
                                    ${data.proof_file
                                        ? `<a href="/storage/${data.proof_file}"
                                                                             class="inline-flex items-center space-x-2 text-blue-600 hover:text-blue-800"
                                                                             target="_blank">
                                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                      d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                                                            </svg>
                                                                            <span>Faylni yuklab olish</span>
                                                                           </a>`
                                        : '<p class="text-gray-500">Fayl yuklanmagan</p>'}
                                </div>

                            </div>
                        `;
                                modal.classList.remove('hidden');

                                // Apellyatsiya tugmasini yashirish/ko'rsatish
                                if (data.status === 'rejected' || data.status === 'approved' ) {
                                    apilationBtn.classList.remove('hidden');
                                } else {
                                    apilationBtn.classList.add('hidden');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                modalContent.innerHTML = `
                            <div class="bg-red-50 p-4 rounded-lg">
                                <p class="text-red-600 text-lg">Ma'lumotlarni yuklashda xatolik yuz berdi</p>
                            </div>
                        `;
                            });
                    });
                });

                // Apellyatsiya tugmasi bosilganda
                apilationBtn.addEventListener('click', () => {
                    modal.classList.add('hidden');
                    apilationModal.classList.remove('hidden');
                });

                // Apellyatsiya formasi yuborilganda
                apilationForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);

                    fetch(`/kpi-submissions/${currentSubmissionId}/apilation`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                apilation_message: formData.get('apilation_message')
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                apilationModal.classList.add('hidden');
                                location.reload(); // Sahifani yangilash
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });

                // Modal oynalarni yopish
                closeButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        modal.classList.add('hidden');
                    });
                });

                closeApilationButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        apilationModal.classList.add('hidden');
                    });
                });

                // Modal tashqarisini bosganda yopish
                window.addEventListener('click', function(event) {
                    if (event.target === modal) {
                        modal.classList.add('hidden');
                    }
                    if (event.target === apilationModal) {
                        apilationModal.classList.add('hidden');
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
