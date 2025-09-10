<div x-data="carousel({{ !empty($banners) ? count($banners) : 3 }})" x-init="init()" class="relative w-full sm:w-[90%] mx-auto overflow-hidden rounded-2xl shadow-lg">
    <!-- Slides -->
    <div class="relative flex transition-transform duration-700 ease-in-out"
         :style="`transform: translateX(-${active * 100}%);`">

        @forelse ($banners ?? [] as $banner)
            <div class="w-full flex-shrink-0">
                <x-mart.slide
                    :src="$banner['photo']"
                    :text1="$banner['title']"
                    :text2="$banner['text']"
                    text3="Up to"
                    :text4="$banner['description']"
                    button="🛒 Shop Now"
                />
            </div>
        @empty
            <div class="w-full flex-shrink-0">
                <div class="text-center text-gray-500 py-8">
                    <p>No banners available at the moment.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Navigation Arrows -->
    <button @click="prev()" class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-gray-800 rounded-full p-2 shadow-lg transition-all">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
    </button>
    <button @click="next()" class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-gray-800 rounded-full p-2 shadow-lg transition-all">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </button>

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
    function carousel(bannerCount) {
        return {
            active: 0,
            slides: Array.from({length: Math.max(1, bannerCount)}, (_, i) => i),
            next() {
                this.active = (this.active + 1) % this.slides.length;
            },
            prev() {
                this.active = (this.active - 1 + this.slides.length) % this.slides.length;
            },
            autoplay() {
                if (this.slides.length > 1) {
                    setInterval(() => {
                        this.next();
                    }, 5000);
                }
            },
            init() {
                if (this.slides.length > 1) {
                    this.autoplay();
                }
            }
        }
    }
</script>
