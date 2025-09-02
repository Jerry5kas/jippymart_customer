<!-- Navbar -->
<header class="w-full text-sm bg-gradient-to-b from-purple-100 to-white"
        x-data="{ mobileMenu: false, cartOpen: false }">

    <div class="sm:w-[90%] mx-auto w-full px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">

            <!-- Left section -->
            <div class="flex items-center space-x-4">
                <a href="/mart" class="text-2xl font-extrabold text-purple-600">Mart</a>

                <!-- Location -->
                <button class="hidden md:flex items-center space-x-1 font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-4 text-gray-600 font-semibold">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                    </svg>
                    <span class="font-semibold text-gray-600">Select Location</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-600" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
            </div>

            <div x-data="{ openFilter: false }" class="relative">

                <!-- Filter Button -->
                <button @click="openFilter = !openFilter"
                        class="flex items-center gap-2 px-4 py-2 bg-violet-600 text-white rounded-full shadow-md hover:bg-violet-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L15 14.414V20a1 1 0 01-1.447.894l-4-2A1 1 0 019 18v-3.586L3.293 6.707A1 1 0 013 6V4z"/>
                    </svg>
                    Filters
                </button>

                <!-- Dropdown Panel -->
                <div x-show="openFilter" @click.away="openFilter = false"
                     x-transition
                     class="absolute top-12 left-0 w-72 bg-white shadow-2xl rounded-xl border border-gray-200 p-4 space-y-6 z-50">

                    <!-- Category Filter -->
                    <div x-data="{ openCategory: true }">
                        <button @click="openCategory = !openCategory"
                                class="flex justify-between items-center w-full text-left font-semibold">
                            Category
                            <svg :class="{ 'rotate-180': openCategory }" class="w-4 h-4 transition-transform"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="openCategory" x-collapse class="mt-2 space-y-1">
                            <label class="flex items-center space-x-2 text-sm"><input type="checkbox"> <span>Beverages</span></label>
                            <label class="flex items-center space-x-2 text-sm"><input type="checkbox"> <span>Snacks</span></label>
                            <label class="flex items-center space-x-2 text-sm"><input type="checkbox"> <span>Dairy</span></label>
                        </div>
                    </div>

                    <!-- Price Filter -->
                    <div x-data="{ openPrice: false }">
                        <button @click="openPrice = !openPrice"
                                class="flex justify-between items-center w-full text-left font-semibold">
                            Price
                            <svg :class="{ 'rotate-180': openPrice }" class="w-4 h-4 transition-transform"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="openPrice" x-collapse class="mt-2">
                            <input type="range" min="10" max="1000" class="w-full accent-violet-600">
                            <div class="flex justify-between text-xs text-gray-600">
                                <span>₹10</span>
                                <span>₹1000</span>
                            </div>
                        </div>
                    </div>

                    <!-- Brand Filter -->
                    <div x-data="{ openBrand: false }">
                        <button @click="openBrand = !openBrand"
                                class="flex justify-between items-center w-full text-left font-semibold">
                            Brand
                            <svg :class="{ 'rotate-180': openBrand }" class="w-4 h-4 transition-transform"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="openBrand" x-collapse class="mt-2 space-y-1">
                            <label class="flex items-center space-x-2 text-sm"><input type="checkbox"> <span>Nestle</span></label>
                            <label class="flex items-center space-x-2 text-sm"><input type="checkbox"> <span>Amul</span></label>
                            <label class="flex items-center space-x-2 text-sm"><input type="checkbox"> <span>PepsiCo</span></label>
                        </div>
                    </div>

                    <!-- Apply / Reset Buttons -->
                    <div class="flex justify-between pt-3 border-t border-gray-100">
                        <button class="px-3 py-1 text-sm text-gray-600 hover:text-gray-800">Reset</button>
                        <button class="px-4 py-1.5 bg-violet-600 text-white rounded-lg hover:bg-violet-700 text-sm">Apply</button>
                    </div>
                </div>
            </div>


            <!-- Right section -->
            <div class="hidden md:flex items-center space-x-6 text-sm text-gray-600 font-semibold">
                <a href="#" class="flex items-center space-x-1 text-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25"/>
                    </svg>
                    <span>Login</span>
                </a>

                <!-- Cart Trigger -->
                <button @click="cartOpen = true" class="flex items-center space-x-1 text-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/>
                    </svg>
                    <span>Cart</span>
                </button>
            </div>

            <!-- Hamburger for mobile -->
            <div class="md:hidden">
                <button @click="mobileMenu = !mobileMenu" class="text-gray-800 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Slide-in Cart Drawer -->
    <div x-show="cartOpen" class="fixed inset-0 z-50 flex justify-end"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">

        <!-- Background overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-50" @click="cartOpen = false"></div>

        <!-- Cart Panel -->
        <div class="relative bg-white w-full sm:w-[400px] h-full shadow-lg z-50
                    transform transition-transform duration-300 ease-in-out"
             x-show="cartOpen"
             x-transition:enter="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="translate-x-0"
             x-transition:leave-end="translate-x-full">

            <!-- Header -->
            <div class="flex items-center justify-between p-4 border-b">
                <h2 class="text-lg font-semibold">Your Cart</h2>
                <button @click="cartOpen = false" class="text-gray-600 hover:text-black">
                    ✕
                </button>
            </div>

            <!-- Empty Cart State -->
            <div class="flex flex-col items-center justify-center h-full text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="w-16 h-16 mb-4 text-gray-400">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/>
                </svg>
                <p class="mb-4">Your cart is empty</p>
                <button class="px-6 py-2 bg-black text-white rounded-lg">Browse Products</button>
            </div>
        </div>
    </div>
</header>
