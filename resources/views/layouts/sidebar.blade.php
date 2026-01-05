<div class="sidebar bg-gradient-to-b from-indigo-800 to-purple-900 text-white w-64 h-screen fixed left-0 top-0 overflow-y-auto z-20 shadow-xl transition-all duration-300 transform -translate-x-full lg:translate-x-0 flex flex-col">
    <div class="flex items-center justify-center h-20 border-b border-indigo-700 bg-gradient-to-r from-indigo-700 to-purple-800">
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
                <img src="{{ asset('images/logo.jpg') }}"
                     alt="ፍኖት ዘሕይወት Logo"
                     class="h-12 w-12 rounded-full object-cover shadow-lg ring-2 ring-white/20">
            </div>
            <div class="flex flex-col">
                <h2 class="text-xl font-bold text-white leading-tight">ፍኖት ዘሕይወት</h2>
                <p class="text-xs text-indigo-200">መንፈሳዊ ማህበር</p>
            </div>
        </div>
    </div>

    <!-- User Profile -->
    <div class="p-4 border-b border-indigo-700 bg-gradient-to-r from-indigo-900/50 to-purple-900/50">
        <div class="flex items-center space-x-3">
            <div class="bg-gradient-to-br from-indigo-500 to-purple-500 rounded-full h-12 w-12 flex items-center justify-center shadow-lg ring-2 ring-white/20">
                <span class="text-lg font-semibold">{{ substr(Auth::user()->name, 0, 1) }}</span>
            </div>
            <div>
                <p class="text-sm font-medium">{{ Auth::user()->name }}</p>
                <div class="flex items-center mt-1">
                    @if(Auth::user()->isAdmin())
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <svg class="mr-1 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3" />
                            </svg>
                            Administrator
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <svg class="mr-1 h-2 w-2 text-blue-400" fill="currentColor" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3" />
                            </svg>
                            Member
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="mt-3 flex items-center text-xs text-indigo-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <a href="{{ route('profile.edit') }}" class="hover:text-white transition-colors">Edit Profile</a>
        </div>
    </div>

    <!-- Navigation Links -->
    <nav class="mt-4 px-2 pb-28">
        <div class="space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-700 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-5 w-5 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" />
                </svg>
                Dashboard
            </a>

            <!-- Group Categories -->
            <a href="{{ route('group_cats.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('group_cats.*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-700 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-5 w-5 {{ request()->routeIs('group_cats.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                </svg>
                Group Categories
            </a>

            <!-- Monthly Payments -->
            <a href="{{ route('monthly-payments.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('monthly-payments.*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-700 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-5 w-5 {{ request()->routeIs('monthly-payments.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" />
                </svg>
                Monthly Payments
            </a>

            <!-- Social Contribution Types -->
            <a href="{{ route('social-contributions.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('social-contribution*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-700 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-5 w-5 {{ request()->routeIs('social-contribution*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                </svg>
                Social Contribution Types
            </a>
            @if(Auth::user()->isAdmin())
                <a href="{{ route('social-contribution-categories.index') }}" class="ml-8 group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('social-contribution-categories.*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-700 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-4 w-4 {{ request()->routeIs('social-contribution-categories.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h4.5a.5.5 0 00.5-.5V15a3 3 0 013-3h3.5a.5.5 0 00.5-.5V5a2 2 0 00-2-2H4z" />
                    </svg>
                    Contribution Categories
                </a>
            @endif

            <!-- Jobs -->
            <a href="{{ route('jobs.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('job*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-700 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-5 w-5 {{ request()->routeIs('job*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd" />
                    <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z" />
                </svg>
                Jobs
            </a>

            <!-- Events -->
            <a href="{{ route('events.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('event*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-700 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-5 w-5 {{ request()->routeIs('event*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                </svg>
                Events
            </a>

            <!-- Tasks -->
            <a href="{{ route('tasks.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('tasks.*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-700 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-5 w-5 {{ request()->routeIs('tasks.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                </svg>
                Tasks
            </a>

            <!-- Planning -->
            <a href="{{ route('plannings.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('plannings.*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-700 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-5 w-5 {{ request()->routeIs('plannings.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                </svg>
                Planning
            </a>
            <a href="{{ route('plannings.dashboard') }}" class="ml-8 group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('plannings.dashboard') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-700 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-4 w-4 {{ request()->routeIs('plannings.dashboard') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z" />
                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z" />
                </svg>
                Planning Dashboard
            </a>

            <!-- Contributions -->
            <a href="{{ route('social-contributors.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('social-contributors.*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-700 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-5 w-5 {{ request()->routeIs('social-contributors.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.366-.446 1.12-.446 1.486 0l6.518 7.943c.43.523.052 1.308-.743 1.308H2.482c-.795 0-1.173-.785-.743-1.308l6.518-7.943zM11 13a1 1 0 10-2 0v2a1 1 0 102 0v-2z" clip-rule="evenodd" />
                </svg>
                Contributions
            </a>
        </div>

        @if(Auth::user()->isAdmin())
            <!-- Admin Section -->
            <div class="mt-8">
                <h3 class="px-3 text-xs font-semibold text-indigo-300 uppercase tracking-wider">
                    Admin Area
                </h3>
                <div class="mt-2 space-y-1">
                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-700 hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-5 w-5 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z" />
                            <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z" />
                        </svg>
                        Admin Dashboard
                    </a>

                    <!-- User Management -->
                    <a href="{{ route('admin.users.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.users.*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-700 hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-5 w-5 {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                        </svg>
                        User Management
                    </a>

                    <!-- Group Management -->
                    <a href="{{ route('admin.group_cats.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.group_cats.*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-700 hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-5 w-5 {{ request()->routeIs('admin.group_cats.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        Group Management
                    </a>

                    <!-- Monthly Payments -->
                    <a href="{{ route('monthly-payments.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('monthly-payments.*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-700 hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-5 w-5 {{ request()->routeIs('monthly-payments.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                        Monthly Payments
                    </a>

                    @if(Auth::user()->isAdmin())
                        <!-- Payment Settings -->
                        <a href="{{ route('admin.monthly-payment-settings.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.monthly-payment-settings.*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-700 hover:text-white' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-5 w-5 {{ request()->routeIs('admin.monthly-payment-settings.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                            </svg>
                            Payment Settings
                        </a>
                    @endif

                    <!-- Job Management -->
                    <a href="{{ route('jobs.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('jobs.*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-700 hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-5 w-5 {{ request()->routeIs('jobs.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd" />
                            <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z" />
                        </svg>
                        Job Management
                    </a>

                    <!-- Event Management -->
                    <a href="{{ route('events.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('events.*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-700 hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-5 w-5 {{ request()->routeIs('events.*') ? 'text-white' : 'text-indigo-300 group-hover:text-white' }}" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                        </svg>
                        Event Management
                    </a>

                    <!-- System Settings -->
                    <a href="{{ route('admin.system-settings.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.system-settings.*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-700 hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-5 w-5 text-indigo-300 group-hover:text-white" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                        </svg>
                        System Settings
                    </a>
                </div>
            </div>
        @endif
    </nav>

    <!-- Logout -->
    <div class="mt-auto p-4 border-t border-indigo-700 w-full bg-gradient-to-r from-indigo-900/70 to-purple-900/70">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center px-3 py-2 text-sm font-medium rounded-md bg-gradient-to-r from-red-500 to-pink-500 text-white hover:from-red-600 hover:to-pink-600 shadow-md transition-all duration-300 transform hover:scale-105">
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V4a1 1 0 00-1-1H3zm10 8a1 1 0 01-1 1H8a1 1 0 01-1-1V8a1 1 0 011-1h4a1 1 0 011 1v3z" clip-rule="evenodd" />
                </svg>
                Logout
            </button>
        </form>
    </div>
</div>
