<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("Men yuborgan ma'lumotlar ro'yxati") }}
        </h2>

    </x-slot>



    <div class="py-1 mt-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

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
                    <h3 class="text-lg font-medium">Siz yuborgan ma'lumotlarni to'liq ro'yxatini shu sahifadan topishingiz mumkin!</h3>
                </div>
                
                
            </div>


        </div>
        <form class="mb-6" action="{{ route('murojatlar.list') }}" method="get">
            <div class="max-w-8xl mx-auto sm:px-1 lg:px-1">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-1 bg-white border-b border-gray-200">
                        <h3 class="mb-5 ml-4 mt-8 text-lg font-medium text-gray-900 dark:text-white">Aniqroq qidirish
                            uchun
                            tanlang:</h3>
                        <div class="flex justify-center mt-4 mx-4">

                            <ul class="grid w-full gap-6 md:grid-cols-4">
                                <li>
                                    <input type="radio" id="category1" name="category" value="all" class="hidden peer"
                                        required checked @if($form_info['category']==='all' ) checked @endif />
                                    <label for="category1"
                                        class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                                        <div class="block">
                                            <div class="w-full text-lg font-semibold"> Barcha ma'lumotlar -
                                                <span
                                                class="inline-flex items-center justify-center w-auto h-4 ms-2 text-xs font-semibold text-blue-800 bg-blue-200 rounded-full">
                                                {{$filter->count()}}
                                            </span>    
                                            </div>
                                            <div class="w-full"> Barcha kelgan ma'lumotlar</div>
                                        </div>
                                        <svg class="w-5 h-5 ms-3 rtl:rotate-180" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                                        </svg>
                                    </label>
                                </li>
                                <li>
                                    <input type="radio" id="category2" name="category" value="must_be_confirmed"
                                        class="hidden peer" @if($form_info['category']==='must_be_confirmed' ) checked
                                        @endif>
                                    <label for="category2"
                                        class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-indigo-600 peer-checked:text-indigo-600 hover:text-indigo-600 hover:bg-indigo-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                                        <div class="block">
                                            <div class="w-full text-lg font-semibold">Tasdiqlash kerak - 
                                                <span
                                                class="inline-flex items-center justify-center w-auto h-4 ms-2 text-xs font-semibold text-indigo-800 bg-indigo-200 rounded-full">
                                                {{$filter->where('ariza_holati', 'kutulmoqda')->count()}}
                                            </span>
                                            </div>
                                            <div class="w-full">Tasdiqlash lozim bo'lganlar</div>
                                        </div>
                                        <svg class="w-5 h-5 ms-3 rtl:rotate-180" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                                        </svg>
                                    </label>
                                </li>
                                <li>
                                    <input type="radio" id="category3" name="category" value="approved"
                                        class="hidden peer" @if($form_info['category']==='approved' ) checked @endif>
                                    <label for="category3"
                                        class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-green-600 peer-checked:text-green-600 hover:text-green-600 hover:bg-green-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                                        <div class="block">
                                            <div class="w-full text-lg font-semibold"> Tasdiqlangan - 
                                                <span
                                                class="inline-flex items-center justify-center w-auto h-4 ms-2 text-xs font-semibold text-green-800 bg-green-200 rounded-full">
                                                {{$filter->where('ariza_holati', 'maqullandi')->count()}}
                                            </span>
                                            </div>
                                            <div class="w-full">Tasdiqlangan barcha ma'lumotlar</div>
                                        </div>
                                        <svg class="w-5 h-5 ms-3 rtl:rotate-180" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                                        </svg>
                                    </label>
                                </li>
                                <li>
                                    <input type="radio" id="category4" name="category" value="rejected"
                                        class="hidden peer" @if($form_info['category']==='rejected' ) checked @endif>
                                    <label for="category4"
                                        class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-red-600 peer-checked:text-red-600 hover:text-red-600 hover:bg-red-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                                        <div class="block">
                                            <div class="w-full text-lg font-semibold">Rad etilgan - 
                                                <span
                                                class="inline-flex items-center justify-center w-auto h-4 ms-2 text-xs font-semibold text-red-800 bg-red-200 rounded-full">
                                                {{$filter->where('ariza_holati', 'rad_etildi')->count()}}
                                            </span>
                                            </div>
                                            <div class="w-full">Rad etilgan ma'lumotlar</div>
                                        </div>
                                        <svg class="w-5 h-5 ms-3 rtl:rotate-180" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                                        </svg>
                                    </label>
                                </li>
                            </ul>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                            <div class="p-6 text-gray-900 mb-8">

                                <label for="default-search"
                                    class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Ism sharif
                                    bilan qidirish</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                        </svg>
                                    </div>
                                    <div class="flex justify-between">
                                        <input type="search" name="name" id="default-search" @if($form_info['name'])
                                            value="{{$form_info['name']}}" @endif
                                            class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Kordinator, Moderator yoki Operator ism familyasini yozing..."
                                            style="width: 500px">



                                        <select id="default" name="sort" style="width: 200px;"
                                            class=" ml-4 bg-gray-50 border border-gray-300 text-gray-900 w-6 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">

                                            <option value="asc" @if($form_info['sort']==='asc' ) selected @endif>
                                                Birinchi yuborilgan</option>
                                            <option value="desc" @if($form_info['sort']==='desc' ) selected @endif>
                                                Ohirgi yuborilgan</option>

                                        </select>

                                        <div date-rangepicker class="flex items-center ml-4 mr-6">
                                            <div class="relative">
                                                <div
                                                    class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                                    </svg>
                                                </div>

                                                <script>
                                                    $('#start_data').change(function() {
                                                    var originalDate = $(this).val(); // Get the date from the input
                                                    var formattedDate = moment(originalDate, "MM/DD/YYYY").format("YYYY-MM-DD"); // Convert to desired format
                                                    $(this).val(formattedDate); // Update the input value with the formatted date
                                                    });
                                                </script>
                                           
                                                <input name="start_data" type="text" disabled
                                                  
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-md rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                    placeholder="Boshlanish">
                                            </div>
                                            <span class="mx-4 text-gray-500">gacha</span>
                                            <div class="relative">
                                                <div
                                                    class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                                    </svg>
                                                </div>
                                                <input name="end_data" type="text" disabled

                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-md rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                    placeholder="Tugash">
                                            </div>
                                        </div>

                                 

                                        <button type="submit"
                                            class="text-white  end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Qidirish</button>
                                    </div>



                                </div>
                            </div>
                        </div>


        </form>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

            <div class="p-6 text-gray-900 mb-8">

                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    @if (count($murojatlar) > 0)
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="p-4">
                                    <div class="flex items-center">
                                        №
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    F.I.SH
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Yo'nalish
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Ma'lumot holati
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Berilgan ball
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Vaqti
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Bajarish
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $i = 1;
                            @endphp
                            @foreach ($murojatlar as $item)
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4">
                                    <div class="flex items-center font-bold">
                                        {{ $i++ }}
                                    </div>
                                </td>
                                <th scope="row"
                                    class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                    <img class="hidden sm:block w-10 h-10 rounded-full" style="object-fit: cover;"
                                        src="{{ $item->surat }}" alt="">
                                    <div class="ps-3" style="    width: 300px;">
                                        <div class="text-base font-semibold"
                                            style="max-width: 260px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            {{ $item->name }}
                                        </div>
                                        <div class="font-normal text-gray-500">
                                            @if ($item->professor_id)
                                            Kordinator
                                            @elseif($item->moderator_id)
                                            Moderator
                                            @else
                                            Operator
                                            @endif
                                        </div>
                                    </div>
                                </th>

                                <td class="px-6 py-4">
                                    {{ $item->category_name }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if ($item->ariza_holati == 'maqullandi')
                                        <div class="h-2.5 w-2.5 rounded-full bg-green-500 me-2">
                                        </div>
                                        Maqullangan!
                                        @elseif($item->ariza_holati == 'kutulmoqda')
                                        <div class="h-2.5 w-2.5 rounded-full bg-indigo-500 me-2">
                                        </div>
                                        Ko'rib chiqish lozim!
                                        @else
                                        <div class="h-2.5 w-2.5 rounded-full bg-red-500 me-2">
                                        </div>
                                        Rad etilgan
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($item->ariza_holati == 'maqullandi')
                                    <span
                                        class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">{{
                                        $item->points }}
                                        - ball</span>
                                    @elseif($item->ariza_holati == 'rad_etildi')
                                    <span
                                        class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Hisoblanmadi!</span>
                                    @else
                                    <span
                                        class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">Baholanmagan!</span>
                                    @endif

                                </td>
                                <td class="px-6 py-4">
                                    {{ $item->created_at }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('murojatlar.show', $item->id) }}"
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Ko'rish</a>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                    @else
                    <h1 class="text-center text-xl font-medium mb-4 mt-2 text-gray-400">
                        Murojaatlar kelib tushmagan!</h1>
                    @include('reyting.frontend.frogments.skeletonTable')
                    @endif

                </div>
                <div class=" items-center justify-between mt-6">
                    {{ $murojatlar->appends(['category' => $form_info['category'], 'sort' => $form_info['sort'], 'name'
                    => $form_info['name']])->links() }}
                </div>
            </div>

        </div>
    </div>
    </div>
    </div>
    </div>

</x-app-layout>