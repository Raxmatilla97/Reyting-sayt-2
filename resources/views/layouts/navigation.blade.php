<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate>
                        <img class="h-10 mx-auto" src="https://otm.cspu.uz/assets/images/logo3.png" alt="Logo">
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex items-center">
                    <!-- Yuborish dropdown -->
                    <div class="relative" x-data="{ isOpen: false }">
                        <button @click="isOpen = !isOpen" @click.away="isOpen = false"
                            class="inline-flex items-center px-3 text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                            :class="{ 'text-blue-500': isOpen || request() - > routeIs(['dashboard.employee_form_chose',
                                    'dashboard.department_form_chose', 'kpi.index'
                                ]) }">
                            {{ __('Yuborish') }}
                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="isOpen"
                            class="absolute z-50 mt-2 w-72 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95">
                            <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1">
                                <!-- O'qituvchilar bo'limi -->
                                <a href="{{ route('dashboard.employee_form_chose') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('dashboard.employee_form_chose') ? 'bg-gray-100' : '' }}">
                                    <div class="font-medium">O'qituvchilar ma'lumotlari</div>
                                    <div class="text-xs text-gray-500">O'qituvchilar tomonidan yuboriladigan ma'lumotlar
                                    </div>
                                </a>

                                <!-- Kafedra bo'limi -->
                                <a href="{{ route('dashboard.department_form_chose') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('dashboard.department_form_chose') ? 'bg-gray-100' : '' }}">
                                    <div class="font-medium">Kafedra ma'lumotlari</div>
                                    <div class="text-xs text-gray-500">Kafedra ma'lumotlarini yuborish</div>
                                </a>

                                <!-- KPI reyting bo'limi -->
                                <a href="{{ route('kpi.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('kpi.index') ? 'bg-gray-100' : '' }}">
                                    <div class="font-medium">KPI reyting</div>
                                    <div class="text-xs text-gray-500">KPI reytingini aniqlash</div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard.faculties')" :active="request()->routeIs('dashboard.faculties')" wire:navigate>
                        {{ __('Fakultetlar') }}
                    </x-nav-link>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard.departments')" :active="request()->routeIs('dashboard.departments')" wire:navigate>
                        {{ __('Kafedralar') }}
                    </x-nav-link>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard.employees')" :active="request()->routeIs('dashboard.employees')" wire:navigate>
                        {{ __('O\'qituvchilar') }}
                    </x-nav-link>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard.my_submitted_information')" :active="request()->routeIs('dashboard.my_submitted_information')" wire:navigate>
                        {{ __('Yuborganlarim') }}
                    </x-nav-link>
                </div>

                @if (Auth::user()->is_admin || Auth::user()->is_kpi_reviewer)
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex items-center"> <!-- items-center qo'shildi -->
                    <div class="relative" x-data="{ isOpen: false }">
                        <button @click="isOpen = !isOpen" @click.away="isOpen = false"
                            class="inline-flex items-center h-full px-3 text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                            :class="{ 'text-blue-500': isOpen || request()->routeIs('murojatlar.*') }">
                            {{ __('Kelib tushgan') }}
                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
             
                        <div x-show="isOpen"
                            class="absolute z-50 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95">
                            <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">
                                @if (Auth::user()->is_admin)
                                    <a href="{{ route('murojatlar.list') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('murojatlar.list') ? 'bg-gray-100' : '' }}">
                                        {{ __('Fakultet va Kafedra reytingi') }}
                                    </a>
                                @endif
             
                                @if (Auth::user()->is_admin || Auth::user()->is_kpi_reviewer)
                                    <a href="{{ route('admin.kpi.index') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.kpi.index') ? 'bg-gray-100' : '' }}">
                                        {{ __("O'qituvchi reytingi") }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
             @endif

                @if (Auth::user()->is_admin)
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex items-center">
                        <div class="relative" x-data="{ isOpen: false }">
                            <button @click="isOpen = !isOpen" @click.away="isOpen = false"
                                class="inline-flex items-center px-3 text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                :class="{ 'text-blue-500': isOpen || request() - > routeIs(['export', 'admin.criteria.*']) }">
                                {{ __('Boshqaruv') }}
                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="isOpen"
                                class="absolute right-0 z-50 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95">
                                <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1">
                                    <!-- Export -->
                                    <a href="{{ route('export') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('export') ? 'bg-gray-100' : '' }}">
                                        {{ __('Eksport va Yangilash') }}
                                    </a>

                                    <!-- KPI Mezonlari -->
                                    <a href="{{ route('admin.criteria.index') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.criteria.*') ? 'bg-gray-100' : '' }}">
                                        {{ __('KPI Mezonlari') }}
                                    </a>

                                    <!-- KPI tekshiruvchilar -->
                                    <a href="{{ route('admin.kpi-reviewers.index') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.kpi-reviewers.*') ? 'bg-gray-100' : '' }}">
                                        {{ __('KPI Tekshiruvchilari') }}
                                    </a>

                                    <!-- Sayt sozlamalari -->
                                    <a href="{{ route('admin.settings') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.settings.*') ? 'bg-gray-100' : '' }}">
                                        {{ __('Sayt sozlamlari') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __("Profil ma'lumotlari") }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Chiqish') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = !open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <!-- Mobil menyu -->
            <x-responsive-nav-link :href="route('dashboard.employee_form_chose')" :active="request()->routeIs('dashboard.employee_form_chose')">
                {{ __("O'qituvchilar ma'lumotlari") }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('dashboard.department_form_chose')" :active="request()->routeIs('dashboard.department_form_chose')">
                {{ __("Kafedra ma'lumotlari") }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('kpi.index')" :active="request()->routeIs('kpi.index')">
                {{ __('KPI reyting') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('dashboard.faculties')" :active="request()->routeIs('dashboard.faculties')" wire:navigate>
                {{ __('Fakultetlar') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('dashboard.departments')" :active="request()->routeIs('dashboard.departments')" wire:navigate>
                {{ __('Kafedralar') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('dashboard.employees')" :active="request()->routeIs('dashboard.employees')" wire:navigate>
                {{ __("O'qituvchilar") }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('dashboard.my_submitted_information')" :active="request()->routeIs('dashboard.my_submitted_information')" wire:navigate>
                {{ __('Yuborganlarim') }}
            </x-responsive-nav-link>

            @if (Auth::user()->is_admin || Auth::user()->is_kpi_reviewer)
                <div class="pt-4 pb-1 border-t border-gray-200">
                    <div class="font-medium text-base text-gray-800 px-4">{{ __('Kelib tushgan') }}</div>

                    @if (Auth::user()->is_admin)
                        <x-responsive-nav-link :href="route('murojatlar.list')" :active="request()->routeIs('murojatlar.list')">
                            {{ __('Fakultet va Kafedra reytingi') }}
                        </x-responsive-nav-link>
                    @endif

                    @if (Auth::user()->is_admin || Auth::user()->is_kpi_reviewer)
                        <x-responsive-nav-link :href="route('admin.kpi.index')" :active="request()->routeIs('admin.kpi.index')">
                            {{ __("O'qituvchi reytingi") }}
                        </x-responsive-nav-link>
                    @endif
                </div>
            @endif

            @if (Auth::user()->is_admin)
                <div class="pt-4 pb-1 border-t border-gray-200">
                    <div class="font-medium text-base text-gray-800 px-4">{{ __('Boshqaruv') }}</div>

                    <x-responsive-nav-link :href="route('export')" :active="request()->routeIs('export')">
                        {{ __('Eksport va Yangilash') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('admin.criteria.index')" :active="request()->routeIs('admin.criteria.*')">
                        {{ __('KPI Mezonlari') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('admin.kpi-reviewers.index')" :active="request()->routeIs('admin.kpi-reviewers.*')">
                        {{ __('KPI Tekshiruvchilari') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('admin.settings')" :active="request()->routeIs('admin.settings.*')">
                        {{ __('Sayt sozlamlari') }}
                    </x-responsive-nav-link>
                </div>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __("Profil ma'lumotlari") }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                       this.closest('form').submit();">
                        {{ __('Chiqish') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
