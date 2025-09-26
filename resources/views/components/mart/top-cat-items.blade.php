@props([
    'categories' => 'categories'
])

<!-- Quick debug to see what's happening -->
<div class="bg-blue-100 p-2 text-lg border border-blue-300">
    <strong>DEBUG:</strong> 
    Count: {{ count($categories ?? []) }} | 
    Empty: {{ empty($categories) ? 'YES' : 'NO' }} |
    @if(!empty($categories))
        First: {{ $categories[0]['title'] ?? 'NO TITLE' }} |
        Keys: {{ implode(',', array_keys($categories[0] ?? [])) }}
    @endif
</div>

<div class="w-full pb-5 pt-8" id="top-cat-items">

    <!-- Categories Navigation -->
    <div class="w-full border-b bg-[#F9FDF6]" style="background-color: #F9FDF6 !important;">
        <div class="sm:w-[90%] mx-auto w-full px-4">
            <div class="flex items-center space-x-6 overflow-x-auto scrollbar-hide py-3" style="min-height: 60px;">
                @if(!empty($categories) && count($categories) > 0)
                    @foreach($categories as $index => $category)
                        <button
                            class="category-tab flex items-center space-x-1 text-gray-600 font-semibold flex-shrink-0 px-3 py-2 border-b-2 transition text-sm"
                            data-category="{{ $category['title'] ?? $category['name'] ?? 'Category' }}"
                            onclick="setActiveCategory('{{ $category['title'] ?? $category['name'] ?? 'Category' }}')"
                            @if($index === 0) style="color: #007F73 !important; border-color: #007F73 !important;" @endif>
                            <span style="color: inherit !important;">{{ $category['title'] ?? $category['name'] ?? 'Category' }}</span>
                        </button>
                    @endforeach
                @else
                    <!-- Fallback categories if data is empty -->
                    <button class="category-tab flex items-center space-x-1 text-[#007F73] font-semibold flex-shrink-0 px-3 py-2 border-b-2 border-[#007F73] text-sm" style="color: #007F73 !important; border-color: #007F73 !important;">
                        <span>Personal Care</span>
                    </button>
                    <button class="category-tab flex items-center space-x-1 text-gray-600 font-semibold flex-shrink-0 px-3 py-2 border-b-2 text-sm" style="color: #6B7280 !important;">
                        <span>Home & Health</span>
                    </button>
                    <button class="category-tab flex items-center space-x-1 text-gray-600 font-semibold flex-shrink-0 px-3 py-2 border-b-2 text-sm" style="color: #6B7280 !important;">
                        <span>Pet Care</span>
                    </button>
                    <button class="category-tab flex items-center space-x-1 text-gray-600 font-semibold flex-shrink-0 px-3 py-2 border-b-2 text-sm" style="color: #6B7280 !important;">
                        <span>Cooking Essentials</span>
                    </button>
                    <button class="category-tab flex items-center space-x-1 text-gray-600 font-semibold flex-shrink-0 px-3 py-2 border-b-2 text-sm" style="color: #6B7280 !important;">
                        <span>Fruits & Vegetables</span>
                    </button>
                    <button class="category-tab flex items-center space-x-1 text-gray-600 font-semibold flex-shrink-0 px-3 py-2 border-b-2 text-sm" style="color: #6B7280 !important;">
                        <span>Mom & Baby Care</span>
                    </button>
                    <button class="category-tab flex items-center space-x-1 text-gray-600 font-semibold flex-shrink-0 px-3 py-2 border-b-2 text-sm" style="color: #6B7280 !important;">
                        <span>Dairy & Bakery</span>
                    </button>
                    <button class="category-tab flex items-center space-x-1 text-gray-600 font-semibold flex-shrink-0 px-3 py-2 border-b-2 text-sm" style="color: #6B7280 !important;">
                        <span>Cookies & Biscuits</span>
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Sub Category Items Scroll -->
    <div class="w-full pt-8 pb-8">
        @if(!empty($categories) && count($categories) > 0)
            @foreach($categories as $index => $category)
            <div class="category-content flex space-x-6 overflow-x-auto px-4 py-5 scrollbar-hide sm:w-[90%] mx-auto w-full text-xs"
                 data-category="{{ $category['title'] }}"
                 id="content-{{ $index }}"
                 @if($index !== 0) style="display: none;" @endif>
                @if(isset($category['subcategories']) && is_array($category['subcategories']))
                    @foreach($category['subcategories'] as $sub)
                        <a href="{{ route('mart.items.by.subcategory', ['subcategoryTitle' => $sub['title']]) }}">
                            <div class="flex-shrink-0 w-20 rounded-full bg-[#F9FDF6] text-center">
                                <img src="{{ $sub['photo'] }}" alt="{{ $sub['title'] }}"
                                     class="w-20 h-20 mx-auto object-cover rounded-full shadow-lg">
                                <p class="mt-2 text-xs font-medium text-[#007F73] text-center">{{ $sub['title'] }}</p>
                            </div>
                        </a>
                    @endforeach
                @else
                    <div class="text-center text-gray-500 py-4">
                        <p>No subcategories available for {{ $category['title'] }}</p>
                    </div>
                @endif
            </div>
            @endforeach
        @else
            <!-- Fallback subcategories if no data -->
            <div class="category-content flex space-x-6 overflow-x-auto px-4 py-5 scrollbar-hide sm:w-[90%] mx-auto w-full text-xs">
                <!-- Sample subcategories for Personal Care -->
                <a href="#" class="flex-shrink-0">
                    <div class="w-20 rounded-full bg-[#F9FDF6] text-center">
                        <div class="w-20 h-20 mx-auto bg-gray-200 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <p class="mt-2 text-xs font-medium text-[#007F73] text-center">Men's Hygiene</p>
                    </div>
                </a>
                <a href="#" class="flex-shrink-0">
                    <div class="w-20 rounded-full bg-[#F9FDF6] text-center">
                        <div class="w-20 h-20 mx-auto bg-gray-200 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="mt-2 text-xs font-medium text-[#007F73] text-center">Hair Care</p>
                    </div>
                </a>
                <a href="#" class="flex-shrink-0">
                    <div class="w-20 rounded-full bg-[#F9FDF6] text-center">
                        <div class="w-20 h-20 mx-auto bg-gray-200 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                            </svg>
                        </div>
                        <p class="mt-2 text-xs font-medium text-[#007F73] text-center">Bath & Hand</p>
                    </div>
                </a>
                <a href="#" class="flex-shrink-0">
                    <div class="w-20 rounded-full bg-[#F9FDF6] text-center">
                        <div class="w-20 h-20 mx-auto bg-gray-200 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <p class="mt-2 text-xs font-medium text-[#007F73] text-center">Oral Care</p>
                    </div>
                </a>
            </div>
        @endif
    </div>

    <!-- Hide scrollbar helper -->
    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        /* Force categories to be visible */
        .category-tab {
            display: flex !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
        
        #top-cat-items {
            display: block !important;
            visibility: visible !important;
        }
        
        .category-tab.active {
            color: #007F73 !important;
            border-color: #007F73 !important;
            font-weight: 600;
        }
        
        .category-tab:hover {
            color: #007F73;
            border-color: #007F73;
        }
        
        .category-content {
            transition: opacity 0.3s ease-in-out;
        }
    </style>

    <script>
        function setActiveCategory(categoryTitle) {
            // Hide all category contents
            document.querySelectorAll('.category-content').forEach(content => {
                content.style.display = 'none';
            });
            
            // Remove active class from all tabs
            document.querySelectorAll('.category-tab').forEach(tab => {
                tab.classList.remove('active');
                tab.style.color = '#6B7280';
                tab.style.borderColor = 'transparent';
            });
            
            // Show selected category content
            let selectedContent = document.querySelector(`.category-content[data-category="${categoryTitle}"]`);
            
            // If not found by data-category, try to find by index
            if (!selectedContent) {
                const tabs = document.querySelectorAll('.category-tab');
                tabs.forEach((tab, index) => {
                    if (tab.getAttribute('data-category') === categoryTitle) {
                        selectedContent = document.getElementById(`content-${index}`);
                    }
                });
            }
            
            if (selectedContent) {
                selectedContent.style.display = 'flex';
            }
            
            // Add active class to selected tab
            const selectedTab = document.querySelector(`.category-tab[data-category="${categoryTitle}"]`);
            if (selectedTab) {
                selectedTab.classList.add('active');
                selectedTab.style.color = '#007F73';
                selectedTab.style.borderColor = '#007F73';
            }
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Ensure first category is active
            const firstCategory = document.querySelector('.category-tab');
            if (firstCategory) {
                const categoryTitle = firstCategory.getAttribute('data-category');
                setActiveCategory(categoryTitle);
            }
        });
    </script>

</div>

