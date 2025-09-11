@props([
    'src' => 'https://icon2.cleanpng.com/lnd/20250108/yj/011d1e60d8d65ba818e537fc0cf2d3.webp',
    'title' => 'Fruits & Veg',
    'price' => 0,
    'disPrice' => 0,
    'rating' => 4.5,
    'reviews' => 100,
    ])

<div class="flex-shrink-0 w-32 h-auto rounded-xl shadow flex flex-col items-center justify-center snap-center bg-white">
    <!-- Product Image -->
    <div class="relative w-full h-24 rounded-t-xl overflow-hidden">
        <img
            src="{{ $src }}"
            alt="{{ $title }}" 
            class="w-full h-full object-cover"
            onerror="this.src='https://via.placeholder.com/150'">
        
        <!-- Rating Badge -->
        <div class="absolute top-1 right-1 bg-yellow-400 text-black text-xs px-1 rounded flex items-center">
            <span class="text-xs">★</span>
            <span class="text-xs ml-0.5">{{ number_format($rating, 1) }}</span>
        </div>
    </div>
    
    <!-- Product Info -->
    <div class="p-2 w-full">
        <!-- Product Name -->
        <h3 class="text-xs font-semibold text-gray-800 line-clamp-2 mb-1" title="{{ $title }}">
            {{ Str::limit($title, 20) }}
        </h3>
        
        <!-- Price -->
        <div class="flex items-center justify-between">
            @if($disPrice > 0 && $disPrice < $price)
                <div class="flex flex-col">
                    <span class="text-xs font-bold text-green-600">₹{{ number_format($disPrice) }}</span>
                    <span class="text-xs text-gray-500 line-through">₹{{ number_format($price) }}</span>
                </div>
            @else
                <span class="text-xs font-bold text-gray-800">₹{{ number_format($price) }}</span>
            @endif
</div>

        <!-- Reviews -->
        <div class="text-xs text-gray-500 mt-1">
            {{ $reviews }} reviews
        </div>
    </div>
</div>