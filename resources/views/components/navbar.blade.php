<!-- Navbar -->
<header class="w-full text-sm bg-gradient-to-b from-purple-100 to-white">
    <div class="sm:w-[90%] mx-auto w-full px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">

            <!-- Left section -->
            <div class="flex items-center space-x-4">
                <!-- Logo -->
                <span class="text-2xl font-extrabold text-purple-600">Mart</span>

                <!-- Super Saver Toggle -->
                {{--                <div class="flex items-center">--}}
                {{--                    <label class="relative inline-flex items-center cursor-pointer">--}}
                {{--                        <input type="checkbox" value="" class="sr-only peer">--}}
                {{--                        <div class="w-28 h-10 bg-gray-200 rounded-full peer peer-checked:bg-purple-600 transition"></div>--}}
                {{--                        <span class="absolute left-1 top-1 bg-white w-8 h-8 rounded-full peer-checked:translate-x-[72px] transition-transform"></span>--}}
                {{--                        <span class="absolute inset-0 flex justify-center items-center text-xs font-bold text-gray-700 peer-checked:text-white">Mart</span>--}}
                {{--                    </label>--}}
                {{--                </div>--}}

                <!-- Location -->
                <button class="hidden md:flex items-center space-x-1 text-gray-900 font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                    </svg>
                    <span>Select Location</span>
                    <!-- Heroicon: Chevron Down -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
            </div>

            <!-- Search bar -->
            <div class="flex-1 mx-6 hidden md:block">
                <div class="relative">
                    <input type="text" placeholder='Search for "cheese slices"'
                           class="w-full border border-gray-300 rounded-lg py-2 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    <!-- Heroicon: Search -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 absolute left-3 top-2.5 text-gray-400"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 18.5a7.5 7.5 0 006.15-3.35z"/>
                    </svg>
                </div>
            </div>

            <!-- Right section -->
            <div class="hidden md:flex items-center space-x-6">
                <!-- Login -->
                <a href="#" class="flex items-center space-x-1 text-gray-800">
                    <!-- Heroicon: User -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25"/>
                    </svg>
                    <span>Login</span>
                </a>
                <!-- Cart -->
                <a href="#" class="flex items-center space-x-1 text-gray-800">
                    <!-- Heroicon: Shopping Cart -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/>
                    </svg>

                    <span>Cart</span>
                </a>
            </div>

            <!-- Hamburger for mobile -->
            <div class="md:hidden">
                <button @click="mobileMenu = !mobileMenu" class="text-gray-800 focus:outline-none">
                    <!-- Heroicon: Menu -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileMenu" class="md:hidden bg-white border-t shadow-md">
        <div class="px-4 py-3 space-y-3">
            <!-- Search -->
            <div class="relative">
                <input type="text" placeholder='Search for "cheese slices"'
                       class="w-full border border-gray-300 rounded-lg py-2 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                <!-- Heroicon: Search -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 absolute left-3 top-2.5 text-gray-400"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 18.5a7.5 7.5 0 006.15-3.35z"/>
                </svg>
            </div>

            <a href="#" class="flex items-center space-x-2 text-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                </svg>
                <span>Select Location</span>

            </a>
            <a href="#" class="flex items-center space-x-2 text-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25"/>
                </svg>
                <span>Login</span>
            </a>
            <a href="#" class="flex items-center space-x-2 text-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/>
                </svg>
                <span>Cart</span>
            </a>
        </div>
    </div>
</header>
