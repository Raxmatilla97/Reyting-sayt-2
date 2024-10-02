
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __("Profil ma'lumotlari") }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Akkountingiz ma'lumotlarini va elektron pochta manzilini yangilashingiz mumkin.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="image" :value="__('Profil rasmi')" />
            <input id="image" name="image" type="file" class="mt-1 block w-full" accept="image/*" />
            <x-input-error class="mt-2" :messages="$errors->get('image')" />
        </div>

        @if ($user->image)
        <div class="flex justify-center items-center mb-4">
            <div class="w-24 h-24 rounded-full overflow-hidden">
                <img src="{{ $user->image ? asset('storage/users/image/' . $user->image) : 'https://www.svgrepo.com/show/192244/man-user.svg' }}"
                    alt="Profil rasmi" class="w-full h-full object-cover">
            </div>
        </div>
        @endif


        <div>
            <x-input-label for="first_name" :value="__('Ismingiz')" />
            <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" :value="old('first_name', $user->first_name)"
                required autofocus autocomplete="first_name" />
            <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
        </div>

        <div>
            <x-input-label for="second_name" :value="__('Familyangiz')" />
            <x-text-input id="second_name" name="second_name" type="text" class="mt-1 block w-full" :value="old('second_name', $user->second_name)"
                required autofocus autocomplete="second_name" />
            <x-input-error class="mt-2" :messages="$errors->get('second_name')" />
        </div>

        <div>
            <x-input-label for="third_name" :value="__('Sharifingiz')" />
            <x-text-input id="third_name" name="third_name" type="text" class="mt-1 block w-full" :value="old('third_name', $user->third_name)"
                required autofocus autocomplete="third_name" />
            <x-input-error class="mt-2" :messages="$errors->get('third_name')" />
        </div>
        <hr>
        <div>
            <x-input-label for="name" :value="__('Kirish loginingiz')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)"
                required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email manzilingiz')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Email manzilingiz tasdiqlanmagan.') }}

                        <button form="send-verification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Tasdiqlash xatini qayta yuborish uchun shu yerni bosing.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('E-pochta manzilingizga yangi tasdiqlash havolasi yuborildi.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Saqlash') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">{{ __('Saqlandi.') }}</p>
            @endif
        </div>
    </form>
</section>



