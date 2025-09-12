@props(['items' => [], 'subcategoryTitle' => '', 'subcategories' => [], 'categoryTitle' => ''])

<x-layouts.app>

    <div
        class="pt-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 font-semibold text-xs text-gray-500 inline-flex items-center text-gray-700 gap-x-3">
        <a href="{{ route('mart.index') }}" class="hover:text-violet-600">Home</a>
        <span>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                 stroke="currentColor" class="size-4 ">
              <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
            </svg>
        </span>
        @if(!empty($categoryTitle))
            <span>{{ $categoryTitle }}</span>
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="size-4 ">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                </svg>
            </span>
        @else
            <span>Groceries</span>
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="size-4 ">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                </svg>
            </span>
        @endif
        <span class="text-violet-700 font-semibold">{{ $subcategoryTitle ?: 'Top Picks' }}</span>
    </div>
    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <x-layouts.sidebar :subcategories="$subcategories" :categoryTitle="$categoryTitle"/>

        <!-- Main content -->
        <div class="flex-1 overflow-y-auto">
            <!-- Top bar -->
            <header class="flex items-center justify-between p-4 bg-white border-b shadow-md">
                <div>
                    <h1 class="text-xl font-bold text-gray-800">{{ $subcategoryTitle ?: 'Groceries' }}</h1>
                    @if(!empty($categoryTitle))
                        <p class="text-sm text-gray-500 mt-1">Category: {{ $categoryTitle }}</p>
                    @endif
                </div>
                <div class="inline-flex items-center gap-x-2">
                    <x-mart.filter/>
                    <button class="md:hidden px-3 py-2 border rounded-lg" @click="sidebarOpen = true">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75"/>
                        </svg>
                    </button>
                </div>
            </header>

            <!-- Products -->
            <main class="p-4">
                @if(count($items) > 0)
                    <div class="mb-4 text-sm text-gray-600">
                        Showing {{ count($items) }} items in {{ $subcategoryTitle }}
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 gap-y-8">
                        @foreach($items as $item)
                            <div class="w-full flex-shrink-0">
                                <div class="bg-white rounded-2xl flex flex-col gap-y-1 shadow-md hover:shadow-lg transition-shadow" x-data="{ added: false }">
                                    <div class="relative">
                                        <img src="{{ $item['photo'] }}" alt="{{ $item['name'] }}" 
                                             class="w-full h-32 object-cover rounded-t-2xl">
                                        @if($item['isBestSeller'])
                                            <span class="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">Best Seller</span>
                                        @endif
                                        @if($item['isNew'])
                                            <span class="absolute top-2 right-2 bg-green-500 text-white text-xs px-2 py-1 rounded-full">New</span>
                                        @endif
                                        @if($item['isSpotlight'])
                                            <span class="absolute bottom-2 left-2 bg-yellow-500 text-white text-xs px-2 py-1 rounded-full">⭐ Spotlight</span>
                                        @endif
                                    </div>
                                    <div class="p-3 flex-1 flex flex-col">
                                        <h3 class="font-semibold text-sm text-gray-800 mb-1 line-clamp-2">{{ $item['name'] }}</h3>
                                        <p class="text-xs text-gray-500 mb-2 line-clamp-2">{{ $item['description'] }}</p>
                                        <div class="flex items-center mb-2">
                                            <div class="flex text-yellow-400 text-xs">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= floor($item['rating']))
                                                        ★
                                                    @else
                                                        ☆
                                                    @endif
                                                @endfor
                                            </div>
                                            <span class="text-xs text-gray-500 ml-1">({{ $item['reviews'] }})</span>
                                        </div>
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex items-center space-x-2">
                                                @if($item['disPrice'] > 0)
                                                    <span class="text-lg font-bold text-violet-600">₹{{ $item['disPrice'] }}</span>
                                                    <span class="text-sm text-gray-500 line-through">₹{{ $item['price'] }}</span>
                                                @else
                                                    <span class="text-lg font-bold text-violet-600">₹{{ $item['price'] }}</span>
                                                @endif
                                            </div>
                                            <span class="text-xs text-gray-500">{{ $item['grams'] }}</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs text-gray-500">{{ $item['vendorTitle'] }}</span>
                                            @if($item['veg'])
                                                <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">🟢 Veg</span>
                                            @else
                                                <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded">🔴 Non-Veg</span>
                                            @endif
                                        </div>
                                        <button 
                                            class="mt-3 w-full bg-violet-600 text-white py-2 px-4 rounded-lg hover:bg-violet-700 transition-colors text-sm font-medium"
                                            @click="added = !added"
                                            :class="added ? 'bg-green-600 hover:bg-green-700' : 'bg-violet-600 hover:bg-violet-700'"
                                            x-text="added ? 'Added to Cart' : 'Add to Cart'">
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-gray-400 text-6xl mb-4">📦</div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">No items found</h3>
                        <p class="text-gray-500 mb-4">We couldn't find any items in the "{{ $subcategoryTitle }}" category.</p>
                        <a href="{{ route('mart.index') }}" class="inline-flex items-center px-4 py-2 bg-violet-600 text-white rounded-lg hover:bg-violet-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                            </svg>
                            Back to Home
                        </a>
                    </div>
                @endif
            </main>
        </div>
    </div>
</x-layouts.app>
