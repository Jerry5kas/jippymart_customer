@props([
    'href' => '#',
    'src' => 'https://img.icons8.com/color/48/vegetarian-food.png',
    'text' => 'Fresh Vegetables'
])
<a href="{{$href}}"

   class="flex items-center space-x-3 p-2 rounded-xl hover:bg-[#E8F8DB] text-gray-700 font-medium active:bg-gradient-to-t active:from-[#C9EDAB] active:to-[#E8F8DB]" {{$attributes}}>
    <div
        class="w-12 h-12 rounded-full bg-gradient-to-b from-[#E8F8DB] to-[#C9EDAB] flex items-center justify-center">
        <img src="{{$src}}" class="w-full h-full object-cover rounded-full" alt=""/>
    </div>
    <span class="text-sm font-semibold text-gray-700">{{$text}}</span>
</a>
