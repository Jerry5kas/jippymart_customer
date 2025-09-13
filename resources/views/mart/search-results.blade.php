<x-layouts.app>
    <div class="pt-20 pb-8">
        <div class="sm:w-[90%] w-full mx-auto px-4">
            <!-- Search Header -->
            <div class="mb-6">
{{--                <div class="flex items-center space-x-4 mb-4">--}}
{{--                    <a href="{{ route('mart.index') }}" class="text-purple-600 hover:text-purple-700 flex items-center space-x-2">--}}
{{--                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">--}}
{{--                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>--}}
{{--                        </svg>--}}
{{--                        <span>Back to Mart</span>--}}
{{--                    </a>--}}
{{--                </div>--}}

                <h1 class="text-2xl font-bold text-gray-800 mb-2">
                    @if($query)
                        Search Results for "{{ $query }}"
                    @else
                        Search Mart Items
                    @endif
                </h1>

                <p class="text-gray-600">
                    @if($totalResults > 0)
                        Found {{ $totalResults }} {{ $totalResults === 1 ? 'item' : 'items' }}
                    @else
                        No items found
                    @endif
                </p>
            </div>

            <!-- Search Form -->
            <div class="mb-8">
                <form action="{{ route('mart.search') }}" method="GET" class="max-w-md">
                    <div class="relative">
                        <input type="text"
                               name="q"
                               value="{{ $query }}"
                               placeholder="Search for products..."
                               class="w-full px-4 py-3 pl-12 pr-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">

                        <button type="submit" class="absolute left-4 top-3.5 text-gray-400 hover:text-purple-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 18.5a7.5 7.5 0 006.15-3.35z"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Search Results -->
            @if($totalResults > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 gap-y-8">
                        @foreach($items as $item)
                            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                                <!-- Product Image -->
                                <div class="relative">
                                    <img src="{{ $item['photo'] }}" alt="{{ $item['name'] }}"
                                         class="w-full h-40 object-cover rounded-t-xl">
                                    <!-- ADD Button -->
                                    <div class="absolute top-2 right-2" x-data="{ qty: 0 }">
                                        <button x-show="qty === 0" @click="qty = 1"
                                                class="px-3 py-1.5 bg-white text-violet-600 border border-violet-400 rounded-full text-xs font-semibold hover:bg-violet-50 transition">
                                            ADD
                                        </button>
                                        <div x-show="qty > 0" class="flex items-center space-x-2 bg-violet-600 text-white rounded-full px-3 py-1 text-xs font-semibold">
                                            <button @click="if(qty > 0) qty--" class="px-2">−</button>
                                            <span x-text="qty"></span>
                                            <button @click="qty++" class="px-2">+</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Price and Save Info -->
                                <div class="p-3 space-y-1">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-1">
                                            <span class="text-sm font-bold text-green-900">₹{{ $item['disPrice'] }}</span>
                                            @if($item['price'] > $item['disPrice'])
                                                <span class="text-xs text-red-400 line-through">₹{{ $item['price'] }}</span>
                                            @endif
                                        </div>
                                        @if($item['price'] > $item['disPrice'])
                                            <span class="text-xs bg-green-100 text-green-600 px-2 py-0.5 rounded-full font-semibold">
                                SAVE ₹{{ $item['price'] - $item['disPrice'] }}
                            </span>
                                        @endif
                                    </div>

                                    <!-- Delivery Time -->
                                    <div class="flex items-center justify-between bg-gray-100 rounded-full px-3 py-1 text-xs text-gray-500">
                                        <!-- Grams -->
                                        <div class="flex items-center space-x-1">
                                            <span>{{ $item['grams'] }}</span>
                                        </div>

                                        <!-- Time -->
                                        <div class="flex items-center space-x-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                 stroke="currentColor" stroke-width="2"
                                                 class="w-3 h-3 text-gray-600" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M12 8v4l3 3"/>
                                                <circle cx="12" cy="12" r="9"/>
                                            </svg>
                                            <span>15 mins</span>
                                        </div>
                                    </div>
                                    <!-- Name -->
                                    <h3 class="text-sm font-medium text-gray-700 truncate">{{ $item['name'] }}</h3>

                                    <!-- Ratings and Reviews -->
                                    <div class="flex items-center justify-between text-xs text-gray-500 mt-2">
                                        <!-- Rating -->
                                        <div class="flex items-end space-x-1 text-xs text-gray-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                 class="w-3 h-3 text-yellow-400" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.963a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.963c.3.921-.755 1.688-1.538 1.118L10 13.347l-3.37 2.448c-.783.57-1.838-.197-1.538-1.118l1.287-3.963a1 1 0 00-.364-1.118L3.645 9.39c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.963z"/>
                                            </svg>
                                            <span class="leading-none">{{ $item['reviewSum'] }}</span>
                                        </div>
                                        <div>
                                            <span class="leading-none text-gray-600">({{ $item['reviewCount'] }})</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                </div>
            @else
                <!-- No Results -->
                <div class="text-center py-12">
                    <div class="w-24 h-24 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 18.5a7.5 7.5 0 006.15-3.35z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">No items found</h3>
                    <p class="text-gray-600 mb-6">
                        @if($query)
                            We couldn't find any items matching "{{ $query }}". Try searching with different keywords.
                        @else
                            Enter a search term to find products in our mart.
                        @endif
                    </p>
                    <a href="{{ route('mart.index') }}" class="inline-flex items-center px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors duration-200">
                        Browse All Products
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Line Clamp CSS -->
    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</x-layouts.app>
