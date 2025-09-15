@props([
    'src' => 'data:image/jpeg;base64,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',
    'disPrice' => 69,
    'price' => 78,
    'save' => 9,
    'title' => 'Onion',
    'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Magnam, possimus?',
    'reviews' => 4,
    'rating' => 4.5,
    'grams' => 200,
    'subcategoryTitle' => 'fruits',
    ])





<div class="relative shadow-md hover:shadow-xl transition-all duration-300 rounded-xl bg-white overflow-hidden group w-full h-full">
    <!-- Product Image -->
    <div class="relative">
        <img
            src="{{ is_array($src) ? $src[0] : $src }}"
            alt="product Image"
            class="rounded-t-xl w-full h-40 object-cover group-hover:scale-105 transition-transform duration-300">

        <!-- Floating Add/Qty Button -->
        <div x-data="martCartItem('{{ $title }}', {{ $disPrice }}, {{ $price }}, '{{ $src }}', '{{ $subcategoryTitle }}', '{{ $description }}', '{{ $grams }}', {{ $rating }}, {{ $reviews }})" class="absolute bottom-2 right-2">
            <!-- Loading state -->
            <div x-show="isLoading" class="px-5 py-1.5 rounded-full bg-[#007F73] text-white text-xs font-semibold shadow-md">
                <div class="flex items-center gap-1">
                    <div class="w-3 h-3 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                    <span>Adding...</span>
                </div>
            </div>

            <!-- If not added yet -->
            <button x-show="!isLoading && quantity === 0"
                    @click="addToCart()"
                    class="px-5 py-1.5 rounded-full border border-[#007F73]
                   text-[#007F73] text-xs font-semibold bg-white shadow-md hover:bg-[#E8F8DB] transition">
                ADD
            </button>

            <!-- If added, show increment/decrement -->
            <div x-show="!isLoading && quantity > 0"
                 x-transition
                 class="flex items-center gap-2 bg-[#007F73] text-white px-3 py-1.5 rounded-full text-xs font-semibold shadow-md">
                <button @click="decreaseQuantity()" class="px-2 hover:bg-[#005f56] rounded">−</button>
                <span x-text="quantity"></span>
                <button @click="increaseQuantity()" class="px-2 hover:bg-[#005f56] rounded">+</button>
            </div>
        </div>
    </div>

    <!-- Product Info -->
    <div class="p-3 space-y-2">
        <!-- Price & Save -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-1">
                <span class="text-base font-bold text-green-600">₹{{$disPrice}}</span>
                <span class="text-gray-400 line-through text-red-600 text-xs">₹{{$price}}</span>
            </div>
            <span class="bg-[#E8F8DB] text-[#007F73] text-[10px] px-2 py-0.5 rounded-full font-semibold inline-flex items-center gap-x-1">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                     fill="currentColor" class="w-3 h-3">
                    <path fill-rule="evenodd"
                          d="M5.25 2.25a3 3 0 0 0-3 3v4.318a3 3 0 0 0 .879 2.121l9.58 9.581c.92.92 2.39 1.186 3.548.428a18.849 18.849 0 0 0 5.441-5.44c.758-1.16.492-2.629-.428-3.548l-9.58-9.581a3 3 0 0 0-2.122-.879H5.25ZM6.375 7.5a1.125 1.125 0 1 0 0-2.25 1.125 1.125 0 0 0 0 2.25Z"
                          clip-rule="evenodd"/>
                </svg>
                SAVE ₹{{$save}}
            </span>
        </div>

        <!-- Product Title & Gram -->
        <div>
            <h3 class="text-sm font-semibold text-gray-700 truncate">{{$title}}</h3>
            <p class="text-gray-400 text-[12px] line-clamp-2">{{$description}} g</p>
        </div>

        <!-- Category & Delivery Time -->
        <div class="flex items-center justify-between text-[10px]">
            <span class="bg-[#E8F8DB] text-[#007F73] px-2 py-0.5 rounded-full font-semibold">{{$subcategoryTitle}}</span>
            <div class="flex items-center gap-1 bg-gray-100 rounded-full px-2 py-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                     fill="currentColor" class="w-3 h-3">
                    <path fill-rule="evenodd"
                          d="M14.615 1.595a.75.75 0 0 1 .359.852L12.982 9.75h7.268a.75.75 0 0 1 .548 1.262l-10.5 11.25a.75.75 0 0 1-1.272-.71l1.992-7.302H3.75a.75.75 0 0 1-.548-1.262l10.5-11.25a.75.75 0 0 1 .913-.143Z"
                          clip-rule="evenodd"/>
                </svg>
                <span>15 mins</span>
            </div>
        </div>

        <!-- Rating -->
        <div class="flex items-center gap-1">
            <span class="bg-yellow-100 text-yellow-600 px-2 py-0.5 text-[10px] inline-flex items-center gap-x-1 rounded-full font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                     fill="currentColor" class="w-3 h-3">
                    <path fill-rule="evenodd"
                          d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z"
                          clip-rule="evenodd"/>
                </svg>
                {{$rating}}
            </span>
            <span class="text-gray-500 text-[10px]">({{$reviews}})</span>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('martCartItem', (id, name, price, disPrice, photo, subcategoryTitle, description, grams, rating, reviews) => ({
        id: id,
        name: name,
        price: price,
        disPrice: disPrice,
        photo: photo,
        subcategoryTitle: subcategoryTitle,
        description: description,
        grams: grams,
        rating: rating,
        reviews: reviews,
        quantity: 0,
        isLoading: false,
        
        init() {
            this.loadCartState();
        },
        
        loadCartState() {
            // Load cart state from local storage
            const cartData = localStorage.getItem('mart_cart');
            if (cartData) {
                const cart = JSON.parse(cartData);
                if (cart[this.id]) {
                    this.quantity = cart[this.id].quantity;
                }
            }
        },
        
        addToCart() {
            this.isLoading = true;
            
            const productData = {
                id: this.id,
                name: this.name,
                price: this.price,
                disPrice: this.disPrice,
                photo: this.photo,
                subcategoryTitle: this.subcategoryTitle,
                description: this.description,
                grams: this.grams,
                rating: this.rating,
                reviews: this.reviews
            };
            
            // Add to local storage
            const cartData = localStorage.getItem('mart_cart') || '{}';
            const cart = JSON.parse(cartData);
            
            if (cart[this.id]) {
                cart[this.id].quantity++;
            } else {
                cart[this.id] = { ...productData, quantity: 1 };
            }
            
            localStorage.setItem('mart_cart', JSON.stringify(cart));
            this.quantity = cart[this.id].quantity;
            
            // Dispatch events
            this.dispatchCartUpdate();
            this.dispatchItemAdded(cart[this.id]);
            
            this.isLoading = false;
        },
        
        increaseQuantity() {
            this.isLoading = true;
            
            const cartData = localStorage.getItem('mart_cart') || '{}';
            const cart = JSON.parse(cartData);
            
            if (cart[this.id]) {
                cart[this.id].quantity++;
                localStorage.setItem('mart_cart', JSON.stringify(cart));
                this.quantity = cart[this.id].quantity;
                this.dispatchCartUpdate();
            }
            
            this.isLoading = false;
        },
        
        decreaseQuantity() {
            this.isLoading = true;
            
            const cartData = localStorage.getItem('mart_cart') || '{}';
            const cart = JSON.parse(cartData);
            
            if (cart[this.id] && cart[this.id].quantity > 0) {
                cart[this.id].quantity--;
                if (cart[this.id].quantity === 0) {
                    delete cart[this.id];
                }
                localStorage.setItem('mart_cart', JSON.stringify(cart));
                this.quantity = cart[this.id] ? cart[this.id].quantity : 0;
                this.dispatchCartUpdate();
            }
            
            this.isLoading = false;
        },
        
        dispatchCartUpdate() {
            // Dispatch event to update cart count in navbar
            window.dispatchEvent(new CustomEvent('cart-updated'));
        },
        
        dispatchItemAdded(item) {
            // Dispatch event to show cart popup
            window.dispatchEvent(new CustomEvent('item-added-to-cart', {
                detail: { item: item }
            }));
        }
    }));
});
</script>
