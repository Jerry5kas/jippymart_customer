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
           <x-mart.top-cat-card />
           <x-mart.top-cat-card image="https://icon2.cleanpng.com/20180629/sij/kisspng-atta-flour-aashirvaad-multigrain-bread-roti-whole-barely-5b3636ab6d17d2.0614586915302795954469.jpg" title="Atta, Rice & Dals"/>
           <x-mart.top-cat-card image="https://icon2.cleanpng.com/20180629/sij/kisspng-atta-flour-aashirvaad-multigrain-bread-roti-whole-barely-5b3636ab6d17d2.0614586915302795954469.jpg" title="Atta, Rice & Dals"/>

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
