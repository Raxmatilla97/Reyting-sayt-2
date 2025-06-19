<!-- Loglar -->
@if(count($recentLogs) > 0)
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900 mb-4">So'nggi amallar tarixi</h3>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Vaqt
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Amal
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ma'lumotlar
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($recentLogs as $log)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($log['timestamp'])->format('d.m.Y H:i:s') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @switch($log['action'])
                                @case('fix_table11_duplicates')
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded">Table 11 tuzatish</span>
                                    @break
                                @case('fix_table20_duplicates')
                                    <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded">Table 20 tuzatish</span>
                                    @break
                                @case('fix_single_record')
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">Yagona yozuv tuzatish</span>
                                    @break
                                @default
                                    <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded">{{ $log['action'] }}</span>
                            @endswitch
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <div class="max-w-xs">
                                @if(isset($log['data']['user_id']))
                                    <p><strong>Foydalanuvchi ID:</strong> {{ $log['data']['user_id'] }}</p>
                                @endif
                                @if(isset($log['data']['year']))
                                    <p><strong>Yil:</strong> {{ $log['data']['year'] }}</p>
                                @endif
                                @if(isset($log['data']['kept_record_id']))
                                    <p><strong>Saqlangan yozuv:</strong> {{ $log['data']['kept_record_id'] }}</p>
                                @endif
                                @if(isset($log['data']['zeroed_records']) && count($log['data']['zeroed_records']) > 0)
                                    <p><strong>0 ga o'tkazilgan:</strong> {{ implode(', ', $log['data']['zeroed_records']) }}</p>
                                @endif
                                @if(isset($log['data']['record_id']))
                                    <p><strong>Yozuv ID:</strong> {{ $log['data']['record_id'] }}</p>
                                @endif
                                @if(isset($log['data']['action']))
                                    <p><strong>Amal:</strong> {{ $log['data']['action'] }}</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif 