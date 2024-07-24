<div>
    <p class="text-lg font-semibold">Item ID: {{ $item->id }}</p>

    @foreach($relatedData as $table => $data)
        @if($data instanceof \Illuminate\Support\Collection ? $data->isNotEmpty() : $data)
            <h4 class="text-md font-medium mt-4">{{ ucfirst(str_replace('_', ' ', $table)) }} ma'lumotlari</h4>
            <ul class="list-disc list-inside">
                @foreach($data as $row)
                    @if(is_object($row)) <!-- Check if $row is an object -->
                        <li>
                            @foreach($row->toArray() as $column => $value)
                                <strong>{{ ucfirst(str_replace('_', ' ', $column)) }}:</strong> {{ $value }}<br>
                            @endforeach
                        </li>
                    @endif
                @endforeach
            </ul>
        @endif
    @endforeach
</div>
