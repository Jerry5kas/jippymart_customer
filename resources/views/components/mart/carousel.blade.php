<div x-data="carousel({{ !empty($banners) ? count($banners) : 3 }})" 
     x-init="init()" 
     class="relative w-full max-w-7xl mx-auto overflow-hidden rounded-3xl shadow-2xl bg-gradient-to-br from-[#E8F8DB] to-[#C9EDAB]"
     @mouseenter="pauseAutoplay()" 
     @mouseleave="resumeAutoplay()">
    
    <!-- Slides Container -->
    <div class="relative h-64 sm:h-80 md:h-96 lg:h-[28rem] xl:h-[32rem]">
        <div class="relative flex h-full transition-all duration-1000 ease-out"
             :style="`transform: translateX(-${active * 100}%);`">

            @forelse ($banners ?? [] as $banner)
                <div class="w-full flex-shrink-0 relative">
                    <!-- Clickable Banner Image -->
                    <a href="{{ route('mart.banner.redirect', ['bannerTitle' => $banner['title'] ?? '']) }}" 
                       class="block w-full h-full cursor-pointer">
                        <img src="{{ $banner['photo'] }}" 
                             alt="{{ $banner['title'] ?? 'Banner' }}"
                             class="w-full h-full object-cover object-center hover:scale-105 transition-transform duration-300"
                             loading="lazy">
                    </a>
                </div>
            @empty
                <div class="w-full flex-shrink-0 flex items-center justify-center bg-gradient-to-br from-[#E8F8DB] to-[#C9EDAB]">
                    <div class="text-center text-gray-500">
                        <div class="w-16 h-16 mx-auto mb-4 bg-[#007F73] rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <p class="text-lg font-medium text-[#007F73]">No banners available</p>
                        <p class="text-sm text-[#007F73]/70">Check back later for exciting offers!</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Navigation Arrows -->
    <!-- <button @click="prev()" 
            class="absolute left-2 sm:left-4 top-1/2 -translate-y-1/2 z-30 bg-white/90 hover:bg-white text-gray-800 rounded-full p-2 sm:p-3 shadow-lg hover:shadow-xl transform hover:scale-110 transition-all duration-300 ease-out backdrop-blur-sm">
        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
    </button> -->
    
    <!-- <button @click="next()" 
            class="absolute right-2 sm:right-4 top-1/2 -translate-y-1/2 z-30 bg-white/90 hover:bg-white text-gray-800 rounded-full p-2 sm:p-3 shadow-lg hover:shadow-xl transform hover:scale-110 transition-all duration-300 ease-out backdrop-blur-sm">
        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </button> -->

    <!-- Progress Dots -->
    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 z-30">
        <div class="flex space-x-2 bg-black/20 backdrop-blur-sm rounded-full px-3 py-2">
            <template x-for="(dot, index) in slides" :key="index">
                <button @click="goToSlide(index)"
                        :class="{
                            'bg-white scale-110': active === index, 
                            'bg-white/50 hover:bg-white/70': active !== index
                        }"
                        class="w-2 h-2 sm:w-3 sm:h-3 rounded-full transition-all duration-300 ease-out hover:scale-110"></button>
            </template>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="absolute bottom-0 left-0 w-full h-1 bg-black/10">
        <div class="h-full bg-[#007F73] transition-all duration-1000 ease-out"
             :style="`width: ${((active + 1) / slides.length) * 100}%`"></div>
    </div>
</div>

<script>
    function carousel(bannerCount) {
        return {
            active: 0,
            slides: Array.from({length: Math.max(1, bannerCount)}, (_, i) => i),
            autoplayInterval: null,
            isPaused: false,
            
            next() {
                this.active = (this.active + 1) % this.slides.length;
            },
            
            prev() {
                this.active = (this.active - 1 + this.slides.length) % this.slides.length;
            },
            
            goToSlide(index) {
                this.active = index;
            },
            
            startAutoplay() {
                if (this.slides.length > 1 && !this.isPaused) {
                    this.autoplayInterval = setInterval(() => {
                        this.next();
                    }, 6000); // Increased to 6 seconds for better UX
                }
            },
            
            stopAutoplay() {
                if (this.autoplayInterval) {
                    clearInterval(this.autoplayInterval);
                    this.autoplayInterval = null;
                }
            },
            
            pauseAutoplay() {
                this.isPaused = true;
                this.stopAutoplay();
            },
            
            resumeAutoplay() {
                this.isPaused = false;
                this.startAutoplay();
            },
            
            init() {
                if (this.slides.length > 1) {
                    this.startAutoplay();
                }
                
                // Pause autoplay when page is not visible
                document.addEventListener('visibilitychange', () => {
                    if (document.hidden) {
                        this.pauseAutoplay();
                    } else {
                        this.resumeAutoplay();
                    }
                });
            }
        }
    }
</script>
