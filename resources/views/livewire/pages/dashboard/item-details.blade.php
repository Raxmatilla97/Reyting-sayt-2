<div>
    @if($item)
        <h2>{{ $item->employee->FullName }}</h2>
        <p>Murojaat kodi: {{ $item->murojaat_codi }}</p>
        <p>Murojaat nomi: {{ $item->murojaat_nomi }}</p>
        <p>Ball: {{ $item->points }}</p>
        <p>Holati:
            @if($item->status == 1)
                Maqullangan
            @elseif($item->status == 0)
                Rad etilgan
            @else
                Tekshiruvda
            @endif
        </p>
        <p>Vaqti: {{ $item->created_at }}</p>
        <!-- Boshqa ma'lumotlarni qo'shing -->

        <button wire:click="closeModal" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
            Yopish
        </button>
    @else
        <p>Ma'lumot topilmadi.</p>
    @endif
</div>
