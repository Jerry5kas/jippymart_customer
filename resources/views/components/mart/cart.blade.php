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

        <!-- Free Delivery Notice -->
        <div class="p-3 bg-green-50 flex items-center text-sm text-green-700 rounded-b-lg">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                <path fill-rule="evenodd"
                      d="M8.603 3.799A4.49 4.49 0 0 1 12 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 0 1 3.498 1.307 4.491 4.491 0 0 1 1.307 3.497A4.49 4.49 0 0 1 21.75 12a4.49 4.49 0 0 1-1.549 3.397 4.491 4.491 0 0 1-1.307 3.497 4.491 4.491 0 0 1-3.497 1.307A4.49 4.49 0 0 1 12 21.75a4.49 4.49 0 0 1-3.397-1.549 4.49 4.49 0 0 1-3.498-1.306 4.491 4.491 0 0 1-1.307-3.498A4.49 4.49 0 0 1 2.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 0 1 1.307-3.497 4.49 4.49 0 0 1 3.497-1.307Zm7.007 6.387a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z"
                      clip-rule="evenodd"/>
            </svg> &nbsp;&nbsp; Free delivery auto applied on this order!
        </div>

        <!-- Scrollable Content -->
        <div class="flex-1 overflow-y-auto bg-gray-50">
            <!-- Delivery ETA -->
            <div
                class="w-full bg-white p-4 border-b flex items-center justify-center space-x-2 text-gray-700 rounded-lg p-3 m-3 shadow-MD">
                <span class=" text-blue-400">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                      <path fill-rule="evenodd"
                            d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 6a.75.75 0 0 0-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 0 0 0-1.5h-3.75V6Z"
                            clip-rule="evenodd"/>
                    </svg>
                </span>
                <p class="font-semibold text-md text-gray-400">Delivery in 6 mins</p>
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
                    <div class="text-gray-400 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"/>
                        </svg>
                    </div>
                    <p class="text-gray-500 text-sm">Your cart is empty</p>
                    <p class="text-gray-400 text-xs">Add some items to get started</p>
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
                            ₹<span x-text="originalTotal"></span>
                        </div>
                        <span class="text-xl font-bold text-[#007F73]">₹<span x-text="finalTotal"></span></span>
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
                                ₹<span x-text="finalTotal"></span>
                                <span x-show="appliedCoupon" class="line-through text-gray-400 text-xs ml-1">₹<span x-text="originalTotal"></span></span>
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

                        <div class="flex justify-between">
                            <span>Handling Charge</span>
                            <span>₹20.99</span>
                        </div>

                        <div class="flex justify-between">
                            <span>Rain Fee</span>
                            <span><span class="line-through text-xs text-gray-400 mr-1">₹15</span>₹0</span>
                        </div>

                        <div class="flex justify-between">
                            <span>Delivery Fee</span>
                            <span><span class="line-through text-xs text-gray-400 mr-1">₹30</span>₹0</span>
                        </div>

                        <div class="flex justify-between">
                            <span>GST</span>
                            <span>₹0.75</span>
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
                                <p class="text-base">₹<span x-text="finalTotal"></span>
                                    <span x-show="appliedCoupon" class="line-through text-xs text-gray-400 ml-1">₹<span x-text="originalTotal"></span></span>
                                </p>
                                <span x-show="appliedCoupon" class="inline-block mt-1 text-xs font-semibold text-green-700
                           bg-green-100 px-2 py-0.5 rounded-full">
                  SAVING ₹<span x-text="appliedCoupon?.discountAmount || 0"></span>
              </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-data="{ showInstructions: false, showTip: false, showSafety: false, selectedTip: null }" class="space-y-4">

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
                                    <button @click="selectedTip = tip"
                                            :class="selectedTip === tip ? 'border-green-500 bg-green-50 text-green-700' : 'border-gray-300 bg-white text-gray-700'"
                                            class="flex-1 flex items-center justify-center gap-1 px-3 py-2 border rounded-full text-sm font-medium transition">
                                        💰 ₹<span x-text="tip"></span>
                                    </button>
                                </template>
                            </div>

                            <!-- Custom Tip -->
                            <button class="w-full py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-100 transition text-sm font-medium">
                                Add Custom Tip
                            </button>
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
            <button
                class="w-full bg-[#007F73] text-white font-semibold py-3 rounded-lg hover:bg-[#005f56] transition">
                Click to Pay ₹<span x-text="finalTotal"></span>
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('cart', {
            items: JSON.parse(localStorage.getItem('cartItems')) || {},
            appliedCoupon: JSON.parse(localStorage.getItem('appliedCoupon')) || null,
            save() {
                localStorage.setItem('cartItems', JSON.stringify(this.items));
                localStorage.setItem('appliedCoupon', JSON.stringify(this.appliedCoupon));
            },
            get grandTotal() {
                return Object.values(this.items).reduce((sum, item) => sum + (item.price * item.quantity), 0);
            },
            get originalTotal() {
                return this.grandTotal;
            },
            get finalTotal() {
                if (this.appliedCoupon) {
                    return Math.max(0, this.grandTotal - (this.appliedCoupon.discountAmount || 0));
                }
                return this.grandTotal;
            },
            applyCoupon(coupon) {
                this.appliedCoupon = coupon;
                this.save();
            },
            removeCoupon() {
                this.appliedCoupon = null;
                this.save();
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
            
            get grandTotal() {
                return this.cartItems.reduce((sum, item) => sum + ((item.disPrice || item.price) * item.quantity), 0);
            },
            get originalTotal() {
                return this.grandTotal;
            },
            get finalTotal() {
                if (this.appliedCoupon) {
                    return Math.max(0, this.grandTotal - (this.appliedCoupon.discountAmount || 0));
                }
                return this.grandTotal;
            },
            
            init() {
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
                    window.dispatchEvent(new CustomEvent('cart-updated'));
                }
            },
            
            applyCoupon(coupon) {
                this.appliedCoupon = coupon;
                localStorage.setItem('appliedCoupon', JSON.stringify(coupon));
            },
            
            removeCoupon() {
                this.appliedCoupon = null;
                localStorage.removeItem('appliedCoupon');
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
