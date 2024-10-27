<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("Ma'lumotlarni export qilib olish") }}
        </h2>

    </x-slot>

    <div class="py-1 mt-6 mb-4">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">

        </div>

        <style>
            <style>#downloadProgress {
                width: 100%;
                margin-top: 20px;
            }

            #statusMessage {
                margin-top: 10px;
                font-weight: bold;
            }
        </style>
        </style>



        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex justify-center items-center p-8">
            <div class="flex items-center justify-center w-full">

                <div class="p-6 text-gray-900 mb-8 w-full max-w-6xl">
                    <div id="alert-additional-content-1"
                        class="p-4 mb-4 text-green-800 border border-green-300 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-800"
                        role="alert">
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 w-4 h-4 me-2" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                            </svg>
                            <span class="sr-only">Info</span>
                            <h3 class="text-lg font-medium">Xali ishlab chiqilayotgan fuksiyalar mavjud ular qachon
                                ishlatishga tayyor bo'lgach bu haqda xabar beriladi!</h3>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-4 md:p-6 bg-white rounded-lg shadow-md flex flex-col items-center justify-center text-center">

                <h3 class="text-lg md:text-xl font-bold mb-2">Excel Faylni Yuklash</h3>
                <p class="text-gray-600 mb-4 text-sm md:text-base">Qabul qilingan ma'lumotlarni Excelga yuklab olish.
                </p>
                <p class="text-green-500 mb-4 text-xs md:text-sm">Ishlamoqda!</p>
                <button onclick="startDownload()"
                    class="w-full md:w-auto bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition duration-300">
                    Yuklab olish
                </button>
                <div id="progressContainer" class="hidden mt-4">
                    <div class="w-full h-4 bg-gray-200 rounded-full dark:bg-gray-700 mb-4">
                        <div id="progressBar"
                            class="h-4 bg-blue-600 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full"
                            style="width: 0%">0%</div>
                    </div>
                    <div id="statusMessage" class="text-sm font-medium text-gray-700 mt-4"></div>
                </div>
            </div>
            <div class="p-4 md:p-6 bg-white rounded-lg shadow-md flex flex-col items-center justify-center text-center">
                <h3 class="text-lg md:text-xl font-bold mb-2">Faklultetlarni yangilash</h3>
                <p class="text-gray-600 mb-4 text-sm md:text-base">Eng so'ngi fakultet yangilanishlarni amalga oshiradi!
                </p>
                <p class="text-red-500 mb-4 text-xs md:text-sm">Ishlab chiqilmoqda!</p>
                <button
                    class="w-full md:w-auto bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition duration-300">
                    Yangilash
                </button>
            </div>

            <div class="p-4 md:p-6 bg-white rounded-lg shadow-md flex flex-col items-center justify-center text-center">
                <h3 class="text-lg md:text-xl font-bold mb-2">Kafedralarni yangilash</h3>
                <p class="text-gray-600 mb-4 text-sm md:text-base">Eng so'ngi fakultet yangilanishlarni amalga oshiradi!
                    Buni fakultetlarni yangilagandan so'ng amalga oshirish mumkin!</p>
                <p class="text-red-500 mb-4 text-xs md:text-sm">Ishlab chiqilmoqda!</p>
                <button
                    class="w-full md:w-auto bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition duration-300">
                    Yangilash
                </button>
            </div>

            <div class="p-4 md:p-6 bg-white rounded-lg shadow-md flex flex-col items-center justify-center text-center">
                <h3 class="text-lg md:text-xl font-bold mb-2">O'qituvchilar sonini yangilash</h3>
                <p class="text-gray-600 mb-4 text-sm md:text-base">Kafedradagi o'qituvchilarning sonini yangilanadi va
                    hisoblash aynan shu sondan olinadi bundan tashqari o'qituvchilarning joriy kafedra id lari
                    o'zgartirilishi mumkin!</p>
                <p class="text-red-500 mb-4 text-xs md:text-sm">Ishlab chiqilmoqda!</p>
                <button
                    class="w-full md:w-auto bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition duration-300">
                    Yangilash
                </button>
            </div>

            <div class="p-4 md:p-6 bg-white rounded-lg shadow-md flex flex-col items-center justify-center text-center">
                <h3 class="text-lg md:text-xl font-bold mb-2">Rad etilganlarni o'chirish</h3>
                <p class="text-gray-600 mb-4 text-sm md:text-base">Barcha rad etilgan ma'lumotlarni o'chirish.</p>
                <p class="text-green-500 mb-4 text-xs md:text-sm">Ishlamoqda!</p>
                <button onclick="deleteRejectedData()" id="rejectedDeleteButton"
                    class="w-full md:w-auto bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-lg transition duration-300">
                    O'chirish
                </button>

                <!-- Progress Bar -->
                <div id="rejectedProgressContainer" class="w-full hidden mt-4">
                    <div class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
                        <div id="rejectedProgressBar" class="bg-blue-600 h-2.5 rounded-full" style="width: 0%"></div>
                    </div>
                    <p id="rejectedProgressText" class="text-sm text-gray-600">0%</p>
                </div>

                <!-- Status Message -->
                <div id="rejectedStatusMessage" class="hidden mb-4">
                    <p class="text-sm"></p>
                </div>
            </div>
        </div>
    </div>

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
            setTimeout(connectEventSource, 1000); // 1 soniyadan so'ng qayta ulanish
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


    <!-- JavaScript Delete all infos -->

<script>
    function deleteRejectedData() {
        const button = document.getElementById('rejectedDeleteButton');
        const progressContainer = document.getElementById('rejectedProgressContainer');
        const progressBar = document.getElementById('rejectedProgressBar');
        const progressText = document.getElementById('rejectedProgressText');
        const statusMessage = document.getElementById('rejectedStatusMessage');

        // Button ni o'chirish
        button.disabled = true;
        button.classList.add('opacity-50');

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
            credentials: 'same-origin'  // CSRF token uchun
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Server xatosi');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Muvaffaqiyatli bajarildi
                statusMessage.querySelector('p').textContent = data.message;
                statusMessage.classList.remove('hidden', 'text-red-500');
                statusMessage.classList.add('text-green-500');

                // Progress bar 100% ko'rsatish
                progressBar.style.width = '100%';
                progressText.textContent = '100%';

                // 2 sekunddan keyin sahifani yangilash
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                throw new Error(data.message || 'Kutilmagan xatolik');
            }
        })
        .catch(error => {
            // Xatolik yuz berdi
            statusMessage.querySelector('p').textContent = error.message;
            statusMessage.classList.remove('hidden', 'text-green-500');
            statusMessage.classList.add('text-red-500');

            // Progress bar ni yashirish
            progressContainer.classList.add('hidden');
        })
        .finally(() => {
            // Button ni qayta yoqish
            button.disabled = false;
            button.classList.remove('opacity-50');
        });
    }
    </script>
</x-app-layout>
