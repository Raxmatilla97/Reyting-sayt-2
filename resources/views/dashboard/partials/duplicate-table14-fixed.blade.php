<!-- Table 14 tuzatilgan dublikatlari -->
@if(count($table14FixedDuplicates) > 0)
<div class="bg-white overflow-hidden shadow-lg sm:rounded-lg mb-6 border border-green-300">
    <div class="p-6 bg-gradient-to-r from-green-50 to-green-100 border-b border-green-200">
        <h3 class="text-lg font-bold text-green-900 mb-4">
            <i class="fas fa-check-circle text-green-600 mr-2"></i>
            Table 14 tuzatilgan dublikatlari ({{ count($table14FixedDuplicates) }} ta)
        </h3>
        
        @foreach($table14FixedDuplicates as $fixed)
        <div class="mb-6 border-2 border-green-400 rounded-lg p-4 bg-green-50 shadow-md">
            <!-- Foydalanuvchi ma'lumotlari -->
            <div class="mb-4 pb-3 border-b-2 border-green-300">
                <h4 class="text-lg font-bold text-gray-900">
                    <i class="fas fa-user text-green-600 mr-2"></i>
                    {{ $fixed['employee_name'] }}
                </h4>
                                 <p class="text-sm text-gray-700 mt-1">
                     <i class="fas fa-calendar text-orange-500 mr-1"></i>
                     Yil: {{ $fixed['year'] }} | 
                     <i class="fas fa-star text-green-600 mr-1"></i>
                     Aktiv ball: <span class="font-bold text-green-700 bg-green-200 px-2 py-1 rounded">{{ $fixed['total_active_points'] }}</span> | 
                     <i class="fas fa-clock text-blue-600 mr-1"></i>
                     Tuzatilgan: {{ \Carbon\Carbon::parse($fixed['fixed_at'])->format('d.m.Y H:i') }}
                 </p>
                 <a href="{{ route('dashboard.employeeShow', ['id_employee' => $fixed['employee_id_number']]) }}" 
                    target="_blank" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs transition-all duration-200 mt-2 inline-block">
                     <i class="fas fa-external-link-alt mr-1"></i>Ko'rish
                 </a>
                <p class="text-sm font-medium text-purple-800 mt-2">
                    <i class="fas fa-file-alt text-purple-600 mr-1"></i>
                    Tezis: {{ $fixed['article_title'] }}
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Aktiv yozuvlar -->
                <div>
                    <h5 class="text-sm font-bold text-green-800 mb-3">
                        <i class="fas fa-check-circle text-green-600 mr-1"></i>
                        Aktiv yozuvlar (saqlanganlar):
                    </h5>
                    <div class="space-y-2">
                        @foreach($fixed['active_records'] as $record)
                        <div class="bg-green-100 border-l-4 border-green-600 p-3 rounded shadow">
                            <div class="flex items-center justify-between mb-2">
                                <span class="bg-green-200 text-green-900 text-xs font-bold px-2 py-1 rounded">
                                    {{ $record['table_type'] }} (ID: {{ $record['id'] }})
                                </span>
                                <span class="font-bold text-green-700 bg-green-200 px-2 py-1 rounded text-xs">
                                    {{ $record['point'] }} ball
                                </span>
                            </div>
                            <div class="text-xs text-gray-700 space-y-1">
                                <p><strong>Davlat va OTM:</strong> {{ Str::limit($record['davlat_otm_nomi'], 40) }}</p>
                                <p><strong>Tezis:</strong> {{ Str::limit($record['tezis_nomi'], 40) }}</p>
                                <p><strong>Konferensiya:</strong> {{ Str::limit($record['konf_seminar_nomi'] ?: 'Ma\'lumot yo\'q', 40) }}</p>
                                <div class="mt-2">
                                    <a href="{{ route('murojatlar.show', ['id' => $record['id']]) }}" 
                                       target="_blank" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs transition-all duration-200">
                                        <i class="fas fa-external-link-alt mr-1"></i>Ko'rish
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Tuzatilgan yozuvlar -->
                <div>
                    <h5 class="text-sm font-bold text-red-800 mb-3">
                        <i class="fas fa-times-circle text-red-600 mr-1"></i>
                        Tuzatilgan yozuvlar (0 ballga o'tkazilganlar):
                    </h5>
                    <div class="space-y-2">
                        @foreach($fixed['fixed_records'] as $record)
                        <div class="bg-red-100 border-l-4 border-red-600 p-3 rounded shadow">
                            <div class="flex items-center justify-between mb-2">
                                <span class="bg-red-200 text-red-900 text-xs font-bold px-2 py-1 rounded">
                                    {{ $record['table_type'] }} (ID: {{ $record['id'] }})
                                </span>
                                <div class="text-xs">
                                    <span class="font-bold text-red-700 bg-red-200 px-2 py-1 rounded">
                                        0 ball
                                    </span>
                                    <span class="font-bold text-blue-700 bg-blue-200 px-2 py-1 rounded ml-1">
                                        +0.10 kafedra
                                    </span>
                                </div>
                            </div>
                            <div class="text-xs text-gray-700 space-y-1">
                                <p><strong>Davlat va OTM:</strong> {{ Str::limit($record['davlat_otm_nomi'], 40) }}</p>
                                <p><strong>Tezis:</strong> {{ Str::limit($record['tezis_nomi'], 40) }}</p>
                                <p><strong>Konferensiya:</strong> {{ Str::limit($record['konf_seminar_nomi'] ?: 'Ma\'lumot yo\'q', 40) }}</p>
                                <div class="mt-2">
                                    <a href="{{ route('murojatlar.show', ['id' => $record['id']]) }}" 
                                       target="_blank" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs transition-all duration-200">
                                        <i class="fas fa-external-link-alt mr-1"></i>Ko'rish
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@else
<div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
    <div class="flex items-center">
        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
        <span class="text-blue-800">Table 14 da hali tuzatilgan dublikatlar yo'q.</span>
    </div>
</div>
@endif 