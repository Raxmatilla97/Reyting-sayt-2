<!-- Table 20 tuzatilgan dublikatlari -->
@if(count($table20FixedDuplicates) > 0)
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
    <div class="p-6 bg-white border-b border-gray-200">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                Table 20 tuzatilgan dublikatlari ({{ count($table20FixedDuplicates) }} ta jurnal)
            </h3>
            <div class="text-sm text-gray-500">
                <i class="fas fa-info-circle mr-1"></i>
                Aniq mos keluvchi jurnal nomlari bo'yicha tuzatilgan
            </div>
        </div>
        
        @foreach($table20FixedDuplicates as $fixedDuplicate)
        <div class="mb-8 border border-orange-200 rounded-lg p-4 bg-orange-50">
            <!-- Foydalanuvchi ma'lumotlari -->
            <div class="mb-4 pb-3 border-b border-orange-300">
                <div class="flex justify-between items-center">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-user text-blue-500 mr-2"></i>
                            {{ $fixedDuplicate['employee_name'] }}
                        </h4>
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-calendar mr-1"></i>
                            Yil: {{ $fixedDuplicate['year'] }} | 
                            <i class="fas fa-star text-yellow-500 mr-1"></i>
                            Aktiv ball: <span class="font-bold text-orange-600">{{ $fixedDuplicate['total_active_points'] }}</span>
                        </p>
                        <p class="text-sm text-blue-600 mt-1">
                            <i class="fas fa-book mr-1"></i>
                            <strong>Jurnal:</strong> {{ $fixedDuplicate['journal_name'] ?? 'Noma\'lum' }}
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">
                            <i class="fas fa-check mr-1"></i>
                            Tuzatilgan
                        </span>
                        <div class="text-xs text-gray-500 mt-1">
                            {{ \Carbon\Carbon::parse($fixedDuplicate['fixed_at'])->format('d.m.Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Aktiv yozuvlar -->
                <div>
                    <h5 class="text-sm font-medium text-green-700 mb-3">
                        <i class="fas fa-check-circle text-green-500 mr-1"></i>
                        Saqlangan yozuvlar ({{ count($fixedDuplicate['active_records']) }} ta)
                    </h5>
                    @foreach($fixedDuplicate['active_records'] as $record)
                    <div class="bg-white rounded-lg border-l-4 border-green-500 p-4 shadow-sm mb-3">
                        <div class="flex items-center mb-2">
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded mr-2">
                                {{ $record['table_type'] }}
                            </span>
                            <span class="text-sm text-gray-500">ID: {{ $record['id'] }}</span>
                            <span class="ml-2 bg-green-100 text-green-800 text-xs px-2 py-1 rounded">
                                <i class="fas fa-star mr-1"></i>{{ $record['point'] }} ball
                            </span>
                        </div>
                        
                        <div class="space-y-1 text-sm">
                            <p><strong>Jurnal:</strong> 
                                <span class="text-blue-600">{{ Str::limit($record['jurnal_nomi'] ?: 'Ma\'lumot yo\'q', 80) }}</span>
                            </p>
                            <p><strong>Mualliflar soni:</strong> 
                                <span class="text-purple-600">{{ $record['mualliflar_soni'] ?: 'Ma\'lumot yo\'q' }}</span>
                            </p>
                            <p class="text-xs text-gray-500">
                                <i class="fas fa-clock mr-1"></i>
                                Qo'shilgan: {{ \Carbon\Carbon::parse($record['created_at'])->format('d.m.Y H:i') }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Tuzatilgan yozuvlar -->
                <div>
                    <h5 class="text-sm font-medium text-red-700 mb-3">
                        <i class="fas fa-times-circle text-red-500 mr-1"></i>
                        Tuzatilgan yozuvlar ({{ count($fixedDuplicate['fixed_records']) }} ta)
                    </h5>
                    @foreach($fixedDuplicate['fixed_records'] as $record)
                    <div class="bg-white rounded-lg border-l-4 border-red-500 p-4 shadow-sm mb-3 opacity-75">
                        <div class="flex items-center mb-2">
                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2 py-1 rounded mr-2">
                                {{ $record['table_type'] }}
                            </span>
                            <span class="text-sm text-gray-500">ID: {{ $record['id'] }}</span>
                            <span class="ml-2 bg-red-100 text-red-800 text-xs px-2 py-1 rounded">
                                <i class="fas fa-ban mr-1"></i>0 ball
                            </span>
                        </div>
                        
                        <div class="space-y-1 text-sm">
                            <p><strong>Jurnal:</strong> 
                                <span class="text-blue-600">{{ Str::limit($record['jurnal_nomi'] ?: 'Ma\'lumot yo\'q', 80) }}</span>
                            </p>
                            <p><strong>Mualliflar soni:</strong> 
                                <span class="text-purple-600">{{ $record['mualliflar_soni'] ?: 'Ma\'lumot yo\'q' }}</span>
                            </p>
                            <p class="text-xs text-gray-500">
                                <i class="fas fa-clock mr-1"></i>
                                Qo'shilgan: {{ \Carbon\Carbon::parse($record['created_at'])->format('d.m.Y H:i') }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif 