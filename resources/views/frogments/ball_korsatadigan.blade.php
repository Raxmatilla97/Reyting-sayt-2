                                        @php
                                            $departemant_points = \App\Models\DepartPoints::where(
                                                'point_user_deport_id',
                                                $item->id,
                                            )->get();
                                            $total_departemant_points = $departemant_points->sum('point');
                                        @endphp
                                        <td class="px-6 py-4">
                                            @if ($item->status == '1')
                                                <span data-tooltip-target="tooltip-ticher-{{ $item->id }}"
                                                    class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">
                                                    {{ round($item->point, 2) }} ball
                                                </span>
                                                <div id="tooltip-ticher-{{ $item->id }}" role="tooltip"
                                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                                    O'qituvchiga berilgan ball
                                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                                </div>

                                                @if ($total_departemant_points > 0)
                                                    <span data-tooltip-target="tooltip-kafedra-{{ $item->id }}"
                                                        class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">
                                                        {{ round($total_departemant_points, 2) }} ball
                                                    </span>
                                                    <div id="tooltip-kafedra-{{ $item->id }}" role="tooltip"
                                                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                                        Kafedraga o'tgan ball
                                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                                    </div>
                                                @endif
                                            @elseif($item->status == '0')
                                                <span
                                                    class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">
                                                    Hisoblanmadi!
                                                </span>
                                            @else
                                                <span
                                                    class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                                    Baholanmagan!
                                                </span>
                                            @endif
                                        </td>
