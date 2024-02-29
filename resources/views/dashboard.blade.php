<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
           Salom {{ ucfirst(strtolower($auth->first_name)) }} {{ __('OTM reyting saytiga hush kelibsiz!') }}


        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 flex justify-between">
                    
                        @include('dashboard.asosiy-sahifa.card')
                  
                  
                    <div>
                        @include('dashboard.asosiy-sahifa.forma2')
                    </div>
                  
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
