{{-- Table 11 o'xshash ma'lumotlar uchun alert --}}
@if(isset($hasTable11SimilarData) && $hasTable11SimilarData)
<li class="text-orange-600 font-semibold mt-2 w-full">
    <div class="bg-orange-50 border-l-4 border-orange-400 p-4 rounded w-full">
        <div class="flex w-full">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-orange-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3 w-full">
                <h3 class="text-sm font-medium text-orange-800">
                    Table 11 o'xshash ma'lumotlar topildi!
                </h3>
                <div class="mt-2 text-sm text-orange-700 w-full">
                    <p class="mb-3 w-full"><strong>Joriy tekshirilayotgan ma'lumot:</strong> {{ $currentTable11Type }} tipida</p>
                    
                    <div class="mb-4 w-full">
                        <h4 class="font-semibold text-orange-800 mb-2">🔍 Tasdiqlangan o'xshash ma'lumotlar topildi:</h4>
                        @foreach($table11SimilarData as $index => $similarItem)
                        <div class="border rounded-lg p-3 mb-3 shadow-sm w-full
                            @if($similarItem['has_points']) 
                                bg-red-50 border-red-300
                            @else 
                                bg-green-50 border-green-300
                            @endif">
                            
                            <div class="flex justify-between items-start mb-2 w-full">
                                <div class="flex items-center space-x-2">
                                    <span class="bg-{{ $similarItem['table_type'] == 'table_11_1' ? 'red' : ($similarItem['table_type'] == 'table_11_2' ? 'blue' : 'purple') }}-100 text-{{ $similarItem['table_type'] == 'table_11_1' ? 'red' : ($similarItem['table_type'] == 'table_11_2' ? 'blue' : 'purple') }}-800 px-2 py-1 rounded text-xs font-medium">
                                        {{ $similarItem['table_type'] }}
                                    </span>
                                    
                                    @if($similarItem['has_points'])
                                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-bold flex items-center">
                                            ⚠️ {{ $similarItem['point'] }} ball berilgan!
                                        </span>
                                    @else
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-bold flex items-center">
                                            ✅ 0 ball (Xavfsiz)
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="text-right text-xs text-gray-500">
                                    {{ $similarItem['created_at'] }}
                                </div>
                            </div>
                            
                            <div class="space-y-1 text-sm mb-3 w-full">
                                <div class="bg-white p-2 rounded border w-full">
                                    <strong class="text-gray-700">Jurnal:</strong> 
                                    <span class="text-gray-900">{{ $similarItem['jurnal_nomi'] }}</span>
                                </div>
                                <div class="bg-white p-2 rounded border w-full">
                                    <strong class="text-gray-700">Maqola:</strong> 
                                    <span class="text-gray-900">{{ $similarItem['maqola_nomi'] }}</span>
                                </div>
                                <div class="bg-white p-2 rounded border w-full">
                                    <strong class="text-gray-700">Yil:</strong> 
                                    <span class="text-gray-900">{{ $similarItem['nashr_yili'] }}</span>
                                </div>
                            </div>
                            
                            <div class="border-t pt-2 w-full">
                                <div class="flex justify-between items-center flex-wrap gap-2">
                                    <div class="flex flex-wrap gap-1 text-xs">
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                            Jurnal: {{ $similarItem['similarity']['journal_similarity'] }}%
                                        </span>
                                        <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded">
                                            Maqola: {{ $similarItem['similarity']['article_similarity'] }}%
                                        </span>
                                        @if($similarItem['similarity']['year_match'])
                                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded">
                                                ✓ Yil mos
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <a href="{{ route('murojatlar.show', $similarItem['id']) }}" 
                                       class="text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded font-medium">
                                        Batafsil ko'rish
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-3 p-4 bg-red-50 border-l-4 border-red-400 rounded-r w-full">
                        <div class="flex w-full">
                            <div class="ml-2 w-full">
                                <p class="text-red-800 font-semibold text-sm mb-2">
                                    ⚠️ MUHIM OGOHLANTIRISH
                                </p>
                                <div class="text-red-700 text-sm space-y-2 w-full">
                                    @php
                                        $hasPointsItems = collect($table11SimilarData)->where('has_points', true);
                                        $noPointsItems = collect($table11SimilarData)->where('has_points', false);
                                    @endphp
                                    
                                    @if($hasPointsItems->count() > 0)
                                        <div class="bg-red-100 p-3 rounded border border-red-300 w-full">
                                            <p class="font-bold text-red-800">🚨 XAVFLI HOLAT:</p>
                                            <p>• Yuqorida <strong>{{ $hasPointsItems->count() }}</strong> ta o'xshash ma'lumot topildi va ularga <strong>ball berilgan</strong></p>
                                            <p>• Agar bu ma'lumotni tasdiqlasangiz, o'sha ballari mavjud ma'lumotlar <strong>0 ball</strong> ga o'zgaradi</p>
                                            <p>• Jami yo'qotiladigan ball: <strong>{{ $hasPointsItems->sum('point') }}</strong></p>
                                        </div>
                                    @endif
                                    
                                    @if($noPointsItems->count() > 0)
                                        <div class="bg-green-100 p-3 rounded border border-green-300 w-full">
                                            <p class="font-bold text-green-800">✅ XAVFSIZ HOLAT:</p>
                                            <p class="text-green-800">• <strong>{{ $noPointsItems->count() }}</strong> ta o'xshash ma'lumot topildi lekin ularga ball berilmagan (0 ball)</p>
                                            <p class="text-green-800">• Bu ma'lumotlarni tasdiqlash xavfsiz chunki o'xshash ma'lumotlarga oldin ball berilmagan</p>
                                        </div>
                                    @endif
                                    
                                    <div class="bg-yellow-100 p-3 rounded border border-yellow-300 w-full">
                                        <p class="font-bold text-yellow-800">📝 ESLATMA:</p>
                                        <p>• Bu amal qaytarib bo'lmaydi!</p>
                                        <p>• Faqat joriy tekshirilayotgan ma'lumotga ball beriladi</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</li>
