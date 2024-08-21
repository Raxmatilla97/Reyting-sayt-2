<div class="w-full max-w-md bg-white border border-gray-200 rounded-lg shadow ">
    <div class="flex justify-end px-4 pt-8">


    </div>
    <div class="flex flex-col items-center pb-3">
        <img class="w-24 h-24 mb-3 rounded-full shadow-lg"
            src="{{ $auth->image ? asset('storage/users/image/' . $auth->image) : 'https://www.svgrepo.com/show/192244/man-user.svg' }}"
            alt="User image" />

        <h5 class="mb-1 text-xl font-medium text-gray-900 ">
            {{ $auth->second_name ?? 'Kuzatuvchi' }} {{ $auth->first_name ?? '' }}
        </h5>

        <span class="text-sm text-gray-500">#ID {{ $auth->employee_id_number ?? 'N/A' }}</span>


    </div>

    <div class=" w-full bg-white rounded-lg  px-4 pt-1">

        @include('frogments.birinchi_chart')
    </div>

</div>
