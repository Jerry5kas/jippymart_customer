@props([
    "image" => "https://icon2.cleanpng.com/20240319/bgk/transparent-groceries-food-shopping-shopping-basket-fruits-veg-cartoon-grocery-basket-filled-with-food65fa5cd5d94d36.77879088.webp",
    "title" => "Mart Categories"
])

<div class="w-32  h-auto space-y-3 rounded-full ">
    <div
        class="w-32 h-32 rounded-full  bg-gray-100 shadow-lg hover:shadow-xl flex flex-col items-center text-center cursor-pointer transition">
        <img
            src="{{$image}}"
            alt="Fruits & Vegetables" class="w-full h-full object-contain mb-2 rounded-full">
    </div>
    <div class="text-sm text-center font-medium text-gray-500">{{$title}}</div>
</div>
