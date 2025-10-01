<!-- Slide-in Cart Drawer -->
<div x-data="cartStore" x-show="cartOpen" class="fixed inset-0 z-50 flex justify-end"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     style="display: none;">

    <!-- Background overlay -->
    <div class="absolute inset-0 bg-black bg-opacity-50" @click="cartOpen = false"></div>

    <!-- Cart Panel -->
    <div class="relative bg-white w-full sm:w-[400px] h-full shadow-lg z-50
                transform transition-transform duration-300 ease-in-out flex flex-col"
         x-show="cartOpen"
         x-transition:enter="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="translate-x-0"
         x-transition:leave-end="translate-x-full">

        <!-- Header -->
        <div class="flex items-center justify-between p-4 shadow-lg border-b">
            <h2 class="text-lg font-semibold flex items-center gap-2">
                Your Cart
                <span class="px-3 py-1 text-xs font-medium text-white rounded-full
               bg-gradient-to-r from-green-500 via-emerald-500 to-teal-500
               shadow-md">
                Saved ₹896
              </span>
            </h2>

            <button @click="cartOpen = false" class="text-gray-600 hover:text-black">✕</button>
        </div>

         <!-- Dynamic Delivery Notice -->
        <div x-show="cartItems.length > 0 && deliveryChargeDisplay.type === 'free_delivery'" class="p-3 bg-green-50 flex items-center text-sm text-green-700 rounded-b-lg">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 flex-shrink-0">
                <path d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25ZM13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875h.375a3 3 0 1 1 6 0h3a.75.75 0 0 0 .75-.75V15Z" />
                <path d="M8.25 19.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0ZM15.75 6.75a.75.75 0 0 0-.75.75v11.25c0 .087.015.17.042.248a3 3 0 0 1 5.958.464c.853-.175 1.522-.935 1.464-1.883a18.659 18.659 0 0 0-3.732-10.104 1.837 1.837 0 0 0-1.47-.725H15.75Z" />
                <path d="M19.5 19.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0Z" />
            </svg> &nbsp;&nbsp; <span x-text="deliveryChargeDisplay.main_text"></span> auto applied on this order!
        </div>
        
        <!-- Delivery Charge Notice for non-free delivery -->
        <div x-show="cartItems.length > 0 && deliveryChargeDisplay.type !== 'free_delivery' && deliveryCharge > 0" class="p-3 bg-blue-50 flex items-center text-sm text-blue-700 rounded-b-lg">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 flex-shrink-0">
                <path d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25ZM13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875h.375a3 3 0 1 1 6 0h3a.75.75 0 0 0 .75-.75V15Z" />
                <path d="M8.25 19.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0ZM15.75 6.75a.75.75 0 0 0-.75.75v11.25c0 .087.015.17.042.248a3 3 0 0 1 5.958.464c.853-.175 1.522-.935 1.464-1.883a18.659 18.659 0 0 0-3.732-10.104 1.837 1.837 0 0 0-1.47-.725H15.75Z" />
                <path d="M19.5 19.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0Z" />
            </svg> &nbsp;&nbsp; Delivery charge: <span x-text="deliveryChargeDisplay.main_text"></span>
        </div>

         <!-- Scrollable Content -->
        <div class="flex-1 overflow-y-auto bg-gray-50">
            <!-- Delivery ETA -->
            <div x-show="cartItems.length > 0"
                class="w-full bg-white p-4 border-b flex items-center justify-center space-x-2 text-gray-700 rounded-lg p-3 m-3 shadow-MD">
                <span class="text-green-500">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                        <path fill-rule="evenodd" d="M7.5 5.25a3 3 0 0 1 3-3h3a3 3 0 0 1 3 3v.205c.933.085 1.857.197 2.774.334 1.454.218 2.476 1.483 2.476 2.917v3.033c0 1.211-.734 2.352-1.936 2.752A24.726 24.726 0 0 1 12 15.75c-2.73 0-5.357-.442-7.814-1.259-1.202-.4-1.936-1.541-1.936-2.752V8.706c0-1.434 1.022-2.7 2.476-2.917A48.814 48.814 0 0 1 7.5 5.455V5.25Zm7.5 0v.09a49.488 49.488 0 0 0-6 0v-.09a1.5 1.5 0 0 1 1.5-1.5h3a1.5 1.5 0 0 1 1.5 1.5Zm-3 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                        <path d="M3 18.4v-2.796a4.3 4.3 0 0 0 .713.31A26.226 26.226 0 0 0 12 17.25c2.892 0 5.68-.468 8.287-1.335.252-.084.49-.189.713-.311V18.4c0 1.452-1.047 2.728-2.523 2.923-2.12.282-4.282.427-6.477.427a49.19 49.19 0 0 1-6.477-.427C4.047 21.128 3 19.852 3 18.4Z" />
                    </svg>
                </span>
                <p class="font-semibold text-md text-gray-700">Delivery in 6 mins</p>
            </div>

            <!-- Cart Items -->
            <div class="max-w-2xl mx-auto space-y-4 p-4">
                <!-- Dynamic Cart Items -->
                <template x-for="item in cartItems" :key="item.id">
                    <div class="flex items-center justify-between bg-white p-4 rounded-xl shadow">
                        <div class="flex items-center space-x-3">
                            <img :src="item.photo || 'data:image/jpeg;base64,/9j/4AAQSkAAAH/2Q=='" class="w-12 h-12 rounded-md border object-cover">
                            <div class="flex-1">
                                <h3 class="text-sm font-semibold text-gray-800" x-text="item.name"></h3>
                                <p class="text-xs text-gray-500" x-text="item.subcategoryTitle"></p>
                                <p class="text-xs text-gray-500" x-text="item.grams || '1 Piece'"></p>
                                
                                <!-- Price and Savings -->
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-sm font-semibold text-green-700">
                                        ₹<span x-text="item.disPrice"></span>
                                    </span>
                                    <span x-show="item.disPrice < item.price" class="text-xs text-red-400 line-through">
                                        ₹<span x-text="item.price"></span>
                                    </span>
                                    <span class="text-xs text-green-600 font-semibold">
                                        Save ₹<span x-text="item.savings"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col items-end space-y-2">
                            <!-- Quantity controls -->
                            <div class="flex items-center border rounded-lg px-2">
                                <button @click="decreaseQuantity(item.id)" class="text-[#007F73] px-2 text-lg font-bold hover:bg-gray-100 rounded">−</button>
                                <span class="px-3 text-gray-700 font-semibold" x-text="item.quantity"></span>
                                <button @click="increaseQuantity(item.id)" class="text-[#007F73] px-2 text-lg font-bold hover:bg-gray-100 rounded">+</button>
                            </div>
                            <!-- Total Price -->
                            <div class="text-right">
                                <span class="text-sm font-semibold text-gray-900">
                                    ₹<span x-text="item.totalPrice"></span>
                                </span>
                                <p class="text-xs text-gray-500">
                                    <span x-text="item.quantity"></span> × ₹<span x-text="item.disPrice"></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </template>
                
                <!-- Empty Cart Message -->
                <div x-show="cartItems.length === 0" class="text-center py-8">
                    <div class="text-gray-300 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 mx-auto" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M2.25 2.25a.75.75 0 0 0 0 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 0 0-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 0 0 0-1.5H5.378A2.25 2.25 0 0 1 7.5 15h11.218a.75.75 0 0 0 .674-.421 60.358 60.358 0 0 0 2.96-7.228.75.75 0 0 0-.525-.965A60.864 60.864 0 0 0 5.68 4.509l-.232-.867A1.875 1.875 0 0 0 3.636 2.25H2.25ZM3.75 20.25a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0ZM16.5 20.25a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Z" />
                        </svg>
                    </div>
                    <p class="text-gray-500 text-sm font-medium">Your cart is empty</p>
                    <p class="text-gray-400 text-xs mt-1">Add some groceries to get started! 🛒</p>
                </div>

                <!-- Applied Coupon Display -->
                <div x-show="appliedCoupon" class="bg-green-50 border border-green-200 rounded-lg p-3 mt-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-green-800">
                                Coupon Applied: <span x-text="appliedCoupon?.code" class="font-bold"></span>
                            </p>
                            <p class="text-xs text-green-600" x-text="getCouponDescription(appliedCoupon)"></p>
                        </div>
                        <button @click="removeCoupon()" class="text-green-600 hover:text-green-800 text-sm">
                            Remove
                        </button>
                    </div>
                    <div class="mt-2 text-sm text-green-700">
                        You saved: <span class="font-bold">₹<span x-text="appliedCoupon?.discountAmount || 0"></span></span>
                    </div>
                </div>

                <!-- Grand Total -->
                <div class="flex justify-between items-center border-t pt-4 mt-4">
                    <h2 class="text-lg font-bold text-gray-800">Grand Total:</h2>
                    <div class="text-right">
                        <div x-show="appliedCoupon" class="text-sm text-gray-500 line-through">
                            ₹<span x-text="originalTotal + deliveryCharge + totalTax + tipAmount"></span>
                        </div>
                        <span class="text-xl font-bold text-[#007F73]">₹<span x-text="totalWithDeliveryTaxAndTip"></span></span>
                    </div>
                </div>
            </div>


            <!-- Additional Details Section -->
            <div x-data="{ bag: true, showToPay: false, showInstructions: false, showTip: false, showSafety: false }"
                 class="p-4 space-y-4">

