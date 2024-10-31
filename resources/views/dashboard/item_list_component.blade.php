<div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
    @if (count($pointUserInformations) > 0)
        <!-- Header qismi -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6">
            <h2 class="text-xl font-bold text-white">Ma'lumotlar ro'yxati</h2>
            <p class="text-blue-100 mt-2">Jami: {{ $pointUserInformations->total() }} ta ma'lumot mavjud</p>
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
                                                $textToRemove = "Chirchiq davlat pedagogika universitetida";
                                                
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
    /* Modal animatsiyalari */
    .modal-backdrop {
        transition: all 0.3s ease-out;
        opacity: 0;
    }

    .modal-backdrop.show {
        opacity: 1;
    }

    .modal-content {
        transform: scale(0.95);
        opacity: 0;
        transition: all 0.3s ease-out;
    }

    .modal-content.show {
        transform: scale(1);
        opacity: 1;
    }

    /* Hover effekti uchun */
    .truncate-text {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>

<!-- Yangilangan Modal strukturasi -->
<div id="default-modal" tabindex="-1" aria-hidden="true"

    class="modal-backdrop hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="relative p-4 w-full max-w-4xl max-h-[90vh]">
        <div class="modal-content relative bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Modal header -->
            <div class="modal-header flex items-center justify-between p-6 border-b">
                <div class="flex items-center gap-3">
                    <h3 class="text-xl font-bold text-gray-900">
                        To'liq ma'lumot
                    </h3>
                    <div class="flex items-center gap-2">
                        @if (isset($item->status) && ($item->status === '1' || $item->status === 1))
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-md bg-green-100 text-green-700 text-xs font-medium border border-green-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                                Maqullangan
                            </span>
                        @elseif(isset($item->status) &&  ($item->status === '0' || $item->status === 0))
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-md bg-red-100 text-red-700 text-xs font-medium border border-red-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span>
                                Rad etilgan
                            </span>
                        @else
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-md bg-yellow-100 text-yellow-700 text-xs font-medium border border-yellow-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-700 mr-1.5"></span>
                                Tekshiruvda
                            </span>
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-md bg-blue-100 text-blue-700 text-xs font-medium border border-blue-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-1.5"></span>
                                Baholanmagan
                            </span>
                        @endif
                    </div>
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
            <!-- Modal body -->
            <div class="modal-body p-6 overflow-y-auto" style="max-height: calc(90vh - 200px);">
                <!-- AJAX content -->
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const viewButtons = document.querySelectorAll('.view-details-btn');
        const modal = document.querySelector('#default-modal');
        const modalContent = modal.querySelector('.modal-content');
        const modalBody = modal.querySelector('.modal-body');
        const closeButtons = document.querySelectorAll('[data-modal-hide="default-modal"]');

        function showModal() {
            modal.classList.remove('hidden');
            // Keyingi frame'da animatsiyani ishga tushirish
            requestAnimationFrame(() => {
                modal.classList.add('show');
                modalContent.classList.add('show');
            });
            document.body.style.overflow = 'hidden';
        }

        function hideModal() {
            modal.classList.remove('show');
            modalContent.classList.remove('show');
            // Animatsiya tugashini kutish
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }, 300);
        }

        viewButtons.forEach(button => {
            button.addEventListener('click', async function() {
                const id = this.getAttribute('data-id');
                try {
                    const response = await fetch(`/getItemDetails/${id}`);
                    const data = await response.json();
                    modalBody.innerHTML = data.html;
                    showModal();
                } catch (error) {
                    console.error('Error:', error);
                }
            });
        });

        closeButtons.forEach(button => {
            button.addEventListener('click', hideModal);
        });

        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                hideModal();
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                hideModal();
            }
        });
    });
</script>
