@props([
    'src' => 'data:image/jpeg;base64,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'
])
<div x-data="{ cartOpen: true }" class="relative">
    <!-- Cart Popup -->
    <div
        x-show="cartOpen"
        x-transition
        class="fixed right-4 top-16 w-80 max-h-[70vh] bg-white rounded-2xl shadow-lg border border-gray-200 overflow-y-auto z-50 md:right-8 md:top-20"
    >
        <!-- Header -->
        <div class="flex items-center justify-between px-4 py-3 border-b">
            <div class="flex items-center space-x-2">
                <span class="text-green-600">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
  <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
</svg>

                </span>
                <p class="font-medium text-green-700 font-semibold">Added to Cart</p>
            </div>
            <button @click="cartOpen = false" class="text-gray-500 hover:text-red-500 font-semibold">
                ✕
            </button>
        </div>

        <!-- Cart Items -->
        <div class="divide-y divide-gray-100">
            <!-- Item -->
            <div class="flex space-x-3 p-4">
                <img src="{{$src}}" class="w-16 h-16 rounded-lg object-cover" alt="Item">
                <div class="flex-1">
                    <p class="font-medium text-gray-800">Samosa - 2 Pieces</p>
                    <p class="max-w-max text-xs font-semibold text-gray-500 pb-0.5">2 Piece × 1</p>
                    <p class="text-sm font-semibold text-green-700">₹65
                        <span class="line-through text-red-400 text-xs ml-1">₹110</span>
                    </p>
                </div>
            </div>

            <!-- Item -->
            <div class="flex space-x-3 p-4">
                <img src="{{$src}}" class="w-16 h-16 rounded-lg object-cover" alt="Item">
                <div class="flex-1 space-y-1">
                    <p class="font-medium text-gray-800">Samosa Pav</p>
                    <p class="max-w-max text-xs font-semibold text-gray-500 pb-0.5">1 Piece × 1</p>
                    <p class="text-sm font-semibold text-green-700">₹69</p>
                </div>
            </div>

            <!-- Item -->
            <div class="flex space-x-3 p-4">
                <img src="{{$src}}" class="w-16 h-16 rounded-lg object-cover" alt="Item">
                <div class="flex-1">
                    <p class="font-medium text-gray-800">Chole Samose</p>
                    <p class="max-w-max text-xs font-semibold text-gray-500 pb-0.5">1 Portion × 1</p>
                    <p class="text-sm font-semibold text-green-700">₹155
                        <span class="line-through text-red-400 text-xs ml-1">₹209</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="border-t p-3">
            <button
                class="w-full text-violet-600 font-medium py-2 rounded-md border-2 border-violet-600 hover:bg-violet-200 transition"
            >
                Go to Cart →
            </button>
        </div>
    </div>
</div>
