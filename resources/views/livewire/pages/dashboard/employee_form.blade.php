<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ma'lumot bo'limini tanlang
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 col columns-1 text-center">
                    @foreach ($jadvallar_codlari as $key => $volume)
                    <button type="button" class="py-2.5 px-5 me-2 mb-4 text-md font-medium text-gray-900 focus:outline-none bg-white rounded-full border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">{{$volume}}</button>
                        
                    @endforeach
                  
                </div>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 ">
                   
<div id="detailed-pricing" class="w-full overflow-x-auto">
    <div class="overflow-hidden min-w-max">
        <div class="grid grid-cols-2 p-4 text-sm font-medium text-gray-900 bg-gray-100 border-t border-b border-gray-200 gap-x-16 dark:bg-gray-800 dark:border-gray-700 dark:text-white">
            <div class="flex items-start">CODE</div>
            <div>Yo'nalish nomi</div>
            
        </div>
        @foreach ($jadvallar_codlari as $key => $volume)
        <div class="grid grid-cols-2 px-4 py-5 text-sm text-gray-700 border-b border-gray-200 gap-x-16 dark:border-gray-700">
            <div class="text-gray-500 dark:text-gray-400">{{$key}}</div>
            <div>
                <button type="button" class="py-2.5 px-5 me-2 mb-4 text-md font-medium text-gray-900 focus:outline-none bg-white rounded-full border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">{{$volume}}</button>

            </div>
          
        </div>
        @endforeach
      
    </div>
</div>

                  
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
