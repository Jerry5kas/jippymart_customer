@props([
    'products' => [],
    'title' => 'Products'
])

<div x-data="{ scroll: 0 }" class="md:w-full w-full mt-8 px-4 rounded-2xl">

    <!-- Banner (Fixed) -->
    <div class="h-40 bg-[#007F73] flex items-center justify-center rounded-t-2xl">
        <h1 class="text-xl px-2 text-center text-white font-bold">{{$title}}</h1>
    </div>

    <!-- Featured Products Carousel -->
    @if(count($products) > 0)
    <div class="relative px-4 bg-[#007F73] rounded-b-2xl mb-5" x-data="{ scroll: 0 }">
        <!-- Left Button -->
        <button
            @click="$refs.scroller.scrollBy({ left: -200, behavior: 'smooth' })"
            class="absolute left-0 top-1/2 -translate-y-1/2 bg-white shadow rounded-full p-1 text-xs text-gray-400 hidden md:block">
            ◀
        </button>
        <!-- Featured Products -->
        <div
            x-ref="scroller"
            class="flex space-x-4 bg-[#007F73] overflow-x-auto scrollbar-hide scroll-smooth snap-x snap-mandatory pb-6 px-2 rounded-b-2xl ">

            @foreach($products as $product)
                @if(!empty($product['subcategoryTitle']))
                    <a href="{{ route('mart.items.by.subcategory', ['subcategoryTitle' => $product['subcategoryTitle']]) }}" class="block">
                        <x-mart.small-cat-card
                            :src="$product['photo']"
                            :title="$product['name']"
                            :price="$product['price']"
                            :disPrice="$product['disPrice']"
                            :rating="$product['rating']"
                            :reviews="$product['reviews']"
                            :description="$product['description'] ?? 'Lorem ipsum dolor sit amet'"
                            :grams="$product['grams'] ?? 200"
                            :subcategoryTitle="$product['subcategoryTitle']"
                            :brandTitle="$product['brandTitle'] ?? ''"
                            :brandID="$product['brandID'] ?? ''"
                        />
                    </a>
                @else
                    <div class="block">
                        <x-mart.small-cat-card
                            :src="$product['photo']"
                            :title="$product['name']"
                            :price="$product['price']"
                            :disPrice="$product['disPrice']"
                            :rating="$product['rating']"
                            :reviews="$product['reviews']"
                            :description="$product['description'] ?? 'Lorem ipsum dolor sit amet'"
                            :grams="$product['grams'] ?? 200"
                            :subcategoryTitle="$product['subcategoryTitle']"
                            :brandTitle="$product['brandTitle'] ?? ''"
                            :brandID="$product['brandID'] ?? ''"
                        />
                    </div>
                @endif
            @endforeach

        </div>

        <!-- Right Button -->
        <button
            @click="$refs.scroller.scrollBy({ left: 200, behavior: 'smooth' })"
            class="absolute right-0 top-1/2 -translate-y-1/2 bg-white shadow rounded-full p-1 text-xs text-gray-400 hidden md:block">
            ▶
        </button>
    </div>
    @else
    <!-- No Featured Products Message -->
    <div class="relative px-4 bg-[#007F73] rounded-2xl mb-5 py-8">
        <div class="text-center text-white">
            <p class="text-lg">No featured products available</p>
            <p class="text-sm text-gray-400">Check back later for exciting offers!</p>
        </div>
    </div>
    @endif

</div>
