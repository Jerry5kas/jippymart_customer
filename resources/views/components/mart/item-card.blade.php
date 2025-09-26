@props(
    [
        "headings" => '',
        "items" => []
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

    /* Enhanced card spacing and alignment */
    .item-card-container {
        min-width: 240px; /* w-60 equivalent */
        max-width: 240px;
        flex-shrink: 0;
    }

    .item-card-wrapper {
        height: 100%;
        display: flex;
        flex-direction: column;
        width: 100%;
        min-height: 360px;
        border: 1px solid #f3f4f6;
        transition: all 0.3s ease;
    }

    .item-card-wrapper:hover {
        border-color: #d1d5db;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    /* Ensure proper spacing between cards */
    .item-card-container:not(:last-child) {
        margin-right: 0;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .item-card-container {
            min-width: 200px;
            max-width: 200px;
        }
    }

    @media (max-width: 640px) {
        .item-card-container {
            min-width: 180px;
            max-width: 180px;
        }
    }
</style>

<div class="w-full max-w-7xl mx-auto px-4 py-6">
        <!-- Section Header -->
        <div class="text-2xl font-semibold mb-6 text-[#007F73] px-2">
            {{$headings}}
        </div>

        <!-- Product Cards Container -->
    <div class="relative">
        <!-- Left Arrow -->
        <button
            @click="$refs.scroller.scrollBy({ left: -260, behavior: 'smooth' })"
            class="hidden md:flex absolute left-2 top-1/2 -translate-y-1/2 z-20 bg-white shadow-lg rounded-full p-2 text-sm text-gray-600 hover:bg-gray-50 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
            </svg>
        </button>

        <!-- Right Arrow -->
        <button
            @click="$refs.scroller.scrollBy({ left: 260, behavior: 'smooth' })"
            class="hidden md:flex absolute right-2 top-1/2 -translate-y-1/2 z-20 bg-white shadow-lg rounded-full p-2 text-sm text-gray-600 hover:bg-gray-50 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
            </svg>
        </button>

        <div
            x-ref="scroller"
            class="flex gap-6 overflow-x-auto scrollbar-hide scroll-smooth snap-x snap-mandatory pb-4 px-8 md:px-12">

            <!-- Product Cards -->
            @if(count($items) > 0)
                @foreach($items as $item)
                    <div class="item-card-container snap-center">
                        @if(!empty($item['subcategoryTitle']))
                            <a href="{{ route('mart.items.by.subcategory', ['subcategoryTitle' => $item['subcategoryTitle']]) }}" class="block hover:scale-105 transition-transform duration-300">
                                <div class="item-card-wrapper bg-white rounded-2xl shadow-sm" x-data="{ added: false }">
                                    <x-mart.product-item-card-1
                                        :src="$item['photo']"
                                        :price="$item['price']"
                                        :disPrice="$item['disPrice']"
                                        :title="$item['name']"
                                        :description="$item['description']"
                                        :reviews="$item['reviews']"
                                        :rating="$item['rating']"
                                        :grams="$item['grams']"
                                        :subcategoryTitle="$item['subcategoryTitle']"
                                    />
                                </div>
                            </a>
                        @else
                            <div class="block">
                                <div class="item-card-wrapper bg-white rounded-2xl shadow-sm" x-data="{ added: false }">
                                    <x-mart.product-item-card-1
                                        :src="$item['photo']"
                                        :price="$item['price']"
                                        :disPrice="$item['disPrice']"
                                        :title="$item['name']"
                                        :description="$item['description']"
                                        :reviews="$item['reviews']"
                                        :rating="$item['rating']"
                                        :grams="$item['grams']"
                                        :subcategoryTitle="$item['subcategoryTitle'] ?? 'General'"
                                    />
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                <!-- Fallback: Show placeholder cards if no items -->
                <template x-for="i in 5" :key="i">
                    <div class="item-card-container snap-center">
                        <div class="item-card-wrapper bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300" x-data="{ added: false }">
                            <x-mart.product-item-card-1/>
                        </div>
                    </div>
                </template>
            @endif
        </div>
    </div>
</div>
