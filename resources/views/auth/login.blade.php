<x-guest-layout class="h-screen overflow-hidden">
    <!-- Background Image Container -->
    <div class="fixed inset-0">
        <img
            src="{{ asset('assets/3U9A1507.webp') }}"
            alt="Background"
            class="w-full h-full object-cover"
            id="bgImage"
            onerror="handleImageError(this)"
        />
        <div class="absolute inset-0 bg-gradient-to-br from-blue-900/70 to-blue-700/70"></div>
    </div>

    <div class="relative h-full w-full flex items-center justify-center z-10">
        <!-- Session Status -->
        <x-auth-session-status class="mb-4 z-20" :status="session('status')" />

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative z-20 mb-4" role="alert">
                <strong class="font-bold">Whoops!</strong>
                <span class="block sm:inline">Muommo bor!:</span>
                <ul class="mt-3 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Login Modal -->
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md mx-4 z-20">
            <div class="mb-4 border-b border-gray-200">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" role="tablist">
                    <li class="mr-2 flex-1">
                        <button class="w-full inline-block p-4 border-b-2 rounded-t-lg tab-button active" id="hemis-tab" data-tab="hemis">
                            HEMIS orqali kirish
                        </button>
                    </li>
                    <li class="mr-2 flex-1">
                        <button class="w-full inline-block p-4 border-b-2 rounded-t-lg tab-button" id="login-tab" data-tab="login">
                            Login orqali kirish
                        </button>
                    </li>
                </ul>
            </div>

            <!-- HEMIS Tab Content -->
            <div id="hemis" class="tab-content block opacity-100 transform translate-x-0 transition-all duration-300 ease-in-out">
                <div class="flex flex-col items-center gap-4 mt-3">
                    <!-- HEMIS Logo -->
                    <div class="w-32 h-32 flex items-center justify-center">
                        <img src="https://hemis.cspi.uz/static/crop/5/9/250_250_90_590934569.png" alt="HEMIS Logo" class="w-full h-full object-contain">
                    </div>

                    <a href="{{ route('redirectToAuthorization') }}"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded text-xl w-full text-center transition-colors duration-200">
                        HEMIS orqali kirish
                    </a>
                </div>
            </div>

            <!-- Login Tab Content -->
            <div id="login" class="tab-content hidden opacity-0 transform translate-x-full transition-all duration-300 ease-in-out">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email yoki Login')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                            required autofocus autocomplete="username" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Parol')" />
                        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                            autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="block mt-4">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                            <span class="ms-2 text-sm text-gray-600">{{ __('Ishonchli qurulma') }}</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                href="{{ route('password.request') }}">
                                {{ __('Parolni qayta tiklash') }}
                            </a>
                        @endif

                        <x-primary-button class="ms-3">
                            {{ __('Kirish') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .tab-button {
            border-bottom-color: transparent;
            transition: all 0.3s ease-in-out;
        }
        .tab-button.active {
            border-bottom-color: #3b82f6;
            color: #3b82f6;
        }
        .tab-content {
            transition: all 0.3s ease-in-out;
            position: relative;
        }
    </style>

    <script>
        function showTab(tabId) {
            const tabs = document.querySelectorAll('.tab-content');
            tabs.forEach(tab => {
                if (tab.id === tabId) {
                    tab.classList.remove('hidden', 'opacity-0', 'translate-x-full');
                    tab.classList.add('block', 'opacity-100', 'translate-x-0');
                } else {
                    setTimeout(() => {
                        tab.classList.add('hidden');
                    }, 300); // Match this with CSS transition duration
                    tab.classList.remove('opacity-100', 'translate-x-0');
                    tab.classList.add('opacity-0', 'translate-x-full');
                }
            });
        }

        function handleImageError(img) {
            console.error('Image failed to load:', img.src);
            img.style.display = 'none';
            console.log('Image path:', img.src);
            console.log('Image element:', img);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const bgImage = document.getElementById('bgImage');
            bgImage.onload = function() {
                console.log('Image loaded successfully');
            };

            const tabs = document.querySelectorAll('.tab-button');
            const contents = document.querySelectorAll('.tab-content');

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    tabs.forEach(t => {
                        t.classList.remove('active', 'border-blue-500', 'text-blue-500');
                    });

                    tab.classList.add('active', 'border-blue-500', 'text-blue-500');

                    contents.forEach(content => {
                        content.classList.add('hidden');
                    });

                    const contentId = tab.getAttribute('data-tab');
                    showTab(contentId);
                });
            });
        });
    </script>
</x-guest-layout>
