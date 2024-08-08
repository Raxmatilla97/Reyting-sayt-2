<div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        @if ($pointUserInformations->count() > 0)
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="p-4">№</th>
                        <th scope="col" class="px-6 py-3">F.I.SH va Boshqa ma'lumotlar</th>
                        <th scope="col" class="px-6 py-3" style="min-width: 150px;">Ma'lumot holati</th>
                        <th scope="col" class="px-6 py-3">Berilgan ball</th>
                        <th scope="col" class="px-6 py-3">Vaqti</th>
                        <th scope="col" class="px-6 py-3">Bajarish</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = 1; @endphp
                    @foreach ($pointUserInformations as $item)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="w-4 p-4">{{ $i++ }}</td>
                            <th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-normal dark:text-white">
                                <img class="hidden sm:block w-10 h-10 rounded-full" style="object-fit: cover;" src="{{ '/storage/users/image/' . $item->employee->image }}" alt="">
                                <div class="ps-3">
                                    <div class="text-base font-semibold">
                                        Maxsus forma kodi:
                                        <span class="bg-indigo-100 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                            {{ ucwords(strtolower($item->employee->FullName)) }}
                                        </span>
                                    </div>
                                    <div class="font-normal text-gray-500 text-xs ">
                                        <span class="bg-blue-100 text-blue-800 text-md font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                            {{ $item->murojaat_codi ?? 'Noma’lum' }}
                                        </span> - {{ $item->murojaat_nomi ?? 'Noma’lum' }}
                                    </div>
                                </div>
                            </th>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if ($item->status == 1)
                                        <div class="h-2.5 w-2.5 rounded-full bg-green-500 me-2"></div>
                                        Maqullangan!
                                    @elseif($item->status == 0)
                                        <div class="h-2.5 w-2.5 rounded-full bg-red-500 me-2"></div>
                                        Rad etilgan
                                    @else
                                        <div class="h-2.5 w-2.5 rounded-full bg-indigo-500 me-2"></div>
                                        Tekshiruvda!
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if ($item->status == 1)
                                    <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">{{ $item->points }} - ball</span>
                                @elseif($item->status == 0)
                                    <span class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Hisoblanmadi!</span>
                                @else
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">Baholanmagan!</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">{{ $item->created_at }}</td>
                            <td class="px-6 py-4">
                                <div class="mr-3 flex-shrink-0 item">
                                    <button wire:click="showDetails({{ $item->id }})" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-sm rounded-lg text-sm px-3 py-1.5 text-center" type="button">
                                        KO'RISH
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <h1 class="text-center text-xl font-medium mb-4 mt-2 text-gray-400">Murojaatlar kelib tushmagan!</h1>
        @endif
    </div>
    <div class="items-center justify-between mt-6">
        {{ $pointUserInformations->links() }}
    </div>

    <div id="default-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50">
        <div class="relative p-4 w-full max-w-3xl h-full md:h-auto">
            <div class="bg-white rounded-lg shadow overflow-y-auto">
                <div class="flex items-center justify-between p-4 border-b rounded-t">
                    <h3 class="text-xl font-semibold text-gray-900">To'liq ma'lumot</h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 flex justify-center items-center" wire:click="closeModal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="modal-body pb-4 pl-4 pr-4 pt-2">
                    @if ($modalContent)
                        {!! $modalContent !!}
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
