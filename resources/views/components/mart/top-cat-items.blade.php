@props([
    'categories' => 'categories'
])
<div x-data="{ 
    active: '{{ $categories[0]['title'] ?? '' }}',
    scrollToTop() {
        // Smooth scroll to the top of the categories section
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }
}" class="w-full pb-5 pt-16">

    <!-- Categories Navigation -->
    <div class="fixed z-40 w-full border-b bg-gradient-to-t from-slate-50 to-white">
        <div class="sm:w-[90%] mx-auto w-full px-4">
            <div class="flex items-center space-x-6 overflow-x-auto scrollbar-hide py-3">
                @foreach($categories as $category)
                    <button
                        class="flex items-center space-x-1 text-gray-600 font-medium flex-shrink-0 px-2 pb-1 border-b-2 transition"
                        :class="active === '{{ $category['title'] }}' ? 'text-purple-600 border-purple-600' : 'border-transparent hover:text-purple-500'"
                        @click="active = '{{ $category['title'] }}'; scrollToTop()">
                        <span>{{ $category['title'] }}</span>
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Sub Category Items Scroll -->
    <div class="w-full pt-16 pb-8">
        @foreach($categories as $category)
            <div x-show="active === '{{ $category['title'] }}'" class="flex space-x-6 overflow-x-auto px-4 py-5 scrollbar-hide sm:w-[90%] mx-auto w-full text-xs">
                @foreach($category['subcategories'] as $sub)
                <a href="{{ route('mart.items.by.subcategory', ['subcategoryTitle' => $sub['title']]) }}">
                    <div class="flex-shrink-0 w-20 rounded-full bg-white text-center">
                        <img src="{{ $sub['photo'] }}" alt="{{ $sub['title'] }}"
                             class="w-20 h-20 mx-auto object-cover rounded-full shadow-lg">
                        <p class="mt-2 text-xs font-semibold text-gray-800">{{ $sub['title'] }}</p>
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
    </style>

</div>

