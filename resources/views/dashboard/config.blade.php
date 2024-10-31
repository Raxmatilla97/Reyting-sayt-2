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
                <h3 class="text-lg md:text-xl font-bold mb-2">Faklultetlar va kafedralarni yangilash</h3>
                <p class="text-gray-600 mb-4 text-sm md:text-base">
                    Eng so'ngi fakultet va kafedralarni yangilanishlarni amalga oshiradi!
                </p>
                <p class="text-green-500 mb-4 text-xs md:text-sm">Ishlamoqda!</p>

                <button onclick="window.toggleDepartmentsUpdate()" id="departmentUpdateButton"
                    class="w-full md:w-auto bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition duration-300">
                    Yangilash
                </button>

                <!-- Progress Container -->
                <div id="departmentUpdateProgressContainer" class="w-full hidden mt-4">
                    <div class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
                        <div id="departmentUpdateProgressBar"
                            class="bg-blue-600 h-2.5 rounded-full transition-all duration-300" style="width: 0%">
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 mb-4">
                        <div id="departmentUpdateCurrentStatus" class="text-gray-600 text-sm"></div>
                    </div>
                </div>

                <!-- O'zgarishlar konteyneri -->
                <div id="departmentChangesContainer" class="w-full mt-4 text-left">
                </div>
            </div>

            <div class="p-4 md:p-6 bg-white rounded-lg shadow-md flex flex-col items-center justify-center text-center">
                <h3 class="text-lg md:text-xl font-bold mb-2">O'qituvchilarni ro'yxatdan o'tkazish</h3>
                <p class="text-gray-600 mb-4 text-sm md:text-base">
                    HEMISdagi barcha o'qituvchilarni avtomatik ravishda tizimga ro'yxatdan o'tkazadi
                </p>
                <p class="text-green-500 mb-4 text-xs md:text-sm">Ishlamoqda!</p>

                <div class="flex gap-2">
                    <button onclick="toggleTeachersRegistration()" id="registerTeachersButton"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition duration-300">
                        Ro'yxatdan o'tkazish
                    </button>

                    <button onclick="stopTeachersRegistration()" id="stopRegistrationButton"
                        class="hidden bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-lg transition duration-300">
                        To'xtatish
                    </button>
                </div>

                <!-- Progress Container -->
                <div id="registrationProgressContainer" class="w-full hidden mt-4">
                    <div class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
                        <div id="registrationProgressBar"
                            class="bg-blue-600 h-2.5 rounded-full transition-all duration-300" style="width: 0%">
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 mb-4">
                        <div id="registrationCurrentStatus" class="text-gray-600 text-sm"></div>
                    </div>
                </div>

                <!-- Results Container -->
                <div id="registrationResults" class="w-full mt-4 text-left"></div>

                <style>

                    #registrationResults {
                        max-height: 300px;
                        overflow-y: auto;
                    }
                </style>

            </div>


            <div class="p-4 md:p-6 bg-white rounded-lg shadow-md flex flex-col items-center justify-center text-center">
                <h3 class="text-lg md:text-xl font-bold mb-2">O'qituvchilarni kafedradagi o'rnini yangilash</h3>
                <p class="text-gray-600 mb-4 text-sm md:text-base">Kafedradagi o'qituvchilarning hozirgi o'rnini
                    o'zgartirish (11,15,12) ketmaketlikda.</p>
                <p class="text-green-500 mb-4 text-xs md:text-sm">Ishlamoqda!</p>

                <button onclick="window.startTeacherDeptPositionUpdate()" id="teacherPositionUpdateBtn"
                    class="w-full md:w-auto bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition duration-300">
                    Yangilash
                </button>

                <!-- Progress Container -->
                <div id="teacherPositionProgressWrapper" class="w-full hidden mt-4">
                    <div class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
                        <div id="teacherPositionProgressBar"
                            class="bg-blue-600 h-2.5 rounded-full transition-all duration-300" style="width: 0%">
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 mb-4">
                        <div id="teacherPositionUpdateMsg" class="text-left"></div>
                    </div>
                </div>

                <!-- Status Message -->
                <div id="teacherPositionStatusMsg" class="mb-4">
                    <p class="text-sm"></p>
                </div>
            </div>


            <div
                class="p-4 md:p-6 bg-white rounded-lg shadow-md flex flex-col items-center justify-center text-center">
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
                    credentials: 'same-origin' // CSRF token uchun
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

</x-app-layout>
