@props(['banners' => []])
<div x-data="carousel()" class="relative w-full sm:w-[90%] mx-auto overflow-hidden rounded-2xl shadow-lg">
    <!-- Slides -->
    <div class="relative flex transition-transform duration-700 ease-in-out"
         :style="`transform: translateX(-${active * 100}%);`">

        @if(!empty($banners) && count($banners) > 0)
            @foreach($banners as $banner)
                <div class="w-full flex-shrink-0 bg-cover bg-center relative" style="height: 320px; background-image: url('{{ $banner['photo'] ?? '' }}')">
                    <div class="absolute inset-0 bg-black/30"></div>
                    <div class="absolute inset-0 flex items-center p-6 md:p-12 text-white">
                        <div class="max-w-lg">
                            <p class="text-sm uppercase font-semibold">{{ $banner['text'] ?? '' }}</p>
                            <h1 class="text-3xl md:text-5xl font-bold mt-2">{{ $banner['title'] ?? '' }}</h1>
                            <p class="mt-4 text-lg">{{ $banner['description'] ?? '' }}</p>
                            @php
                                $href = 'javascript:void(0)';
                                if(($banner['redirect_type'] ?? '') === 'external' && !empty($banner['external_link'] ?? null)) {
                                    $href = $banner['external_link'];
                                } elseif(($banner['redirect_type'] ?? '') === 'product' && !empty($banner['productId'] ?? null)) {
                                    $href = url('/product/' . urlencode($banner['productId']));
                                }
                            @endphp
                            <a href="{{ $href }}" class="inline-block mt-6 px-5 py-2 bg-pink-600 text-white rounded-xl shadow hover:bg-pink-700">View</a>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <!-- Static fallback when no dynamic banners -->
            <x-mart.slide
                src="https://images.unsplash.com/photo-1581579186919-4c16e6d27ec0?auto=format&fit=crop&w=1200&q=80"
                text1="✨ Everyday Essentials"
                text2="Your Daily Needs, Delivered Fast"
                text3="Up to"
                text4="50% OFF on Groceries"
                button="🛒 Shop Now"
            />
            <x-mart.slide
                src="https://images.unsplash.com/photo-1606788075761-8a90d99ba3a2?auto=format&fit=crop&w=1200&q=80"
                text1="🥬 Fresh & Quick"
                text2="Fresh Picks. 🚀 Lightning Fast Delivery."
                text3="Get Fruits & Veggies"
                text4="Best Prices"
                button="🍎 Order Now"
            />
            <x-mart.slide
                src="https://images.unsplash.com/photo-1606788075761-8a90d99ba3a2?auto=format&fit=crop&w=1200&q=80"
                text1="💰 Big Savings"
                text2="Save More Every Day"
                text3="Mart Fest Deals – Up to"
                text4="70% OFF"
                button="⚡ Grab the Offer"
            />
        @endif
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
