@props([
  "products" => []
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

<div class="sm:w-[90%] w-full mx-auto my-10 " x-data>
    <div class="bg-orange-50 rounded-2xl p-4 sm:p-6 w-full flex-shrink-0 bg-cover bg-center"
         style="background-image: url('https://static.vecteezy.com/system/resources/thumbnails/005/715/816/small/banner-abstract-background-board-for-text-and-message-design-modern-free-vector.jpg')">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-center">

            <!-- Left Banner -->
            <div class="md:col-span-1 flex flex-col justify-between space-y-4">
                <div>
                    <p class="uppercase tracking-widest text-sm text-gray-500">hey this a mart </p>
                    <h2 class="text-2xl sm:text-3xl font-bold leading-snug text-gray-900 mt-2">
                        go go
                    </h2>
                </div>
                <button
                    class="bg-brown-600 hover:bg-brown-700 text-white px-4 py-2 rounded-lg text-sm font-semibold w-max">
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

{{--                    <!-- Product Card -->--}}
{{--                    <template x-for="i in 10" :key="i">--}}
{{--                        <div class="w-40 flex-shrink-0 snap-center">--}}
                    @forelse ($products ?? [] as $product)
                    <div class="bg-white rounded-2xl flex flex-col space-y-1 p-1" x-data="{ added: false }">
                                <x-mart.product-item-card :disPrice="$product['disPrice']" :price="$product['price']" :title="$product['name']" :description="$product['description']"
                                                          :photo="$product['photo']" :grams="$product['grams']" :rating="$product['rating']" :reviews="$product['reviews']"
                                                          :subcategoryTitle="$product['subcategoryTitle']"/>
                            </div>
{{--                        </div>--}}
{{--                    </template>--}}
                    @empty
                        <div class="w-full flex-shrink-0">
                            <div class="text-center text-gray-500 py-8">
                                <p>No banners available at the moment.</p>
                            </div>
                        </div>
                    @endforelse
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
