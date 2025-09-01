@props([
    'href' => '#',
    'src' => 'https://img.icons8.com/color/48/vegetarian-food.png',
    'text' => 'Fresh Vegetables'
])
<a href="{{$href}}"

   class="flex items-center space-x-3 p-2 rounded-xl hover:bg-purple-50 text-gray-700 font-medium active:bg-gradient-to-t active:from-violet-100 active:to-violet-50">
    <div
        class="w-12 h-12 rounded-full bg-gradient-to-b from-violet-100 to-violet-50 flex items-center justify-center">
        <img src="{{$src}}" class="w-8 h-8" alt=""/>
    </div>
    <span>{{$text}}</span>
</a>
