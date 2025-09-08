@props(
    [
        "headings" => ''
]
)
<style>
    /* hide scrollbar for all browsers */
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
<div class="sm:w-[90%] w-full mx-auto" x-data>

    <div class="text-2xl font-semibold mb-4 px-4">{{$headings}}</div>
    <!-- Slider wrapper -->
    <div class="relative">
        <!-- Left Arrow -->
{{--        <button--}}
{{--            @click="$refs.scroller.scrollBy({ left: -220, behavior: 'smooth' })"--}}
{{--            class="hidden md:flex absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white shadow rounded-full p-2">--}}
{{--            ◀--}}
{{--        </button>--}}
        <!-- Cards scroller -->
        <div
            x-ref="scroller"
            class="flex space-x-4 overflow-x-auto scrollbar-hide scroll-smooth snap-x snap-mandatory pb-4">

            <!-- Product Card -->
            <template x-for="i in 10" :key="i">
                <div class="w-40 flex-shrink-0 snap-center">
                    <div class="bg-white rounded-2xl flex flex-col " x-data="{ added: false }">
                        <!-- Product Image -->
                        <div class="relative shadow rounded-xl">
                            <img src="https://icon2.cleanpng.com/20180411/ucw/avu5woreh.webp" alt="Product"
                                 class="rounded-xl w-full object-cover">
                            <!-- Add Button -->
                            <button
                                @click="added = !added"
                                class="absolute bottom-2 right-2 px-4 py-1.5 rounded-xl border border-b-2 border-r-2 border-violet-500 text-violet-500 text-xs font-semibold bg-white hover:bg-violet-50 transition">
                                <span x-show="!added">ADD</span>
                                <span x-show="added">✔ Added</span>
                            </button>
                        </div>

                        <!-- Price -->
                        <div class="mt-2">
                            <div class="flex items-center space-x-1">
                                <span class="text-xs font-bold text-gray-900">₹12,999</span>
                                <span class="text-gray-400 line-through text-xs">₹37,999</span>
                            </div>
                            <p class="text-green-600 font-semibold text-xs">SAVE ₹25K</p>
                            <p class="text-gray-500 text-xs">1 pc</p>
                        </div>

                        <!-- EMI -->
                        <span
                            class="bg-gradient-to-r from-yellow-100 to-white py-0.5 text-yellow-800 px-2 rounded text-xs font-semibold mt-1">
                ₹1246/month EMI
              </span>

                        <!-- Title -->
                        <h3 class="mt-1 text-xs font-medium text-gray-800 line-clamp-2">
                            Agaro Rejoice Foot, Calf And Leg Massager…
                        </h3>

                        <!-- Tags + ETA -->
                        <div class="inline-flex items-center space-x-1 text-xs mt-2">
                            <span class="bg-blue-100 text-blue-600 px-2 rounded-full font-semibold">Fruits</span>
                            <div
                                class="flex items-center space-x-1 bg-gradient-to-r from-gray-200 to-white rounded-full px-1">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                     fill="currentColor" class="w-3 h-3">
                                    <path fill-rule="evenodd"
                                          d="M14.615 1.595a.75.75 0 0 1 .359.852L12.982 9.75h7.268a.75.75 0 0 1 .548 1.262l-10.5 11.25a.75.75 0 0 1-1.272-.71l1.992-7.302H3.75a.75.75 0 0 1-.548-1.262l10.5-11.25a.75.75 0 0 1 .913-.143Z"
                                          clip-rule="evenodd"/>
                                </svg>
                                <span>15 mins</span>
                            </div>
                        </div>

                        <!-- Rating -->
                        <div class="flex items-center space-x-1 mb-1">
                            <span
                                class="bg-green-100 text-green-600 px-2 py-0.5 text-xs rounded-full font-semibold">4.5</span>
                            <span class="text-gray-500 text-xs">(728)</span>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Right Arrow -->
{{--        <button--}}
{{--            @click="$refs.scroller.scrollBy({ left: 220, behavior: 'smooth' })"--}}
{{--            class="hidden md:flex absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white shadow rounded-full p-2">--}}
{{--            ▶--}}
{{--        </button>--}}
    </div>
</div>
