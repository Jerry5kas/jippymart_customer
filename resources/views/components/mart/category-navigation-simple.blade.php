@props([
    'categories' => []
])
<div class="w-full pb-5 pt-16" id="category-navigation">

    <!-- Categories Navigation -->
    <div class="fixed z-40 w-full border-b bg-[#F9FDF6]">
        <div class="sm:w-[90%] mx-auto w-full px-4">
            <div class="flex items-center space-x-6 overflow-x-auto scrollbar-hide py-3">
                @foreach($categories as $index => $category)
                    <button
                        class="category-tab flex items-center space-x-1 text-gray-600 font-medium flex-shrink-0 px-2 pb-1 border-b-2 transition"
                        data-category="{{ $category['title'] }}"
                        onclick="setActiveCategory('{{ $category['title'] }}')"
                        @if($index === 0) style="color: #007F73; border-color: #007F73;" @endif>
                        <span>{{ $category['title'] }}</span>
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Sub Category Items Scroll -->
    <div class="w-full pt-16 pb-8">
        @foreach($categories as $index => $category)
            <div class="category-content flex space-x-6 overflow-x-auto px-4 py-5 scrollbar-hide sm:w-[90%] mx-auto w-full text-xs"
                 data-category="{{ $category['title'] }}"
                 @if($index !== 0) style="display: none;" @endif>
                @foreach($category['subcategories'] as $sub)
                    <a href="{{ route('mart.items.by.subcategory', ['subcategoryTitle' => $sub['title']]) }}">
                        <div class="flex-shrink-0 w-20 rounded-full bg-[#F9FDF6] text-center">
                            <img src="{{ $sub['photo'] }}" alt="{{ $sub['title'] }}"
                                 class="w-20 h-20 mx-auto object-cover rounded-full shadow-lg">
                            <p class="mt-2 text-xs font-semibold text-[#007F73]">{{ $sub['title'] }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endforeach
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
        
        .category-tab.active {
            color: #007F73 !important;
            border-color: #007F73 !important;
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
            const selectedContent = document.querySelector(`[data-category="${categoryTitle}"]`);
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
            
            // Scroll to top
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
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
