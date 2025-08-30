<div x-data="{ active: 'All' }" class="max-w-6xl mx-auto py-5">

    <!-- Categories Navigation -->
    <div class="w-full border-b bg-gradient-to-b from-purple-50 to-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center space-x-6 overflow-x-auto scrollbar-hide py-3">

                <!-- Category Tabs -->
                <template x-for="tab in ['All','Cafe','Home','Toys','Fresh','Electronics','Mobiles','Beauty','Fashion']"
                          :key="tab">
                    <button
                        class="flex items-center space-x-1 text-gray-600 font-medium flex-shrink-0 px-2 pb-1 border-b-2 transition"
                        :class="active === tab ? 'text-purple-600 border-purple-600' : 'border-transparent hover:text-purple-500'"
                        @click="active = tab">
                        <span x-text="tab"></span>
                    </button>
                </template>

            </div>
        </div>
    </div>

    <!-- Category Items Scroll -->
    <div class="w-full">
        <div class="flex space-x-6 overflow-x-auto px-4 py-5 scrollbar-hide max-w-7xl mx-auto text-xs">

            <!-- Category Card -->
            <div class="flex-shrink-0 w-28 text-center">
                <img src="https://placehold.co/100x100" alt="fruits"
                     class="w-20 h-20 mx-auto object-cover rounded-lg shadow-sm">
                <p class="mt-2 text-xs font-medium text-gray-800">Fruits & Veg</p>
            </div>

            <div class="flex-shrink-0 w-28 text-center">
                <img src="https://placehold.co/100x100" alt="atta"
                     class="w-20 h-20 mx-auto object-cover rounded-lg shadow-sm">
                <p class="mt-2 text-xs font-medium text-gray-800">Atta, Rice & Dals</p>
            </div>

            <div class="flex-shrink-0 w-28 text-center">
                <img src="https://placehold.co/100x100" alt="masala"
                     class="w-20 h-20 mx-auto object-cover rounded-lg shadow-sm">
                <p class="mt-2 text-xs font-medium text-gray-800">Masala & Dry Fruits</p>
            </div>

            <div class="flex-shrink-0 w-28 text-center">
                <img src="https://placehold.co/100x100" alt="cafe"
                     class="w-20 h-20 mx-auto object-cover rounded-lg shadow-sm">
                <p class="mt-2 text-xs font-medium text-gray-800">Mart Cafe</p>
            </div>

            <div class="flex-shrink-0 w-28 text-center">
                <img src="https://placehold.co/100x100" alt="sweet"
                     class="w-20 h-20 mx-auto object-cover rounded-lg shadow-sm">
                <p class="mt-2 text-xs font-medium text-gray-800">Sweet Cravings</p>
            </div>

            <div class="flex-shrink-0 w-28 text-center">
                <img src="https://placehold.co/100x100" alt="sports"
                     class="w-20 h-20 mx-auto object-cover rounded-lg shadow-sm">
                <p class="mt-2 text-xs font-medium text-gray-800">Toys & Sports</p>
            </div>

            <div class="flex-shrink-0 w-28 text-center">
                <img src="https://placehold.co/100x100" alt="apparel"
                     class="w-20 h-20 mx-auto object-cover rounded-lg shadow-sm">
                <p class="mt-2 text-xs font-medium text-gray-800">Apparel & Lifestyle</p>
            </div>

            <div class="flex-shrink-0 w-28 text-center">
                <img src="https://placehold.co/100x100" alt="jewellery"
                     class="w-20 h-20 mx-auto object-cover rounded-lg shadow-sm">
                <p class="mt-2 text-xs font-medium text-gray-800">Jewellery</p>
            </div>

            <div class="flex-shrink-0 w-28 text-center">
                <img src="https://placehold.co/100x100" alt="frozen"
                     class="w-20 h-20 mx-auto object-cover rounded-lg shadow-sm">
                <p class="mt-2 text-xs font-medium text-gray-800">Frozen Food</p>
            </div>

            <div class="flex-shrink-0 w-28 text-center">
                <img src="https://placehold.co/100x100" alt="icecream"
                     class="w-20 h-20 mx-auto object-cover rounded-lg shadow-sm">
                <p class="mt-2 text-xs font-medium text-gray-800">Ice Creams</p>
            </div>

            <div class="flex-shrink-0 w-28 text-center">
                <img src="https://placehold.co/100x100" alt="packaged"
                     class="w-20 h-20 mx-auto object-cover rounded-lg shadow-sm">
                <p class="mt-2 text-xs font-medium text-gray-800">Packaged Food</p>
            </div>
        </div>
    </div>

    <!-- Hide scrollbar helper -->
    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

</div>
