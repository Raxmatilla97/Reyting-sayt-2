<!-- Table 20 aktiv dublikatlari -->
@if(count($table20Duplicates) > 0)
<div class="bg-white overflow-hidden shadow-lg sm:rounded-lg mb-6 border border-orange-300">
    <div class="p-6 bg-gradient-to-r from-orange-50 to-orange-100 border-b border-orange-200">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-orange-900">
                <i class="fas fa-exclamation-triangle text-orange-600 mr-2"></i>
                Table 20 aktiv dublikatlari ({{ count($table20Duplicates) }} ta topildi)
            </h3>
            <form action="{{ route('duplicate-management.fix-table20') }}" method="POST" class="inline" 
                  onsubmit="return confirm('Barcha Table 20 dublikatlarini tuzatishni istaysizmi? Eng yuqori Table turi saqlanadi, qolganlari 0 ballga o\'tkaziladi.')">
                @csrf
                <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded-lg shadow-lg transition-all duration-200">
                    <i class="fas fa-tools mr-1"></i>
                    Barcha Table 20 dublikatlarini tuzatish
                </button>
            </form>
        </div>
        
        @foreach($table20Duplicates as $duplicate)
        <div class="mb-8 border-2 border-orange-400 rounded-lg p-4 bg-orange-50 shadow-md">
            <!-- Foydalanuvchi ma'lumotlari -->
            <div class="mb-4 pb-3 border-b-2 border-orange-300">
                <div class="flex justify-between items-center">
                    <div>
                        <h4 class="text-lg font-bold text-gray-900">
                            <i class="fas fa-user text-blue-600 mr-2"></i>
                            {{ $duplicate['employee_name'] }}
                        </h4>
                        <p class="text-sm text-gray-700 mt-1">
                            <i class="fas fa-calendar text-purple-500 mr-1"></i>
                            Yil: {{ $duplicate['year'] }} | 
                            <i class="fas fa-star text-yellow-600 mr-1"></i>
                            Jami ball: <span class="font-bold text-orange-700 bg-orange-200 px-2 py-1 rounded">{{ $duplicate['total_points'] }}</span>
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="bg-orange-200 text-orange-900 text-xs font-bold px-3 py-1 rounded-full mb-2 block shadow">
                            {{ count($duplicate['records']) }} ta yozuv
                        </span>
                        <form action="{{ route('duplicate-management.fix-single') }}" method="POST" class="inline ajax-form">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $duplicate['user_id'] }}">
                            <input type="hidden" name="year" value="{{ $duplicate['year'] }}">
                            <input type="hidden" name="record_ids" value="{{ implode(',', array_column($duplicate['records'], 'id')) }}">
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold py-2 px-3 rounded-lg shadow transition-all duration-200">
                                <i class="fas fa-wrench mr-1"></i>
                                Tuzatish
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Dublikat yozuvlar -->
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach($duplicate['records'] as $record)
                <div class="bg-white rounded-lg border-l-4 @if($loop->first) border-green-600 bg-green-50 @else border-orange-600 bg-orange-50 @endif p-4 shadow-lg">
                    <div class="flex items-center mb-3">
                        <span class="@if($loop->first) bg-green-200 text-green-900 @else bg-orange-200 text-orange-900 @endif text-xs font-bold px-3 py-1 rounded-full mr-2">
                            {{ $record['table_type'] }}
                        </span>
                        <span class="text-sm text-gray-600 bg-gray-100 px-2 py-1 rounded">ID: {{ $record['id'] }}</span>
                        @if($loop->first)
                            <span class="ml-2 bg-green-200 text-green-900 text-xs font-bold px-2 py-1 rounded-full">
                                <i class="fas fa-check mr-1"></i>Saqlanadi
                            </span>
                        @else
                            <span class="ml-2 bg-orange-200 text-orange-900 text-xs font-bold px-2 py-1 rounded-full">
                                <i class="fas fa-times mr-1"></i>Tuzatiladi
                            </span>
                        @endif
                    </div>
                    
                    <div class="space-y-2 text-sm">
                        <p><strong class="text-blue-800">Jurnal:</strong> 
                            <span class="text-blue-700 font-medium">{{ Str::limit($record['jurnal_nomi'] ?: 'Ma\'lumot yo\'q', 50) }}</span>
                        </p>
                        <p><strong class="text-purple-800">Mualliflar soni:</strong> 
                            <span class="text-purple-700 font-medium">{{ $record['mualliflar_soni'] ?: 'Ma\'lumot yo\'q' }}</span>
                        </p>
                        <p><strong class="text-green-800">Ball:</strong> 
                            <span class="font-bold text-green-700 bg-green-100 px-2 py-1 rounded">{{ $record['point'] }}</span>
                        </p>
                        <div class="flex items-center justify-between text-xs text-gray-600 bg-gray-100 p-1 rounded">
                            <span>
                                <i class="fas fa-clock mr-1"></i>
                                Qo'shilgan: {{ \Carbon\Carbon::parse($record['created_at'])->format('d.m.Y H:i') }}
                            </span>
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

            <!-- O'xshashlik ballari -->
            @if(isset($duplicate['similarity_pairs']) && count($duplicate['similarity_pairs']) > 0)
            <div class="mt-4 pt-4 border-t-2 border-orange-300">
                <h5 class="text-sm font-bold text-gray-800 mb-3">
                    <i class="fas fa-chart-bar text-purple-600 mr-1"></i>
                    O'xshashlik ballari:
                </h5>
                <div class="grid gap-3">
                    @foreach($duplicate['similarity_pairs'] as $pair)
                    <div class="bg-purple-100 border-2 border-purple-300 rounded-lg p-3 text-xs shadow">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-bold text-gray-800">ID {{ $pair['record1_id'] }} ↔ ID {{ $pair['record2_id'] }}</span>
                            <span class="font-bold text-purple-700 bg-purple-200 px-2 py-1 rounded">{{ number_format($pair['similarity_score'] * 100, 1) }}%</span>
                        </div>
                        @if(isset($pair['details']))
                        <div class="text-gray-700 space-x-4 bg-white p-2 rounded">
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">Jurnal: {{ number_format($pair['details']['journal_similarity'] * 100, 1) }}%</span>
                            <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs">Mualliflar: {{ number_format($pair['details']['authors_similarity'] * 100, 1) }}%</span>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @endforeach
    </div>
</div>
@else
<div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
    <div class="flex items-center">
        <i class="fas fa-check-circle text-green-500 mr-2"></i>
        <span class="text-green-800">Table 20 da dublikat yozuvlar topilmadi!</span>
    </div>
</div>
@endif 