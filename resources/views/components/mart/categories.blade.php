@props(['categories' => []])

@php
    $list = is_array($categories) ? array_values($categories) : [];
    // Fallback demo data if none provided
    if (count($list) === 0) {
        $list = [
            ['title' => 'Fruits & Vegetables'],
            ['title' => 'Dairy, Bread & Eggs'],
            ['title' => 'Atta, Rice, Oil & Dals'],
            ['title' => 'Meat, Fish & Eggs'],
            ['title' => 'Masala & Dry Fruits'],
            ['title' => 'Breakfast & Sauces'],
            ['title' => 'Biscuits & Cookies'],
            ['title' => 'Chocolates'],
            ['title' => 'Breads'],
            ['title' => 'Cakes and Toffies'],
        ];
    }
    $first = array_slice($list, 0, 12);
    $second = array_slice($list, 12, 12);
@endphp

<div class="sm:w-[90%] w-full mx-auto h-full space-y-8">
    <div>
        <h2 class="text-xl font-bold mb-4 text-[#007F73]">Grocery & Kitchen</h2>
        <div class="w-full grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 lg:grid-cols-9 gap-4 justify-center">
            @foreach($first as $category)
                <x-mart.category-card :title="$category['title'] ?? ''" :image="$category['photo'] ?? ($category['image'] ?? null)" />
            @endforeach
        </div>
    </div>

    <div>
        <h2 class="text-xl font-bold mb-4 text-[#007F73]">Sweets & Snacks</h2>
        <div class="w-full grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 lg:grid-cols-9 gap-4 justify-center">
            @forelse($second as $category)
                <x-mart.category-card :title="$category['title'] ?? ''" :image="$category['photo'] ?? ($category['image'] ?? null)" />
            @empty
                {{-- If not enough data for second section, reuse trailing items from the first list to keep UI filled --}}
                @foreach(array_slice($first, 6, 6) as $category)
                    <x-mart.category-card :title="$category['title'] ?? ''" :image="$category['photo'] ?? ($category['image'] ?? null)" />
                @endforeach
            @endforelse
        </div>
    </div>
</div>

