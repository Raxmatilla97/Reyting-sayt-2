<div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
    @if (count($pointUserInformations) > 0)
        <!-- Header qismi -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6">
            <h2 class="text-xl font-bold text-white">Ma'lumotlar ro'yxati</h2>
            <div class="flex gap-4">
                <p class="text-blue-100 mt-2">Jami: {{ $pointUserInformations->total() }} ta ma'lumot mavjud</p>
                <p class="text-green-300 mt-2">Shundan qabul qilingan: {{ $totalInfos }} ta</p>
            </div>
        </div>

        <!-- Table qismi -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-sm font-bold text-gray-700">№</th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-700">F.I.SH va Boshqa ma'lumotlar</th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-700 min-w-[150px]">Ma'lumot holati</th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-700 min-w-[125px]">Berilgan ball</th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-700">Vaqti</th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-700">Bajarish</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pointUserInformations as $item)
                        <tr class="hover:bg-gray-50/50 transition-all duration-200">
                            <td class="px-6 py-4">
                                <span
                                    class="text-md font-bold text-gray-900 bg-gray-100 w-8 h-8 rounded-lg flex items-center justify-center">
                                    {{ ($pointUserInformations->currentPage() - 1) * $pointUserInformations->perPage() + $loop->iteration }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <img class="w-14 h-14 rounded-xl shadow-md object-cover border-2 border-white"
                                            src="{{ '/storage/users/image' }}/{{ $item->employee->image }}"
                                            alt="">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-lg font-semibold text-gray-900 truncate mb-1">
                                            {{ ucwords(strtolower($item->employee->FullName)) }}
                                        </p>
                                        <div class="flex items-center space-x-2">
                                            <span
                                                class="bg-blue-100/80 text-blue-700 text-sm font-medium px-3 py-1 rounded-lg border border-blue-200">
                                                {{ $item->murojaat_codi ?? 'Noma\'lum' }}
                                            </span>
                                            <span class="text-gray-600 text-sm"
                                                title="{{ $item->murojaat_nomi ?? 'Noma\'lum' }}">
                                                @php
                                                    $originalText = $item->murojaat_nomi ?? 'Noma\'lum';
                                                    $textToRemove = 'Chirchiq davlat pedagogika universitetida';

                                                    // Textni qayta ishlash
                                                    if (str_starts_with($originalText, $textToRemove)) {
                                                        // Kerakmas so'zni olib tashlash
    $trimmedText = substr($originalText, strlen($textToRemove));
    // Boshi va oxiridagi bo'sh joylarni olib tashlash
                                                        $trimmedText = trim($trimmedText);
                                                        // Birinchi harfni katta qilish
                                                        $formattedText = ucfirst($trimmedText);
                                                    } else {
                                                        $formattedText = $originalText;
                                                    }
                                                @endphp
                                                {{ \Str::limit($formattedText, 100, '...') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if ($item->status == 1)
                                    <div class="flex items-center">
                                        <div class="relative">
                                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                            <div
                                                class="absolute -inset-1 bg-green-500 rounded-full animate-ping opacity-20">
                                            </div>
                                        </div>
                                        <span class="ml-3 text-sm font-medium text-green-700">Maqullangan</span>
                                    </div>
                                @elseif($item->status == 0)
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                        <span class="ml-3 text-sm font-medium text-red-700">Rad etilgan</span>
                                    </div>
                                @else
                                    <div class="flex items-center">
                                        <div class="relative">
                                            <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                            <div
                                                class="absolute -inset-1 bg-yellow-500 rounded-full animate-ping opacity-20">
                                            </div>
                                        </div>
                                        <span class="ml-3 text-sm font-medium text-yellow-700">Tekshiruvda</span>
                                    </div>
                                @endif
                            </td>
                            @include('frogments.ball_korsatadigan')
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-sm text-gray-600">{{ $item->created_at->diffForHumans() }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <button data-modal-target="default-modal-{{ $item->id }}"
                                    data-modal-toggle="default-modal-{{ $item->id }}"
                                    class="view-details-btn group relative inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-all duration-200 hover:shadow-lg"
                                    type="button" data-id="{{ $item->id }}">
                                    <span class="relative">Ko'rish</span>
                                    <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-gray-100">
            {{ $pointUserInformations->links() }}
        </div>
    @else
        <div class="flex flex-col items-center justify-center py-16">
            <div class="bg-gray-100 rounded-full p-6 mb-4">
                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900">Ma'lumotlar mavjud emas</h3>
            <p class="mt-2 text-gray-500">Hozircha hech qanday ma'lumot kelib tushmagan</p>
        </div>
    @endif
</div>

<style>
    .hover\:shadow-custom:hover {
        box-shadow: 0 4px 20px -5px rgba(0, 0, 0, 0.1), 0 2px 10px -5px rgba(0, 0, 0, 0.06);
    }
</style>



<!-- Modal -->
<style>
    .modal-backdrop {
        opacity: 0;
        transition: opacity 0.3s ease-out;
    }

    .modal-backdrop:not(.hidden) {
        opacity: 1;
    }

    .modal-content {
        transform: scale(0.95);
        opacity: 0;
        transition: all 0.3s ease-out;
    }

    .modal-content:not(.hidden) {
        transform: scale(1);
        opacity: 1;
    }

    .modal-body {
        scrollbar-width: thin;
        scrollbar-color: #CBD5E0 #F7FAFC;
    }

    .modal-body::-webkit-scrollbar {
        width: 8px;
    }

    .modal-body::-webkit-scrollbar-track {
        background: #F7FAFC;
    }

    .modal-body::-webkit-scrollbar-thumb {
        background-color: #CBD5E0;
        border-radius: 4px;
    }
</style>

<div id="default-modal" tabindex="-1" aria-hidden="true"
    class="modal-backdrop hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="relative p-4 w-full max-w-4xl max-h-[90vh]">
        <div class="modal-content relative bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Modal header -->
            <div class="modal-header flex items-center justify-between p-6 border-b">
                <div class="flex items-center gap-3">
                    <h3 class="text-xl font-bold text-gray-900">
                        Ma'lumot ko'rish
                    </h3>
                    <div class="flex items-center gap-2">
                        <span id="modal-status-badge"></span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <div id="edit-button-container" style="display: none;">
                        <a href="#" id="edit-link"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Tahrirlash
                        </a>
                    </div>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-100 hover:text-gray-900 rounded-lg text-sm p-2 inline-flex items-center transition-colors duration-200"
                        data-modal-hide="default-modal">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <!-- Modal body -->
            <div id="modal-body" class="modal-body p-6 overflow-y-auto" style="max-height: calc(90vh - 200px);">
                <!-- AJAX content -->
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('default-modal');
        const modalBody = document.getElementById('modal-body');
        const editButton = document.getElementById('edit-button-container');
        const editLink = document.getElementById('edit-link');

        function closeModal() {
            modal.classList.add('hidden');
            modalBody.innerHTML = '';
            editButton.style.display = 'none';
        }

        // Ko'rish tugmalari
        document.querySelectorAll('.view-details-btn').forEach(button => {
            button.addEventListener('click', async function(e) {
                e.preventDefault();
                const itemId = this.getAttribute('data-id');

                try {
                    modalBody.innerHTML = '<div class="text-center">Loading...</div>';
                    modal.classList.remove('hidden');

                    const response = await fetch(`/getItemDetails/${itemId}`);
                    const data = await response.json();

                    if (data.error) {
                        modalBody.innerHTML =
                            '<div class="text-red-500">Xatolik yuz berdi</div>';
                        return;
                    }

                    modalBody.innerHTML = data.html;

                    if (data.status === 0 && data.creator_id === {{ Auth::id() }}) {
                        editButton.style.display = 'block';
                        const baseUrl = data.formType === 'employee' ?
                            '/show-employee-form/' : '/show-department-form/';
                        editLink.href = `${baseUrl}${data.tableName}?edit=${data.itemId}`;
                    } else {
                        editButton.style.display = 'none';
                    }

                } catch (error) {
                    console.error('Error:', error);
                    modalBody.innerHTML =
                        '<div class="text-red-500">Xatolik yuz berdi</div>';
                }
            });
        });

        // Modal yopish
        document.querySelectorAll('[data-modal-hide="default-modal"]').forEach(button => {
            button.addEventListener('click', closeModal);
        });

        // Modal tashqarisiga bosilganda
        modal.addEventListener('click', function(e) {
            if (!e.target.closest('.modal-content')) {
                closeModal();
            }
        });

        // Escape tugmasi
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });
    });
</script>
