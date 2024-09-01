@push('styles')
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">

    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
@endpush
<!-- component -->




<div class="max-w-xxl w-full space-y-8 p-10 border border-gray-200 rounded-lg shadow z-10">
    <div class="grid  gap-8 grid-cols-1">
        <div class="flex flex-col ">
            <div class="flex flex-col sm:flex-row items-center">
                <h2 class="font-semibold text-lg mr-auto">Reyting ma'lumotini joylash uchun kerakli yo'nalishni tanlang</h2>
                <div class="w-full sm:w-auto sm:ml-auto mt-3 sm:mt-0"></div>
            </div>
            <div class="flex items-center p-4 mt-4 mb-4 text-sm text-blue-800 border border-blue-300 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-800" role="alert">
                <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                </svg>
                <span class="sr-only">Info</span>
                <div>
                  <span class="font-medium">Etibor bering!</span> Ma'lumot yuklashdan oldin uning yo'nalishini belgilang.

                </div>
              </div>

              <div class="flex items-center p-4 mt-4 mb-4 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-800" role="alert">
                <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                </svg>
                <span class="sr-only">Info</span>
                <div>
                  <span class="font-medium">Diqqat!</span> Saytdan hozirda faqat kompyuter va noutbuklarda to'liq foydalanish mumkin. Mobil qurilmalar uchun moslashtirish ustida ishlar olib borilmoqda.

                </div>
              </div>

        </div>
        <div class=" flex justify-start w-full ">
            <a href="{{ route('dashboard.employee_form_chose')}}" class="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow md:flex-row md:max-w-xl hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
                <img class="object-cover w-full rounded-t-lg h-96 md:h-auto md:w-48 md:rounded-none md:rounded-s-lg" src="https://mir-s3-cdn-cf.behance.net/project_modules/fs/1fcafd93322439.5e6164ef9bb7f.jpg" alt="">
                <div class="flex flex-col justify-between p-4 leading-normal">
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">O'qituvchilar tomonidan yuboriladigan ma'lumotlar uchun</h5>
                    <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Universitet o'qituvchi hodimlari tomonidan ilmiy yo'nalishdagi ma'lumotlarni yuborishlari uchun</p>
                </div>
            </a>
        </div>
        <div class="mt-2 flex justify-start">
            <a href="{{route('dashboard.department_form_chose')}}" class="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow md:flex-row md:max-w-xl hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
                <img class="object-cover w-full rounded-t-lg h-96 md:h-auto md:w-48 md:rounded-none md:rounded-s-lg" src="https://i.pinimg.com/736x/f4/b2/e9/f4b2e9d31c338e7bc4efd5c83f5af10f.jpg" alt="">
                <div class="flex flex-col justify-between p-4 leading-normal">
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Kafedra ma'lumotlarini yuborish uchun</h5>
                    <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Kafedra ma'lumotlarini yuborish uchun </p>
                </div>
            </a>
        </div>

    </div>

</div>
