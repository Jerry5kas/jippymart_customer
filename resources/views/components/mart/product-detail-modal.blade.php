<!-- Product Detail Modal Component -->
<div x-data="productDetailModal()" 
     x-show="isOpen" 
     x-cloak
     x-on:keydown.escape.window="closeModal()"
     x-on:product-detail-open.window="openModal($event.detail)"
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;">
    
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"
         x-show="isOpen"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-on:click="closeModal()">
    </div>

    <!-- Modal Content -->
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-4xl w-full mx-auto"
             x-show="isOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             x-on:click.stop="">
            
            <!-- Close Button -->
            <button x-on:click="closeModal()" 
                    class="absolute top-4 right-4 z-10 bg-white/90 hover:bg-white rounded-full p-2 shadow-lg transition-all duration-200 hover:scale-110">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <!-- Loading State -->
            <div x-show="loading" class="flex items-center justify-center p-12">
                <div class="flex flex-col items-center gap-4">
                    <div class="w-16 h-16 border-4 border-[#007F73] border-t-transparent rounded-full animate-spin"></div>
                    <p class="text-gray-600 font-medium">Loading product details...</p>
                </div>
            </div>

            <!-- Product Content -->
            <div x-show="!loading && product" class="grid md:grid-cols-2 gap-6 p-6 md:p-8">
                <!-- Product Image -->
                <div class="relative">
                    <div class="aspect-square rounded-xl overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100 shadow-inner">
                        <img x-bind:src="product && product.photo ? product.photo : '{{ asset('img/demo.jpg') }}'" 
                             x-bind:alt="product ? product.name : 'Product'"
                             class="w-full h-full object-contain p-6">
                    </div>
                    
                    <!-- Tags/Badges -->
                    <div class="absolute top-4 left-4 flex flex-col gap-2">
                        <span x-show="product && product.veg" 
                              class="inline-flex items-center gap-1 px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-full shadow-lg">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <circle cx="10" cy="10" r="8"/>
                            </svg>
                            VEG
                        </span>
                        <span x-show="product && product.nonveg" 
                              class="inline-flex items-center gap-1 px-3 py-1 bg-red-500 text-white text-xs font-bold rounded-full shadow-lg">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <circle cx="10" cy="10" r="8"/>
                            </svg>
                            NON-VEG
                        </span>
                        <span x-show="product && product.isNew" 
                              class="px-3 py-1 bg-blue-500 text-white text-xs font-bold rounded-full shadow-lg">
                            NEW
                        </span>
                        <span x-show="product && product.isBestSeller" 
                              class="px-3 py-1 bg-purple-500 text-white text-xs font-bold rounded-full shadow-lg">
                            BEST SELLER
                        </span>
                    </div>

                    <!-- Discount Badge -->
                    <div x-show="product && product.disPrice && product.price && product.disPrice < product.price" 
                         class="absolute top-4 right-4 bg-gradient-to-br from-red-500 to-pink-600 text-white px-4 py-2 rounded-xl shadow-lg transform -rotate-3">
                        <p class="text-xs font-semibold">SAVE</p>
                        <p class="text-lg font-bold" x-text="product ? '₹' + (product.price - product.disPrice) : ''"></p>
                    </div>
                </div>

                <!-- Product Details -->
                <div class="flex flex-col">
                    <!-- Header Info -->
                    <div class="flex-1">
                        <div class="mb-4">
                            <span class="inline-block px-3 py-1 bg-[#007F73]/10 text-[#007F73] text-xs font-semibold rounded-full mb-2" 
                                  x-text="product ? product.subcategoryTitle : ''"></span>
                            <h2 class="text-3xl font-bold text-gray-900 mb-2" x-text="product ? product.name : ''"></h2>
                            <p class="text-gray-600 text-sm" x-text="product ? product.description : ''"></p>
                        </div>

                        <!-- Rating & Reviews -->
                        <div x-show="product && product.reviewCount && product.reviewCount > 0" class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-200">
                            <div class="flex items-center gap-2">
                                <div class="flex items-center gap-1">
                                    <template x-for="i in 5" x-bind:key="i">
                                        <svg class="w-5 h-5" 
                                             x-bind:class="i <= Math.floor(((product && product.reviewSum) ? product.reviewSum : 0) / ((product && product.reviewCount) ? product.reviewCount : 1)) ? 'text-yellow-400 fill-current' : 'text-gray-300'"
                                             viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                        </svg>
                                    </template>
                                </div>
                                <span class="text-lg font-bold text-gray-900" 
                                      x-text="product && product.reviewSum && product.reviewCount ? ((product.reviewSum / product.reviewCount).toFixed(1)) : '0.0'"></span>
                            </div>
                            <span class="text-sm text-gray-500" x-text="product && product.reviewCount ? '(' + product.reviewCount + ' reviews)' : ''"></span>
                        </div>

                        <!-- Category Path -->
                        <div class="mb-6">
                            <p class="text-xs text-gray-500 uppercase font-medium mb-2">Category</p>
                            <div class="flex items-center gap-2 text-sm">
                                <span class="text-[#007F73] font-semibold" x-text="product ? product.categoryTitle : ''"></span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                <span class="text-gray-700" x-text="product ? product.subcategoryTitle : ''"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Price & Add to Cart Section -->
                    <div class="mt-auto pt-6 border-t border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-medium mb-1">Price</p>
                                <div class="flex items-baseline gap-3">
                                    <span class="text-3xl font-bold text-[#007F73]" x-text="product ? '₹' + product.disPrice : ''"></span>
                                    <span x-show="product && product.disPrice && product.price && product.disPrice < product.price" 
                                          class="text-lg text-gray-400 line-through" 
                                          x-text="product ? '₹' + product.price : ''"></span>
                                </div>
                            </div>
                            <div x-show="product && product.disPrice && product.price && product.disPrice < product.price" class="text-right">
                                <p class="text-xs text-gray-500 uppercase font-medium mb-1">You Save</p>
                                <p class="text-xl font-bold text-green-600" x-text="product ? '₹' + (product.price - product.disPrice) : ''"></p>
                            </div>
                        </div>

                        <!-- Add to Cart Section -->
                        <div>
                            <!-- Add to Cart Button (when not in cart) -->
                            <button x-show="cartQuantity === 0"
                                    x-on:click="addToCart()"
                                    class="w-full bg-gradient-to-r from-[#007F73] to-[#00A86B] text-white py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Add to Cart
                            </button>

                            <!-- Quantity Controls (when in cart) -->
                            <div x-show="cartQuantity > 0"
                                 x-transition
                                 class="w-full bg-gradient-to-r from-[#007F73] to-[#00A86B] text-white py-3 rounded-xl shadow-lg flex items-center justify-between px-6">
                                <button x-on:click="decreaseQuantity()"
                                        class="w-12 h-12 flex items-center justify-center bg-white/20 hover:bg-white/30 rounded-lg font-bold text-2xl transition-all duration-200">
                                    −
                                </button>
                                <div class="flex flex-col items-center">
                                    <span class="text-3xl font-bold" x-text="cartQuantity"></span>
                                    <span class="text-xs text-green-100">in cart</span>
                                </div>
                                <button x-on:click="increaseQuantity()"
                                        class="w-12 h-12 flex items-center justify-center bg-white/20 hover:bg-white/30 rounded-lg font-bold text-2xl transition-all duration-200">
                                    +
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function productDetailModal() {
        return {
            isOpen: false,
            loading: false,
            product: null,
            cartQuantity: 0,

            openModal(productData) {
                console.log('Opening modal with product:', productData);
                this.product = productData;
                this.loading = false;
                this.isOpen = true;
                
                // Load cart quantity for this product
                this.loadCartQuantity();
                
                document.body.style.overflow = 'hidden';
            },

            closeModal() {
                this.isOpen = false;
                document.body.style.overflow = '';
                setTimeout(() => {
                    this.product = null;
                    this.cartQuantity = 0;
                }, 300);
            },

            loadCartQuantity() {
                if (!this.product) return;
                
                const cartData = localStorage.getItem('mart_cart');
                if (cartData) {
                    const cart = JSON.parse(cartData);
                    this.cartQuantity = cart[this.product.name] ? cart[this.product.name].quantity : 0;
                } else {
                    this.cartQuantity = 0;
                }
                console.log('📊 Loaded cart quantity:', this.cartQuantity, 'for', this.product.name);
            },

            addToCart() {
                if (!this.product) {
                    console.error('❌ No product data');
                    return;
                }

                const productData = {
                    id: this.product.name,
                    name: this.product.name,
                    price: this.product.price,
                    disPrice: this.product.disPrice,
                    photo: this.product.photo,
                    subcategoryTitle: this.product.subcategoryTitle,
                    description: this.product.description,
                    grams: this.product.grams,
                    rating: this.product.reviewSum,
                    reviews: this.product.reviewCount
                };

                const cartData = localStorage.getItem('mart_cart') || '{}';
                const cart = JSON.parse(cartData);

                if (cart[this.product.name]) {
                    cart[this.product.name].quantity++;
                } else {
                    cart[this.product.name] = { ...productData, quantity: 1 };
                }

                localStorage.setItem('mart_cart', JSON.stringify(cart));
                this.cartQuantity = cart[this.product.name].quantity;

                // Dispatch events
                window.dispatchEvent(new CustomEvent('cart-updated'));
                window.dispatchEvent(new CustomEvent('item-added-to-cart', {
                    detail: { item: cart[this.product.name] }
                }));

                console.log('✅ Added to cart from modal:', this.product.name, 'Quantity:', this.cartQuantity);
            },

            increaseQuantity() {
                if (!this.product) return;

                const cartData = localStorage.getItem('mart_cart') || '{}';
                const cart = JSON.parse(cartData);

                if (cart[this.product.name]) {
                    cart[this.product.name].quantity++;
                    localStorage.setItem('mart_cart', JSON.stringify(cart));
                    this.cartQuantity = cart[this.product.name].quantity;
                    window.dispatchEvent(new CustomEvent('cart-updated'));
                    console.log('✅ Increased quantity:', this.product.name, this.cartQuantity);
                }
            },

            decreaseQuantity() {
                if (!this.product) return;

                const cartData = localStorage.getItem('mart_cart') || '{}';
                const cart = JSON.parse(cartData);

                if (cart[this.product.name] && cart[this.product.name].quantity > 0) {
                    cart[this.product.name].quantity--;
                    if (cart[this.product.name].quantity === 0) {
                        delete cart[this.product.name];
                    }
                    localStorage.setItem('mart_cart', JSON.stringify(cart));
                    this.cartQuantity = cart[this.product.name] ? cart[this.product.name].quantity : 0;
                    window.dispatchEvent(new CustomEvent('cart-updated'));
                    console.log('✅ Decreased quantity:', this.product.name, this.cartQuantity);
                }
            }
        }
    }
</script>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>
