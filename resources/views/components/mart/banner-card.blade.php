@props([
  "headings" => "Coffee Lovers",
  "subheadings" => "Dive into the world of fresh brew"
])

<style>
    /* Hide scrollbar for all browsers */
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<div class="sm:w-[90%] w-full mx-auto my-10" x-data>
    <div class="bg-orange-50 rounded-2xl p-4 sm:p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-center">

            <!-- Left Banner -->
            <div class="md:col-span-1 flex flex-col justify-between space-y-4">
                <div>
                    <p class="uppercase tracking-widest text-sm text-gray-500"> {{$headings}} </p>
                    <h2 class="text-2xl sm:text-3xl font-bold leading-snug text-gray-900 mt-2">
                        {{$subheadings}}
                    </h2>
                </div>
                <button class="bg-brown-600 hover:bg-brown-700 text-white px-4 py-2 rounded-lg text-sm font-semibold w-max">
                    More Items →
                </button>
            </div>

            <!-- Right Carousel -->
            <div class="md:col-span-3 relative">
                <!-- Left Arrow -->
                <button
                    @click="$refs.scroller.scrollBy({ left: -220, behavior: 'smooth' })"
                    class="hidden md:flex absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white shadow rounded-full p-1 text-xs text-gray-400">
                    ◀
                </button>

                <!-- Scroller -->
                <div
                    x-ref="scroller"
                    class="flex space-x-4 overflow-x-auto scrollbar-hide scroll-smooth snap-x snap-mandatory pb-4 px-2">

                    <!-- Product Card -->
                    <template x-for="i in 10" :key="i">
                        <div class="w-40 flex-shrink-0 snap-center">
                            <div class="bg-white rounded-2xl shadow hover:shadow-lg transition flex flex-col p-2" x-data="{ added: false }">
                                <!-- Product Image -->
                                <div class="relative">
                                    <img src='https://icon2.cleanpng.com/20180411/ucw/avu5woreh.webp'
                                         alt="Product"
                                         class="rounded-xl w-full h-28 object-cover">
                                    <!-- Add Button -->
                                    <button
                                        @click="added = !added"
                                        class="absolute bottom-2 right-2 px-4 py-1.5 rounded-xl border border-b-2 border-r-2 border-pink-500 text-pink-500 text-xs font-semibold bg-white hover:bg-pink-50 transition">
                                        <span x-show="!added">ADD</span>
                                        <span x-show="added">✔ Added</span>
                                    </button>
                                </div>

                                <!-- Price -->
                                <div class="mt-2">
                                    <div class="flex items-center space-x-1">
                                        <span class="text-sm font-bold text-gray-900">₹189</span>
                                        <span class="text-gray-400 line-through text-xs">₹329</span>
                                    </div>
                                    <p class="bg-gradient-to-r from-green-100 to-white text-green-600 font-semibold text-xs p-0.5">SAVE ₹25K</p>
                                    <p class="text-gray-500 text-xs">250 ml</p>
                                </div>

                                <!-- Title -->
                                <h3 class="mt-1 text-xs font-medium text-gray-800 line-clamp-2">
                                    Classic Cold Coffee
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
                                <div class="flex items-center space-x-1 mt-1">
                                    <span class="bg-yellow-100 text-yellow-600 px-2 py-0.5 text-xs rounded-full font-semibold">4.3</span>
                                    <span class="text-gray-500 text-xs">(574)</span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Right Arrow -->
                <button
                    @click="$refs.scroller.scrollBy({ left: 220, behavior: 'smooth' })"
                    class="hidden md:flex absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white shadow rounded-full p-1 text-xs text-gray-400">
                    ▶
                </button>
            </div>
        </div>
    </div>
</div>
