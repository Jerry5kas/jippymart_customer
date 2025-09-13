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
                            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                                <!-- Product Image -->
                                <div class="relative">
                                    <img src="{{ $item['photo'] }}" alt="{{ $item['name'] }}"
                                         class="w-full h-40 object-cover rounded-t-xl">
                                    <!-- ADD Button -->
                                    <div class="absolute bottom-2 left-2" x-data="{ qty: 0 }">
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
                                                                            <span
                                            class="self-end bg-gradient-to-r from-green-200 to-white text-green-600 text-[9px] px-2 font-semibold p-0.5 rounded-sm inline-flex items-center gap-x-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                 fill="currentColor" class="size-3">
                                              <path fill-rule="evenodd"
                                                    d="M5.25 2.25a3 3 0 0 0-3 3v4.318a3 3 0 0 0 .879 2.121l9.58 9.581c.92.92 2.39 1.186 3.548.428a18.849 18.849 0 0 0 5.441-5.44c.758-1.16.492-2.629-.428-3.548l-9.58-9.581a3 3 0 0 0-2.122-.879H5.25ZM6.375 7.5a1.125 1.125 0 1 0 0-2.25 1.125 1.125 0 0 0 0 2.25Z"
                                                    clip-rule="evenodd"/>
                                            </svg>
                                             SAVE ₹30</span>
                                    </div>
                                    <div class="flex items-center space-x-1 text-xs text-gray-500">
                                            <span>{{ $item['grams'] }}</span>
                                        </div>

                                        <h3 class="text-sm font-medium text-gray-700 truncate text-xs">{{ $item['name'] }}</h3>

                                    <!-- Delivery Time -->
                                    <div class="flex items-center justify-between bg-gray-100 rounded-full px-3 py-1 text-xs text-gray-500">
                                        <!-- Grams -->
                                        

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
