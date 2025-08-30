<div x-data="{ active: 'All' }" class="w-full py-5">

    <!-- Categories Navigation -->
    <div class="w-full border-b bg-gradient-to-t from-slate-50 to-white">
        <div class="sm:w-[90%] mx-auto w-full px-4">
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
        <div class="flex space-x-6 overflow-x-auto px-4 py-5 scrollbar-hide sm:w-[90%] mx-auto w-full text-xs">

            <!-- Category Card -->
            <div class="flex-shrink-0 w-28 text-center">
                <img src="https://icon2.cleanpng.com/lnd/20250108/yj/011d1e60d8d65ba818e537fc0cf2d3.webp" alt="fruits"
                     class="w-20 h-20 mx-auto object-cover rounded-lg shadow-sm">
                <p class="mt-2 text-xs font-medium text-gray-800">Fruits & Veg</p>
            </div>

            <div class="flex-shrink-0 w-28 text-center">
                <img src="https://icon2.cleanpng.com/20180629/sij/kisspng-atta-flour-aashirvaad-multigrain-bread-roti-whole-barely-5b3636ab6d17d2.0614586915302795954469.jpg" alt="atta"
                     class="w-20 h-20 mx-auto object-cover rounded-lg shadow-sm">
                <p class="mt-2 text-xs font-medium text-gray-800">Atta, Rice & Dals</p>
            </div>

            <div class="flex-shrink-0 w-28 text-center">
                <img src="https://icon2.cleanpng.com/20240205/rkr/transparent-dry-fruits-mixed-nuts-almonds-walnuts-blackberries-bowl-of-mixed-nuts-with-almonds-walnuts-and-1710886080607.webp" alt="masala"
                     class="w-20 h-20 mx-auto object-cover rounded-lg shadow-sm">
                <p class="mt-2 text-xs font-medium text-gray-800">Masala & Dry Fruits</p>
            </div>

            <div class="flex-shrink-0 w-28 text-center">
                <img src="https://icon2.cleanpng.com/20231010/ewj/transparent-coffee-beans-1711062059904.webp" alt="cafe"
                     class="w-20 h-20 mx-auto object-cover rounded-lg shadow-sm">
                <p class="mt-2 text-xs font-medium text-gray-800">Mart Cafe</p>
            </div>

            <div class="flex-shrink-0 w-28 text-center">
                <img src="https://icon2.cleanpng.com/ci2/kma/qtp/ajyjyps46.webp" alt="sweet"
                     class="w-20 h-20 mx-auto object-cover rounded-lg shadow-sm">
                <p class="mt-2 text-xs font-medium text-gray-800">Sweet Cravings</p>
            </div>

            <div class="flex-shrink-0 w-28 text-center">
                <img src="https://icon2.cleanpng.com/20180421/ure/avtgdgnap.webp" alt="sports"
                     class="w-20 h-20 mx-auto object-cover rounded-lg shadow-sm">
                <p class="mt-2 text-xs font-medium text-gray-800">Toys & Sports</p>
            </div>

            <div class="flex-shrink-0 w-28 text-center">
                <img src="https://icon2.cleanpng.com/lnd/20240902/jy/0e373b3af7459aac55970806447374.webp" alt="apparel"
                     class="w-20 h-20 mx-auto object-cover rounded-lg shadow-sm">
                <p class="mt-2 text-xs font-medium text-gray-800">Apparel & Lifestyle</p>
            </div>

            <div class="flex-shrink-0 w-28 text-center">
                <img src="https://icon2.cleanpng.com/20180326/pfq/avdsc03so.webp" alt="jewellery"
                     class="w-20 h-20 mx-auto object-cover rounded-lg shadow-sm">
                <p class="mt-2 text-xs font-medium text-gray-800">Jewellery</p>
            </div>

            <div class="flex-shrink-0 w-28 text-center">
                <img src="https://icon2.cleanpng.com/20180411/cgq/avu3i4jmw.webp" alt="frozen"
                     class="w-20 h-20 mx-auto object-cover rounded-lg shadow-sm">
                <p class="mt-2 text-xs font-medium text-gray-800">Frozen Food</p>
            </div>

            <div class="flex-shrink-0 w-28 text-center">
                <img src="https://icon2.cleanpng.com/20240104/zip/transparent-ice-cream-cones-various-ice-cream-cones-with-toppings-1710939177855.webp" alt="icecream"
                     class="w-20 h-20 mx-auto object-cover rounded-lg shadow-sm">
                <p class="mt-2 text-xs font-medium text-gray-800">Ice Creams</p>
            </div>

            <div class="flex-shrink-0 w-28 text-center">
                <img src="https://icon2.cleanpng.com/20180703/fyk/kisspng-ground-turkey-ground-beef-meatloaf-drop-down-box-5b3bf4ba456222.8526961215306559302842.jpg" alt="packaged"
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
