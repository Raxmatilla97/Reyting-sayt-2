<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __("Kafedrani o'zgartirish") }}
        </h2>
    </header>

    @if (session('status') === 'department-updated')
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">Muvaffaqiyat!</strong>
        <span class="block sm:inline">Kafedrangiz muvaffaqiyatli yangilandi.</span>
    </div>
@endif

    <form method="post" action="{{ route('profile.update.department') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <div class="flex items-center p-4 mb-4 mt-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
                <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                </svg>
                <span class="sr-only">Info</span>
                <div>
                  <span class="font-medium">Diqqat!</span> Agar boshqa kafedraga o'tishni istasangiz ro'yxatdagi biron kafedrani tanlang. (Diqqat barcha siz yuklagan ma'lumotlar va ularga berilgan ballar ham ko'chib o'tadi!)
                </div>
              </div>
            <select id="department" name="department_id" class="mt-1 block w-full">
                @foreach($departments as $department)
                    <option value="{{ $department->id }}" {{ $user->department_id == $department->id ? 'selected' : '' }}>
                        {{ $department->name }}
                    </option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('department_id')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __("O'zgartirish") }}</x-primary-button>
        </div>
    </form>
</section>
