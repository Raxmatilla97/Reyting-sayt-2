<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("Ma'lumotlarni export qilib olish") }}
        </h2>

    </x-slot>

    <div class="py-1 mt-6">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">

            <div id="alert-additional-content-1"
                class="p-4 mb-4 text-blue-800 border border-blue-300 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-800"
                role="alert">
                <div class="flex items-center">
                    <svg class="flex-shrink-0 w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                    </svg>
                    <span class="sr-only">Info</span>
                    <h3 class="text-lg font-medium">Ma'lumotlar ko'pligi uchun yuklash jarayoni biroz ko'proq davom etishi mumkin!</h3>
                </div>
            </div>


        </div>

        <style>
            <style>
        #downloadProgress {
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
                <div class="p-6 text-gray-900 mb-8 w-full max-w-2xl">
                    <div class="w-full p-8 bg-white rounded-lg shadow-lg">
                        <h1 class="text-3xl font-bold mb-6 text-center">Excel Faylni Yuklash</h1>
                        <button onclick="startDownload()" class="w-full lg:w-auto lg:mx-auto block px-8 py-4 bg-blue-600 text-white font-medium text-xl leading-tight rounded-lg shadow-md hover:bg-blue-700 focus:bg-blue-700 focus:outline-none focus:ring-0 active:bg-blue-800 transition duration-150 ease-in-out mb-6">
                            Yuklashni Boshlash
                        </button>
                        <div id="progressContainer" class="hidden">
                            <div class="w-full h-4 bg-gray-200 rounded-full dark:bg-gray-700 mb-4">
                                <div id="progressBar" class="h-4 bg-blue-600 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full" style="width: 0%">0%</div>
                            </div>
                            <div id="statusMessage" class="text-lg font-medium text-gray-700 mt-4"></div>
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

                            const blob = new Blob(byteArrays, { type: contentType });
                            return blob;
                        }
                        </script>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
