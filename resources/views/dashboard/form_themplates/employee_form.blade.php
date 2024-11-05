<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @if(isset($oldData))
                Ma'lumotni tahrirlash
            @else
                Ma'lumot bo'limini tanlang
            @endif
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Notifications --}}
            <div class="mb-4 space-y-4">
                @if (session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-r" role="alert">
                        <p class="font-bold">Xatolik!</p>
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-r" role="alert">
                        <p class="font-bold">Muvaffaqiyatli!</p>
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-r" role="alert">
                        <p class="font-bold">Xatolar yuz berdi!</p>
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Header with back button and table name --}}
                    <div class="flex justify-between items-center mb-6">
                        <a href="{{ route('dashboard.employee_form_chose') }}"
                           class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Orqaga qaytish
                        </a>
                        <span class="px-4 py-2 bg-indigo-100 text-indigo-800 rounded-md font-bold">
                            {{ $tableName }}
                        </span>
                    </div>

                    {{-- Info Alert --}}
                    <div class="max-w-3xl mx-auto mb-8">
                        <div class="flex p-4 text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 mt-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <div class="text-red-600 mb-3 font-medium">
                                    Ma'lumotlarni to'g'ri kiriting! Agarda ma'lumotlar tog'ri kiritilmasa u rad etilishi mumkin.
                                </div>
                                <h1 class="text-lg font-semibold">{{$title}}</h1>
                            </div>
                        </div>
                    </div>

                    {{-- Form --}}
                    <form class="max-w-3xl mx-auto space-y-6" method="POST"
                          action="{{ route('dashboard.employee_store_form', ['tableName' => $tableName]) }}"
                          enctype="multipart/form-data">
                        @csrf

                        @if(request()->has('edit'))
                        <input type="hidden" name="edit" value="{{ request()->query('edit') }}">
                         @endif

                        @foreach ($fields as $field)
                            <div class="space-y-2">
                                <label for="{{ $field['name'] }}"
                                       class="block text-sm font-medium text-gray-700">
                                    {{ $field['label'] }}
                                </label>

                                @if ($field['type'] === 'text')
                                    <input type="text"
                                           id="{{ $field['name'] }}"
                                           name="{{ $field['name'] }}"
                                           value="{{ $oldData->{$field['name']} ?? old($field['name']) }}"
                                           required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition duration-150 ease-in-out"/>
                                @elseif($field['type'] === 'file')
                                    <div class="flex flex-col space-y-2">
                                        <input type="file"
                                               id="{{ $field['name'] }}"
                                               name="{{ $field['name'] }}"
                                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                               {{ !isset($oldData) ? 'required' : '' }}/>

                                        @if(isset($oldData) && $oldData->{$field['name']})
                                            <div class="text-sm text-gray-500 bg-gray-50 p-2 rounded-md flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                Mavjud fayl: {{ basename($oldData->{$field['name']}) }}
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach

                        {{-- Year Selection --}}
                        <div class="space-y-2">
                            <label for="year" class="block text-sm font-medium text-gray-700">
                                Ushbu ma'lumotlar qaysi yilda yaratilgan yoki tegishli bo'lishi mumkin:
                            </label>
                            <select id="year"
                                    name="year"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @for ($year = 2015; $year <= date('Y'); $year++)
                                    <option value="{{ $year }}" {{ (isset($oldData) && $oldData->year == $year) ? 'selected' : '' }}>
                                        {{ $year }} yil
                                    </option>
                                @endfor
                            </select>
                        </div>

                        {{-- Confirmation Checkbox --}}
                        <div class="flex items-center space-x-2">
                            <input type="checkbox"
                                   id="terms"
                                   required
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"/>
                            <label for="terms" class="text-sm text-gray-700">
                                Barcha ma'lumotlar to'g'riligiga ishonaman
                            </label>
                        </div>

                        {{-- Submit Button --}}
                        <div class="flex justify-end pt-4">
                            <button type="submit"
                                    id="submitBtn"
                                    disabled
                                    class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-150 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-indigo-600">
                                {{ isset($oldData) ? "Ma'lumotni yangilash" : "Ma'lumotni yuborish" }}
                            </button>
                        </div>
                    </form>

                    <script>
                        document.getElementById('terms').addEventListener('change', function() {
                            document.getElementById('submitBtn').disabled = !this.checked;
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
