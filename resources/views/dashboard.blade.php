<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center sm:text-left">
           Salom {{ ucfirst(strtolower($auth->first_name)) }} {{ __('OTM Ilmiy tadqiqot faoliyatini tashkil etish saytiga hush kelibsiz!') }}
        </h2>
    </x-slot>
    @include('texnik')
    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900 flex flex-col sm:flex-row justify-between">
                    <div class="w-full sm:w-1/2 mb-6 sm:mb-0">
                        @include('dashboard.asosiy-sahifa.card')
                    </div>
                    <div class="w-full sm:w-1/2">
                        @include('dashboard.asosiy-sahifa.forma2')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
