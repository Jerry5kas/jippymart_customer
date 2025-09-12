
@props(['subcategories' => [], 'categoryTitle' => '', 'currentSubcategory' => ''])

<aside class="bg-white border-r-2 border-violet-400 w-64 hidden md:flex flex-col p-4 space-y-3 shadow-lg">
    <div>
        <h2 class="text-lg font-bold text-gray-800">SubCategories</h2>
        @if(!empty($categoryTitle))
            <p class="text-sm text-gray-500 mt-1">in {{ $categoryTitle }}</p>
        @endif
    </div>

    <nav class="space-y-2">
        @if(!empty($subcategories))
            @foreach($subcategories as $subcategory)
                <a href="{{ route('mart.items.by.subcategory', ['subcategoryTitle' => $subcategory['title']]) }}" 
                   class="flex items-center space-x-3 p-2 rounded-xl transition-colors {{ $subcategory['isActive'] ? 'bg-violet-100 border border-violet-300' : 'hover:bg-violet-50' }}">
                    <img src="{{ $subcategory['photo'] ?? 'https://img.icons8.com/color/48/vegetarian-food.png' }}" 
                         alt="{{ $subcategory['title'] }}" class="w-8 h-8 object-cover rounded-full">
                    <span class="font-medium {{ $subcategory['isActive'] ? 'text-violet-700' : 'text-gray-700' }}">
                        {{ $subcategory['title'] }}
                        @if($subcategory['isActive'])
                            <span class="text-xs text-violet-500 ml-1">●</span>
                        @endif
                    </span>
                </a>
            @endforeach
        @else
            {{-- Fallback static menu if no subcategories provided --}}
            <div class="text-center py-8">
                <div class="text-gray-400 text-4xl mb-2">📂</div>
                <p class="text-sm text-gray-500">No subcategories available</p>
            </div>
        @endif
    </nav>
</aside>

<!-- Mobile Sidebar -->
<div x-show="sidebarOpen" class="fixed inset-0 z-40 flex md:hidden" x-transition>
    <div class="fixed inset-0 bg-black bg-opacity-40" @click="sidebarOpen = false"></div>
    <aside class="relative bg-white w-64 p-4 space-y-3">
        <button class="absolute top-2 right-2" @click="sidebarOpen = false">✖</button>
        <div>
            <h2 class="text-lg font-bold">SubCategories</h2>
            @if(!empty($categoryTitle))
                <p class="text-sm text-gray-500 mt-1">in {{ $categoryTitle }}</p>
            @endif
        </div>
        <nav class="space-y-2">
            @if(!empty($subcategories))
                @foreach($subcategories as $subcategory)
                    <a href="{{ route('mart.items.by.subcategory', ['subcategoryTitle' => $subcategory['title']]) }}" 
                       class="flex items-center space-x-3 p-2 rounded-xl transition-colors {{ $subcategory['isActive'] ? 'bg-violet-100 border border-violet-300' : 'hover:bg-violet-50' }}"
                       @click="sidebarOpen = false">
                        <img src="{{ $subcategory['photo'] ?? 'https://img.icons8.com/color/48/vegetarian-food.png' }}" 
                             alt="{{ $subcategory['title'] }}" class="w-8 h-8 object-cover rounded-full">
                        <span class="font-medium {{ $subcategory['isActive'] ? 'text-violet-700' : 'text-gray-700' }}">
                            {{ $subcategory['title'] }}
                            @if($subcategory['isActive'])
                                <span class="text-xs text-violet-500 ml-1">●</span>
                            @endif
                        </span>
                    </a>
                @endforeach
            @else
                {{-- Fallback static menu if no subcategories provided --}}
                <div class="text-center py-8">
                    <div class="text-gray-400 text-4xl mb-2">📂</div>
                    <p class="text-sm text-gray-500">No subcategories available</p>
                </div>
            @endif
        </nav>
    </aside>
</div>
