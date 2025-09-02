<div x-data="{ open: false }" class="space-y-4">

    <!-- Coupon Trigger Card -->
    <div @click="open = true"
         class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 flex items-center justify-between cursor-pointer hover:bg-yellow-100 transition">
        <div>
            <p class="font-medium text-gray-800">
                You have unlocked <span class="text-violet-600">10 new coupons</span>
            </p>
            <p class="text-xs text-gray-600">Explore Now</p>
        </div>
        <span class="text-gray-400 text-xl">›</span>
    </div>

    <!-- Modal -->
    <div
        x-show="open"
        @keydown.escape.window="open = false"
        class="fixed inset-0 flex items-center justify-center z-50"
        x-cloak
    >
        <!-- Overlay -->
        <div
            class="fixed inset-0 bg-black bg-opacity-40"
            @click="open = false"
        ></div>

        <!-- Modal Box -->
        <div class="relative bg-white w-full max-w-md mx-4 rounded-2xl shadow-lg p-5 overflow-y-auto max-h-[90vh]">
            <!-- Header -->
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Apply Coupons</h2>
                <button @click="open = false" class="text-gray-500 hover:text-gray-700">&times;</button>
            </div>

            <!-- Input -->
            <div class="flex gap-2 mb-4">
                <input type="text" placeholder="Enter Coupon Code"
                       class="flex-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none">
                <button class="px-4 py-2 bg-gray-200 rounded-lg text-sm font-medium hover:bg-gray-300">APPLY</button>
            </div>

            <!-- Coupon List -->
            <div class="space-y-4">
                <!-- Coupon Item -->
                <div class="p-4 border rounded-lg flex flex-col gap-2">
                    <div class="flex justify-between items-center">
                        <span class="px-2 py-1 text-xs bg-purple-100 text-purple-600 font-bold rounded">MOBIZEPUPI</span>
                        <button class="text-purple-600 font-semibold text-sm hover:underline">APPLY</button>
                    </div>
                    <p class="font-semibold text-gray-800">Get flat ₹20 discount with MobiKwik UPI</p>
                    <p class="text-sm text-gray-600">Get assured Discount ₹20 using MobiKwik UPI handle (@ikwik) on orders above ₹399</p>
                    <button class="text-xs text-purple-500 hover:underline">+MORE</button>
                </div>

                <div class="p-4 border rounded-lg flex flex-col gap-2">
                    <div class="flex justify-between items-center">
                        <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 font-bold rounded">AMAZONPAY</span>
                        <button class="text-purple-600 font-semibold text-sm hover:underline">APPLY</button>
                    </div>
                    <p class="font-semibold text-gray-800">Get Upto ₹50 Cashback on using Amazon Pay Balance</p>
                    <p class="text-sm text-gray-600">Get up to ₹50 cashback with Amazon Pay Balance on orders above ₹399</p>
                    <button class="text-xs text-purple-500 hover:underline">+MORE</button>
                </div>

                <div class="p-4 border rounded-lg flex flex-col gap-2">
                    <div class="flex justify-between items-center">
                        <span class="px-2 py-1 text-xs bg-purple-100 text-purple-600 font-bold rounded">MBK300</span>
                        <button class="text-purple-600 font-semibold text-sm hover:underline">APPLY</button>
                    </div>
                    <p class="font-semibold text-gray-800">Assured ₹15 – ₹300 Cashback using MobiKwik wallet at Zepto</p>
                    <p class="text-sm text-gray-600">Get assured cashback up to ₹300 using MobiKwik wallet on orders above ₹399</p>
                    <button class="text-xs text-purple-500 hover:underline">+MORE</button>
                </div>
            </div>
        </div>
    </div>
</div>
