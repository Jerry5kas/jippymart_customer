
<aside class="bg-white border-r-2 border-violet-400 w-64 hidden md:flex flex-col p-4 space-y-3 shadow-lg">
{{--    <h2 class="text-lg font-bold text-gray-800">Top Categories</h2>--}}

    <nav class="space-y-2">
        <x-layouts.menu  href="#" text="Top Picks" src="https://icon2.cleanpng.com/lnd/20240921/v/7d42c2a7d0611ceb83a88d707e4a04.webp" class="bg-violet-200" />
        <x-layouts.menu href="#" text="Fresh Vegetables" src="https://img.icons8.com/color/48/vegetarian-food.png"/>
        <x-layouts.menu href="#" text="Fresh Fruits" src="https://img.icons8.com/color/48/apple.png"/>
        <x-layouts.menu href="#" text="Exotics & Premium" src="https://img.icons8.com/color/48/avocado.png"/>
        <x-layouts.menu href="#" text="Flowers & Leaves" src="https://img.icons8.com/color/48/flowers.png"/>
    </nav>
</aside>

<!-- Mobile Sidebar -->
<div x-show="sidebarOpen" class="fixed inset-0 z-40 flex md:hidden" x-transition>
    <div class="fixed inset-0 bg-black bg-opacity-40" @click="sidebarOpen = false"></div>
    <aside class="relative bg-white w-64 p-4 space-y-3">
        <button class="absolute top-2 right-2" @click="sidebarOpen = false">✖</button>
        <h2 class="text-lg font-bold">Categories</h2>
        <nav class="space-y-2">
            <a href="#" class="flex items-center space-x-3 p-2 rounded-xl hover:bg-violet-50">🍅 Fresh
                Vegetables</a>
            <a href="#" class="flex items-center space-x-3 p-2 rounded-xl hover:bg-violet-50">🍎 Fresh Fruits</a>
            <a href="#" class="flex items-center space-x-3 p-2 rounded-xl hover:bg-violet-50">🥑 Exotics &
                Premium</a>
            <a href="#" class="flex items-center space-x-3 p-2 rounded-xl hover:bg-violet-50">🌸 Flowers &
                Leaves</a>
        </nav>
    </aside>
</div>
