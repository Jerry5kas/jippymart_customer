@props([
    'src' => 'data:image/jpeg;base64,/9j/4AAQSkAAAH/2Q=='
])
<div x-data="cartPopup()" class="relative">
    <!-- Cart Popup -->
    <div
        x-show="showPopup"
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
            <button @click="closePopup()" class="text-gray-500 hover:text-red-500 font-semibold">
                ✕
            </button>
        </div>

        <!-- Progress Bar -->
        <div x-show="showProgress" class="px-4 py-2">
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-green-600 h-2 rounded-full transition-all duration-300" 
                     :style="`width: ${progress}%`"></div>
            </div>
            <p class="text-xs text-gray-600 mt-1">Adding to cart...</p>
        </div>

        <!-- Cart Items -->
        <div class="divide-y divide-gray-100">
            <template x-for="item in cartItems" :key="item.id">
                <div class="flex space-x-3 p-4">
                    <img :src="item.photo || '{{$src}}'" class="w-16 h-16 rounded-lg object-cover" :alt="item.name">
                    <div class="flex-1">
                        <p class="font-medium text-gray-800" x-text="item.name"></p>
                        <p class="max-w-max text-xs font-semibold text-gray-500 pb-0.5" 
                           x-text="`${item.grams || '1 Piece'} × ${item.quantity}`"></p>
                        <p class="text-sm font-semibold text-green-700">
                            ₹<span x-text="item.disPrice || item.price"></span>
                            <span x-show="item.disPrice && item.disPrice < item.price" 
                                  class="line-through text-red-400 text-xs ml-1">₹<span x-text="item.price"></span></span>
                        </p>
                    </div>
                </div>
            </template>
        </div>

        <!-- Footer -->
        <div class="border-t p-3">
            <button
                @click="goToCart()"
                class="w-full text-violet-600 font-medium py-2 rounded-md border-2 border-violet-600 hover:bg-violet-200 transition"
            >
                Go to Cart →
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('cartPopup', () => ({
        showPopup: false,
        showProgress: false,
        progress: 0,
        cartItems: [],
        
        init() {
            // Listen for cart updates
            window.addEventListener('cart-updated', (event) => {
                this.updateCartItems();
            });
            
            // Listen for item added events
            window.addEventListener('item-added-to-cart', (event) => {
                this.showItemAdded(event.detail.item);
            });
        },
        
        showItemAdded(item) {
            this.cartItems = [item];
            this.showPopup = true;
            this.showProgress = true;
            this.progress = 0;
            
            // Animate progress bar
            const interval = setInterval(() => {
                this.progress += 10;
                if (this.progress >= 100) {
                    clearInterval(interval);
                    setTimeout(() => {
                        this.showProgress = false;
                    }, 500);
                }
            }, 100);
            
            // Auto close after 3 seconds
            setTimeout(() => {
                this.closePopup();
            }, 3000);
        },
        
        updateCartItems() {
            const cartData = localStorage.getItem('mart_cart');
            if (cartData) {
                this.cartItems = Object.values(JSON.parse(cartData));
            }
        },
        
        closePopup() {
            this.showPopup = false;
            this.showProgress = false;
            this.progress = 0;
        },
        
        goToCart() {
            // Trigger cart drawer to open
            window.dispatchEvent(new CustomEvent('open-cart'));
            this.closePopup();
        }
    }));
});
</script>
