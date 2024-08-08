<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("Men yuborgan ma'lumotlar ro'yxati") }}
        </h2>

    </x-slot>



    <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">

            <div id="alert-additional-content-1"
                class="p-4 mb-4 text-blue-800 border border-blue-300 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-800"
                role="alert">
                <div class="flex items-center">
                    <svg class="flex-shrink-0 w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                    </svg>
                    <span class="sr-only">Info</span>
                    <h3 class="text-lg font-medium">Siz yuborgan ma'lumotlarni to'liq ro'yxatini shu sahifadan
                        topishingiz mumkin!</h3>


                </div>
                <p class="text-right">
                    Umumiy to'plagan ballaringiz: <span
                        class="bg-blue-100 text-blue-800 text-md font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">{{ $totalPoints }}</span>
                </p>

            </div>


        </div>

        {{-- Search joyi --}}
        <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('dashboard.item_list_component')



        </div>
    </div>

</x-app-layout>

