@props([
    'href' => '#',
    'src' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRXCvb73HhjFBt19ng-vZW_jBB87zuWGDKYUg&s',
    'text1' => 'Powered by Lakmé',
    'text2' => 'Beauty LIT Fest',
    'text3' => 'UP TO ',
    'text4' => '60% OFF',
    'button' => 'Shop Now'
])
<div class="w-full flex-shrink-0 bg-pink-100 bg-cover bg-center"
     style="background-image: url('{{$src}}')">
    <div class="bg-black/30 w-full h-full flex items-center p-6 md:p-12">
        <div class="max-w-lg text-white">
            <p class="text-sm uppercase font-semibold">{{$text1}}</p>
            <h1 class="text-3xl md:text-5xl font-bold mt-2">{{$text2}}</h1>
            <p class="mt-4 text-lg">{{$text3}}<span class="font-bold">{{$text4}}</span></p>
            <button class="mt-6 px-5 py-2 bg-pink-600 text-white rounded-xl shadow hover:bg-pink-700">
                {{$button}}
            </button>
        </div>
    </div>
</div>
