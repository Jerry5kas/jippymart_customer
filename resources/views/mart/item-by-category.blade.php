@props(['items' => [], 'subcategoryTitle' => '', 'subcategories' => [], 'categoryTitle' => ''])

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

    /* Enhanced sidebar styles */
    .sidebar-container {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 127, 115, 0.1);
        backdrop-filter: blur(10px);
    }

    .sidebar-header {
        background: linear-gradient(135deg, #007F73 0%, #00A86B 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 20px 20px 0 0;
    }

    .sidebar-content {
        padding: 1.5rem;
    }

    .subcategory-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 1rem;
        margin-bottom: 0.5rem;
        border-radius: 12px;
        transition: all 0.3s ease;
        cursor: pointer;
        border: 2px solid transparent;
    }

    .subcategory-item:hover {
        background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
        border-color: rgba(0, 127, 115, 0.2);
        transform: translateX(4px);
    }

    .subcategory-item.active {
        background: linear-gradient(135deg, #007F73 0%, #00A86B 100%);
        color: white;
        border-color: #007F73;
    }

    .subcategory-count {
        background: rgba(0, 127, 115, 0.1);
        color: #007F73;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .subcategory-item.active .subcategory-count {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }

    /* Enhanced header styles */
    .page-header {
        background: linear-gradient(135deg, #007F73 0%, #00A86B 100%);
        box-shadow: 0 4px 20px rgba(0, 127, 115, 0.3);
    }

    .breadcrumb {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        padding: 0.75rem 1rem;
    }

    .breadcrumb a {
        color: rgba(255, 255, 255, 0.9);
        transition: color 0.3s ease;
    }

    .breadcrumb a:hover {
        color: white;
    }

    /* Enhanced main content area */
    .main-content {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        min-height: 100vh;
    }

    .content-header {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 127, 115, 0.1);
    }

    .filter-button {
        background: linear-gradient(135deg, #007F73 0%, #00A86B 100%);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(0, 127, 115, 0.3);
    }

    .filter-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 127, 115, 0.4);
    }

    /* Enhanced empty state */
    .empty-state {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 127, 115, 0.1);
    }

    .empty-state-icon {
        background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
        border-radius: 50%;
        padding: 2rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
</style>

<x-layouts.app>

    <div class="pt-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="breadcrumb inline-flex items-center gap-x-3 text-sm font-medium">
            <a href="{{ route('mart.index') }}" class="flex items-center gap-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Home
            </a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            @if(!empty($categoryTitle))
                <span class="flex items-center gap-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    {{ $categoryTitle }}
                </span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            @else
                <span class="flex items-center gap-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    Groceries
                </span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            @endif
            <span class="flex items-center gap-x-2 font-semibold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                {{ $subcategoryTitle ?: 'Top Picks' }}
            </span>
        </div>
    </div>
    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">
        <!-- Enhanced Sidebar -->
        <div class="hidden lg:block w-80 bg-gradient-to-b from-gray-50 to-gray-100 overflow-y-auto">
            <div class="p-6">
                <div class="sidebar-container">
                    <!-- Sidebar Header -->
                    <div class="sidebar-header">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            <h3 class="text-xl font-bold">Subcategories</h3>
                        </div>
                        <p class="text-sm text-green-100 mt-2">{{ count($subcategories) }} available</p>
                    </div>

                    <!-- Sidebar Content -->
                    <div class="sidebar-content">
                        @foreach($subcategories as $subcategory)
                            <a href="{{ route('mart.items.by.subcategory', ['subcategoryTitle' => $subcategory['title']]) }}"
                               class="subcategory-item {{ $subcategoryTitle === $subcategory['title'] ? 'active' : '' }}">
                                <div class="flex items-center gap-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    <span class="font-medium">{{ $subcategory['title'] }}</span>
                                </div>
                                <span class="subcategory-count">{{ $subcategory['itemCount'] ?? 0 }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="flex-1 overflow-y-auto main-content">
            <!-- Enhanced Top bar -->
            <header class="page-header flex items-center justify-between p-6 text-white">
                <div>
                    <h1 class="text-3xl font-bold">{{ $subcategoryTitle ?: 'Groceries' }}</h1>
                    @if(!empty($categoryTitle))
                        <p class="text-sm text-green-100 mt-2 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            Category: {{ $categoryTitle }}
                        </p>
                    @endif
                </div>
{{--                <div class="inline-flex items-center gap-x-3">--}}
{{--                    <x-mart.filter/>--}}
{{--                    <button class="lg:hidden filter-button" @click="sidebarOpen = true">--}}
{{--                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"--}}
{{--                             stroke="currentColor" class="size-5">--}}
{{--                            <path stroke-linecap="round" stroke-linejoin="round"--}}
{{--                                  d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75"/>--}}
{{--                        </svg>--}}
{{--                    </button>--}}
{{--                </div>--}}
            </header>

            <!-- Enhanced Products Section -->
            <main class="p-6 min-h-screen">
                @if(count($items) > 0)
                    <div class="content-header mb-8 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                                    <svg class="w-6 h-6 text-[#007F73]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    Product Collection
                                </h2>
                                <p class="text-sm text-gray-600 mt-2">Showing {{ count($items) }} items in {{ $subcategoryTitle }}</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ count($items) }} Available
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
                    <div class="empty-state text-center py-16 px-8">
                        <div class="empty-state-icon mx-auto mb-6">
                            <svg class="w-16 h-16 text-[#007F73]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-3">No items found</h3>
                        <p class="text-gray-600 mb-8 max-w-md mx-auto">We couldn't find any items in the "{{ $subcategoryTitle }}" category. Try browsing other subcategories or go back to explore all products.</p>
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
                                Browse All Items
                            </a>
                        </div>
                    </div>
                @endif
            </main>
        </div>
    </div>
    
    <!-- Cart Popup -->
    <x-mart.cart-popup />
</x-layouts.app>
