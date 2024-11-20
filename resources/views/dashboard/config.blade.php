<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("Ma'lumotlarni export qilib olish") }}
        </h2>

    </x-slot>

    <!-- Info Alert -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <div class="bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-500 rounded-lg shadow-sm p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-green-800">Ma'lumot</h3>
                    <p class="mt-2 text-green-700">
                        Xali ishlab chiqilayotgan fuksiyalar mavjud ular qachon ishlatishga tayyor bo'lgach bu haqda
                        xabar beriladi!
                    </p>
                </div>
            </div>
        </div>
    </div>


    <!-- Action Cards Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            <!-- Card for Student Counts Update -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300">
                <div class="p-6">
                    <div class="flex flex-col items-center">
                        <!-- Icon and Title -->
                        <div class="p-3 bg-blue-100 rounded-full">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="mt-4 text-xl font-bold text-gray-900">Talabalar sonini yangilash</h3>
                        <p class="mt-2 text-sm text-gray-600 text-center">
                            Kafedralar bo'yicha talabalar sonini Excel orqali yangilash
                        </p>

                        <!-- Action Buttons -->
                        <div class="flex gap-4 mt-6">
                            <button onclick="window.location.href='{{ route('student-counts.export') }}'"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-200 transition-all duration-300 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                Export
                            </button>
                            <button onclick="showImportModal()"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-all duration-300 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                Import
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Excel Export Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex flex-col items-center">
                        <div class="p-3 bg-blue-100 rounded-full">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                        </div>
                        <h3 class="mt-4 text-xl font-bold text-gray-900">Excel Faylni Yuklash</h3>
                        <p class="mt-2 text-sm text-gray-600 text-center">
                            Qabul qilingan ma'lumotlarni Excelga yuklab olish.
                        </p>
                        <p class="mt-2 text-sm font-semibold text-green-600">Ishlamoqda!</p>
                        <button onclick="startDownload()"
                            class="mt-4 w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-all duration-300">
                            Yuklab olish
                        </button>
                        <div id="progressContainer" class="hidden w-full mt-4">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div id="progressBar" class="bg-blue-600 h-2 rounded-full transition-all duration-300">

                                </div>
                            </div>
                            <div id="statusMessage" class="mt-2 text-sm text-gray-600 text-center"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Departments Update Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex flex-col items-center">
                        <div class="p-3 bg-indigo-100 rounded-full">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h3 class="mt-4 text-xl font-bold text-gray-900">Fakultet va Kafedralar</h3>
                        <p class="mt-2 text-sm text-gray-600 text-center">
                            Fakultet va kafedralar bo'yicha yangilanishlar.
                        </p>
                        <p class="mt-2 text-sm font-semibold text-green-600">Ishlamoqda!</p>
                        <button onclick="window.toggleDepartmentsUpdate()" id="departmentUpdateButton"
                            class="mt-4 w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-200 transition-all duration-300">
                            Yangilash
                        </button>
                        <div id="departmentUpdateProgressContainer" class="hidden w-full mt-4">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div id="departmentUpdateProgressBar"
                                    class="bg-indigo-600 h-2 rounded-full transition-all duration-300"></div>
                            </div>
                            <div id="departmentUpdateCurrentStatus" class="mt-2 text-sm text-gray-600 text-center">
                            </div>
                        </div>
                        <div id="departmentChangesContainer" class="mt-4 w-full text-sm"></div>
                    </div>
                </div>
            </div>

            <!-- Teachers Registration Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex flex-col items-center">
                        <div class="p-3 bg-purple-100 rounded-full">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <h3 class="mt-4 text-xl font-bold text-gray-900">O'qituvchilarni ro'yxatdan o'tkazish</h3>
                        <p class="mt-2 text-sm text-gray-600 text-center">
                            HEMISdagi barcha o'qituvchilarni avtomatik ravishda tizimga ro'yxatdan o'tkazadi
                        </p>
                        <p class="mt-2 text-sm font-semibold text-green-600">Ishlamoqda!</p>

                        <div class="flex gap-2 mt-4">
                            <button onclick="toggleTeachersRegistration()" id="registerTeachersButton"
                                class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 focus:ring-4 focus:ring-purple-200 transition-all duration-300">
                                Ro'yxatdan o'tkazish
                            </button>

                            <button onclick="stopTeachersRegistration()" id="stopRegistrationButton"
                                class="hidden bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-200 transition-all duration-300">
                                To'xtatish
                            </button>
                        </div>

                        <div id="registrationProgressContainer" class="hidden w-full mt-4">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div id="registrationProgressBar"
                                    class="bg-purple-600 h-2 rounded-full transition-all duration-300"></div>
                            </div>
                            <div id="registrationCurrentStatus" class="mt-2 text-sm text-gray-600 text-center"></div>
                        </div>

                        <div id="registrationResults" class="mt-4 w-full text-sm max-h-40 overflow-y-auto"></div>
                    </div>
                </div>
            </div>

            <!-- Teacher Position Update Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex flex-col items-center">
                        <div class="p-3 bg-green-100 rounded-full">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="mt-4 text-xl font-bold text-gray-900">O'qituvchilar lavozimini yangilash</h3>
                        <p class="mt-2 text-sm text-gray-600 text-center">
                            Kafedradagi o'qituvchilarning hozirgi lavozimini o'zgartirish
                        </p>
                        <p class="mt-2 text-sm font-semibold text-green-600">Ishlamoqda!</p>

                        <button onclick="window.startTeacherDeptPositionUpdate()" id="teacherPositionUpdateBtn"
                            class="mt-4 w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-200 transition-all duration-300">
                            Yangilash
                        </button>

                        <div id="teacherPositionProgressWrapper" class="hidden w-full mt-4">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div id="teacherPositionProgressBar"
                                    class="bg-green-600 h-2 rounded-full transition-all duration-300"></div>
                            </div>
                            <div id="teacherPositionUpdateMsg" class="mt-2 text-sm text-gray-600 text-center"></div>
                        </div>

                        <div id="teacherPositionStatusMsg" class="mt-4 text-sm text-gray-600"></div>
                    </div>
                </div>
            </div>



            <!-- Excel Export Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex flex-col items-center">
                        <div class="p-3 bg-green-100 rounded-full">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="mt-4 text-xl font-bold text-gray-900">Excel export</h3>
                        <p class="mt-2 text-sm text-gray-600 text-center">
                            Barcha kafedra ma'lumotlarini Excel formatida yuklash
                        </p>
                        <p class="mt-2 text-sm font-semibold text-yellow-600">Ishlab chiqilmoqda!</p>

                        <button onclick="generateExcel()" id="excelGenerateButton"
                            class="mt-4 w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-200 transition-all duration-300">
                            Yuklash
                        </button>
                    </div>
                </div>
            </div>

            <!-- Excel Progress Modal -->
            <div id="excelProgressModal"
                class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" aria-modal="true"
                role="dialog">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3 text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                            <svg class="h-6 w-6 text-green-600 animate-spin" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Excel fayl tayyorlanmoqda</h3>

                        <div class="mt-4">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div id="excelProgressBar"
                                    class="bg-green-600 h-2 rounded-full transition-all duration-300"
                                    style="width: 0%">
                                </div>
                            </div>
                            <div id="excelProgressText" class="mt-2 text-sm text-gray-600">0%</div>
                        </div>

                        <div class="mt-4" id="excelStatusMessage"></div>
                    </div>
                </div>
            </div>

            @push('scripts')
                <script>
                function generateExcel() {
    const button = document.getElementById('excelGenerateButton');
    const progressModal = document.getElementById('excelProgressModal');
    const progressBar = document.getElementById('excelProgressBar');
    const progressText = document.getElementById('excelProgressText');
    const statusMessage = document.getElementById('excelStatusMessage');

    // UI ni tayyorlash
    button.disabled = true;
    button.classList.add('opacity-75', 'cursor-not-allowed');
    progressModal.classList.remove('hidden');
    progressBar.style.width = '0%';
    progressText.textContent = '0%';
    statusMessage.textContent = 'Boshlanmoqda...';

    // EventSource yaratish
    const eventSource = new EventSource('{{ route("excel.generate_two") }}');

    // SSE event handler
    eventSource.onmessage = function(event) {
        const data = JSON.parse(event.data);

        // Progress va xabarni yangilash
        progressBar.style.width = data.progress + '%';
        progressText.textContent = data.progress + '%';
        statusMessage.textContent = data.message;

        // Agar jarayon tugagan bo'lsa
        if (data.progress >= 100 && data.success) {
            eventSource.close();

            // Faylni yuklab olish
            setTimeout(() => {
                window.location.href = data.download_url;
            }, 1000);

            // Modal va buttonni qayta tiklash
            setTimeout(() => {
                progressModal.classList.add('hidden');
                button.disabled = false;
                button.classList.remove('opacity-75', 'cursor-not-allowed');
            }, 2000);
        }

        // Xatolik bo'lsa
        if (data.success === false) {
            eventSource.close();
            statusMessage.textContent = data.message;
            statusMessage.classList.add('text-red-600');

            setTimeout(() => {
                progressModal.classList.add('hidden');
                button.disabled = false;
                button.classList.remove('opacity-75', 'cursor-not-allowed');
            }, 3000);
        }
    };

    // SSE xatolik handler
    eventSource.onerror = function(error) {
        console.error('SSE Error:', error);
        eventSource.close();

        statusMessage.textContent = 'Serverda xatolik yuz berdi';
        statusMessage.classList.add('text-red-600');

        setTimeout(() => {
            progressModal.classList.add('hidden');
            button.disabled = false;
            button.classList.remove('opacity-75', 'cursor-not-allowed');
        }, 3000);
    };
}
                </script>
            @endpush

            <!-- Delete Rejected Data Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex flex-col items-center">
                        <div class="p-3 bg-red-100 rounded-full">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </div>
                        <h3 class="mt-4 text-xl font-bold text-gray-900">Rad etilganlarni o'chirish</h3>
                        <p class="mt-2 text-sm text-gray-600 text-center">
                            Barcha rad etilgan ma'lumotlarni o'chirish
                        </p>
                        <p class="mt-2 text-sm font-semibold text-green-600">Ishlamoqda!</p>

                        <button id="rejectedDeleteButton"
                            class="mt-4 w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-200 transition-all duration-300">
                            O'chirish
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal Dialog -->
            <div id="rejectedDeleteConfirmModal"
                class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
                <div class="bg-white rounded-lg p-6 max-w-sm mx-auto">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Ma'lumotlarni o'chirish</h3>
                    <p class="text-sm text-gray-600 mb-6">
                        Haqiqatan ham barcha rad etilgan ma'lumotlarni o'chirishni istaysizmi?
                    </p>

                    <!-- Progress Container -->
                    <div id="rejectedModalProgressContainer" class="hidden mb-4">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div id="rejectedModalProgressBar" class="bg-red-600 h-2 rounded-full"></div>
                        </div>
                        <div id="rejectedModalProgressText" class="mt-2 text-sm text-gray-600 text-center">0%</div>
                    </div>

                    <!-- Status Message -->
                    <div id="rejectedModalStatusMessage" class="mb-4 text-sm hidden"></div>

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-3">
                        <button id="rejectedCancelDelete"
                            class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg">
                            Bekor qilish
                        </button>
                        <button id="rejectedConfirmDelete"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg">
                            O'chirish
                        </button>
                    </div>
                </div>
            </div>


            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Modal oynani ochish
                    document.getElementById('rejectedDeleteButton').addEventListener('click', function(e) {
                        e.preventDefault();
                        showRejectedModal();
                    });

                    // Modal oynani yopish (Bekor qilish tugmasi)
                    document.getElementById('rejectedCancelDelete').addEventListener('click', function() {
                        hideRejectedModal();
                    });

                    // O'chirish tugmasi bosilganda
                    document.getElementById('rejectedConfirmDelete').addEventListener('click', function() {
                        handleRejectedDelete();
                    });
                });

                function showRejectedModal() {
                    const modal = document.getElementById('rejectedDeleteConfirmModal');
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                }

                function hideRejectedModal() {
                    const modal = document.getElementById('rejectedDeleteConfirmModal');
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }

                function updateRejectedModalProgress(progress) {
                    const progressBar = document.getElementById('rejectedModalProgressBar');
                    const progressText = document.getElementById('rejectedModalProgressText');
                    progressBar.style.width = `${progress}%`;
                    progressText.textContent = `${progress}%`;
                }

                function handleRejectedDelete() {
                    const confirmButton = document.getElementById('rejectedConfirmDelete');
                    const cancelButton = document.getElementById('rejectedCancelDelete');
                    const progressContainer = document.getElementById('rejectedModalProgressContainer');
                    const statusMessage = document.getElementById('rejectedModalStatusMessage');

                    // Tugmalarni o'chirish
                    confirmButton.disabled = true;
                    cancelButton.disabled = true;
                    confirmButton.classList.add('opacity-50');
                    cancelButton.classList.add('opacity-50');

                    // Progress bar ni ko'rsatish
                    progressContainer.classList.remove('hidden');

                    // CSRF token olish
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    // Ajax so'rov
                    fetch('/delete-rejected-data', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json'
                            },
                            credentials: 'same-origin'
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Server xatosi');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                // Progress bar 100% ko'rsatish
                                updateRejectedModalProgress(100);

                                // Muvaffaqiyatli xabar
                                statusMessage.textContent = data.message;
                                statusMessage.classList.remove('hidden', 'text-red-500');
                                statusMessage.classList.add('text-green-500', 'block');

                                // 2 sekunddan keyin sahifani yangilash
                                setTimeout(() => {
                                    window.location.reload();
                                }, 2000);
                            } else {
                                throw new Error(data.message || 'Kutilmagan xatolik');
                            }
                        })
                        .catch(error => {
                            // Xatolik xabari
                            statusMessage.textContent = error.message;
                            statusMessage.classList.remove('hidden', 'text-green-500');
                            statusMessage.classList.add('text-red-500', 'block');

                            // Progress bar ni yashirish
                            progressContainer.classList.add('hidden');

                            // Tugmalarni qayta yoqish
                            confirmButton.disabled = false;
                            cancelButton.disabled = false;
                            confirmButton.classList.remove('opacity-50');
                            cancelButton.classList.remove('opacity-50');
                        });
                }
            </script>



        </div>
        <div class="mt-5">
            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nomer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kafedra</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Talabalar soni</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Oxirgi yangilanish</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Holati</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            $i = 1;
                        @endphp
                        @foreach ($studentCounts as $count)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $i++ }}
                                </td>
                                <td class="px-1 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $count->department->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ number_format($count->number) }} ta</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">{{ $count->updated_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($count->status)
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Aktiv
                                        </span>
                                    @else
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Nofaol
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <style>
                /* Transition Effects */
                .transition-all {
                    transition: all 0.3s ease-in-out;
                }

                /* Hover Effects */
                .hover\:shadow-xl:hover {
                    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
                }

                /* Progress Bar Animation */
                .progress-bar-animate {
                    transition: width 0.3s ease-in-out;
                }

                /* Modal Backdrop Blur */
                .backdrop-blur-sm {
                    backdrop-filter: blur(8px);
                }

                /* Custom Scrollbar */
                #registrationResults {
                    scrollbar-width: thin;
                    scrollbar-color: #E5E7EB transparent;
                }

                #registrationResults::-webkit-scrollbar {
                    width: 6px;
                }

                #registrationResults::-webkit-scrollbar-track {
                    background: transparent;
                }

                #registrationResults::-webkit-scrollbar-thumb {
                    background-color: #E5E7EB;
                    border-radius: 3px;
                }
            </style>
        </div>

    </div>



    <!-- Import Modal -->
    <div id="importModal"
        class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl p-6 max-w-md w-full mx-4">
            <!-- Modal Header -->
            <div class="flex items-start justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">Talabalar sonini yangilash</h3>
                <button onclick="closeImportModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Upload Form -->
            <form id="importForm" action="{{ route('student-counts.import') }}" method="POST"
                enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Excel fayl yuklang</label>
                    <div class="flex items-center justify-center w-full">
                        <label
                            class="flex flex-col w-full h-32 border-2 border-dashed border-gray-300 rounded-lg
                                 cursor-pointer hover:bg-gray-50 transition-all duration-200">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-4 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <p class="mb-2 text-sm text-gray-500">
                                    <span class="font-semibold">Faylni tanlang</span> yoki bu yerga tashlang
                                </p>
                                <p class="text-xs text-gray-500">Excel (.xlsx)</p>
                            </div>
                            <input type="file" name="file" class="hidden" accept=".xlsx" required />
                        </label>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div id="uploadProgress" class="hidden">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                    <p class="text-sm text-gray-600 mt-2 text-center">Yuklanmoqda...</p>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-4">
                    <button type="button" onclick="closeImportModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200
                               focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors duration-200">
                        Bekor qilish
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700
                               focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200">
                        Yuklash
                    </button>
                </div>
            </form>

            <!-- Result Message -->
            <div id="importResult" class="hidden mt-4">
                <div class="p-4 rounded-lg"></div>
            </div>
        </div>
    </div>
    <style>
        /* Transition Effects */
        .transition-all {
            transition: all 0.3s ease-in-out;
        }

        /* Hover Effects */
        .hover\:shadow-xl:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* Progress Bar Animation */
        .progress-bar-animate {
            transition: width 0.3s ease-in-out;
        }

        /* Modal Backdrop Blur */
        .backdrop-blur-sm {
            backdrop-filter: blur(8px);
        }

        /* Custom Scrollbar */
        #registrationResults {
            scrollbar-width: thin;
            scrollbar-color: #E5E7EB transparent;
        }

        #registrationResults::-webkit-scrollbar {
            width: 6px;
        }

        #registrationResults::-webkit-scrollbar-track {
            background: transparent;
        }

        #registrationResults::-webkit-scrollbar-thumb {
            background-color: #E5E7EB;
            border-radius: 3px;
        }
    </style>
    <script>
        const progressContainer = document.getElementById('progressContainer');
        const progressBar = document.getElementById('progressBar');
        const statusMessage = document.getElementById('statusMessage');
        let eventSource;
        let lastUpdateTime;

        function startDownload() {
            console.log('Starting download...');
            progressContainer.classList.remove('hidden');
            connectEventSource();
        }

        function connectEventSource() {
            eventSource = new EventSource('/download');
            lastUpdateTime = Date.now();

            eventSource.onopen = function(event) {
                console.log('SSE connection opened', event);
                statusMessage.textContent = 'Ulanish o\'rnatildi...';
            };

            eventSource.onmessage = function(event) {
                console.log('Received message:', event.data);
                lastUpdateTime = Date.now();
                try {
                    const data = JSON.parse(event.data);

                    if (data.type === 'file') {
                        handleFileDownload(data);
                    } else {
                        updateProgress(data);
                    }
                } catch (error) {
                    console.error('Error parsing message:', error);
                    statusMessage.textContent = 'Xatolik: Ma\'lumotlarni qayta ishlashda muammo';
                }
            };

            eventSource.onerror = function(event) {
                console.error('SSE error:', event);
                if (Date.now() - lastUpdateTime > 60000) { // 1 daqiqa
                    console.error('No updates received for 1 minute. Reconnecting...');
                    restartEventSource();
                } else {
                    statusMessage.textContent = 'Ulanishda xatolik. Qayta ulanishga urinilmoqda...';
                }
            };

            // Har 30 soniyada serverdan javob kelishini tekshirish
            const intervalId = setInterval(() => {
                if (Date.now() - lastUpdateTime > 30000) { // 30 soniya
                    console.warn('No updates received for 30 seconds');
                    statusMessage.textContent = 'Ma\'lumot kelishini kutmoqda...';
                }
            }, 30000);

            eventSource.onclose = function() {
                clearInterval(intervalId);
            };
        }

        function handleFileDownload(data) {
            const blob = b64toBlob(data.content, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            const link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = data.filename;
            link.click();
            eventSource.close();
            progressBar.style.width = '100%';
            progressBar.textContent = '100%';
            statusMessage.textContent = 'Yuklash tugadi!';
        }

        function updateProgress(data) {
            const roundedProgress = Math.round(data.progress);
            progressBar.style.width = roundedProgress + '%';
            progressBar.textContent = roundedProgress + '%';
            statusMessage.textContent = data.message;
        }

        function restartEventSource() {
            if (eventSource) {
                eventSource.close();
            }
            setTimeout(connectEventSource, 6000); // 1 soniyadan so'ng qayta ulanish
        }

        // Base64 ni blob ga o'girish uchun funksiya
        function b64toBlob(b64Data, contentType = '', sliceSize = 512) {
            const byteCharacters = atob(b64Data);
            const byteArrays = [];

            for (let offset = 0; offset < byteCharacters.length; offset += sliceSize) {
                const slice = byteCharacters.slice(offset, offset + sliceSize);
                const byteNumbers = new Array(slice.length);
                for (let i = 0; i < slice.length; i++) {
                    byteNumbers[i] = slice.charCodeAt(i);
                }
                const byteArray = new Uint8Array(byteNumbers);
                byteArrays.push(byteArray);
            }

            const blob = new Blob(byteArrays, {
                type: contentType
            });
            return blob;
        }
    </script>




    {{--  Teacher Position Update in Departments Functionality --}}

    <script>
        window.startTeacherDeptPositionUpdate = function() {
            const button = document.getElementById('teacherPositionUpdateBtn');
            const progressWrapper = document.getElementById('teacherPositionProgressWrapper');
            const progressBar = document.getElementById('teacherPositionProgressBar');
            const updateMessage = document.getElementById('teacherPositionUpdateMsg');
            const statusDiv = document.getElementById('teacherPositionStatusMsg');
            let eventSource;

            // Disable button during process
            button.disabled = true;
            button.classList.add('opacity-50', 'cursor-not-allowed');
            button.textContent = 'Yangilanmoqda...';

            // Show progress container
            progressWrapper.classList.remove('hidden');

            // Create EventSource connection
            eventSource = new EventSource('/update-teacher-departments');

            // Handle incoming messages
            eventSource.onmessage = function(event) {
                try {
                    const data = JSON.parse(event.data);

                    // Update progress bar
                    progressBar.style.width = `${data.progress}%`;

                    // Update status message
                    updateMessage.innerHTML = `<p class="text-sm text-gray-600">${data.message}</p>`;

                    // If process is complete
                    if (data.progress >= 100) {
                        finishTeacherPositionUpdate();
                    }
                } catch (error) {
                    console.error('Error processing teacher position update:', error);
                    finishTeacherPositionUpdate('Xatolik yuz berdi');
                }
            };

            // Handle errors
            eventSource.onerror = function() {
                console.error('Teacher position update EventSource failed');
                finishTeacherPositionUpdate('Serverda xatolik yuz berdi');
            };

            function finishTeacherPositionUpdate(errorMessage = null) {
                // Close EventSource connection
                if (eventSource) {
                    eventSource.close();
                }

                // Reset button
                button.disabled = false;
                button.classList.remove('opacity-50', 'cursor-not-allowed');
                button.textContent = 'Yangilash';

                // Show completion or error message
                if (errorMessage) {
                    statusDiv.innerHTML = `<p class="text-sm text-red-500">${errorMessage}</p>`;
                } else {
                    statusDiv.innerHTML = '<p class="text-sm text-green-500">Muvaffaqiyatli yakunlandi!</p>';
                }

                // Hide progress after 3 seconds
                setTimeout(() => {
                    progressWrapper.classList.add('hidden');
                    statusDiv.innerHTML = '';
                }, 30000);
            }
        };
    </script>


    {{-- Hemisdan Fakultet va Departamentni yangilash --}}

    <script>
        let isUpdating = false;

        window.toggleDepartmentsUpdate = async function() {
            const button = document.getElementById('departmentUpdateButton');
            const progressContainer = document.getElementById('departmentUpdateProgressContainer');
            const progressBar = document.getElementById('departmentUpdateProgressBar');
            const currentStatus = document.getElementById('departmentUpdateCurrentStatus');
            const statusElement = document.getElementById('departmentUpdateStatus');
            const changesContainer = document.getElementById('departmentChangesContainer');

            if (isUpdating) {
                try {
                    const response = await fetch('/stop-departments-update', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    if (response.ok) {
                        button.textContent = 'Yangilash';
                        button.classList.remove('bg-red-500', 'hover:bg-red-600');
                        button.classList.add('bg-blue-500', 'hover:bg-blue-600');
                        isUpdating = false;
                    }
                } catch (error) {
                    console.error('Xatolik:', error);
                }
                return;
            }

            isUpdating = true;
            button.textContent = 'To\'xtatish';
            button.classList.remove('bg-blue-500', 'hover:bg-blue-600');
            button.classList.add('bg-red-500', 'hover:bg-red-600');
            progressContainer.classList.remove('hidden');
            changesContainer.innerHTML = ''; // O'zgarishlar konteynerini tozalash

            const eventSource = new EventSource('/update-departments');

            eventSource.onmessage = function(event) {
                try {
                    const data = JSON.parse(event.data);
                    progressBar.style.width = `${data.progress}%`;

                    // Jarayon davom etayotganda
                    if (data.progress < 100) {
                        currentStatus.textContent = data.message;
                    }
                    // Jarayon tugaganida
                    else {
                        eventSource.close();
                        button.textContent = 'Yangilash';
                        button.classList.remove('bg-red-500', 'hover:bg-red-600');
                        button.classList.add('bg-blue-500', 'hover:bg-blue-600');
                        isUpdating = false;

                        // O'zgarishlar hisobotini ko'rsatish
                        const changes = data.message.split("\n");
                        let htmlContent = '';
                        let currentSection = '';

                        changes.forEach((line) => {
                            if (line.trim()) {
                                if (line.includes(":")) { // Yangi section
                                    if (currentSection) {
                                        htmlContent += '</ul></div>';
                                    }
                                    currentSection = line;
                                    let colorClass = 'text-blue-600';
                                    if (line.includes("O'chirilgan")) colorClass = 'text-red-600';
                                    if (line.includes("Yangi")) colorClass = 'text-green-600';

                                    htmlContent += `
                            <div class="mb-4">
                                <h4 class="font-bold ${colorClass} mb-2">${line.trim()}</h4>
                                <ul class="list-disc list-inside space-y-1">
                        `;
                                } else if (line.startsWith("-")) { // Element
                                    htmlContent += `
                            <li class="text-gray-600 ml-4">${line.substring(1).trim()}</li>
                        `;
                                } else if (line.includes("Hech qanday o'zgarish topilmadi")) {
                                    htmlContent = `<p class="text-gray-600">${line}</p>`;
                                }
                            }
                        });

                        if (currentSection) {
                            htmlContent += '</ul></div>';
                        }

                        changesContainer.innerHTML = htmlContent;
                        currentStatus.textContent = 'Jarayon yakunlandi';
                    }
                } catch (error) {
                    console.error('Ma\'lumotlarni qayta ishlashda xatolik:', error);
                }
            };

            eventSource.onerror = function(error) {
                console.error('EventSource xatoligi:', error);
                eventSource.close();
                button.textContent = 'Yangilash';
                button.classList.remove('bg-red-500', 'hover:bg-red-600');
                button.classList.add('bg-blue-500', 'hover:bg-blue-600');
                isUpdating = false;
                currentStatus.textContent = 'Xatolik yuz berdi. Qaytadan urinib ko\'ring.';
            };
        }

        // O'zgarishlar bo'limini formatlash uchun yordamchi funksiya
        function formatSection(section, color) {
            const lines = section.split("\n");
            const title = lines[0];
            const items = lines.slice(1);

            let colorClass = 'text-blue-600';
            if (color === 'red') colorClass = 'text-red-600';
            if (color === 'green') colorClass = 'text-green-600';

            let html = `<div class="mb-4">
        <h4 class="font-bold ${colorClass} mb-2">${title}</h4>
        <ul class="list-disc list-inside space-y-1">`;

            items.forEach(item => {
                if (item.trim()) {
                    html += `<li class="text-gray-600 ml-4">${item.replace("-", "").trim()}</li>`;
                }
            });

            html += `</ul></div>`;
            return html;
        }
    </script>

    {{-- Bu funksiya barcha o'qituvchilarni HEMISdan oladi va ro'yxatdan o'tkazadi: --}}
    <script>
        let registrationEventSource = null;

        function toggleTeachersRegistration() {
            const button = document.getElementById('registerTeachersButton');
            const stopButton = document.getElementById('stopRegistrationButton');
            const progressContainer = document.getElementById('registrationProgressContainer');
            const progressBar = document.getElementById('registrationProgressBar');
            const currentStatus = document.getElementById('registrationCurrentStatus');
            const resultsContainer = document.getElementById('registrationResults');

            if (registrationEventSource) {
                stopTeachersRegistration();
                return;
            }

            button.classList.add('hidden');
            stopButton.classList.remove('hidden');
            progressContainer.classList.remove('hidden');
            resultsContainer.innerHTML = '';

            registrationEventSource = new EventSource('/register-all-teachers');

            registrationEventSource.onmessage = function(event) {
                const data = JSON.parse(event.data);
                progressBar.style.width = `${data.progress}%`;

                if (data.progress >= 100) {
                    registrationComplete();
                }

                // Format results
                if (data.message.includes("YAKUNIY NATIJA")) {
                    const results = data.message.split("\n");
                    let html = '<div class="mt-4">';

                    results.forEach(line => {
                        if (line.trim()) {
                            if (line.includes("YAKUNIY NATIJA")) {
                                html += `<h4 class="font-bold text-lg mb-2">${line}</h4>`;
                            } else if (line.startsWith("Royxatdan")) {
                                html += `<p class="text-green-600">${line}</p>`;
                            } else if (line.includes("Xatoliklar:")) {
                                html += `<h4 class="font-bold text-red-600 mt-4 mb-2">${line}</h4>`;
                            } else {
                                html += `<p class="text-gray-600">${line}</p>`;
                            }
                        }
                    });

                    html += '</div>';
                    resultsContainer.innerHTML = html;
                } else {
                    currentStatus.textContent = data.message;
                }
            };

            registrationEventSource.onerror = function(error) {
                console.error('EventSource xatoligi:', error);
                registrationComplete('Xatolik yuz berdi. Qaytadan urinib ko\'ring.');
            };
        }

        function stopTeachersRegistration() {
            if (registrationEventSource) {
                fetch('/stop-teachers-registration', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    }).then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            registrationComplete('Jarayon to\'xtatildi');
                        }
                    })
                    .catch(error => {
                        console.error('To\'xtatishda xatolik:', error);
                        registrationComplete('To\'xtatishda xatolik yuz berdi');
                    });

                registrationEventSource.close();
                registrationEventSource = null;
            }
        }

        function registrationComplete(message = null) {
            const button = document.getElementById('registerTeachersButton');
            const stopButton = document.getElementById('stopRegistrationButton');
            const currentStatus = document.getElementById('registrationCurrentStatus');

            if (registrationEventSource) {
                registrationEventSource.close();
                registrationEventSource = null;
            }

            button.classList.remove('hidden');
            stopButton.classList.add('hidden');

            if (message) {
                currentStatus.textContent = message;
            }
        }
    </script>

    {{-- Talabalar sonini yangilash --}}

    <script>
        function showImportModal() {
            document.getElementById('importModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeImportModal() {
            document.getElementById('importModal').classList.add('hidden');
            document.getElementById('importForm').reset();
            document.getElementById('uploadProgress').classList.add('hidden');
            document.getElementById('importResult').classList.add('hidden');
            document.body.style.overflow = '';
        }

        document.getElementById('importForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const progressBar = document.querySelector('#uploadProgress div');
            const resultDiv = document.getElementById('importResult');

            document.getElementById('uploadProgress').classList.remove('hidden');

            fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    resultDiv.querySelector('div').className =
                        `p-4 rounded-lg ${data.success ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`;
                    resultDiv.querySelector('div').textContent = data.message;
                    resultDiv.classList.remove('hidden');

                    if (data.success) {
                        setTimeout(() => {
                            closeImportModal();
                            window.location.reload();
                        }, 2000);
                    }
                })
                .catch(error => {
                    resultDiv.querySelector('div').className = 'p-4 rounded-lg bg-red-100 text-red-800';
                    resultDiv.querySelector('div').textContent = 'Xatolik yuz berdi';
                    resultDiv.classList.remove('hidden');
                })
                .finally(() => {
                    document.getElementById('uploadProgress').classList.add('hidden');
                });
        });

        // Drag and Drop
        const dropZone = document.querySelector('label.flex.flex-col');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            dropZone.classList.add('border-blue-500', 'bg-blue-50');
        }

        function unhighlight(e) {
            dropZone.classList.remove('border-blue-500', 'bg-blue-50');
        }

        dropZone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            const fileInput = dropZone.querySelector('input[type="file"]');
            fileInput.files = files;
        }
    </script>

</x-app-layout>
