@props([
    'src' => 'https://icon2.cleanpng.com/lnd/20250108/yj/011d1e60d8d65ba818e537fc0cf2d3.webp',
    'title' => 'Fruits & Veg',
    'price' => 0,
    'disPrice' => 0,
    'rating' => 4.5,
    'reviews' => 100,
    'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.',
    'grams' => 200,
    'subcategoryTitle' => 'fruits',
    ])

<div class="flex-shrink-0 w-32 h-auto rounded-xl shadow-md flex flex-col items-center justify-between snap-center bg-white hover:shadow-lg transition duration-200"
     x-data="martCartItem('{{ addslashes($title) }}', {{ $disPrice }}, {{ $price }}, '{{ addslashes($src) }}', '{{ addslashes($subcategoryTitle) }}', '{{ addslashes(str_replace(["\r\n", "\r", "\n"], ' ', $description)) }}', '{{ $grams }}', {{ $rating }}, {{ $reviews }})"
     x-cloak
     x-init="loadCartState(); ready = true">
    <!-- Product Image -->
    <div class="relative w-full h-24 rounded-t-xl overflow-hidden">
        <img
            src="{{ $src }}"
            alt="{{ $title }}"
            class="w-full h-full object-cover"
            onerror="this.src='/img/pro1.jpg'">

        <!-- Rating Badge -->
        <div class="absolute top-1 right-1 bg-yellow-200 text-yellow-600 text-[10px] px-1.5 py-0.5 rounded-lg flex items-center shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-2.5 h-2.5 fill-current Modify Selected Code…" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.429L24 9.753l-6 5.847L19.335 24 12 19.897 4.665 24 6 15.6 0 9.753l8.332-1.737z"/></svg>
            <span class="ml-0.5 font-medium">{{ number_format($rating, 1) }}</span>
        </div>
    </div>

    <!-- Product Info -->
    <div class="p-2 w-full flex flex-col gap-1">
        <!-- Product Name -->
        <h3 class="text-[11px] font-semibold text-gray-800 line-clamp-1 leading-tight" title="{{ $title }}">
            {{ Str::limit($title, 22) }}
        </h3>

        <!-- Price -->
        <div class="flex items-center justify-between">
            @if($disPrice > 0 && $disPrice < $price)
                <div class="flex flex-row items-center gap-x-2 leading-none">
                    <span class="text-[12px] font-bold text-green-600">₹{{ number_format($disPrice) }}</span>
                    <span class="text-[11px] text-red-500 line-through">₹{{ number_format($price) }}</span>
                </div>
            @else
                <span class="text-[8px] font-bold text-gray-800">₹{{ number_format($price) }}</span>
            @endif

            <!-- Add-to-cart button -->
            <div class="relative">
                <!-- If not added yet -->
                <button x-show="ready && quantity === 0"
                        @click.stop.prevent="addToCart()"
                        class="bg-[#007F73] hover:bg-[#00665c] text-white p-1 rounded-lg shadow-sm transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m13-9l2 9m-5-9v9m-4-9v9"/>
                    </svg>
                </button>

                <!-- If added, show quantity -->
                <div x-show="ready && quantity > 0"
                     x-transition
                     class="bg-[#007F73] text-white p-1 rounded-lg shadow-sm flex items-center justify-center">
                    <span class="text-xs font-semibold" x-text="quantity"></span>
                </div>
            </div>
        </div>

        <!-- Reviews -->
        <div class="flex items-center gap-1 text-[11px] text-gray-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v10a2 2 0 01-2 2H7l-4 4V10a2 2 0 012-2h2m10-4h-4a2 2 0 00-2 2v4a2 2 0 002 2h4a2 2 0 002-2V6a2 2 0 00-2-2z"/>
            </svg>
            {{ $reviews }} reviews
        </div>
    </div>
</div>


