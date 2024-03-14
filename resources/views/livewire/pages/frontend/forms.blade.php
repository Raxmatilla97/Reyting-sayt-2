<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ma'lumot bo'limini tanlang
        </h2>
    </x-slot>



<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
              <div class="flex justify-between">
                <a href="{{route('dashboard.employee_form_chose')}}">
                  <button type="button" class="py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Orqaga qaytish</button>
                    
                  </a>
                    <span class="bg-indigo-100 text-indigo-800 text-md font-bold me-2 px-2.5 py-0.5 rounded dark:bg-indigo-900 dark:text-indigo-300" style=" height: 30px;">{{$tableName}}</span>
                        
              </div>
              
                <div class="max-w-3xl mx-auto flex items-center p-4 mb-4 mb-8 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
                    <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                    </svg>
                    <span class="sr-only">Info</span>
                    <div>
                      <span class="font-medium">Ma'lumotlarni to'g'ri kiriting!</span> Agarda ma'lumotlar tog'ri kiritilmasa u rad etilishi mumkin.
                    </div>
                  </div>

                <form class="max-w-3xl mx-auto" method="POST" action="{{ route('data.store', ['tableName' => $tableName]) }}" enctype="multipart/form-data">
                    @csrf
                    @foreach ($fields as $field)
                    <div class="mb-5">
                      <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ $field['label'] }}</label>
                      @if($field['type'] === 'text')
                      <input type="text" id="text" name="{{ $field['name'] }}" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light"/>
                      @elseif($field['type'] === 'file')
                      <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" aria-describedby="user_avatar_help" id="user_avatar" type="file">
                      <div class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="user_avatar_help">A profile picture is useful to confirm your are logged into your account</div>
                      @endif
                    </div>
                    @endforeach

                    <div class="flex items-start mb-5">
                      <div class="flex items-center h-5">
                        <input id="terms" type="checkbox" value="" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800" required />
                      </div>
                      <label for="terms" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Barcha ma'lumotlar to'g'riligiga ishonaman </label>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Register new account</button>

                    </div>
                  </form>
                
                
            </div>
        </div>
    </div>
</div>



  

</x-app-layout>