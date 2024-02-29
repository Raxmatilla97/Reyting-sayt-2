@push('styles')
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">

    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
@endpush
<!-- component -->




<div class="max-w-xl w-full space-y-8 p-10 border border-gray-200 rounded-lg shadow z-10">
    <div class="grid  gap-8 grid-cols-1">
        <div class="flex flex-col ">
            <div class="flex flex-col sm:flex-row items-center">
                <h2 class="font-semibold text-lg mr-auto">Reyting uchun ma'lumot joylash uchun kerakli yo'nalishni tanlang</h2>
                <div class="w-full sm:w-auto sm:ml-auto mt-3 sm:mt-0"></div>
            </div>
            <div class="flex items-center p-4 mt-4 mb-4 text-sm text-blue-800 border border-blue-300 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-800" role="alert">
                <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                </svg>
                <span class="sr-only">Info</span>
                <div>
                  <span class="font-medium">Etibor bering!</span>Ma'lumot yuklashdan oldin uning yo'nalishini belgilang.
                </div>
              </div>
            <div class="mt-2">
                <div class="form mb-12">

                    <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ma'lumot yuborish yo'nalishi</label>                  
                    <select id="countries" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option selected>Yo'nalishni tanlang</option>
                        @foreach($categoryes as $category)                     
                      <option value="{{$category->id}}">{{$category->name}}</option>
                     
                      @endforeach
                    </select>
                    <div class="mt-8 text-right md:space-x-3 md:block flex flex-col-reverse">
                      
                        <button type="submit"
                            class="mb-2 md:mb-0 bg-green-400 px-5 py-2 text-sm shadow-sm font-medium tracking-wider text-white rounded-full hover:shadow-lg hover:bg-green-500">Yuborish</button>
                    </div>


                </div>
            </div>
        </div>
    </div>
