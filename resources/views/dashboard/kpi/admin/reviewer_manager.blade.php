<x-app-layout>
    <div class="py-12">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">KPI Tekshiruvchilarni Boshqarish</h2>

            <!-- Info Alert -->
            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-blue-800">
                    <strong>Eslatma:</strong> Tekshiruvchilar faqat biriktirilgan fakultetidan yuborilgan KPI
                    ma'lumotlarini belgilangan kategoriyalar bo'yicha tekshirish huquqiga ega.
                </p>
            </div>

            <!-- Main Content with Split Layout -->
            <div class="flex gap-6">
                <!-- Left Side - Faculties (65%) -->
                <div class="w-2/3">
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <!-- Stats Section -->
                        <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                            <div class="text-sm text-gray-600">
                                Jami tekshiruvchilar soni: <span class="font-semibold">{{ $reviewerCount }}</span>
                            </div>
                        </div>

                        <!-- Faculties Grid -->
                        <div class="grid grid-cols-2 gap-4">
                            @foreach ($faculties as $faculty)
                                <div class="faculty-drop-zone bg-white p-4 rounded-lg shadow-sm border-2 border-dashed border-gray-200 hover:border-blue-500 transition-colors"
                                    data-faculty-id="{{ $faculty->id }}">
                                    <h3 class="font-semibold text-lg mb-4">{{ $faculty->name }}</h3>

                                    <div class="faculty-users space-y-3 min-h-[150px]">
                                        @foreach ($users->filter(function ($user) use ($faculty) {
        return $user->department && $user->department->faculty_id == $faculty->id;
    }) as $facultyUser)
                                            <div class="user-card bg-gray-50 p-3 rounded-lg shadow-sm cursor-move"
                                                draggable="true" data-user-id="{{ $facultyUser->id }}">
                                                <div class="font-medium">{{ $facultyUser->FullName }}</div>
                                                <div class="text-sm text-gray-500">{{ $facultyUser->email }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Right Side - Search Panel (35%) -->
                <div class="w-1/3">
                    <div class="bg-white rounded-lg shadow-sm p-6 sticky top-6">
                        <h3 class="font-semibold text-lg mb-4">O'qituvchi qidirish</h3>

                        <div class="mb-4">
                            <input type="text" id="searchInput"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                placeholder="F.I.SH yoki email orqali qidirish">
                        </div>

                        <div id="searchResults" class="mt-4 space-y-2 max-h-[calc(100vh-300px)] overflow-y-auto">
                            <!-- Search results will appear here -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users Table Section -->
            <div class="mt-6 bg-white rounded-lg shadow-sm p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    O'qituvchi</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fakultet</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    KPI Kategoriyalar</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Amallar</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($users as $user)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-medium text-gray-900">{{ $user->FullName }}</div>
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $user->department->faculty->name ?? 'Mavjud emas' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <form id="categories-form-{{ $user->id }}" class="space-y-2">
                                            @csrf
                                            <div class="space-y-2">
                                                @foreach ($categories as $key => $value)
                                                    <label class="inline-flex items-center">
                                                        <input type="checkbox" name="categories[]"
                                                            value="{{ $key }}"
                                                            {{ in_array($key, $user->kpi_review_categories ?? []) ? 'checked' : '' }}
                                                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                        <span class="ml-2">{{ $value }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $user->is_kpi_reviewer ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $user->is_kpi_reviewer ? 'Tekshiruvchi' : 'Oddiy foydalanuvchi' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <button type="button"
                                            onclick="document.getElementById('categories-form-{{ $user->id }}').submit()"
                                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-xs">
                                            Saqlash
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        @if (request('search'))
                                            Qidiruv bo'yicha natija topilmadi
                                        @else
                                            Tekshiruvchilar ro'yxati bo'sh
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($users->hasPages())
                    <div class="mt-4">
                        {{ $users->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    @push('scripts')
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('searchInput');
                const searchResults = document.getElementById('searchResults');
                const facultyDropZones = document.querySelectorAll('.faculty-drop-zone');

                function createTableRow(user, categories) {
                    return `
            <tr data-user-id="${user.id}">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="font-medium text-gray-900">${user.name}</div>
                    <div class="text-sm text-gray-500">${user.email}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    ${user.department?.faculty?.name || 'Mavjud emas'}
                </td>
                <td class="px-6 py-4">
                    <form id="categories-form-${user.id}" class="space-y-2">
                        <div class="space-y-2">
                            ${Object.entries(categories).map(([key, value]) => `
                                        <label class="inline-flex items-center">
                                            <input type="checkbox"
                                                   name="categories[]"
                                                   value="${key}"
                                                   ${(user.kpi_review_categories || []).includes(key) ? 'checked' : ''}
                                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <span class="ml-2">${value}</span>
                                        </label>
                                    `).join('')}
                        </div>
                    </form>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                        user.is_kpi_reviewer ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'
                    }">
                        ${user.is_kpi_reviewer ? 'Tekshiruvchi' : 'Oddiy foydalanuvchi'}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <button onclick="document.getElementById('categories-form-${user.id}').submit()"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-xs">
                        Saqlash
                    </button>
                </td>
            </tr>
        `;
                }

                // Debounce function for search
                function debounce(func, wait) {
                    let timeout;
                    return function(...args) {
                        clearTimeout(timeout);
                        timeout = setTimeout(() => func.apply(this, args), wait);
                    };
                }

                // Search function
                const performSearch = debounce(async function(searchTerm) {
                    if (searchTerm.length < 2) {
                        searchResults.innerHTML =
                            '<p class="text-sm text-gray-500 p-2">Kamida 2 ta belgi kiriting</p>';
                        return;
                    }

                    try {
                        const response = await fetch(
                            `/admin/kpi-reviewers/search?search=${encodeURIComponent(searchTerm)}`, {
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .content,
                                    'Accept': 'application/json'
                                }
                            });

                        if (!response.ok) {
                            throw new Error('Server xatosi');
                        }

                        const data = await response.json();
                        console.log('Search results:', data);

                        if (data.length === 0) {
                            searchResults.innerHTML =
                                '<p class="text-sm text-gray-500 p-2">Natija topilmadi</p>';
                            return;
                        }

                        searchResults.innerHTML = data.map(user => `
                <div class="search-result-item bg-white p-3 rounded-lg shadow-sm mb-2 cursor-move border border-gray-200 hover:border-blue-500"
                     draggable="true"
                     data-user-id="${user.id}">
                    <div class="font-medium">${user.name}</div>
                    <div class="text-sm text-gray-500">${user.email}</div>
                    <div class="text-xs text-gray-400 mt-1">
                        ⬅️ Fakultetga tashlash uchun suring
                    </div>
                </div>
            `).join('');

                        initializeDragHandlers();

                    } catch (error) {
                        console.error('Search error:', error);
                        searchResults.innerHTML =
                            '<p class="text-sm text-red-500 p-2">Qidirishda xatolik yuz berdi</p>';
                    }
                }, 300);

                // Search input handler
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.trim();
                    performSearch(searchTerm);
                });

                // Initialize drag handlers
                function initializeDragHandlers() {
                    const draggableItems = document.querySelectorAll('[draggable="true"]');
                    draggableItems.forEach(item => {
                        item.addEventListener('dragstart', handleDragStart);
                        item.addEventListener('dragend', handleDragEnd);
                    });
                }

                // Drag handlers
                function handleDragStart(e) {
                    e.dataTransfer.setData('text/plain', this.dataset.userId);
                    e.dataTransfer.setData('application/json', JSON.stringify({
                        name: this.querySelector('.font-medium').textContent,
                        email: this.querySelector('.text-sm').textContent
                    }));
                    this.classList.add('opacity-50');
                }

                function handleDragEnd(e) {
                    this.classList.remove('opacity-50');
                }

                function initializeFormHandlers() {
                    document.querySelectorAll('button[onclick^="document.getElementById(\'categories-form-"]').forEach(
                        button => {
                            button.onclick = async function(e) {
                                e.preventDefault();

                                const userId = this.getAttribute('onclick').match(/categories-form-(\d+)/)[
                                    1];
                                const form = document.getElementById(`categories-form-${userId}`);

                                try {
                                    const selectedCategories = Array.from(form.querySelectorAll(
                                            'input[name="categories[]"]:checked'))
                                        .map(checkbox => checkbox.value);

                                    const response = await fetch(`/admin/kpi-reviewers/${userId}/update`, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector(
                                                'meta[name="csrf-token"]').content,
                                            'Accept': 'application/json'
                                        },
                                        body: JSON.stringify({
                                            categories: selectedCategories,
                                            _token: document.querySelector(
                                                'meta[name="csrf-token"]').content
                                        })
                                    });

                                    const result = await response.json();

                                    if (!response.ok) {
                                        throw new Error(result.message || 'Xatolik yuz berdi');
                                    }

                                    // Status yangilash
                                    const statusCell = form.closest('tr').querySelector(
                                        'td:nth-child(4) span');
                                    if (statusCell) {
                                        if (selectedCategories.length > 0) {
                                            statusCell.className =
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800';
                                            statusCell.textContent = 'Tekshiruvchi';
                                        } else {
                                            statusCell.className =
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800';
                                            statusCell.textContent = 'Oddiy foydalanuvchi';
                                        }
                                    }

                                    showNotification('Kategoriyalar muvaffaqiyatli saqlandi', 'success');

                                } catch (error) {
                                    console.error('Error saving categories:', error);
                                    showNotification(error.message || 'Saqlashda xatolik yuz berdi',
                                        'error');
                                }
                            };
                        });
                }

                // Faculty drop zones
                facultyDropZones.forEach(zone => {
                    zone.addEventListener('dragenter', function(e) {
                        e.preventDefault();
                        this.classList.add('border-blue-500', 'bg-blue-50');
                    });

                    zone.addEventListener('dragover', function(e) {
                        e.preventDefault();
                    });

                    zone.addEventListener('dragleave', function() {
                        this.classList.remove('border-blue-500', 'bg-blue-50');
                    });

                    zone.addEventListener('drop', async function(e) {
                        e.preventDefault();
                        this.classList.remove('border-blue-500', 'bg-blue-50');

                        const userId = e.dataTransfer.getData('text/plain');
                        const userData = JSON.parse(e.dataTransfer.getData('application/json'));
                        const facultyId = this.dataset.facultyId;
                        const targetFacultyUsers = this.querySelector('.faculty-users');

                        if (!userId || !facultyId) {
                            showNotification('Ma\'lumotlar to\'liq emas', 'error');
                            return;
                        }

                        try {
                            const response = await fetch(
                                `/admin/kpi-reviewers/${userId}/update-faculty`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').content,
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        faculty_id: facultyId
                                    })
                                });

                            const result = await response.json();

                            if (!response.ok) {
                                throw new Error(result.message || 'Xatolik yuz berdi');
                            }

                            // O'chirish va yangi joyga qo'shish
                            const draggedElement = document.querySelector(
                                `[data-user-id="${userId}"]`);
                            if (draggedElement) {
                                draggedElement.remove();
                            }

                            // Yangi user card yaratish
                            const userCard = `
                    <div class="user-card bg-gray-50 p-3 rounded-lg shadow-sm cursor-move"
                         draggable="true"
                         data-user-id="${userId}">
                        <div class="font-medium">${userData.name}</div>
                        <div class="text-sm text-gray-500">${userData.email}</div>
                    </div>
                `;

                            // Yangi fakultetga qo'shish
                            if (targetFacultyUsers) {
                                targetFacultyUsers.insertAdjacentHTML('beforeend', userCard);
                            }

                            // Get additional user data for table
                            const userDataResponse = await fetch(
                                `/admin/kpi-reviewers/user/${userId}`);
                            const userDetails = await userDataResponse.json();

                            // Add or update user in table
                            const tableBody = document.querySelector('table tbody');
                            const existingRow = tableBody.querySelector(
                                `tr[data-user-id="${userId}"]`);

                            if (existingRow) {
                                existingRow.outerHTML = createTableRow(userDetails.user, userDetails
                                    .categories);
                            } else {
                                tableBody.insertAdjacentHTML('afterbegin', createTableRow(
                                    userDetails.user, userDetails.categories));
                            }

                            // Reinitialize handlers
                            initializeDragHandlers();
                            initializeFormHandlers();

                            showNotification(result.message || 'Muvaffaqiyatli yangilandi',
                                'success');

                        } catch (error) {
                            console.error('Error during fetch:', error);
                            showNotification(error.message || 'Xatolik yuz berdi', 'error');
                        }
                    });
                });

                // Notification function
                function showNotification(message, type = 'success') {
                    const existingNotifications = document.querySelectorAll('.notification');
                    existingNotifications.forEach(notification => notification.remove());

                    const notification = document.createElement('div');
                    notification.className = `notification fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        } z-50 animate-fade-in`;

                    notification.textContent = message;
                    document.body.appendChild(notification);

                    setTimeout(() => {
                        notification.classList.add('opacity-0', 'translate-y-2');
                        setTimeout(() => notification.remove(), 300);
                    }, 2700);
                }

                // Add styles
                const style = document.createElement('style');
                style.textContent = `
        .notification {
            transition: all 0.3s ease;
        }
        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(1rem);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .search-result-item {
            transition: all 0.2s ease;
        }
        .search-result-item:hover {
            transform: translateY(-1px);
        }
        .faculty-drop-zone {
            transition: all 0.2s ease;
        }
        .faculty-drop-zone.border-blue-500 {
            border-width: 2px;
        }
        [draggable="true"] {
            cursor: move;
            user-select: none;
        }
    `;
                document.head.appendChild(style);

                // Initialize existing elements
                initializeDragHandlers();
                initializeFormHandlers();
            });
        </script>

        <style>
            .search-result-item {
                transition: all 0.2s ease;
            }

            .search-result-item:hover {
                transform: translateY(-1px);
            }

            .faculty-drop-zone {
                transition: all 0.2s ease;
            }

            .faculty-drop-zone.border-blue-500 {
                border-width: 2px;
            }

            [draggable="true"] {
                cursor: move;
                user-select: none;
            }
        </style>
    @endpush

</x-app-layout>
