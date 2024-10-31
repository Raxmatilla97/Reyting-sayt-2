@php
    $departemant_points = \App\Models\DepartPoints::where('point_user_deport_id', $item->id)->get();
    $total_departemant_points = $departemant_points->sum('point');
@endphp

<td class="px-6 py-4">
    @if ($item->status == '1')
        <div class="flex flex-col gap-1.5">
            @if ($item->point > 0 || $total_departemant_points > 0)
                @if ($item->point > 0)
                    <!-- O'qituvchi bali -->
                    <div class="group relative inline-flex">
                        <span class="bg-green-100 text-green-700 text-sm font-medium px-2.5 py-1 rounded-md border border-green-100 hover:bg-green-100 transition-colors duration-200">
                            {{ number_format($item->point, 2) }} ball
                        </span>
                        <!-- Tooltip -->
                        <div class="absolute bottom-full left-0 mb-1 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 whitespace-nowrap">
                            O'qituvchi bali
                        </div>
                    </div>
                @endif

                @if ($total_departemant_points > 0)
                    <!-- Kafedra bali -->
                    <div class="group relative inline-flex">
                        <span class="bg-blue-100 text-blue-700 text-sm font-medium px-2.5 py-1 rounded-md border border-blue-100 hover:bg-blue-100 transition-colors duration-200">
                            {{ number_format($total_departemant_points, 2) }} ball
                        </span>
                        <!-- Tooltip -->
                        <div class="absolute bottom-full left-0 mb-1 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 whitespace-nowrap">
                            Kafedra bali
                        </div>
                    </div>
                @endif
            @else
                <span class="bg-gray-100 text-gray-700 text-sm font-medium px-2.5 py-1 rounded-md border border-gray-100">
                    Ball berilmagan
                </span>
            @endif
        </div>
    @elseif($item->status == '0')
        <span class="bg-red-100 text-red-700 text-sm font-medium px-2.5 py-1 rounded-md border border-red-100">
            Hisoblanmadi
        </span>
    @else
        <span class="bg-blue-100 text-blue-700 text-sm font-medium px-2.5 py-1 rounded-md border border-blue-100">
            Baholanmagan
        </span>
    @endif
</td>