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

    <div class="text-2xl font-semibold mb-4 px-4 text-[#007F73]">{{$headings}}</div>
    <!-- Slider wrapper -->
    <div class="relative">
        <div
            x-ref="scroller"
            class="flex space-x-4 overflow-x-auto scrollbar-hide scroll-smooth snap-x snap-mandatory pb-4">

            <!-- Product Card -->
            <template x-for="i in 10" :key="i">
                <div class="w-40 flex-shrink-0 snap-center">
                    <div class="bg-white rounded-2xl flex flex-col space-y-1" x-data="{ added: false }">
                        <x-mart.product-item-card/>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
