<div class="max-w-7xl mx-auto p-6 bg-white rounded-xl shadow-lg dark:bg-gray-800">
    @foreach ($relatedData as $table => $data)
        @if ($data)
            @php
                $configKey = "employee_form_fields.{$table}_,department_forms_fields.{$table}_";
                $allFields = [];
                foreach (explode(',', $configKey) as $key) {
                    $fields = config(trim($key)) ?? [];
                    $allFields = array_merge($allFields, $fields);
                }
                $fieldsByName = collect($allFields)->keyBy('name');
            @endphp

            {{-- Header Section --}}
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <h4 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center gap-3">
                        <span class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg dark:bg-blue-900 dark:text-blue-200">
                            Maxsus forma kodi: {{ $table }}
                        </span>
                        <span
                            class="bg-green-100 text-green-800 px-4 py-2 rounded-lg dark:bg-green-900 dark:text-green-200">
                            Ball: {{ $qoyilgan_ball }}
                        </span>
                    </h4>
                </div>
            </div>

            {{-- Year Info Section --}}
            <div
                class="mb-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg dark:bg-gray-700 dark:border-blue-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">Ma'lumotlar yili</h3>
                        <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">{{ $year }} yilda yaratilgan
                            yoki tegishli.</p>
                    </div>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="grid grid-cols-1 gap-6">
                @foreach ($data->toArray() as $column => $value)
                    @if ($column !== 'id')
                        @php
                            $label = $fieldsByName[$column]['label'] ?? ucfirst(str_replace('_', ' ', $column));
                            $isFile = strpos($value, 'documents/') === 0;

                            if ($column === 'created_at') {
                                $label = 'Yaratilgan sana';
                                $created_at = \Carbon\Carbon::parse($yaratilgan_sana);
                                $value = $created_at->format('d-M-Y H:i');
                            } elseif ($column === 'updated_at') {
                                continue; // Tekshirilgan sanani o'tkazib yuborish
                                } elseif ($column === 'asos_file') {
                                    if ($value === null || $value === '' || $value === 'null' || !$value) {
                                        continue;
                                }
                            }

                        @endphp

                        <div
                            class="bg-white rounded-lg shadow-sm border border-gray-100 dark:bg-gray-800 dark:border-gray-700 overflow-hidden">
                            <div class="p-4">
                                <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ $label }}</h5>
                                <div class="mt-2">
                                    @if ($isFile)
                                        @if (Auth::user()->is_admin === 1 || Auth::user()->id === $creator_id)
                                            <a href="{{ asset('storage/' . $value) }}"
                                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-150 ease-in-out">
                                                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                                Yuklab olish
                                            </a>
                                        @else
                                            <div
                                                class="text-red-500 dark:text-red-400 text-sm bg-red-50 dark:bg-red-900/30 p-3 rounded-lg">
                                                <svg class="w-4 h-4 inline mr-1" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                                Hujjatlarning maxfiyligi va ma'lumotlarni himoya qilish qoidalariga
                                                muvofiq, sizning joriy foydalanuvchi darajangiz ushbu faylni yuklash
                                                uchun yetarli huquqlarga ega emas.
                                            </div>
                                        @endif
                                    @else
                                        <p class="text-gray-800 dark:text-gray-200">{{ $value }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            {{-- Response Section --}}
            @if (isset($arizaga_javob))
                <div
                    class="mt-8 bg-white rounded-xl shadow-sm border border-indigo-100 dark:bg-gray-800 dark:border-indigo-900 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                    Arizaga javob
                                </h3>
                                <div class="bg-indigo-50 dark:bg-indigo-900/30 rounded-lg p-6 relative">
                                    <svg class="absolute top-0 left-0 w-4 h-4 text-indigo-400 -translate-x-6 -translate-y-2"
                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                        viewBox="0 0 18 14">
                                        <path
                                            d="M6 0H2a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h4v1a3 3 0 0 1-3 3H2a1 1 0 0 0 0 2h1a5.006 5.006 0 0 0 5-5V2a2 2 0 0 0-2-2Z" />
                                    </svg>
                                    <p class="text-gray-800 dark:text-gray-200 text-lg">
                                        "{{ $arizaga_javob }}"
                                    </p>
                                    <svg class="absolute bottom-0 right-0 w-4 h-4 text-indigo-400 translate-x-6 translate-y-2"
                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                        viewBox="0 0 18 14">
                                        <path
                                            d="M12 14h4a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-4v-1a3 3 0 0 1 3-3h1a1 1 0 0 0 0-2h-1a5.006 5.006 0 0 0-5 5v7a2 2 0 0 0 2 2Z" />
                                    </svg>
                                </div>
                                <div class="mt-4 flex items-center text-sm text-gray-500 dark:text-gray-400">
                                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Tekshirilgan sana: {{ $tekshirilgan_sana->format('d.m.Y H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div
                    class="mt-8 bg-white rounded-xl shadow-sm border border-gray-100 dark:bg-gray-800 dark:border-gray-700 p-6">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                Arizaga javob
                            </h3>
                            <p class="text-gray-500 dark:text-gray-400">
                                Tekshiruvchi tomonidan xabar yozilmagan
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        @endif
    @endforeach
</div>

<style>
    /* Modern Scrollbar Styling */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    /* Dark mode scrollbar */
    @media (prefers-color-scheme: dark) {
        ::-webkit-scrollbar-track {
            background: #1f2937;
        }

        ::-webkit-scrollbar-thumb {
            background: #4b5563;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }
    }

    /* Modal Styling */
    .modal-content {
        @apply bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700;
    }

    .modal-header {
        @apply flex justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700;
    }

    .modal-title {
        @apply text-lg font-semibold text-gray-900 dark:text-white;
    }

    .modal-body {
        @apply p-4 max-h-[70vh] overflow-y-auto;
    }

    .modal-footer {
        @apply flex justify-end p-4 border-t border-gray-200 dark:border-gray-700 gap-2;
    }

    .modal-open {
        @apply overflow-hidden;
    }
</style>
