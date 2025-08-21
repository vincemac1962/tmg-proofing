<!--suppress JSDeprecatedSymbols -->
<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex items-center">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <! -- Customers Dropdown -->
                    <x-dropdown align="left" width="48" :active="request()->routeIs('customers.*')">
                        <x-slot name="trigger">
                            <span class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-50 hover:text-gray-700 dark:hover:text-white hover:border-gray-300 dark:hover:border-gray-700 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out">
                                {{ __('Customers') }}</span>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('customers.index')">
                                {{ __('View Customers') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('customers.create')">
                                {{ __('Create Customers') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                    <!-- Reminders Dropdown -->
                    <x-dropdown align="left" width="48" :active="request()->routeIs('reminders.*')">
                        <x-slot name="trigger">
                            <span class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-50 hover:text-gray-700 dark:hover:text-white hover:border-gray-300 dark:hover:border-gray-700 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out">
                                {{ __('Reminders') }}
                            </span>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('reminders.index')">
                                {{ __('View Due Reminders') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('reminders.history')">
                                {{ __('View Reminder History') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                    <!-- Deeming Dropdown -->
                    <x-dropdown align="left" width="48" :active="request()->routeIs('deemable_jobs.index.*')">
                        <x-slot name="trigger">
                            <span class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-50 hover:text-gray-700 dark:hover:text-white hover:border-gray-300 dark:hover:border-gray-700 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out">
                                {{ __('Deemable Jobs') }}
                            </span>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('deemable_jobs.index')">
                                {{ __('View Deemable Jobs') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('deemable_jobs.show_deemed')">
                                {{ __('Show Prev. Deemed Jobs') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                    <!-- Users Dropdown -->
                    <x-dropdown align="left" width="48" :active="request()->routeIs('users.*')">
                        <x-slot name="trigger">
                           <span class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-50 hover:text-gray-700 dark:hover:text-white hover:border-gray-300 dark:hover:border-gray-700 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out">
                                {{ __('Users') }}
                            </span>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('users.index')">
                                {{ __('View Users') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('users.create')">
                                {{ __('Create Users') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                    <!-- Designers Dropdown -->
                    <x-dropdown align="left" width="48" :active="request()->routeIs('designers.*')">
                        <x-slot name="trigger">
                            <span class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-50 hover:text-gray-700 dark:hover:text-white hover:border-gray-300 dark:hover:border-gray-700 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out">
                                {{ __('Designers') }}
                            </span>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('designers.index')">
                                {{ __('View Designers') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('designers.create')">
                                {{ __('Add Designer') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                    <!-- Proofing Companies Dropdown -->
                    <x-dropdown align="left" width="48" :active="request()->routeIs('customers.*')">
                        <x-slot name="trigger">
                            <span class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-50 hover:text-gray-700 dark:hover:text-white hover:border-gray-300 dark:hover:border-gray-700 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out">
                                {{ __('Companies') }}
                            </span>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('proofing_companies.index')">
                                {{ __('View Companies') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('proofing_companies.create')">
                                {{ __('Create Company') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                    <x-dropdown align="left" width="48" :active="request()->routeIs('reports.*')">
                        <x-slot name="trigger">
                            <span class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-50 hover:text-gray-700 dark:hover:text-white hover:border-gray-300 dark:hover:border-gray-700 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out">
                                {{ __('Reporting') }}
                            </span>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('reports.index')">
                                {{ __('View Reports Index') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                    <x-dropdown align="left" width="48" :active="request()->routeIs('reports-maintenance.*')">
                        <x-slot name="trigger">
                            <span class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-50 hover:text-gray-700 dark:hover:text-white hover:border-gray-300 dark:hover:border-gray-700 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out">
                                {{ __('Reports Maintenance') }}
                            </span>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('reports-maintenance.index')">
                                {{ __('View Reports') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>

                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                            @if(Auth::check())
                                <div>{{ Auth::user()->name }}</div>
                            @else
                                <div>Guest</div>
                            @endif
                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Theme Toggle -->
                        <button id="themeToggle" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            Toggle Theme
                        </button>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                             onclick="event.preventDefault();
                            this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')">
                {{ __('View Users') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('users.create')" :active="request()->routeIs('users.create')">
                {{ __('Create Users') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                                           onclick="event.preventDefault();
                                                this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>