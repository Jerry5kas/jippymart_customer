<div x-data="{ scroll: 0 }" class="md:w-1/2 w-full my-6 px-4 rounded-2xl">

    <!-- Banner (Fixed) -->
    <div class="h-40 bg-black flex items-center justify-center rounded-t-2xl">
        <h1 class="text-2xl text-white font-bold">Featured Products</h1>
    </div>

    <!-- Category Carousel -->
    <div class="relative px-4 bg-black" x-data="{ scroll: 0 } rounded-2xl mb-5">
        <!-- Left Button -->
        <button
            @click="$refs.scroller.scrollBy({ left: -200, behavior: 'smooth' })"
            class="absolute left-0 top-1/2 -translate-y-1/2 bg-white shadow rounded-full p-1 text-xs text-gray-400 hidden md:block">
            ◀
        </button>

        <!-- Categories -->
        <div
            x-ref="scroller"
            class="flex space-x-4 bg-black overflow-x-auto scrollbar-hide scroll-smooth snap-x snap-mandatory pb-6 px-2 rounded-2xl ">

            <!-- Category Item -->
            <div
                class="flex-shrink-0 w-24 h-24 bg-white rounded-xl shadow flex flex-col items-center justify-center snap-center">
                <img src="https://icon2.cleanpng.com/20231119/yws/transparent-dental-hygiene-products-toothbrushes-toothpaste-de-colorful-dental-hygiene-basket-with-various-1711009023497.webp" alt="Cat 1" class="w-12 h-12 mb-2">
                <span class="text-sm">Fruits</span>
            </div>

            <div
                class="flex-shrink-0 w-24 h-24 bg-white rounded-xl shadow flex flex-col items-center justify-center snap-center">
                <img src="https://icon2.cleanpng.com/20231119/yws/transparent-dental-hygiene-products-toothbrushes-toothpaste-de-colorful-dental-hygiene-basket-with-various-1711009023497.webp" alt="Cat 2" class="w-12 h-12 mb-2">
                <span class="text-sm">Veggies</span>
            </div>

            <div
                class="flex-shrink-0 w-24 h-24 bg-white rounded-xl shadow flex flex-col items-center justify-center snap-center">
                <img src="https://icon2.cleanpng.com/20231119/yws/transparent-dental-hygiene-products-toothbrushes-toothpaste-de-colorful-dental-hygiene-basket-with-various-1711009023497.webp" alt="Cat 3" class="w-12 h-12 mb-2">
                <span class="text-sm">Bakery</span>
            </div>

            <div
                class="flex-shrink-0 w-24 h-24 bg-white rounded-xl shadow flex flex-col items-center justify-center snap-center">
                <img src="https://icon2.cleanpng.com/20231119/yws/transparent-dental-hygiene-products-toothbrushes-toothpaste-de-colorful-dental-hygiene-basket-with-various-1711009023497.webp" alt="Cat 4" class="w-12 h-12 mb-2">
                <span class="text-sm">Snacks</span>
            </div>

            <div
                class="flex-shrink-0 w-24 h-24 bg-white rounded-xl shadow flex flex-col items-center justify-center snap-center">
                <img src="https://icon2.cleanpng.com/20231119/yws/transparent-dental-hygiene-products-toothbrushes-toothpaste-de-colorful-dental-hygiene-basket-with-various-1711009023497.webp" alt="Cat 5" class="w-12 h-12 mb-2">
                <span class="text-sm">Dairy</span>
            </div>

            <div
                class="flex-shrink-0 w-24 h-24 bg-white rounded-xl shadow flex flex-col items-center justify-center snap-center">
                <img src="https://icon2.cleanpng.com/20231119/yws/transparent-dental-hygiene-products-toothbrushes-toothpaste-de-colorful-dental-hygiene-basket-with-various-1711009023497.webp" alt="Cat 6" class="w-12 h-12 mb-2">
                <span class="text-sm">Beverages</span>
            </div>

            <div
                class="flex-shrink-0 w-24 h-24 bg-white rounded-xl shadow flex flex-col items-center justify-center snap-center">
                <img src="https://icon2.cleanpng.com/20231119/yws/transparent-dental-hygiene-products-toothbrushes-toothpaste-de-colorful-dental-hygiene-basket-with-various-1711009023497.webp" alt="Cat 6" class="w-12 h-12 mb-2">
                <span class="text-sm">Beverages</span>
            </div>

            <div
                class="flex-shrink-0 w-24 h-24 bg-white rounded-xl shadow flex flex-col items-center justify-center snap-center">
                <img src="https://icon2.cleanpng.com/20231119/yws/transparent-dental-hygiene-products-toothbrushes-toothpaste-de-colorful-dental-hygiene-basket-with-various-1711009023497.webp" alt="Cat 6" class="w-12 h-12 mb-2">
                <span class="text-sm">Beverages</span>
            </div>

            <div
                class="flex-shrink-0 w-24 h-24 bg-white rounded-xl shadow flex flex-col items-center justify-center snap-center">
                <img src="https://icon2.cleanpng.com/20231119/yws/transparent-dental-hygiene-products-toothbrushes-toothpaste-de-colorful-dental-hygiene-basket-with-various-1711009023497.webp" alt="Cat 6" class="w-12 h-12 mb-2">
                <span class="text-sm">Beverages</span>
            </div>

            <div
                class="flex-shrink-0 w-24 h-24 bg-white rounded-xl shadow flex flex-col items-center justify-center snap-center">
                <img src="https://icon2.cleanpng.com/20231119/yws/transparent-dental-hygiene-products-toothbrushes-toothpaste-de-colorful-dental-hygiene-basket-with-various-1711009023497.webp" alt="Cat 6" class="w-12 h-12 mb-2">
                <span class="text-sm">Beverages</span>
            </div>

            <div
                class="flex-shrink-0 w-24 h-24 bg-white rounded-xl shadow flex flex-col items-center justify-center snap-center">
                <img src="https://icon2.cleanpng.com/20231119/yws/transparent-dental-hygiene-products-toothbrushes-toothpaste-de-colorful-dental-hygiene-basket-with-various-1711009023497.webp" alt="Cat 6" class="w-12 h-12 mb-2">
                <span class="text-sm">Beverages</span>
            </div>

            <div
                class="flex-shrink-0 w-24 h-24 bg-white rounded-xl shadow flex flex-col items-center justify-center snap-center">
                <img src="https://icon2.cleanpng.com/20231119/yws/transparent-dental-hygiene-products-toothbrushes-toothpaste-de-colorful-dental-hygiene-basket-with-various-1711009023497.webp" alt="Cat 6" class="w-12 h-12 mb-2">
                <span class="text-sm">Beverages</span>
            </div>

            <div
                class="flex-shrink-0 w-24 h-24 bg-white rounded-xl shadow flex flex-col items-center justify-center snap-center">
                <img src="https://icon2.cleanpng.com/20231119/yws/transparent-dental-hygiene-products-toothbrushes-toothpaste-de-colorful-dental-hygiene-basket-with-various-1711009023497.webp" alt="Cat 6" class="w-12 h-12 mb-2">
                <span class="text-sm">Beverages</span>
            </div>

            <div
                class="flex-shrink-0 w-24 h-24 bg-white rounded-xl shadow flex flex-col items-center justify-center snap-center">
                <img src="https://icon2.cleanpng.com/20231119/yws/transparent-dental-hygiene-products-toothbrushes-toothpaste-de-colorful-dental-hygiene-basket-with-various-1711009023497.webp" alt="Cat 6" class="w-12 h-12 mb-2">
                <span class="text-sm">Beverages</span>
            </div>
        </div>

        <!-- Right Button -->
        <button
            @click="$refs.scroller.scrollBy({ left: 200, behavior: 'smooth' })"
            class="absolute right-0 top-1/2 -translate-y-1/2 bg-white shadow rounded-full p-1 text-xs text-gray-400 hidden md:block">
            ▶
        </button>
    </div>

</div>
