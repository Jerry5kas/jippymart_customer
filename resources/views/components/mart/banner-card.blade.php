@props([
  "products" => [],
  'header' => '',
  'idea' => '',
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

    /* Ensure proper spacing and prevent collision */
    .banner-carousel {
        scroll-behavior: smooth;
        scroll-snap-type: x mandatory;
    }

    .banner-carousel > * {
        scroll-snap-align: center;
    }

    /* Ensure cards maintain consistent spacing */
    .product-card-container {
        min-width: 192px; /* w-48 equivalent */
        max-width: 192px;
        flex-shrink: 0;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .product-card-container {
            min-width: 160px;
            max-width: 160px;
        }
    }
</style>

<div class="max-w-7xl w-full mx-auto my-10">
    <div class="bg-[#E8F8DB] rounded-2xl p-4 sm:p-6 w-full flex-shrink-0 bg-cover bg-center"
         style="background-image: url('/')">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-center bg-[#E8F8DB] min-h-[200px]">

            <!-- Left Banner -->
            <div class="md:col-span-1 flex flex-col justify-between space-y-4">
                <div>
                    <p class="uppercase tracking-widest text-sm text-gray-600">{{$header}}</p>
                    <h2 class="text-2xl sm:text-3xl font-bold leading-snug text-gray-600 mt-2">
                        {{$idea}}
                    </h2>
                </div>
                @if(!empty($products) && count($products) > 0 && !empty($products[0]['subcategoryTitle']))
                    <a href="{{ route('mart.items.by.subcategory', ['subcategoryTitle' => $products[0]['subcategoryTitle']]) }}"
                       class="bg-[#007F73] hover:bg-[#005f56] text-white px-4 py-2 rounded-lg text-sm font-semibold w-max inline-block transition-colors">
                        More Items →
                    </a>
                @else
                    <a href="{{ route('mart.index') }}"
                       class="bg-[#007F73] hover:bg-[#005f56] text-white px-4 py-2 rounded-lg text-sm font-semibold w-max inline-block transition-colors">
                        More Items →
                    </a>
                @endif
            </div>

            <!-- Right Carousel -->
            <div class="md:col-span-3 relative overflow-hidden" x-data="{}">
                <!-- Left Arrow -->
                <button
                    @click="$refs.scroller.scrollBy({ left: -240, behavior: 'smooth' })"
                    class="hidden md:flex absolute left-2 top-1/2 -translate-y-1/2 z-20 bg-white shadow-lg rounded-full p-2 text-sm text-gray-600 hover:bg-gray-50 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                </button>

                <!-- Scroller -->
                <div
                    x-ref="scroller"
                    class="flex gap-4 overflow-x-auto scrollbar-hide banner-carousel pb-4 px-8 md:px-12">

                    @forelse ($products ?? [] as $product)
                        <div class="product-card-container snap-center" x-data="{ added: false }">
                            @if(!empty($product['subcategoryTitle']))
                                <a href="{{ route('mart.items.by.subcategory', ['subcategoryTitle' => $product['subcategoryTitle']]) }}" class="block hover:scale-105 transition-transform duration-300">
                                    <x-mart.product-item-card-2 
                                        :disPrice="$product['disPrice']" 
                                        :price="$product['price']" 
                                        :title="$product['name']" 
                                        :description="$product['description']"
                                        :src="$product['photo']" 
                                        :grams="$product['grams']" 
                                        :rating="$product['rating']" 
                                        :reviews="$product['reviews']"
                                        :subcategoryTitle="$product['subcategoryTitle']"
                                        :brandTitle="$product['brandTitle'] ?? ''"
                                        :brandID="$product['brandID'] ?? ''"
                                    />
                                </a>
                            @else
                                <div class="block">
                                    <x-mart.product-item-card-2 
                                        :disPrice="$product['disPrice']" 
                                        :price="$product['price']" 
                                        :title="$product['name']" 
                                        :description="$product['description']"
                                        :src="$product['photo']" 
                                        :grams="$product['grams']" 
                                        :rating="$product['rating']" 
                                        :reviews="$product['reviews']"
                                        :subcategoryTitle="$product['subcategoryTitle'] ?? 'General'"
                                        :brandTitle="$product['brandTitle'] ?? ''"
                                        :brandID="$product['brandID'] ?? ''"
                                    />
                                </div>
                            @endif
                        </div>
                    @empty
                        <!-- Fallback content when no products -->
                        <div class="w-full flex-shrink-0">
                            <div class="text-center text-gray-500 py-8">
                                <div class="w-16 h-16 mx-auto mb-4 bg-gray-200 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                <p class="text-lg font-medium text-gray-600">Coming Soon!</p>
                                <p class="text-sm text-gray-500">Great products will be available here soon</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Right Arrow -->
                <button
                    @click="$refs.scroller.scrollBy({ left: 240, behavior: 'smooth' })"
                    class="hidden md:flex absolute right-2 top-1/2 -translate-y-1/2 z-20 bg-white shadow-lg rounded-full p-2 text-sm text-gray-600 hover:bg-gray-50 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
