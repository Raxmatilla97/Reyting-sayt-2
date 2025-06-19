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
                                {{ count($table11Duplicates) + count($table20Duplicates) + count($table10Duplicates) + count($table14Duplicates) }}
                            </span>
                        </button>
                        <button onclick="showTab('fixed')" id="fixed-tab" 
                                class="tab-button border-b-2 border-transparent py-2 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            <i class="fas fa-check-circle mr-2"></i>
                            Tuzatilgan dublikatlar 
                            <span class="ml-1 bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                                {{ count($table11FixedDuplicates) + count($table20FixedDuplicates) + count($table10FixedDuplicates) + count($table14FixedDuplicates) }}
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
                        
                        @if(count($table11Duplicates) > 0 || count($table20Duplicates) > 0 || count($table10Duplicates) > 0 || count($table14Duplicates) > 0)
                            <!-- Sub tablar -->
                            <div class="border-b border-gray-200 mb-4">
                                <nav class="-mb-px flex space-x-4" aria-label="Sub Tabs">
                                    @if(count($table11Duplicates) > 0)
                                    <button onclick="scrollToSection('table11-active')" 
                                            class="sub-tab-button bg-red-100 text-red-800 border border-red-300 hover:bg-red-200 py-2 px-4 text-sm font-medium rounded-lg transition-all duration-200">
                                        <i class="fas fa-table mr-1"></i>
                                        Table 11 <span class="bg-red-200 text-red-900 text-xs px-2 py-1 rounded-full ml-1">{{ count($table11Duplicates) }}</span>
                                    </button>
                                    @endif
                                    
                                    @if(count($table20Duplicates) > 0)
                                    <button onclick="scrollToSection('table20-active')" 
                                            class="sub-tab-button bg-orange-100 text-orange-800 border border-orange-300 hover:bg-orange-200 py-2 px-4 text-sm font-medium rounded-lg transition-all duration-200">
                                        <i class="fas fa-table mr-1"></i>
                                        Table 20 <span class="bg-orange-200 text-orange-900 text-xs px-2 py-1 rounded-full ml-1">{{ count($table20Duplicates) }}</span>
                                    </button>
                                    @endif
                                    
                                    @if(count($table10Duplicates) > 0)
                                    <button onclick="scrollToSection('table10-active')" 
                                            class="sub-tab-button bg-blue-100 text-blue-800 border border-blue-300 hover:bg-blue-200 py-2 px-4 text-sm font-medium rounded-lg transition-all duration-200">
                                        <i class="fas fa-table mr-1"></i>
                                        Table 10 <span class="bg-blue-200 text-blue-900 text-xs px-2 py-1 rounded-full ml-1">{{ count($table10Duplicates) }}</span>
                                    </button>
                                    @endif
                                    
                                    @if(count($table14Duplicates) > 0)
                                    <button onclick="scrollToSection('table14-active')" 
                                            class="sub-tab-button bg-purple-100 text-purple-800 border border-purple-300 hover:bg-purple-200 py-2 px-4 text-sm font-medium rounded-lg transition-all duration-200">
                                        <i class="fas fa-table mr-1"></i>
                                        Table 14 <span class="bg-purple-200 text-purple-900 text-xs px-2 py-1 rounded-full ml-1">{{ count($table14Duplicates) }}</span>
                                    </button>
                                    @endif
                                </nav>
                            </div>

                            <!-- Table 11 aktiv dublikatlar -->
                            <div id="table11-active">
                                @include('dashboard.partials.duplicate-table11')
                            </div>
                            
                            <!-- Table 20 aktiv dublikatlar -->
                            <div id="table20-active">
                                @include('dashboard.partials.duplicate-table20')
                            </div>
                            
                            <!-- Table 10 aktiv dublikatlar -->
                            <div id="table10-active">
                                @include('dashboard.partials.duplicate-table10')
                            </div>
                            
                            <!-- Table 14 aktiv dublikatlar -->
                            <div id="table14-active">
                                @include('dashboard.partials.duplicate-table14')
                            </div>
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
                        
                        @if(count($table11FixedDuplicates) > 0 || count($table20FixedDuplicates) > 0 || count($table10FixedDuplicates) > 0 || count($table14FixedDuplicates) > 0)
                            <!-- Sub tablar -->
                            <div class="border-b border-gray-200 mb-4">
                                <nav class="-mb-px flex space-x-4" aria-label="Fixed Sub Tabs">
                                    @if(count($table11FixedDuplicates) > 0)
                                    <button onclick="scrollToSection('table11-fixed')" 
                                            class="sub-tab-button bg-green-100 text-green-800 border border-green-300 hover:bg-green-200 py-2 px-4 text-sm font-medium rounded-lg transition-all duration-200">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Table 11 <span class="bg-green-200 text-green-900 text-xs px-2 py-1 rounded-full ml-1">{{ count($table11FixedDuplicates) }}</span>
                                    </button>
                                    @endif
                                    
                                    @if(count($table20FixedDuplicates) > 0)
                                    <button onclick="scrollToSection('table20-fixed')" 
                                            class="sub-tab-button bg-green-100 text-green-800 border border-green-300 hover:bg-green-200 py-2 px-4 text-sm font-medium rounded-lg transition-all duration-200">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Table 20 <span class="bg-green-200 text-green-900 text-xs px-2 py-1 rounded-full ml-1">{{ count($table20FixedDuplicates) }}</span>
                                    </button>
                                    @endif
                                    
                                    @if(count($table10FixedDuplicates) > 0)
                                    <button onclick="scrollToSection('table10-fixed')" 
                                            class="sub-tab-button bg-green-100 text-green-800 border border-green-300 hover:bg-green-200 py-2 px-4 text-sm font-medium rounded-lg transition-all duration-200">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Table 10 <span class="bg-green-200 text-green-900 text-xs px-2 py-1 rounded-full ml-1">{{ count($table10FixedDuplicates) }}</span>
                                    </button>
                                    @endif
                                    
                                    @if(count($table14FixedDuplicates) > 0)
                                    <button onclick="scrollToSection('table14-fixed')" 
                                            class="sub-tab-button bg-green-100 text-green-800 border border-green-300 hover:bg-green-200 py-2 px-4 text-sm font-medium rounded-lg transition-all duration-200">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Table 14 <span class="bg-green-200 text-green-900 text-xs px-2 py-1 rounded-full ml-1">{{ count($table14FixedDuplicates) }}</span>
                                    </button>
                                    @endif
                                </nav>
                            </div>

                            <!-- Table 11 tuzatilgan dublikatlar -->
                            <div id="table11-fixed">
                                @include('dashboard.partials.duplicate-table11-fixed')
                            </div>
                            
                            <!-- Table 20 tuzatilgan dublikatlar -->
                            <div id="table20-fixed">
                                @include('dashboard.partials.duplicate-table20-fixed')
                            </div>
                            
                            <!-- Table 10 tuzatilgan dublikatlar -->
                            <div id="table10-fixed">
                                @include('dashboard.partials.duplicate-table10-fixed')
                            </div>
                            
                            <!-- Table 14 tuzatilgan dublikatlar -->
                            <div id="table14-fixed">
                                @include('dashboard.partials.duplicate-table14-fixed')
                            </div>
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

        // Seksiyaga scrolllash funksiyasi
        function scrollToSection(sectionId) {
            const element = document.getElementById(sectionId);
            if (element) {
                // Smooth scroll animatsiyasi bilan
                element.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'start',
                    inline: 'nearest'
                });
                
                // Seksiyani visual ravishda ajratib ko'rsatish
                element.style.transition = 'all 0.3s ease';
                element.style.boxShadow = '0 0 20px rgba(59, 130, 246, 0.5)';
                element.style.borderRadius = '8px';
                
                // 2 soniyadan so'ng highlight ni olib tashlash
                setTimeout(() => {
                    element.style.boxShadow = 'none';
                }, 2000);
            }
        }
    </script>

    <style>
        .tab-button.active {
            border-color: #4f46e5 !important;
            color: #4f46e5 !important;
        }
    </style>
</x-app-layout> 