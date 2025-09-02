<div x-data="{ scroll: 0 }" class="md:w-1/2 w-full mt-8 px-4 rounded-2xl">

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
            <x-mart.small-cat-card />
            <x-mart.small-cat-card />
            <x-mart.small-cat-card />
            <x-mart.small-cat-card />
            <x-mart.small-cat-card />
            <x-mart.small-cat-card />
            <x-mart.small-cat-card />
            <x-mart.small-cat-card />
            <x-mart.small-cat-card />
            <x-mart.small-cat-card />

        </div>

        <!-- Right Button -->
        <button
            @click="$refs.scroller.scrollBy({ left: 200, behavior: 'smooth' })"
            class="absolute right-0 top-1/2 -translate-y-1/2 bg-white shadow rounded-full p-1 text-xs text-gray-400 hidden md:block">
            ▶
        </button>
    </div>

</div>
