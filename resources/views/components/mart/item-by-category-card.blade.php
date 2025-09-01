<div class="w-full flex-shrink-0">
    <div class="bg-white rounded-2xl flex flex-col" x-data="{ added: false }">
        <!-- Product Image -->
        <div class="relative rounded-xl shadow-lg">
            <img
                src="https://icon2.cleanpng.com/20180411/ucw/avu5woreh.webp"
                alt="Onion"
                class="rounded-xl w-full object-cover">
            <!-- Add Button -->
            <button
                @click="added = !added"
                class="absolute bottom-2 right-2 px-4 py-1.5 rounded-xl border border-b-2 border-r-2 border-violet-500 text-violet-500 text-xs font-semibold bg-white hover:bg-violet-50 transition">
                <span x-show="!added">ADD</span>
                <span x-show="added">✔ Added</span>
            </button>
        </div>

        <!-- Price -->
        <div class="mt-2">
            <div class="flex items-center space-x-1">
                <span class="text-sm font-bold text-gray-900">₹30</span>
                <span class="text-gray-400 line-through text-xs">₹51</span>
            </div>
            <p class="bg-gradient-to-r from-green-200 to-white text-green-600 text-xs font-semibold p-0.5 rounded-lg">
                SAVE ₹9</p>
            <p class="text-gray-500 text-xs">1 Pack (900-1000g)</p>
        </div>

        <!-- Title -->
        <h3 class="mt-1 text-sm font-medium text-gray-800">Fresh Onion</h3>

        <!-- Rating -->
        <div class="flex items-center space-x-1 mt-1">
                                <span
                                    class="bg-yellow-100 text-yellow-600 px-2 py-0.5 text-xs rounded-full font-semibold">4.4</span>
            <span class="text-gray-500 text-xs">(74.9k)</span>
            <div
                class="flex items-center space-x-1 bg-gradient-to-r from-gray-200 to-white rounded-full px-1 text-xs">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                     fill="currentColor" class="w-3 h-3">
                    <path fill-rule="evenodd"
                          d="M14.615 1.595a.75.75 0 0 1 .359.852L12.982 9.75h7.268a.75.75 0 0 1 .548 1.262l-10.5 11.25a.75.75 0 0 1-1.272-.71l1.992-7.302H3.75a.75.75 0 0 1-.548-1.262l10.5-11.25a.75.75 0 0 1 .913-.143Z"
                          clip-rule="evenodd"/>
                </svg>
                <span>15 mins</span>
            </div>
        </div>
    </div>
</div>
