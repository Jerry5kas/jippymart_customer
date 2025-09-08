<x-layouts.app>

    <div
        class="pt-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 font-semibold text-xs text-gray-500 inline-flex items-center text-gray-700 gap-x-3">
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
                <div class="inline-flex items-center gap-x-2">
                    <x-mart.filter/>
                    <button class="md:hidden px-3 py-2 border rounded-lg" @click="sidebarOpen = true">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75"/>
                        </svg>
                    </button>
                </div>
            </header>


            <!-- Products -->
            <main class="p-4">
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 gap-y-8">

                    <!-- Card Component -->
                    <div class="w-full flex-shrink-0">
                        <div class="bg-white rounded-2xl flex flex-col gap-y-1" x-data="{ added: false }">
                            <x-mart.product-item-card/>
                        </div>
                    </div>

                    <!-- duplicate card for demo -->
                    <template x-for="i in 8">
                        <div class="w-full flex-shrink-0">
                            <div class="bg-white rounded-2xl flex flex-col gap-y-1" x-data="{ added: false }">
                                <x-mart.product-item-card/>
                            </div>
                        </div>
                    </template>

                </div>
            </main>
        </div>
    </div>
</x-layouts.app>
