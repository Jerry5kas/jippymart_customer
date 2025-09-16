<!-- Navbar -->
<header class="fixed z-40 w-full text-sm bg-gradient-to-b from-[#E8F8DB] to-white"
        x-data="{ mobileMenu: false, cartOpen: false }">

    <div class="sm:w-[90%] mx-auto w-full px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">

            <!-- Left section -->
            <div class="flex items-center space-x-4">
                <a href="/mart" 
                   class="text-2xl font-extrabold text-[#007F73] hover:text-[#005f56] transition-colors">
                    Mart
                </a>

            <!-- Location display -->
            <div class="flex items-center space-x-2 text-sm text-gray-600" id="location-display" style="display: none;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-[#007F73]">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                </svg>
                <span id="current-location-text" class="text-gray-600 font-medium">Loading location...</span>
            </div>
            </div>

           

           


            <!-- Right section -->
            <div class="hidden md:flex  items-center space-x-6 text-sm text-gray-600 font-semibold">

             <!-- Functional Search bar with animated rotating placeholder -->
             <div class="flex-1 mx-6 hidden md:block"
                 x-data="{
        items: [
          'milk','bread','cheese slices','butter','biscuits','apples','oranges',
          'sweets','beverages','chocolate','chips','cookies','candies',
          'grains','juices','juice mix','juice concentrates','rice',
          'pasta','sauces','seasonings','spices','syrups'
        ],
        index: 0,
        searchQuery: '',
        isSearching: false,
        init() {
            setInterval(() => {
                this.index = (this.index + 1) % this.items.length;
            }, 5000);
        },
        performSearch() {
            if (this.searchQuery.trim()) {
                this.isSearching = true;
                window.location.href = '{{ route('mart.all.items') }}?search=' + encodeURIComponent(this.searchQuery.trim());
            }
        },
        handleKeyPress(event) {
            if (event.key === 'Enter') {
                this.performSearch();
            }
        }
     }">

                <div class="relative">
                    <form @submit.prevent="performSearch()">
                        <input type="text"
                               x-model="searchQuery"
                               @keypress="handleKeyPress"
                               class="w-2/3 border border-gray-300 rounded-lg py-3 pl-12 pr-16 text-sm
                          focus:outline-none focus:ring-2 focus:ring-[#007F73] focus:border-[#007F73]
                          transition-all duration-500"
                               placeholder="">

                        <!-- Animated suggestion overlay (only when no search query) -->
                        <div x-show="!searchQuery" class="absolute left-12 top-3 text-gray-400 text-sm pointer-events-none select-none">
                            <template x-for="(item, i) in items" :key="i">
                        <span x-show="index === i"
                              x-transition:enter="transition ease-out duration-500"
                              x-transition:enter-start="opacity-0 translate-y-2"
                              x-transition:enter-end="opacity-100 translate-y-0"
                              x-transition:leave="transition ease-in duration-500 absolute"
                              x-transition:leave-start="opacity-100 translate-y-0"
                              x-transition:leave-end="opacity-0 -translate-y-2"
                              class="whitespace-nowrap">
                            Search for '<span x-text="item"></span>'
                        </span>
                            </template>
                        </div>

                        <!-- Search Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-4 h-4 absolute left-4 top-3.5 text-gray-400"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 18.5a7.5 7.5 0 006.15-3.35z"/>
                        </svg>

                        <!-- Search Button -->
                        <button type="submit" 
                                :disabled="isSearching || !searchQuery.trim()"
                                class="absolute right-2 top-2 px-3 py-1.5 bg-[#007F73] text-white text-xs rounded-md hover:bg-[#005f56] transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-show="!isSearching">Search</span>
                            <span x-show="isSearching">...</span>
                        </button>
                    </form>
                </div>
            </div>


                 <a href="{{ route('mart.all.items') }}" class="flex flex-col items-center space-y-1.5 text-gray-800 hover:text-[#007F73] transition-colors">
                     <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                         <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                     </svg>
                     <span class="text-xs">All Items</span>
                 </a>
                <a href="{{ url('profile') }}" class="flex flex-col items-center space-y-1.5 text-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                         stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    </svg>

                    <span class="text-xs">Profile</span>
                </a>

       <!-- Cart Trigger -->
       <div x-data="martCartNavbar()" class="relative">
           <!-- Cart Button -->
           <button @click="openCart()" class="flex flex-col items-center text-gray-800 relative hover:text-[#007F73] transition-colors">
               <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                   <path stroke-linecap="round" stroke-linejoin="round"
                         d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/>
               </svg>
               <span class="text-xs">Cart</span>
           </button>

           <!-- Notification Badge -->
           <span x-show="cartCount > 0"
                 x-text="cartCount"
                 x-transition
                 class="absolute -top-1 -right-1 bg-[#007F73] text-white text-[10px] font-bold
                w-5 h-5 flex items-center justify-center rounded-full shadow-lg animate-pulse">
           </span>
       </div>

            </div>

            <!-- Hamburger for mobile -->
            <div class="md:hidden">
                <button @click="mobileMenu = !mobileMenu" class="text-gray-800 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <x-mart.cart />
</header>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('martCartNavbar', () => ({
        cartCount: 0,
        
        init() {
            this.updateCartCount();
            
            // Listen for cart updates
            window.addEventListener('cart-updated', () => {
                this.updateCartCount();
            });
            
            // Listen for item added events
            window.addEventListener('item-added-to-cart', () => {
                this.updateCartCount();
            });
        },
        
        updateCartCount() {
            // Get cart count from local storage - count total quantities, not unique items
            const cartData = localStorage.getItem('mart_cart');
            if (cartData) {
                const cart = JSON.parse(cartData);
                this.cartCount = Object.values(cart).reduce((total, item) => total + (item.quantity || 0), 0);
            } else {
                this.cartCount = 0;
            }
        },
        
        openCart() {
            // Trigger cart drawer to open
            window.dispatchEvent(new CustomEvent('open-cart'));
        }
    }));
});
</script>