@endif

{{-- Table 20 o'xshash ma'lumotlar uchun alert --}}
@if(isset($hasTable20SimilarData) && $hasTable20SimilarData)
<li class="text-purple-600 font-semibold mt-2 w-full">
    <div class="bg-purple-50 border-l-4 border-purple-400 p-4 rounded w-full">
        <div class="flex w-full">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-purple-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3 w-full">
                <h3 class="text-sm font-medium text-purple-800">
                    Table 20 o'xshash ma'lumotlar topildi!
                </h3>
                <div class="mt-2 text-sm text-purple-700 w-full">
                    <p class="mb-3 w-full"><strong>Joriy tekshirilayotgan ma'lumot:</strong> {{ $currentTable20Type }} tipida</p>
                    
                    <div class="mb-4 w-full">
                        <h4 class="font-semibold text-purple-800 mb-2">🔍 Tasdiqlangan o'xshash ma'lumotlar topildi:</h4>
                        @foreach($table20SimilarData as $index => $similarItem)
                        <div class="border rounded-lg p-3 mb-3 shadow-sm w-full
                            @if($similarItem['has_points']) 
                                bg-red-50 border-red-300
                            @else 
                                bg-green-50 border-green-300
                            @endif">
                            
                            <div class="flex justify-between items-start mb-2 w-full">
                                <div class="flex items-center space-x-2">
                                    <span class="bg-{{ $similarItem['table_type'] == 'table_20_1' ? 'red' : ($similarItem['table_type'] == 'table_20_2' ? 'blue' : 'purple') }}-100 text-{{ $similarItem['table_type'] == 'table_20_1' ? 'red' : ($similarItem['table_type'] == 'table_20_2' ? 'blue' : 'purple') }}-800 px-2 py-1 rounded text-xs font-medium">
                                        {{ $similarItem['table_type'] }}
                                    </span>
                                    
                                    @if($similarItem['has_points'])
                                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-bold flex items-center">
                                            ⚠️ {{ $similarItem['point'] }} ball berilgan!
                                        </span>
                                    @else
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-bold flex items-center">
                                            ✅ 0 ball (Xavfsiz)
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="text-right text-xs text-gray-500">
                                    {{ $similarItem['created_at'] }}
                                </div>
                            </div>
                            
                            <div class="space-y-1 text-sm mb-3 w-full">
                                <div class="bg-white p-2 rounded border w-full">
                                    <strong class="text-gray-700">Jurnal nomi:</strong> 
                                    <span class="text-gray-900">{{ $similarItem['jurnal_nomi'] }}</span>
                                </div>
                                <div class="bg-white p-2 rounded border w-full">
                                    <strong class="text-gray-700">Mualliflar soni:</strong> 
                                    <span class="text-gray-900">{{ $similarItem['mualliflar_soni'] }}</span>
                                </div>
                            </div>
                            
                            <div class="border-t pt-2 w-full">
                                <div class="flex justify-between items-center flex-wrap gap-2">
                                    <div class="flex flex-wrap gap-1 text-xs">
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                            Jurnal: {{ $similarItem['similarity']['journal_similarity'] }}%
                                        </span>
                                        <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded">
                                            Mualliflar: {{ $similarItem['similarity']['authors_similarity'] }}%
                                        </span>
                                    </div>
                                    
                                    <a href="{{ route('murojatlar.show', $similarItem['id']) }}" 
                                       class="text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded font-medium">
                                        Batafsil ko'rish
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-3 p-4 bg-red-50 border-l-4 border-red-400 rounded-r w-full">
                        <div class="flex w-full">
                            <div class="ml-2 w-full">
                                <p class="text-red-800 font-semibold text-sm mb-2">
                                    ⚠️ MUHIM OGOHLANTIRISH
                                </p>
                                <div class="text-red-700 text-sm space-y-2 w-full">
                                    @php
                                        $hasPointsItems20 = collect($table20SimilarData)->where('has_points', true);
                                        $noPointsItems20 = collect($table20SimilarData)->where('has_points', false);
                                    @endphp
                                    
                                    @if($hasPointsItems20->count() > 0)
                                        <div class="bg-red-100 p-3 rounded border border-red-300 w-full">
                                            <p class="font-bold text-red-800">🚨 XAVFLI HOLAT:</p>
                                            <p>• Yuqorida <strong>{{ $hasPointsItems20->count() }}</strong> ta o'xshash ma'lumot topildi va ularga <strong>ball berilgan</strong></p>
                                            <p>• Agar bu ma'lumotni tasdiqlasangiz, o'sha ballari mavjud ma'lumotlar <strong>0 ball</strong> ga o'zgaradi</p>
                                            <p>• Jami yo'qotiladigan ball: <strong>{{ $hasPointsItems20->sum('point') }}</strong></p>
                                        </div>
                                    @endif
                                    
                                    @if($noPointsItems20->count() > 0)
                                        <div class="bg-green-100 p-3 rounded border border-green-300 w-full">
                                            <p class="font-bold text-green-800">✅ XAVFSIZ HOLAT:</p>
                                            <p class="text-green-800">• <strong>{{ $noPointsItems20->count() }}</strong> ta o'xshash ma'lumot topildi lekin ularga ball berilmagan (0 ball)</p>
                                            <p class="text-green-800">• Bu ma'lumotlarni tasdiqlash xavfsiz chunki o'xshash ma'lumotlarga oldin ball berilmagan</p>
                                        </div>
                                    @endif
                                    
                                    <div class="bg-yellow-100 p-3 rounded border border-yellow-300 w-full">
                                        <p class="font-bold text-yellow-800">📝 ESLATMA:</p>
                                        <p>• Bu amal qaytarib bo'lmaydi!</p>
                                        <p>• Faqat joriy tekshirilayotgan ma'lumotga ball beriladi</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</li>
@endif 