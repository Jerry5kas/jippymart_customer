@props([
    'type' => 'product', // product, category, banner, carousel
    'count' => 1
])

@if($type === 'product')
    <!-- Product Card Skeleton -->
    <div class="item-card-container snap-center">
        <div class="item-card-wrapper bg-white rounded-2xl shadow-sm animate-pulse">
            <!-- Image Skeleton -->
            <div class="relative h-48 bg-gray-200 rounded-t-2xl">
                <div class="absolute inset-0 bg-gradient-to-r from-gray-200 via-gray-300 to-gray-200 animate-pulse"></div>
            </div>
            
            <!-- Content Skeleton -->
            <div class="p-4 space-y-3">
                <!-- Title Skeleton -->
                <div class="h-4 bg-gray-200 rounded w-3/4 animate-pulse"></div>
                <div class="h-3 bg-gray-200 rounded w-1/2 animate-pulse"></div>
                
                <!-- Price Skeleton -->
                <div class="flex items-center space-x-2">
                    <div class="h-5 bg-gray-200 rounded w-16 animate-pulse"></div>
                    <div class="h-4 bg-gray-200 rounded w-12 animate-pulse"></div>
                </div>
                
                <!-- Rating Skeleton -->
                <div class="flex items-center space-x-1">
                    <div class="h-3 bg-gray-200 rounded w-16 animate-pulse"></div>
                    <div class="h-3 bg-gray-200 rounded w-8 animate-pulse"></div>
                </div>
                
                <!-- Button Skeleton -->
                <div class="h-8 bg-gray-200 rounded animate-pulse"></div>
            </div>
        </div>
    </div>

@elseif($type === 'category')
    <!-- Category Skeleton -->
    <div class="flex-shrink-0 w-20 rounded-full bg-gray-200 animate-pulse">
        <div class="w-20 h-20 mx-auto bg-gray-300 rounded-full animate-pulse"></div>
        <div class="mt-2 h-3 bg-gray-200 rounded w-16 mx-auto animate-pulse"></div>
    </div>

@elseif($type === 'banner')
    <!-- Banner Skeleton -->
    <div class="w-full h-64 sm:h-80 md:h-96 lg:h-[28rem] xl:h-[32rem] bg-gradient-to-br from-gray-200 to-gray-300 rounded-3xl animate-pulse">
        <div class="flex items-center justify-center h-full">
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-gray-400 rounded-full animate-pulse"></div>
                <div class="h-6 bg-gray-300 rounded w-48 mx-auto animate-pulse"></div>
                <div class="h-4 bg-gray-300 rounded w-32 mx-auto mt-2 animate-pulse"></div>
            </div>
        </div>
    </div>

@elseif($type === 'carousel')
    <!-- Carousel Skeleton -->
    <div class="product-card-container snap-center">
        <div class="bg-white rounded-2xl shadow-sm animate-pulse">
            <!-- Image Skeleton -->
            <div class="relative h-40 bg-gray-200 rounded-t-2xl">
                <div class="absolute inset-0 bg-gradient-to-r from-gray-200 via-gray-300 to-gray-200 animate-pulse"></div>
            </div>
            
            <!-- Content Skeleton -->
            <div class="p-3 space-y-2">
                <div class="h-3 bg-gray-200 rounded w-3/4 animate-pulse"></div>
                <div class="h-4 bg-gray-200 rounded w-1/2 animate-pulse"></div>
                <div class="h-3 bg-gray-200 rounded w-1/3 animate-pulse"></div>
            </div>
        </div>
    </div>

@elseif($type === 'section')
    <!-- Section Skeleton -->
    <div class="bg-white rounded-2xl shadow-sm p-6 animate-pulse">
        <!-- Header Skeleton -->
        <div class="h-8 bg-gray-200 rounded w-1/3 mb-6 animate-pulse"></div>
        
        <!-- Items Grid Skeleton -->
        <div class="flex gap-6 overflow-x-auto">
            @for($i = 0; $i < $count; $i++)
                <div class="item-card-container snap-center">
                    <div class="item-card-wrapper bg-white rounded-2xl shadow-sm animate-pulse">
                        <div class="h-48 bg-gray-200 rounded-t-2xl animate-pulse"></div>
                        <div class="p-4 space-y-3">
                            <div class="h-4 bg-gray-200 rounded w-3/4 animate-pulse"></div>
                            <div class="h-3 bg-gray-200 rounded w-1/2 animate-pulse"></div>
                            <div class="h-5 bg-gray-200 rounded w-16 animate-pulse"></div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </div>
@endif

<style>
    @keyframes shimmer {
        0% {
            background-position: -200px 0;
        }
        100% {
            background-position: calc(200px + 100%) 0;
        }
    }
    
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    .animate-shimmer {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200px 100%;
        animation: shimmer 1.5s infinite;
    }
</style>
