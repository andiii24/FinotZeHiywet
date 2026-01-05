<nav x-data="{ open: false }" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white border-b border-indigo-800 shadow-lg">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('login') }}" class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <img src="{{ asset('images/logo.jpg') }}"
                                 alt="ፍኖት ዘሕይወት Logo"
                                 class="h-10 w-10 rounded-full object-cover shadow-lg ring-2 ring-white/20">
                        </div>
                        <div class="flex flex-col">
                            <span class="text-lg font-bold text-white leading-tight">ፍኖት ዘሕይወት</span>
                            <span class="text-xs text-indigo-200">መንፈሳዊ ማህበር</span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Login/Register Links -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                <a href="{{ route('login') }}" class="text-white hover:text-indigo-200 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                    Login
                </a>
                <a href="{{ route('register') }}" class="bg-white text-indigo-600 hover:bg-indigo-100 px-4 py-2 rounded-md text-sm font-medium shadow-md transition-all duration-200 hover:scale-105">
                    Register
                </a>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-indigo-200 hover:bg-indigo-700 focus:outline-none focus:bg-indigo-700 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-indigo-700">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('login') }}" class="block px-4 py-2 text-white hover:bg-indigo-800">
                Login
            </a>
            <a href="{{ route('register') }}" class="block px-4 py-2 text-white hover:bg-indigo-800">
                Register
            </a>
        </div>
    </div>
</nav>
