<div x-data="{ scroll: 0 }" class="md:w-1/2 w-full my-6 px-4">

    <!-- Banner (Fixed) -->
    <div class="h-40 bg-gray-300 flex items-center justify-center rounded-2xl">
        <h1 class="text-2xl font-bold">Main Banner (Fixed)</h1>
    </div>

    <!-- Category Carousel -->
    <div class="relative mt-6 px-4" x-data="{ scroll: 0 }">
        <!-- Left Button -->
        <button
            @click="$refs.scroller.scrollBy({ left: -200, behavior: 'smooth' })"
            class="absolute left-0 top-1/2 -translate-y-1/2 bg-white shadow rounded-full p-2 hidden md:block">
            ◀
        </button>

        <!-- Categories -->
        <div
            x-ref="scroller"
            class="flex space-x-4 overflow-x-auto scrollbar-hide scroll-smooth snap-x snap-mandatory pb-3">

            <!-- Category Item -->
            <div
                class="flex-shrink-0 w-24 h-24 bg-white rounded-xl shadow flex flex-col items-center justify-center snap-center">
                <img src="https://via.placeholder.com/60" alt="Cat 1" class="w-12 h-12 mb-2">
                <span class="text-sm">Fruits</span>
            </div>

            <div
                class="flex-shrink-0 w-24 h-24 bg-white rounded-xl shadow flex flex-col items-center justify-center snap-center">
                <img src="https://via.placeholder.com/60" alt="Cat 2" class="w-12 h-12 mb-2">
                <span class="text-sm">Veggies</span>
            </div>

            <div
                class="flex-shrink-0 w-24 h-24 bg-white rounded-xl shadow flex flex-col items-center justify-center snap-center">
                <img src="https://via.placeholder.com/60" alt="Cat 3" class="w-12 h-12 mb-2">
                <span class="text-sm">Bakery</span>
            </div>

            <div
                class="flex-shrink-0 w-24 h-24 bg-white rounded-xl shadow flex flex-col items-center justify-center snap-center">
                <img src="https://via.placeholder.com/60" alt="Cat 4" class="w-12 h-12 mb-2">
                <span class="text-sm">Snacks</span>
            </div>

            <div
                class="flex-shrink-0 w-24 h-24 bg-white rounded-xl shadow flex flex-col items-center justify-center snap-center">
                <img src="https://via.placeholder.com/60" alt="Cat 5" class="w-12 h-12 mb-2">
                <span class="text-sm">Dairy</span>
            </div>

            <div
                class="flex-shrink-0 w-24 h-24 bg-white rounded-xl shadow flex flex-col items-center justify-center snap-center">
                <img src="https://via.placeholder.com/60" alt="Cat 6" class="w-12 h-12 mb-2">
                <span class="text-sm">Beverages</span>
            </div>

            <div
                class="flex-shrink-0 w-24 h-24 bg-white rounded-xl shadow flex flex-col items-center justify-center snap-center">
                <img src="https://via.placeholder.com/60" alt="Cat 6" class="w-12 h-12 mb-2">
                <span class="text-sm">Beverages</span>
            </div>

            <div
                class="flex-shrink-0 w-24 h-24 bg-white rounded-xl shadow flex flex-col items-center justify-center snap-center">
                <img src="https://via.placeholder.com/60" alt="Cat 6" class="w-12 h-12 mb-2">
                <span class="text-sm">Beverages</span>
            </div>

            <div
                class="flex-shrink-0 w-24 h-24 bg-white rounded-xl shadow flex flex-col items-center justify-center snap-center">
                <img src="https://via.placeholder.com/60" alt="Cat 6" class="w-12 h-12 mb-2">
                <span class="text-sm">Beverages</span>
            </div>

            <div
                class="flex-shrink-0 w-24 h-24 bg-white rounded-xl shadow flex flex-col items-center justify-center snap-center">
                <img src="https://via.placeholder.com/60" alt="Cat 6" class="w-12 h-12 mb-2">
                <span class="text-sm">Beverages</span>
            </div>

            <div
                class="flex-shrink-0 w-24 h-24 bg-white rounded-xl shadow flex flex-col items-center justify-center snap-center">
                <img src="https://via.placeholder.com/60" alt="Cat 6" class="w-12 h-12 mb-2">
                <span class="text-sm">Beverages</span>
            </div>

            <div
                class="flex-shrink-0 w-24 h-24 bg-white rounded-xl shadow flex flex-col items-center justify-center snap-center">
                <img src="https://via.placeholder.com/60" alt="Cat 6" class="w-12 h-12 mb-2">
                <span class="text-sm">Beverages</span>
            </div>

            <div
                class="flex-shrink-0 w-24 h-24 bg-white rounded-xl shadow flex flex-col items-center justify-center snap-center">
                <img src="https://via.placeholder.com/60" alt="Cat 6" class="w-12 h-12 mb-2">
                <span class="text-sm">Beverages</span>
            </div>

            <div
                class="flex-shrink-0 w-24 h-24 bg-white rounded-xl shadow flex flex-col items-center justify-center snap-center">
                <img src="https://via.placeholder.com/60" alt="Cat 6" class="w-12 h-12 mb-2">
                <span class="text-sm">Beverages</span>
            </div>
        </div>

        <!-- Right Button -->
        <button
            @click="$refs.scroller.scrollBy({ left: 200, behavior: 'smooth' })"
            class="absolute right-0 top-1/2 -translate-y-1/2 bg-white shadow rounded-full p-2 hidden md:block">
            ▶
        </button>
    </div>

</div>
