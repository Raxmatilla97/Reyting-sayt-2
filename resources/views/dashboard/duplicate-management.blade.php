<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("Dublikatlarni boshqarish") }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistika -->
            @include('dashboard.partials.duplicate-stats')
            
            <!-- Vkladkalar -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8 px-6 pt-4" aria-label="Tabs">
                        <button onclick="showTab('active')" id="active-tab" 
                                class="tab-button active border-b-2 border-indigo-500 py-2 px-1 text-sm font-medium text-indigo-600">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Aktiv dublikatlar 
                            <span class="ml-1 bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">
                                {{ count($table11Duplicates) + count($table20Duplicates) }}
                            </span>
                        </button>
                        <button onclick="showTab('fixed')" id="fixed-tab" 
                                class="tab-button border-b-2 border-transparent py-2 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            <i class="fas fa-check-circle mr-2"></i>
                            Tuzatilgan dublikatlar 
                            <span class="ml-1 bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                                {{ count($table11FixedDuplicates) + count($table20FixedDuplicates) }}
                            </span>
                        </button>
                        <button onclick="showTab('logs')" id="logs-tab" 
                                class="tab-button border-b-2 border-transparent py-2 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            <i class="fas fa-history mr-2"></i>
                            Loglar
                        </button>
                    </nav>
                </div>

                <!-- Aktiv dublikatlar -->
                <div id="active-content" class="tab-content">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                            Harakatga kiritish kerak bo'lgan dublikatlar
                        </h3>
                        
                        @if(count($table11Duplicates) > 0 || count($table20Duplicates) > 0)
                            <!-- Table 11 aktiv dublikatlar -->
                            @include('dashboard.partials.duplicate-table11')
                            
                            <!-- Table 20 aktiv dublikatlar -->
                            @include('dashboard.partials.duplicate-table20')
                        @else
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    <span class="text-green-800">Hech qanday aktiv dublikat topilmadi! Barcha dublikatlar tuzatilgan.</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Tuzatilgan dublikatlar -->
                <div id="fixed-content" class="tab-content hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            Tuzatilgan dublikatlar tarixi
                        </h3>
                        
                        @if(count($table11FixedDuplicates) > 0 || count($table20FixedDuplicates) > 0)
                            <!-- Table 11 tuzatilgan dublikatlar -->
                            @include('dashboard.partials.duplicate-table11-fixed')
                            
                            <!-- Table 20 tuzatilgan dublikatlar -->
                            @include('dashboard.partials.duplicate-table20-fixed')
                        @else
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-center">
                                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                    <span class="text-blue-800">Hali hech qanday dublikat tuzatilmagan.</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Loglar -->
                <div id="logs-content" class="tab-content hidden">
                    <div class="p-6">
                        @include('dashboard.partials.duplicate-logs')
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded" role="alert">
        <strong class="font-bold">Muvaffaqiyat!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded" role="alert">
        <strong class="font-bold">Xatolik!</strong>
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    <script>
        // Vkladkalar funksionali
        function showTab(tabName) {
            // Barcha vkladkalarni yashirish
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Barcha vkladka tugmalarini dezaktivlash
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active', 'border-indigo-500', 'text-indigo-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Tanlangan vkladkani ko'rsatish
            document.getElementById(tabName + '-content').classList.remove('hidden');
            
            // Tanlangan tugmani aktivlashtirish
            const activeButton = document.getElementById(tabName + '-tab');
            activeButton.classList.add('active', 'border-indigo-500', 'text-indigo-600');
            activeButton.classList.remove('border-transparent', 'text-gray-500');
        }

        // Xabarlarni avtomatik yo'qotish
        setTimeout(function() {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(alert => {
                alert.style.display = 'none';
            });
        }, 5000);
    </script>

    <style>
        .tab-button.active {
            border-color: #4f46e5 !important;
            color: #4f46e5 !important;
        }
    </style>
</x-app-layout> 