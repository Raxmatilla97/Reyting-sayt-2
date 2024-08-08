<div class="p-4 bg-white rounded-lg shadow-md">
    @foreach($relatedData as $table => $data)
        @if($data)
            @php
                // Konfiguratsiya kaliti uchun jadval nomini moslashtirish
                $configKey = "employee_form_fields.{$table}_";
                $fields = config($configKey);
                $fieldsByName = collect($fields)->keyBy('name');
            @endphp
            <h4 class="text-lg font-semibold mb-4 mt-4">
                <span class="bg-blue-100 text-blue-800 text-lg font-medium px-3 py-1 rounded">
                    Maxsus forma kodi: {{  $table }} ma'lumotlari
                </span>
            </h4>

                @foreach($data->toArray() as $column => $value)
                    @if($column !== 'id')
                    @php
                        $label = $fieldsByName[$column]['label'] ?? ucfirst(str_replace('_', ' ', $column));
                        $isFile = strpos($value, 'documents/') === 0;
                    @endphp
                   <div class="flex p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
                    <svg class="flex-shrink-0 inline w-4 h-4 me-3 mt-[2px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                    </svg>
                    <span class="sr-only">Info</span>
                    <div>
                      <span class="font-medium">{{ $label }}:</span>
                      <ul class="mt-1.5 list-disc list-inside">
                        @if($isFile)
                            <a href="{{ asset('storage/'.$value) }}" download class="text-blue-600 hover:text-blue-800 underline">
                                <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    Yuklab olish
                                </button>
                                </a>
                        @else
                            <span class="text-gray-900">{{ $value }}</span>
                        @endif
                    </ul>
                    </div>
                  </div>
                    @endif
                @endforeach

        @endif
    @endforeach

    <style>
        .modal-content {
            background-color: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1a202c;
        }

        .modal-body {
            padding: 1rem 0;
            max-height: 70vh;
            overflow-y: auto;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            padding-top: 1rem;
            border-top: 1px solid #e2e8f0;
        }

        .modal-footer button {
            margin-left: 0.5rem;
        }

        .modal-open {
            overflow: hidden;
        }

        /* Scroll bar styling */
        .modal-body::-webkit-scrollbar {
            width: 12px;
        }

        .modal-body::-webkit-scrollbar-thumb {
            background-color: #cbd5e0;
            border-radius: 6px;
        }

        .modal-body::-webkit-scrollbar-track {
            background-color: #f7fafc;
        }
    </style>
</div>
