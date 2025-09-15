
@props(['subcategories' => [], 'categoryTitle' => '', 'currentSubcategory' => ''])

<aside class="bg-white border-r-2 border-[#007F73] w-64 hidden md:flex flex-col p-4 space-y-3 shadow-lg">
    <div>
        <h2 class="text-lg font-bold text-gray-800">SubCategories</h2>
        @if(!empty($categoryTitle))
            <p class="text-sm text-gray-500 mt-1">in {{ $categoryTitle }}</p>
        @endif
        <p class="text-xs text-gray-400 mt-1">Sorted by item count (high to low)</p>
    </div>

    <nav class="space-y-2">
            @foreach($subcategories as $subcategory)
                <a href="{{ route('mart.items.by.subcategory', ['subcategoryTitle' => $subcategory['title']]) }}"
                    class="flex items-center justify-between p-2 rounded-xl transition-colors {{ $subcategory['isActive'] ? 'bg-[#E8F8DB] border border-[#007F73]' : 'hover:bg-[#E8F8DB]' }}">
                    <div class="flex items-center space-x-3">
                        <img src="{{ $subcategory['photo'] ?? 'https://img.icons8.com/color/48/vegetarian-food.png' }}"
                             alt="{{ $subcategory['title'] }}" class="w-8 h-8 object-cover rounded-full">
                        <span class="font-medium {{ $subcategory['isActive'] ? 'text-[#007F73]' : 'text-gray-700' }}">
                            {{ $subcategory['title'] }}
                            <!-- @if($subcategory['isActive'])
                                <span class="text-xs text-[#007F73] ml-1">●</span>
                            @endif -->
                        </span>
                    </div>
                    <span class="text-xs font-semibold {{ $subcategory['itemCount'] > 0 ? 'text-green-600 bg-green-100' : 'text-gray-500 bg-gray-100' }} px-2 py-1 rounded-full">
                        {{ $subcategory['itemCount'] ?? 0 }}
                    </span>
                </a>
            @endforeach
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
            <p class="text-xs text-gray-400 mt-1">Sorted by item count (high to low)</p>
        </div>
        <nav class="space-y-2">
            @foreach($subcategories as $subcategory)
                <a href="{{ route('mart.items.by.subcategory', ['subcategoryTitle' => $subcategory['title']]) }}"
                   class="flex items-center justify-between p-2 rounded-xl transition-colors {{ $subcategory['isActive'] ? 'bg-[#E8F8DB] border border-[#007F73]' : 'hover:bg-[#E8F8DB]' }}"
                   @click="sidebarOpen = false">
                    <div class="flex items-center space-x-3">
                        <img src="{{ $subcategory['photo'] ?? 'https://img.icons8.com/color/48/vegetarian-food.png' }}"
                             alt="{{ $subcategory['title'] }}" class="w-8 h-8 object-cover rounded-full">
                        <span class="font-medium {{ $subcategory['isActive'] ? 'text-[#007F73]' : 'text-gray-700' }}">
                            {{ $subcategory['title'] }}
                            @if($subcategory['isActive'])
                                <span class="text-xs text-[#007F73] ml-1">●</span>
                            @endif
                        </span>
                    </div>
                    <span class="text-xs font-semibold {{ $subcategory['itemCount'] > 0 ? 'text-green-600 bg-green-100' : 'text-gray-500 bg-gray-100' }} px-2 py-1 rounded-full">
                        {{ $subcategory['itemCount'] ?? 0 }}
                    </span>
                </a>
            @endforeach
        </nav>
    </aside>
</div>
