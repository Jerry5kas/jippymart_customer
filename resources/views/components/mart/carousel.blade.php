<div x-data="carousel()" class="relative w-full sm:w-[90%] mx-auto overflow-hidden rounded-2xl shadow-lg">
    <!-- Slides -->
    <div class="relative flex transition-transform duration-700 ease-in-out"
         :style="`transform: translateX(-${active * 100}%);`">

        <!-- Idea 1: Everyday Essentials -->
        <x-mart.slide
            src="https://images.unsplash.com/photo-1581579186919-4c16e6d27ec0?auto=format&fit=crop&w=1200&q=80"
            text1="✨ Everyday Essentials"
            text2="Your Daily Needs, Delivered Fast"
            text3="Up to"
            text4="50% OFF on Groceries"
            button="🛒 Shop Now"
        />

        <!-- Idea 2: Fresh & Quick -->
        <x-mart.slide
            src="https://images.unsplash.com/photo-1606788075761-8a90d99ba3a2?auto=format&fit=crop&w=1200&q=80"
            text1="🥬 Fresh & Quick"
            text2="Fresh Picks. 🚀 Lightning Fast Delivery."
            text3="Get Fruits & Veggies"
            text4="Best Prices"
            button="🍎 Order Now"
        />

        <!-- Idea 3: Big Savings -->
        <x-mart.slide
            src="https://images.unsplash.com/photo-1606788075761-8a90d99ba3a2?auto=format&fit=crop&w=1200&q=80"
            text1="💰 Big Savings"
            text2="Save More Every Day"
            text3="Mart Fest Deals – Up to"
            text4="70% OFF"
            button="⚡ Grab the Offer"
        />
    </div>

    <!-- Dots -->
    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-2">
        <template x-for="(dot, index) in slides" :key="index">
            <button @click="active = index"
                    :class="{'bg-pink-600': active === index, 'bg-gray-400': active !== index}"
                    class="w-3 h-3 rounded-full transition-colors"></button>
        </template>
    </div>
</div>


<script>
    function carousel() {
        return {
            active: 0,
            slides: [0, 1, 2],
            next() {
                this.active = (this.active + 1) % this.slides.length;
            },
            prev() {
                this.active = (this.active - 1 + this.slides.length) % this.slides.length;
            },
            autoplay() {
                setInterval(() => {
                    this.next();
                }, 10000); // Changed from 6s to 10s to reduce server load
            },
            init() {
                this.autoplay();
            }
        }
    }
</script>