{{--                <!-- Coupons -->--}}
{{--                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 flex items-center justify-between">--}}
{{--                    <div>--}}
{{--                        <p class="font-medium text-gray-800">You have unlocked <span class="text-violet-600">10 new coupons</span>--}}
{{--                        </p>--}}
{{--                        <p class="text-xs text-gray-600">Explore Now</p>--}}
{{--                    </div>--}}
{{--                    <span class="text-gray-400">›</span>--}}
{{--                </div>--}}

                <x-mart.coupon />

                <!-- Bag Option -->
                <div class="bg-white border rounded-lg p-4 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">🌱</div>
                        <div>
                            <p class="font-medium text-gray-800">I don’t need a paper bag!</p>
                            <p class="text-xs text-gray-500">You have opted for no bag delivery</p>
                        </div>
                    </div>
                    <button @click="bag = !bag"
                            class="relative w-12 h-6 flex items-center rounded-full transition"
                            :class="bag ? 'bg-green-500' : 'bg-gray-300'">
                        <span class="absolute left-1 w-4 h-4 bg-white rounded-full transition"
                              :class="bag ? 'translate-x-6' : 'translate-x-0'"></span>
                    </button>
                </div>

                <div x-data="{ showSummary: false }" class="bg-white border rounded-xl shadow-sm overflow-hidden">

                    <!-- To Pay (collapsible header) -->
                    <button @click="showSummary = !showSummary"
                            class="w-full p-4 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-800">To Pay</p>
                            <p class="text-xs text-gray-500">Incl. all taxes and charges</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-800">
                                ₹<span x-text="totalWithDeliveryTaxAndTip"></span>
                                <span x-show="appliedCoupon" class="line-through text-gray-400 text-xs ml-1">₹<span x-text="originalTotal + deliveryCharge + totalTax + tipAmount"></span></span>
                            </p>
                            <p x-show="appliedCoupon" class="text-xs font-medium text-green-600">
                                SAVINGS ₹<span x-text="appliedCoupon?.discountAmount || 0"></span>
                            </p>
                        </div>
                    </button>

                    <!-- Collapsible Bill Summary -->
                    <div x-show="showSummary" x-transition
                         class="border-t bg-gray-50 p-4 space-y-3 text-sm text-gray-700">

                        <!-- Bill Summary Title -->
                        <p class="text-base font-semibold flex items-center gap-2">
                            <span>📄</span> Bill Summary
                        </p>

                        <!-- Items -->
                        <div class="flex justify-between">
                            <span>Item Total</span>
                            <span>
              ₹<span x-text="grandTotal"></span>
              <span x-show="appliedCoupon" class="line-through text-xs text-gray-400 ml-1">₹<span x-text="originalTotal"></span></span>
          </span>
                        </div>

                        <!-- Delivery Fee with dynamic calculation -->
                        <div class="flex justify-between">
                            <span>Delivery Fee</span>
                            <div class="text-right">
                                <template x-if="deliveryChargeDisplay.type === 'free_delivery'">
                                    <div>
                                        <div class="text-green-600 font-semibold" x-text="deliveryChargeDisplay.main_text"></div>
                                        <div class="text-xs text-gray-400 line-through" x-text="deliveryChargeDisplay.sub_text"></div>
                                        <div class="text-xs text-gray-600" x-text="deliveryChargeDisplay.charged_amount"></div>
                                    </div>
                                </template>
                                <template x-if="deliveryChargeDisplay.type === 'extra_distance'">
                                    <div>
                                        <div class="text-green-600 font-semibold" x-text="deliveryChargeDisplay.main_text"></div>
                                        <div class="text-xs text-gray-400 line-through" x-text="deliveryChargeDisplay.sub_text"></div>
                                        <div class="text-xs text-gray-600" x-text="deliveryChargeDisplay.charged_amount"></div>
                                    </div>
                                </template>
                                <template x-if="deliveryChargeDisplay.type === 'normal'">
                                    <span x-text="deliveryChargeDisplay.main_text"></span>
                                </template>
                            </div>
                        </div>

                        <!-- Tax Breakdown -->
                        <div class="flex justify-between">
                            <span>SGST (5%)</span>
                            <span>₹<span x-text="sgst.toFixed(2)"></span></span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span>GST (18%)</span>
                            <span>₹<span x-text="gst.toFixed(2)"></span></span>
                        </div>
                        
                        <div class="flex justify-between font-semibold">
                            <span>Total Tax</span>
                            <span>₹<span x-text="totalTax.toFixed(2)"></span></span>
                        </div>

                        <!-- Tip Amount -->
                        <div x-show="tipAmount > 0" class="flex justify-between">
                            <span>Delivery Partner Tip</span>
                            <span>₹<span x-text="tipAmount.toFixed(2)"></span></span>
                        </div>

                        <!-- Divider -->
                        <hr class="my-2">

                        <!-- Coupon Discount -->
                        <div x-show="appliedCoupon" class="flex justify-between">
                            <span>Coupon Discount (<span x-text="appliedCoupon?.code"></span>)</span>
                            <span class="text-green-600">-₹<span x-text="appliedCoupon?.discountAmount || 0"></span></span>
                        </div>

                        <!-- Final To Pay -->
                        <div class="flex justify-between items-center font-semibold text-gray-800">
                            <div>
                                <p>To Pay</p>
                                <p class="text-xs text-gray-500">Incl. all taxes and charges</p>
                            </div>
                            <div class="text-right">
                                <p class="text-base">₹<span x-text="totalWithDeliveryTaxAndTip"></span>
                                    <span x-show="appliedCoupon" class="line-through text-xs text-gray-400 ml-1">₹<span x-text="originalTotal + deliveryCharge + totalTax + tipAmount"></span></span>
                                </p>
                                <span x-show="appliedCoupon" class="inline-block mt-1 text-xs font-semibold text-green-700
                           bg-green-100 px-2 py-0.5 rounded-full">
                  SAVING ₹<span x-text="appliedCoupon?.discountAmount || 0"></span>
              </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-data="{ showInstructions: false, showTip: false, showSafety: false, selectedTip: null, customTipAmount: '' }" class="space-y-4">

                    <!-- Delivery Instructions -->
                    <div class="bg-white border rounded-xl shadow-sm overflow-hidden">
                        <button @click="showInstructions = !showInstructions"
                                class="w-full p-4 flex items-center justify-between">
                            <p class="font-medium text-gray-800 flex items-center gap-2">
                                💬 Delivery Instructions
                            </p>
                            <span :class="{'rotate-180': showInstructions}"
                                  class="text-gray-400 transition-transform">⌄</span>
                        </button>

                        <div x-show="showInstructions" x-transition class="p-4 border-t bg-gray-50 space-y-3 text-sm text-gray-700">
                            <p class="text-gray-600">Delivery partner will be notified</p>
                            <div class="grid grid-cols-2 gap-3">
                                <!-- Instruction Option -->
                                <div class="p-3 border rounded-lg hover:bg-gray-100 cursor-pointer text-center">
                                    <p class="text-lg">🐶</p>
                                    <p class="font-medium text-gray-800">Beware Of Pets</p>
                                    <p class="text-xs text-gray-500">Will inform about pets</p>
                                </div>

                                <div class="p-3 border rounded-lg hover:bg-gray-100 cursor-pointer text-center">
                                    <p class="text-lg">🔕</p>
                                    <p class="font-medium text-gray-800">Do Not Ring Bell</p>
                                    <p class="text-xs text-gray-500">Will not ring the bell</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Partner Tip -->
                    <div class="bg-white border rounded-xl shadow-sm overflow-hidden">
                        <button @click="showTip = !showTip"
                                class="w-full p-4 flex items-center justify-between">
                            <p class="font-medium text-gray-800 flex items-center gap-2">
                                🎁 Delivery Partner Tip
                            </p>
                            <span :class="{'rotate-180': showTip}"
                                  class="text-gray-400 transition-transform">⌄</span>
                        </button>

                        <div x-show="showTip" x-transition class="p-4 border-t bg-gray-50 space-y-4 text-sm text-gray-700">
                            <p class="text-gray-600">This amount goes to your delivery partner</p>

                            <!-- Tip Options -->
                            <div class="flex gap-3">
                                <template x-for="tip in [10,20,35]" :key="tip">
                                    <button @click="selectTip(tip)"
                                            :class="selectedTip == tip ? 'border-green-500 bg-green-50 text-green-700' : 'border-gray-300 bg-white text-gray-700'"
                                            class="flex-1 flex items-center justify-center gap-1 px-3 py-2 border rounded-full text-sm font-medium transition">
                                        💰 ₹<span x-text="tip"></span>
                                    </button>
                                </template>
                            </div>

                            <!-- Custom Tip -->
                            <div class="space-y-2">
                                <input type="number" 
                                       min="1" 
                                       max="100"
                                       placeholder="Enter custom tip amount"
                                       x-model="customTipAmount"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <button @click="selectTip(parseFloat(customTipAmount) || 0)"
                                        class="w-full py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-100 transition text-sm font-medium">
                                    Add Custom Tip
                                </button>
                            </div>

                            <!-- Current Tip Display -->
                            <div x-show="tipAmount > 0" class="flex justify-between items-center p-3 bg-green-50 border border-green-200 rounded-lg">
                                <span class="text-green-800 font-medium">Selected Tip:</span>
                                <div class="flex items-center gap-2">
                                    <span class="text-green-700 font-bold">₹<span x-text="tipAmount"></span></span>
                                    <button @click="clearTip()" class="text-green-600 hover:text-green-800 text-sm">Remove</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Partner Safety -->
                    <div class="bg-white border rounded-xl shadow-sm overflow-hidden">
                        <button @click="showSafety = !showSafety"
                                class="w-full p-4 flex items-center justify-between">
                            <p class="font-medium text-gray-800 flex items-center gap-2">
                                🛡 Delivery Partner’s Safety
                            </p>
                            <span :class="{'rotate-180': showSafety}"
                                  class="text-gray-400 transition-transform">⌄</span>
                        </button>

                        <div x-show="showSafety" x-transition class="p-4 border-t bg-gray-50 text-sm text-gray-600">
                            Learn more about how we ensure their safety.
                        </div>
                    </div>
                </div>


                <!-- GST Invoice -->
                <div class="bg-[#E8F8DB] border border-[#007F73] rounded-lg p-4 flex items-center justify-between">
                    <div>
                        <p class="font-medium text-[#007F73]">Get GST Invoice</p>
                        <p class="text-xs text-gray-600">Claim upto 28% with the GST Invoice</p>
                    </div>
                    <button class="text-gray-400">✎</button>
                </div>

                <!-- Ordering Info -->
                <div class="bg-white border rounded-lg p-4">
                    <p class="text-sm text-gray-600">Ordering for <span class="text-[#007F73] font-medium">Jerry</span>,
                        7092936243</p>
                    <button class="text-xs text-[#007F73] mt-1">Edit</button>
                </div>
            </div>
        </div>

        <!-- Delivery Address -->
        <div class="border-t p-4 text-sm text-gray-600">
            <p class="font-medium">Delivering to Other</p>
            <p class="truncate text-gray-500">Nishi PG, 1st floor, Yelahanka New Town, Attur Layout</p>
        </div>

        <!-- Footer Checkout -->
        <div class="p-4 border-t bg-white sticky bottom-0">
            <!-- Minimum Order Validation -->
            <div x-show="!isMinimumOrderMet" class="mb-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-yellow-600 mr-2 flex-shrink-0">
                        <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-sm text-yellow-800" x-text="minimumOrderMessage"></p>
                </div>
            </div>
            
            <button
                :disabled="!isMinimumOrderMet"
                :class="isMinimumOrderMet ? 'bg-[#007F73] hover:bg-[#005f56]' : 'bg-gray-400 cursor-not-allowed'"
                class="w-full text-white font-semibold py-3 rounded-lg transition">
                <span x-show="isMinimumOrderMet">Click to Pay ₹<span x-text="totalWithDeliveryTaxAndTip"></span></span>
                <span x-show="!isMinimumOrderMet">Minimum order not met</span>
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        // Mart Delivery Settings (from Firebase collections)
        let martDeliverySettings = {
            min_order_value: 99,
            free_delivery_threshold: 199,
            base_delivery_charge: 23,
            free_delivery_distance_km: 5,
            per_km_charge_above_free_distance: 7,
            min_order_message: "Min Item value is ₹99"
        };

        // Tip settings
        const tipSettings = {
            defaultTips: [10, 20, 35],
            customTipEnabled: true,
            maxTipAmount: 100
        };

        // Mart Coupon Service
        const martCouponService = {
            async applyCoupon(couponCode, cartTotal) {
                try {
                    const response = await fetch('/api/mart/apply-coupon', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            coupon_code: couponCode,
                            cart_total: cartTotal
                        })
                    });

                    const data = await response.json();
                    return data;
                } catch (error) {
                    console.error('Error applying coupon:', error);
                    return {
                        status: false,
                        message: 'Failed to apply coupon. Please try again.'
                    };
                }
            },

            async removeCoupon() {
                try {
                    const response = await fetch('/api/mart/remove-coupon', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    const data = await response.json();
                    return data;
                } catch (error) {
                    console.error('Error removing coupon:', error);
                    return {
                        status: false,
                        message: 'Failed to remove coupon. Please try again.'
                    };
                }
            }
        };

        // Load Mart Delivery Settings from Firebase
        async function loadMartDeliverySettings() {
            try {
                // Load from mart_settings collection
                const martSettingsRef = database.collection('mart_settings').doc('delivery_settings');
                const martSettingsDoc = await martSettingsRef.get();
                
                if (martSettingsDoc.exists) {
                    const martData = martSettingsDoc.data();
                    martDeliverySettings = {
                        min_order_value: martData.min_order_value || 99,
                        free_delivery_threshold: martData.free_delivery_threshold || 199,
                        min_order_message: martData.min_order_message || "Min Item value is ₹99"
                    };
                }

                // Load from settings collection (DeliveryCharge document)
                const deliveryChargeRef = database.collection('settings').doc('DeliveryCharge');
                const deliveryChargeDoc = await deliveryChargeRef.get();
                
                if (deliveryChargeDoc.exists) {
                    const deliveryData = deliveryChargeDoc.data();
                    martDeliverySettings = {
                        ...martDeliverySettings,
                        base_delivery_charge: deliveryData.base_delivery_charge || 23,
                        free_delivery_distance_km: deliveryData.free_delivery_distance_km || 5,
                        per_km_charge_above_free_distance: deliveryData.per_km_charge_above_free_distance || 7,
                        item_total_threshold: deliveryData.item_total_threshold || 199
                    };
                }

                console.log('Loaded mart delivery settings:', martDeliverySettings);
            } catch (error) {
                console.error('Error loading mart delivery settings:', error);
                // Use default settings if Firebase fails
            }
        }

        // Tax Calculation Service
        const taxService = {
            calculateTax(itemTotal, deliveryCharge, originalDeliveryCharge = null) {
                // SGST = 5% of item total (before any discounts)
                const sgst = (itemTotal * 5) / 100;
                
                // GST = 18% of delivery charge
                // Use original delivery charge for GST calculation if provided (for free delivery cases)
                const chargeForGST = originalDeliveryCharge !== null ? originalDeliveryCharge : deliveryCharge;
                const gst = (chargeForGST * 18) / 100;
                
                // Total tax = SGST + GST
                const totalTax = sgst + gst;
                
                return {
                    sgst: sgst,
                    gst: gst,
                    total_tax: totalTax,
                    item_total_before_discount: itemTotal,
                    delivery_charge: deliveryCharge,
                    original_delivery_charge: originalDeliveryCharge
                };
            }
        };

        // Delivery Charge Calculation Service
        const deliveryChargeService = {
            calculateDeliveryCharge(itemTotal, distance, settings = martDeliverySettings) {
                const baseDeliveryCharge = settings.base_delivery_charge;
                const freeDeliveryThreshold = settings.free_delivery_threshold;
                const freeDeliveryDistanceKm = settings.free_delivery_distance_km;
                const perKmChargeAboveFreeDistance = settings.per_km_charge_above_free_distance;

                // Calculate original fee (what would be charged without free delivery)
                const originalFee = this.calculateOriginalFee(distance, baseDeliveryCharge, freeDeliveryDistanceKm, perKmChargeAboveFreeDistance);

                // Calculate actual fee based on business rules
                const actualFee = this.calculateActualFee(itemTotal, distance, settings);

                const calculation = {
                    original_fee: originalFee,
                    actual_fee: actualFee,
                    is_free_delivery: this.isFreeDelivery(itemTotal, distance, settings),
                    savings: originalFee - actualFee,
                    settings: settings,
                    distance: distance,
                    item_total: itemTotal,
                    ui_components: this.getUIComponents(originalFee, actualFee, this.isFreeDelivery(itemTotal, distance, settings))
                };

                return calculation;
            },

            calculateOriginalFee(distance, baseDeliveryCharge, freeDeliveryDistanceKm, perKmChargeAboveFreeDistance) {
                if (distance <= freeDeliveryDistanceKm) {
                    return baseDeliveryCharge;
                } else {
                    const extraDistance = distance - freeDeliveryDistanceKm;
                    return baseDeliveryCharge + (extraDistance * perKmChargeAboveFreeDistance);
                }
            },

            calculateActualFee(itemTotal, distance, settings) {
                const baseDeliveryCharge = settings.base_delivery_charge;
                const freeDeliveryThreshold = settings.free_delivery_threshold;
                const freeDeliveryDistanceKm = settings.free_delivery_distance_km;
                const perKmChargeAboveFreeDistance = settings.per_km_charge_above_free_distance;

                // If item total is below threshold
                if (itemTotal < freeDeliveryThreshold) {
                    return this.calculateOriginalFee(distance, baseDeliveryCharge, freeDeliveryDistanceKm, perKmChargeAboveFreeDistance);
                }

                // If item total is above threshold
                if (distance <= freeDeliveryDistanceKm) {
                    return 0; // Free delivery within free distance
                } else {
                    // Only charge for extra distance above free delivery distance
                    const extraDistance = distance - freeDeliveryDistanceKm;
                    return extraDistance * perKmChargeAboveFreeDistance;
                }
            },

            isFreeDelivery(itemTotal, distance, settings) {
                const freeDeliveryThreshold = settings.free_delivery_threshold;
                const freeDeliveryDistanceKm = settings.free_delivery_distance_km;
                return itemTotal >= freeDeliveryThreshold && distance <= freeDeliveryDistanceKm;
            },

            getUIComponents(originalFee, actualFee, isFreeDelivery) {
                const savings = originalFee - actualFee;
                
                if (savings === 0) {
                    return {
                        type: 'normal',
                        main_text: '₹' + actualFee.toFixed(2),
                        sub_text: '',
                        strikethrough: false
                    };
                } else if (isFreeDelivery) {
                    return {
                        type: 'free_delivery',
                        main_text: 'Free Delivery',
                        sub_text: '₹' + originalFee.toFixed(2),
                        strikethrough: true,
                        charged_amount: '₹0.00'
                    };
                } else {
                    return {
                        type: 'extra_distance',
                        main_text: 'Free Delivery',
                        sub_text: '₹' + originalFee.toFixed(2),
                        strikethrough: true,
                        charged_amount: '₹' + actualFee.toFixed(2)
                    };
                }
            }
        };

        Alpine.store('cart', {
            items: JSON.parse(localStorage.getItem('cartItems')) || {},
            appliedCoupon: JSON.parse(localStorage.getItem('appliedCoupon')) || null,
            deliverySettings: martDeliverySettings,
            deliveryDistance: 3.5, // Default distance in km
            deliveryChargeCalculation: null,
            tipAmount: parseFloat(localStorage.getItem('mart_tip_amount')) || 0,
            selectedTip: localStorage.getItem('mart_selected_tip') || null,
            
            save() {
                localStorage.setItem('cartItems', JSON.stringify(this.items));
                localStorage.setItem('appliedCoupon', JSON.stringify(this.appliedCoupon));
                localStorage.setItem('mart_tip_amount', this.tipAmount.toString());
                localStorage.setItem('mart_selected_tip', this.selectedTip || '');
            },
            
            get grandTotal() {
                return Object.values(this.items).reduce((sum, item) => sum + (item.price * item.quantity), 0);
            },
            
            get originalTotal() {
                return this.grandTotal;
            },
            
            get finalTotal() {
                let total = this.grandTotal;
                if (this.appliedCoupon) {
                    total = Math.max(0, total - (this.appliedCoupon.discountAmount || 0));
                }
                return total;
            },
            
            get deliveryCharge() {
                // Return 0 if cart is empty
                if (Object.keys(this.items).length === 0 || this.finalTotal === 0) {
                    return 0;
                }
                
                if (!this.deliveryChargeCalculation) {
                    this.deliveryChargeCalculation = deliveryChargeService.calculateDeliveryCharge(
                        this.finalTotal, 
                        this.deliveryDistance, 
                        this.deliverySettings
                    );
                }
                return this.deliveryChargeCalculation.actual_fee;
            },
            
            get taxCalculation() {
                // Return zero tax if cart is empty
                if (Object.keys(this.items).length === 0 || this.finalTotal === 0) {
                    return { sgst: 0, gst: 0, total_tax: 0 };
                }
                // Get original delivery charge for GST calculation
                const originalDeliveryCharge = this.deliveryChargeCalculation?.original_fee || this.deliveryCharge;
                return taxService.calculateTax(this.grandTotal, this.deliveryCharge, originalDeliveryCharge);
            },
            
            get totalTax() {
                return this.taxCalculation.total_tax;
            },
            
            get sgst() {
                return this.taxCalculation.sgst;
            },
            
            get gst() {
                return this.taxCalculation.gst;
            },
            
            get totalWithDelivery() {
                // Return 0 if cart is empty
                if (Object.keys(this.items).length === 0 || this.finalTotal === 0) {
                    return 0;
                }
                return this.finalTotal + this.deliveryCharge;
            },
            
            get totalWithDeliveryAndTax() {
                // Return 0 if cart is empty
                if (Object.keys(this.items).length === 0 || this.finalTotal === 0) {
                    return 0;
                }
                return this.finalTotal + this.deliveryCharge + this.totalTax;
            },
            
            get isMinimumOrderMet() {
                // Cart must have items and meet minimum order value
                if (Object.keys(this.items).length === 0) {
                    return false;
                }
                return this.finalTotal >= this.deliverySettings.min_order_value;
            },
            
            get minimumOrderMessage() {
                if (Object.keys(this.items).length === 0) {
                    return 'Add items to your cart';
                }
                if (!this.isMinimumOrderMet) {
                    const remaining = this.deliverySettings.min_order_value - this.finalTotal;
                    return `Add ₹${remaining.toFixed(0)} more to place order`;
                }
                return null;
            },
            
            get totalWithDeliveryTaxAndTip() {
                // Return 0 if cart is empty
                if (Object.keys(this.items).length === 0 || this.finalTotal === 0) {
                    return 0;
                }
                return this.finalTotal + this.deliveryCharge + this.totalTax + this.tipAmount;
            },
            
            selectTip(amount) {
                this.tipAmount = amount;
                this.selectedTip = amount;
                this.save();
            },
            
            clearTip() {
                this.tipAmount = 0;
                this.selectedTip = null;
                this.save();
            },
            
            async applyCoupon(coupon) {
                this.appliedCoupon = coupon;
                this.save();
                this.recalculateDeliveryCharge();
            },
            
            async removeCoupon() {
                this.appliedCoupon = null;
                this.save();
                this.recalculateDeliveryCharge();
            },
            
            async applyCouponByCode(couponCode) {
                const result = await martCouponService.applyCoupon(couponCode, this.finalTotal);
                if (result.status) {
                    this.applyCoupon(result.coupon);
                    return { success: true, message: result.message };
                } else {
                    return { success: false, message: result.message };
                }
            },
            
            recalculateDeliveryCharge() {
                // Force fresh calculation by resetting first
                this.deliveryChargeCalculation = null;
                this.deliveryChargeCalculation = deliveryChargeService.calculateDeliveryCharge(
                    this.finalTotal, 
                    this.deliveryDistance, 
                    this.deliverySettings
                );
            }
        });

        Alpine.data('cartItem', (id, name, price, image) => ({
            id, name, price, image,
            get quantity() {
                return Alpine.store('cart').items[this.id]?.quantity || 0;
            },
            get totalPrice() {
                return this.quantity * this.price;
            },
            increase() {
                const cart = Alpine.store('cart');
                if (!cart.items[this.id]) cart.items[this.id] = {price: this.price, quantity: 0};
                cart.items[this.id].quantity++;
                cart.save();
            },
            decrease() {
                const cart = Alpine.store('cart');
                if (cart.items[this.id]?.quantity > 0) {
                    cart.items[this.id].quantity--;
                    if (cart.items[this.id].quantity === 0) delete cart.items[this.id];
                    cart.save();
                }
            }
        }));

        Alpine.data('cartStore', () => ({
            cartItems: [],
            appliedCoupon: null,
            cartOpen: false,
            deliverySettings: martDeliverySettings,
            deliveryDistance: 3.5, // Default distance in km
            deliveryChargeCalculation: null,
            tipAmount: parseFloat(localStorage.getItem('mart_tip_amount')) || 0,
            selectedTip: localStorage.getItem('mart_selected_tip') || null,
            
            get grandTotal() {
                return this.cartItems.reduce((sum, item) => sum + ((item.disPrice || item.price) * item.quantity), 0);
            },
            
            get originalTotal() {
                return this.grandTotal;
            },
            
            get finalTotal() {
                let total = this.grandTotal;
                if (this.appliedCoupon) {
                    total = Math.max(0, total - (this.appliedCoupon.discountAmount || 0));
                }
                return total;
            },
            
            get deliveryCharge() {
                // Return 0 if cart is empty
                if (this.cartItems.length === 0 || this.finalTotal === 0) {
                    return 0;
                }
                
                if (!this.deliveryChargeCalculation) {
                    this.deliveryChargeCalculation = deliveryChargeService.calculateDeliveryCharge(
                        this.finalTotal, 
                        this.deliveryDistance, 
                        this.deliverySettings
                    );
                }
                return this.deliveryChargeCalculation.actual_fee;
            },
            
            get taxCalculation() {
                // Return zero tax if cart is empty
                if (this.cartItems.length === 0 || this.finalTotal === 0) {
                    return { sgst: 0, gst: 0, total_tax: 0 };
                }
                // Get original delivery charge for GST calculation
                const originalDeliveryCharge = this.deliveryChargeCalculation?.original_fee || this.deliveryCharge;
                return taxService.calculateTax(this.grandTotal, this.deliveryCharge, originalDeliveryCharge);
            },
            
            get totalTax() {
                return this.taxCalculation.total_tax;
            },
            
            get sgst() {
                return this.taxCalculation.sgst;
            },
            
            get gst() {
                return this.taxCalculation.gst;
            },
            
            get totalWithDelivery() {
                // Return 0 if cart is empty
                if (this.cartItems.length === 0 || this.finalTotal === 0) {
                    return 0;
                }
                return this.finalTotal + this.deliveryCharge;
            },
            
            get totalWithDeliveryAndTax() {
                // Return 0 if cart is empty
                if (this.cartItems.length === 0 || this.finalTotal === 0) {
                    return 0;
                }
                return this.finalTotal + this.deliveryCharge + this.totalTax;
            },
            
            get isMinimumOrderMet() {
                // Cart must have items and meet minimum order value
                if (this.cartItems.length === 0) {
                    return false;
                }
                return this.finalTotal >= this.deliverySettings.min_order_value;
            },
            
            get minimumOrderMessage() {
                if (this.cartItems.length === 0) {
                    return 'Add items to your cart';
                }
                if (!this.isMinimumOrderMet) {
                    const remaining = this.deliverySettings.min_order_value - this.finalTotal;
                    return `Add ₹${remaining.toFixed(0)} more to place order`;
                }
                return null;
            },
            
            get totalWithDeliveryTaxAndTip() {
                // Return 0 if cart is empty
                if (this.cartItems.length === 0 || this.finalTotal === 0) {
                    return 0;
                }
                return this.finalTotal + this.deliveryCharge + this.totalTax + this.tipAmount;
            },
            
            selectTip(amount) {
                this.tipAmount = amount;
                this.selectedTip = amount;
                localStorage.setItem('mart_tip_amount', amount.toString());
                localStorage.setItem('mart_selected_tip', amount.toString());
            },
            
            clearTip() {
                this.tipAmount = 0;
                this.selectedTip = null;
                localStorage.setItem('mart_tip_amount', '0');
                localStorage.setItem('mart_selected_tip', '');
            },
            
            get deliveryChargeDisplay() {
                if (!this.deliveryChargeCalculation) {
                    this.deliveryChargeCalculation = deliveryChargeService.calculateDeliveryCharge(
                        this.finalTotal, 
                        this.deliveryDistance, 
                        this.deliverySettings
                    );
                }
                return this.deliveryChargeCalculation.ui_components;
            },
            
            async init() {
                // Load Firebase settings first
                await loadMartDeliverySettings();
                this.deliverySettings = martDeliverySettings;
                
                this.loadCartItems();
                this.loadAppliedCoupon();
                
                // Listen for cart updates
                window.addEventListener('cart-updated', () => {
                    this.loadCartItems();
                });
                
                // Listen for coupon application events
                window.addEventListener('coupon-applied', (event) => {
                    const coupon = event.detail.coupon;
                    this.applyCoupon(coupon);
                });
                
                // Listen for cart open events
                window.addEventListener('open-cart', () => {
                    this.cartOpen = true;
                    // Reset delivery charge calculation when cart opens
                    this.deliveryChargeCalculation = null;
                    this.loadCartItems();
                });
                
                // Watch for cartOpen changes to reset calculation
                this.$watch('cartOpen', (value) => {
                    if (value === true) {
                        // Reset and recalculate when cart opens
                        this.deliveryChargeCalculation = null;
                        this.loadCartItems();
                    }
                });
            },
            
            loadCartItems() {
                const cartData = localStorage.getItem('mart_cart');
                if (cartData) {
                    const cart = JSON.parse(cartData);
                    // Calculate totals for each item
                    this.cartItems = Object.values(cart).map(item => ({
                        ...item,
                        totalPrice: item.disPrice * item.quantity,
                        savings: (item.price - item.disPrice) * item.quantity
                    }));
                } else {
                    this.cartItems = [];
                }
                
                // Reset delivery charge calculation to force recalculation
                this.deliveryChargeCalculation = null;
            },
            
            loadAppliedCoupon() {
                const couponData = localStorage.getItem('appliedCoupon');
                if (couponData) {
                    this.appliedCoupon = JSON.parse(couponData);
                }
            },
            
            increaseQuantity(itemId) {
                const cartData = localStorage.getItem('mart_cart') || '{}';
                const cart = JSON.parse(cartData);
                
                if (cart[itemId]) {
                    cart[itemId].quantity++;
                    localStorage.setItem('mart_cart', JSON.stringify(cart));
                    this.loadCartItems();
                    this.recalculateDeliveryCharge();
                    window.dispatchEvent(new CustomEvent('cart-updated'));
                    
                    // Update the specific item in the cart items array for immediate UI update
                    const itemIndex = this.cartItems.findIndex(item => item.id === itemId);
                    if (itemIndex !== -1) {
                        this.cartItems[itemIndex].quantity = cart[itemId].quantity;
                        this.cartItems[itemIndex].totalPrice = cart[itemId].disPrice * cart[itemId].quantity;
                        this.cartItems[itemIndex].savings = (cart[itemId].price - cart[itemId].disPrice) * cart[itemId].quantity;
                    }
                }
            },
            
            decreaseQuantity(itemId) {
                const cartData = localStorage.getItem('mart_cart') || '{}';
                const cart = JSON.parse(cartData);
                
                if (cart[itemId] && cart[itemId].quantity > 0) {
                    cart[itemId].quantity--;
                    if (cart[itemId].quantity === 0) {
                        delete cart[itemId];
                        // Remove from cart items array
                        this.cartItems = this.cartItems.filter(item => item.id !== itemId);
                    } else {
                        // Update the specific item in the cart items array for immediate UI update
                        const itemIndex = this.cartItems.findIndex(item => item.id === itemId);
                        if (itemIndex !== -1) {
                            this.cartItems[itemIndex].quantity = cart[itemId].quantity;
                            this.cartItems[itemIndex].totalPrice = cart[itemId].disPrice * cart[itemId].quantity;
                            this.cartItems[itemIndex].savings = (cart[itemId].price - cart[itemId].disPrice) * cart[itemId].quantity;
                        }
                    }
                    localStorage.setItem('mart_cart', JSON.stringify(cart));
                    this.recalculateDeliveryCharge();
                    window.dispatchEvent(new CustomEvent('cart-updated'));
                }
            },
            
            async applyCoupon(coupon) {
                this.appliedCoupon = coupon;
                localStorage.setItem('appliedCoupon', JSON.stringify(coupon));
                this.recalculateDeliveryCharge();
            },
            
            async removeCoupon() {
                this.appliedCoupon = null;
                localStorage.removeItem('appliedCoupon');
                this.recalculateDeliveryCharge();
            },
            
            async applyCouponByCode(couponCode) {
                const result = await martCouponService.applyCoupon(couponCode, this.finalTotal);
                if (result.status) {
                    this.applyCoupon(result.coupon);
                    return { success: true, message: result.message };
                } else {
                    return { success: false, message: result.message };
                }
            },
            
            recalculateDeliveryCharge() {
                // Force fresh calculation by resetting first
                this.deliveryChargeCalculation = null;
                this.deliveryChargeCalculation = deliveryChargeService.calculateDeliveryCharge(
                    this.finalTotal, 
                    this.deliveryDistance, 
                    this.deliverySettings
                );
            },
            
            getCouponDescription(coupon) {
                if (!coupon) return '';
                if (coupon.discountType === 'Percentage') {
                    return `${coupon.discount}% off on your order`;
                } else {
                    return `Flat ₹${coupon.discount} discount`;
                }
            }
        }));
    });
</script>
