@props(['items' => [], 'categories' => [], 'subcategories' => [], 'filters' => [], 'totalItems' => 0])

<style>
    /* Enhanced grid layout for product cards */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 1.5rem;
        width: 100%;
        max-width: 100%;
        overflow: hidden;
    }

    @media (max-width: 640px) {
        .product-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            minmax(180px, 1fr);
        }
    }

    @media (min-width: 641px) and (max-width: 1024px) {
        .product-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 1.25rem;
        }
    }

    @media (min-width: 1025px) and (max-width: 1280px) {
        .product-grid {
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
        }
    }

    @media (min-width: 1281px) {
        .product-grid {
            grid-template-columns: repeat(5, 1fr);
            gap: 1.5rem;
        }
    }

    /* Ensure cards maintain consistent height and don't overflow */
    .product-card-wrapper {
        height: 100%;
        display: flex;
        flex-direction: column;
        width: 100%;
        max-width: 100%;
        overflow: hidden;
        border-radius: 12px;
        background: white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .product-card-wrapper:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    /* Ensure product cards fit within their containers */
    .product-card-wrapper .w-full {
        width: 100% !important;
        max-width: 100% !important;
    }

    /* Fix any potential overflow issues */
    .product-card-wrapper img {
        width: 100% !important;
        height: auto !important;
        max-width: 100% !important;
        object-fit: cover;
    }

    /* Enhanced Filter sidebar styles */
    .filter-sidebar {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 127, 115, 0.1);
        backdrop-filter: blur(10px);
    }

    .filter-section {
        padding: 2rem;
        position: relative;
    }

    .filter-section:not(:last-child)::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 2rem;
        right: 2rem;
        height: 1px;
        background: linear-gradient(90deg, transparent 0%, rgba(0, 127, 115, 0.2) 50%, transparent 100%);
    }

    .filter-group {
        margin-bottom: 2rem;
    }

    .filter-group:last-child {
        margin-bottom: 0;
    }

    .filter-label {
        display: block;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.75rem;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .filter-input {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        background: white;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
    }

    .filter-input:focus {
        outline: none;
        border-color: #007F73;
        box-shadow: 0 0 0 3px rgba(0, 127, 115, 0.1);
        transform: translateY(-1px);
    }

    .filter-input:hover {
        border-color: #d1d5db;
        transform: translateY(-1px);
    }

    .price-range-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
    }

    .filter-button {
        padding: 0.875rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .filter-button::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .filter-button:hover::before {
        left: 100%;
    }

    .filter-button-primary {
        background: linear-gradient(135deg, #007F73 0%, #00A86B 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(0, 127, 115, 0.3);
    }

    .filter-button-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 127, 115, 0.4);
    }

    .filter-button-secondary {
        background: #f3f4f6;
        color: #6b7280;
        border: 2px solid #e5e7eb;
    }

    .filter-button-secondary:hover {
        background: #e5e7eb;
        color: #374151;
        transform: translateY(-1px);
    }

    .filter-buttons {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-top: 2rem;
    }

    .filter-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid rgba(0, 127, 115, 0.1);
    }

    .filter-icon {
        width: 1.5rem;
        height: 1.5rem;
        color: #007F73;
    }

    .filter-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }

    .filter-count {
        background: linear-gradient(135deg, #007F73, #00A86B);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-left: auto;
    }

    .filter-reset {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-reset:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        color: white;
    }
</style>

<x-layouts.app>
    <div class="pt-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 font-semibold text-xs text-gray-500 inline-flex items-center text-gray-700 gap-x-3">
        <a href="{{ route('mart.index') }}" class="hover:text-violet-600">Home</a>
        <span>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                 stroke="currentColor" class="size-4 ">
              <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
            </svg>
        </span>
        <span class="text-violet-700 font-semibold">All Items</span>
    </div>

    <div x-data="{ sidebarOpen: false, filtersOpen: false }" class="flex h-screen overflow-hidden">
        <!-- Enhanced Filter Sidebar -->
        <div class="hidden lg:block w-96 bg-gradient-to-b from-gray-50 to-gray-100 overflow-y-auto">
            <div class="p-6">
                <div class="filter-sidebar p-2">
                    <!-- Filter Header -->
                    <div class="filter-header">
                        <svg class="filter-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        <h3 class="filter-title">Smart Filters</h3>
                        <span class="filter-count">{{ $totalItems }}</span>
                    </div>

                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('mart.all.items') }}" class="space-y-6">
                        <!-- Preserve search parameter -->
                        @if(!empty($filters['search']))
                            <input type="hidden" name="search" value="{{ $filters['search'] }}">
                        @endif

                        <!-- Category Filter -->
                        <div class="filter-group">
                            <label class="filter-label">
                                <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                Category
                            </label>
                            <select name="category" class="filter-input">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category['title'] }}" {{ ($filters['category'] ?? '') === $category['title'] ? 'selected' : '' }}>
                                        {{ $category['title'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Subcategory Filter -->
                        <div class="filter-group">
                            <label class="filter-label">
                                <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                Subcategory
                            </label>
                            <select name="subcategory" class="filter-input">
                                <option value="">All Subcategories</option>
                                @foreach($subcategories as $subcategory)
                                    <option value="{{ $subcategory['title'] }}" {{ ($filters['subcategory'] ?? '') === $subcategory['title'] ? 'selected' : '' }}>
                                        {{ $subcategory['title'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Price Range Filter -->
                        <div class="filter-group">
                            <label class="filter-label">
                                <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                Price Range
                            </label>
                            <div class="price-range-container">
                                <input type="number"
                                       name="price_min"
                                       value="{{ $filters['price_min'] ?? '' }}"
                                       placeholder="Min ₹"
                                       class="filter-input">
                                <input type="number"
                                       name="price_max"
                                       value="{{ $filters['price_max'] ?? '' }}"
                                       placeholder="Max ₹"
                                       class="filter-input">
                            </div>
                        </div>

                        <!-- Sort Filter -->
                        <div class="filter-group">
                            <label class="filter-label">
                                <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                </svg>
                                Sort By
                            </label>
                            <select name="sort" class="filter-input">
                                <option value="name" {{ ($filters['sort'] ?? 'name') === 'name' ? 'selected' : '' }}>Name (A-Z)</option>
                                <option value="price_low" {{ ($filters['sort'] ?? '') === 'price_low' ? 'selected' : '' }}>Price (Low to High)</option>
                                <option value="price_high" {{ ($filters['sort'] ?? '') === 'price_high' ? 'selected' : '' }}>Price (High to Low)</option>
                                <option value="rating" {{ ($filters['sort'] ?? '') === 'rating' ? 'selected' : '' }}>Rating (High to Low)</option>
                            </select>
                        </div>

                        <!-- Filter Buttons -->
                        <div class="filter-buttons">
                            <button type="submit" class="flex items-center justify-center gap-x-2 filter-button filter-button-primary">
                                <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Apply Filters
                            </button>
                            <a href="{{ route('mart.all.items') }}" class="flex items-center justify-center gap-x-2 filter-button filter-button-secondary text-center">
                                <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Reset
                            </a>
                        </div>

                        <!-- Active Filters Indicator -->
                        @if(!empty($filters['category']) || !empty($filters['subcategory']) || !empty($filters['price_min']) || !empty($filters['price_max']) || ($filters['sort'] ?? 'name') !== 'name')
                            <div class="mt-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-blue-800">Filters Active</span>
                                    </div>
                                    <a href="{{ route('mart.all.items') }}" class="filter-reset">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Clear All
                                    </a>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="flex-1 overflow-y-auto">
            <!-- Top bar -->
            <header class="flex items-center justify-between p-6 bg-gradient-to-r from-[#007F73] to-[#00A86B] text-white shadow-lg">
                <div>
                    @if(!empty($filters['search']))
                        <h1 class="text-2xl font-bold">Search Results</h1>
                        <p class="text-sm text-green-100 mt-1">Results for "{{ $filters['search'] }}"</p>
                    @else
                        <h1 class="text-2xl font-bold">All Items</h1>
                        <p class="text-sm text-green-100 mt-1">Browse all available products</p>
                    @endif
                </div>
                <div class="inline-flex items-center gap-x-3">
                    @if(!empty($filters['search']))
                        <!-- Clear Search Button -->
                        <a href="{{ route('mart.all.items') }}"
                           class="px-4 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg hover:bg-white/30 transition-colors text-sm">
                            Clear Search
                        </a>
                    @endif
                    <!-- Mobile Filter Button -->
                    <button class="lg:hidden px-4 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg hover:bg-white/30 transition-colors"
                            @click="filtersOpen = true">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75"/>
                        </svg>
                    </button>
                </div>
            </header>

            <!-- Products -->
            <main class="p-6 bg-gray-50 min-h-screen overflow-hidden">
                @if(count($items) > 0)
                    <div class="mb-6 p-4 bg-white rounded-lg shadow-sm border-l-4 border-[#007F73]">
                        <div class="flex items-center justify-between">
                            <div>
                                @if(!empty($filters['search']))
                                    <h2 class="text-lg font-semibold text-gray-800">Search Results</h2>
                                    <p class="text-sm text-gray-600">Found {{ $totalItems }} items matching "{{ $filters['search'] }}"</p>
                                @else
                                    <h2 class="text-lg font-semibold text-gray-800">Product Collection</h2>
                                    <p class="text-sm text-gray-600">Showing {{ $totalItems }} items</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $totalItems }} Available
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="w-full max-w-full overflow-hidden">
                        <div class="product-grid">
                            @foreach($items as $item)
                                <div class="product-card-wrapper">
                                    <a href="{{ route('mart.items.by.subcategory', ['subcategoryTitle' => $item['subcategoryTitle']]) }}" class="block h-full w-full">
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
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <!-- ELEGANT EMPTY STATE - MATCHES CATEGORY PAGE DESIGN -->
                    <div class="text-center py-16 px-8">
                        <div class="mx-auto mb-6">
                            <div class="w-24 h-24 mx-auto bg-gradient-to-br from-green-100 to-green-200 rounded-full flex items-center justify-center">
                                @if(!empty($filters['search']))
                                    <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                @else
                                    <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                @endif
                            </div>
                        </div>
                        @if(!empty($filters['search']))
                            <h3 class="text-2xl font-bold text-gray-800 mb-3">No search results found</h3>
                            <p class="text-gray-600 mb-8 max-w-md mx-auto">We couldn't find any items matching "{{ $filters['search'] }}". Try different keywords or browse all items.</p>
                        @else
                            <h3 class="text-2xl font-bold text-gray-800 mb-3">No items found</h3>
                            <p class="text-gray-600 mb-8 max-w-md mx-auto">We couldn't find any items matching your filters.</p>
                        @endif
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="{{ route('mart.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#007F73] to-[#00A86B] text-white rounded-lg hover:from-[#005f56] hover:to-[#008a5a] transition-all duration-300 shadow-lg hover:shadow-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Back to Home
                            </a>
                            <a href="{{ route('mart.all.items') }}" class="inline-flex items-center px-6 py-3 bg-white text-[#007F73] border-2 border-[#007F73] rounded-lg hover:bg-[#007F73] hover:text-white transition-all duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                @if(!empty($filters['search']))
                                    Browse All Items
                                @else
                                    Clear Filters
                                @endif
                            </a>
                        </div>
                    </div>
                @endif
            </main>
        </div>
    </div>

    <!-- Enhanced Mobile Filter Modal -->
    <div x-show="filtersOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity" @click="filtersOpen = false"></div>

            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-[#007F73] to-[#00A86B] px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            <h3 class="text-xl font-bold text-white">Smart Filters</h3>
                        </div>
                        <button @click="filtersOpen = false" class="text-white hover:text-gray-200 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="bg-white px-6 py-6">
                    <form method="GET" action="{{ route('mart.all.items') }}" class="space-y-6">
                        <!-- Preserve search parameter -->
                        @if(!empty($filters['search']))
                            <input type="hidden" name="search" value="{{ $filters['search'] }}">
                        @endif

                        <!-- Category Filter -->
                        <div class="filter-group">
                            <label class="filter-label">
                                <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                Category
                            </label>
                            <select name="category" class="filter-input">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category['title'] }}" {{ ($filters['category'] ?? '') === $category['title'] ? 'selected' : '' }}>
                                        {{ $category['title'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Subcategory Filter -->
                        <div class="filter-group">
                            <label class="filter-label">
                                <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                Subcategory
                            </label>
                            <select name="subcategory" class="filter-input">
                                <option value="">All Subcategories</option>
                                @foreach($subcategories as $subcategory)
                                    <option value="{{ $subcategory['title'] }}" {{ ($filters['subcategory'] ?? '') === $subcategory['title'] ? 'selected' : '' }}>
                                        {{ $subcategory['title'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Price Range Filter -->
                        <div class="filter-group">
                            <label class="filter-label">
                                <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                Price Range
                            </label>
                            <div class="price-range-container">
                                <input type="number"
                                       name="price_min"
                                       value="{{ $filters['price_min'] ?? '' }}"
                                       placeholder="Min ₹"
                                       class="filter-input">
                                <input type="number"
                                       name="price_max"
                                       value="{{ $filters['price_max'] ?? '' }}"
                                       placeholder="Max ₹"
                                       class="filter-input">
                            </div>
                        </div>

                        <!-- Sort Filter -->
                        <div class="filter-group">
                            <label class="filter-label">
                                <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                </svg>
                                Sort By
                            </label>
                            <select name="sort" class="filter-input">
                                <option value="name" {{ ($filters['sort'] ?? 'name') === 'name' ? 'selected' : '' }}>Name (A-Z)</option>
                                <option value="price_low" {{ ($filters['sort'] ?? '') === 'price_low' ? 'selected' : '' }}>Price (Low to High)</option>
                                <option value="price_high" {{ ($filters['sort'] ?? '') === 'price_high' ? 'selected' : '' }}>Price (High to Low)</option>
                                <option value="rating" {{ ($filters['sort'] ?? '') === 'rating' ? 'selected' : '' }}>Rating (High to Low)</option>
                            </select>
                        </div>

                        <!-- Filter Buttons -->
                        <div class="filter-buttons">
                            <button type="submit" class="filter-button filter-button-primary">
                                <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Apply Filters
                            </button>
                            <a href="{{ route('mart.all.items') }}" class="filter-button filter-button-secondary text-center">
                                <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Cart Popup -->
    <x-mart.cart-popup />
</x-layouts.app>