@props([
    'categories' => []
])
<div x-data="categoryNavigation({{ json_encode($categories) }})" 
     x-init="init()" 
     class="w-full pb-5 pt-16">

    <!-- Categories Navigation -->
    <div class="fixed z-40 w-full border-b bg-[#F9FDF6]">
        <div class="sm:w-[90%] mx-auto w-full px-4">
            <div class="flex items-center space-x-6 overflow-x-auto scrollbar-hide py-3">
                <template x-for="(category, index) in categories" :key="index">
                    <button
                        class="flex items-center space-x-1 text-gray-600 font-medium flex-shrink-0 px-2 pb-1 border-b-2 transition"
                        :class="activeCategory === category.title ? 'text-[#007F73] border-[#007F73]' : 'border-transparent hover:text-[#007F73]'"
                        @click="setActiveCategory(category.title)">
                        <span x-text="category.title"></span>
                    </button>
                </template>
            </div>
        </div>
    </div>

    <!-- Sub Category Items Scroll -->
    <div class="w-full pt-16 pb-8">
        <!-- Loading state to prevent flash of all categories -->
        <div x-show="!isInitialized" class="flex space-x-6 overflow-x-auto px-4 py-5 scrollbar-hide sm:w-[90%] mx-auto w-full text-xs">
            <template x-for="(sub, subIndex) in getFirstCategorySubcategories()" :key="subIndex">
                <a :href="`/mart/items/subcategory/${encodeURIComponent(sub.title)}`">
                    <div class="flex-shrink-0 w-20 rounded-full bg-[#F9FDF6] text-center">
                        <img :src="sub.photo" :alt="sub.title"
                             class="w-20 h-20 mx-auto object-cover rounded-full shadow-lg">
                        <p class="mt-2 text-xs font-semibold text-[#007F73]" x-text="sub.title"></p>
                    </div>
                </a>
            </template>
        </div>

        <!-- Dynamic content after Alpine.js initialization -->
        <template x-for="(category, index) in categories" :key="index">
            <div x-show="isInitialized && activeCategory === category.title" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 class="flex space-x-6 overflow-x-auto px-4 py-5 scrollbar-hide sm:w-[90%] mx-auto w-full text-xs">
                <template x-for="(sub, subIndex) in category.subcategories" :key="subIndex">
                    <a :href="`/mart/items/subcategory/${encodeURIComponent(sub.title)}`">
                        <div class="flex-shrink-0 w-20 rounded-full bg-[#F9FDF6] text-center">
                            <img :src="sub.photo" :alt="sub.title"
                                 class="w-20 h-20 mx-auto object-cover rounded-full shadow-lg">
                            <p class="mt-2 text-xs font-semibold text-[#007F73]" x-text="sub.title"></p>
                        </div>
                    </a>
                </template>
            </div>
        </template>
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
    </style>
</div>

<script>
function categoryNavigation(categories) {
    return {
        categories: categories,
        activeCategory: categories.length > 0 ? categories[0].title : '',
        isInitialized: false,
        
        init() {
            // Set initial state
            this.activeCategory = categories.length > 0 ? categories[0].title : '';
            
            // Use nextTick to ensure DOM is ready
            this.$nextTick(() => {
                this.isInitialized = true;
            });
        },
        
        setActiveCategory(categoryTitle) {
            this.activeCategory = categoryTitle;
            this.scrollToTop();
        },
        
        getFirstCategorySubcategories() {
            return categories.length > 0 ? categories[0].subcategories : [];
        },
        
        scrollToTop() {
            // Smooth scroll to the top of the categories section
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    }
}
</script>
