// Mart Cart Functionality - Global Alpine.js Components
document.addEventListener('alpine:init', () => {
    Alpine.data('martCartItem', (name, disPrice, price, photo, subcategoryTitle, description, grams, rating, reviews) => ({
        id: name, // Use name as unique identifier
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
        ready: false,
        
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
        },
        
        increaseQuantity() {
            const cartData = localStorage.getItem('mart_cart') || '{}';
            const cart = JSON.parse(cartData);
            
            if (cart[this.id]) {
                cart[this.id].quantity++;
                localStorage.setItem('mart_cart', JSON.stringify(cart));
                this.quantity = cart[this.id].quantity;
                this.dispatchCartUpdate();
            }
        },
        
        decreaseQuantity() {
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
        },
        
        init() {
            this.loadCartState();
            this.ready = true;
        }
    }));
});
