<x-layouts.app>

    <div
        class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 font-semibold text-xs text-gray-500 inline-flex items-center text-gray-700 gap-x-3">
        <span>Home</span>
        <span>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                 stroke="currentColor" class="size-4 ">
              <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
            </svg>
        </span>
        <span>Groceries</span>
        <span>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                 stroke="currentColor" class="size-4 ">
              <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
            </svg>
        </span>
        <span class="text-violet-700 font-semibold">Top Picks</span>
    </div>
    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <x-layouts.sidebar/>




        <!-- Main content -->
        <div class="flex-1 overflow-y-auto">
            <!-- Top bar -->
            <header class="flex items-center justify-between p-4 bg-white border-b shadow-md">
                <h1 class="text-xl font-bold text-gray-800">Groceries</h1>
                <button class="md:hidden px-3 py-2 border rounded-lg" @click="sidebarOpen = true">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75"/>
                    </svg>
                </button>
                <div x-data="{ openFilter: false }" class="relative ">

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
            </header>




            <!-- Products -->
            <main class="p-4">
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 gap-y-8">

                    <!-- Card Component -->
                    <div class="w-full flex-shrink-0">
                        <div class="bg-white rounded-2xl flex flex-col gap-y-1" x-data="{ added: false }">
                            <x-mart.product-item-card />
                        </div>
                    </div>

                    <!-- duplicate card for demo -->
                    <template x-for="i in 8">
                        <div class="w-full flex-shrink-0">
                            <div class="bg-white rounded-2xl flex flex-col gap-y-1" x-data="{ added: false }">
                                <x-mart.product-item-card />
                            </div>
                        </div>
                    </template>

                </div>
            </main>
        </div>
    </div>
</x-layouts.app>
