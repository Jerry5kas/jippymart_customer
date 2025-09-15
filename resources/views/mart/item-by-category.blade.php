@props(['items' => [], 'subcategoryTitle' => '', 'subcategories' => [], 'categoryTitle' => ''])

<style>
    /* Enhanced grid layout for product cards */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1.5rem;
    }

    @media (max-width: 640px) {
        .product-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
    }

    @media (min-width: 641px) and (max-width: 1024px) {
        .product-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (min-width: 1025px) and (max-width: 1280px) {
        .product-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    @media (min-width: 1281px) {
        .product-grid {
            grid-template-columns: repeat(5, 1fr);
        }
    }

    /* Ensure cards maintain consistent height */
    .product-card-wrapper {
        height: 100%;
        display: flex;
        flex-direction: column;
    }
</style>

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
            <header class="flex items-center justify-between p-6 bg-gradient-to-r from-[#007F73] to-[#00A86B] text-white shadow-lg">
                <div>
                    <h1 class="text-2xl font-bold">{{ $subcategoryTitle ?: 'Groceries' }}</h1>
                    @if(!empty($categoryTitle))
                        <p class="text-sm text-green-100 mt-1">Category: {{ $categoryTitle }}</p>
                    @endif
                </div>
                <div class="inline-flex items-center gap-x-3">
                    <x-mart.filter/>
                    <button class="md:hidden px-4 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg hover:bg-white/30 transition-colors" @click="sidebarOpen = true">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75"/>
                        </svg>
                    </button>
                </div>
            </header>

            <!-- Products -->
            <main class="p-6 bg-gray-50 min-h-screen">
                @if(count($items) > 0)
                    <div class="mb-6 p-4 bg-white rounded-lg shadow-sm border-l-4 border-[#007F73]">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800">Product Collection</h2>
                                <p class="text-sm text-gray-600">Showing {{ count($items) }} items in {{ $subcategoryTitle }}</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ count($items) }} Available
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="product-grid">
                        @foreach($items as $item)
                            <div class="product-card-wrapper">
                                <x-mart.product-item-card-3
                                    :src="$item['photo']"
                                    :price="$item['price']"
                                    :disPrice="$item['disPrice']"
                                    :title="$item['name']"
                                    :description="$item['description']"
                                    :reviews="$item['reviewCount']"
                                    :rating="$item['reviewSum']"
                                    :grams="$item['grams']"
                                    :subcategoryTitle="$item['subcategoryTitle']"
                                />
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
