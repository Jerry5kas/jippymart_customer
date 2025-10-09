<div x-data="carousel({{ !empty($banners) ? count($banners) : 3 }})" 
     x-init="init()" 
     class="relative w-full max-w-7xl mx-auto overflow-hidden rounded-3xl shadow-2xl bg-gradient-to-br from-[#E8F8DB] to-[#C9EDAB]"
     @mouseenter="pauseAutoplay()" 
     @mouseleave="resumeAutoplay()"
>
    
    <!-- Slides Container -->
    <div class="relative h-64 sm:h-80 md:h-96 lg:h-[28rem] xl:h-[32rem]">
        <div class="relative flex h-full transition-transform duration-1000 ease-in-out"
             :style="`transform: translateX(-${currentIndex * 100}%);`">

            @forelse ($banners ?? [] as $index => $banner)
                <div class="w-full flex-shrink-0 relative">
                    <!-- Clickable Banner Image -->
                    <a href="{{ route('mart.banner.redirect', ['bannerTitle' => $banner['title'] ?? '']) }}" 
                       class="block w-full h-full cursor-pointer">
                        <img src="{{ $banner['photo'] }}" 
                             alt="{{ $banner['title'] ?? 'Banner' }}"
                             class="w-full h-full object-cover object-center hover:scale-105 transition-transform duration-300"
                             loading="lazy">
                        <!-- Debug: Show banner position -->
                        <div class="absolute top-4 right-4 bg-black/70 text-white px-3 py-1 rounded-full text-sm font-bold">
                            Banner {{ $index + 1 }}
                        </div>
                    </a>
                </div>
            @empty
                <!-- Fallback content when no banners -->
                <div class="w-full flex-shrink-0 flex items-center justify-center bg-gradient-to-br from-[#E8F8DB] to-[#C9EDAB]">
                    <div class="text-center text-gray-500">
                        <div class="w-16 h-16 mx-auto mb-4 bg-[#007F73] rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <p class="text-lg font-medium text-[#007F73]">Welcome to JippyMart!</p>
                        <p class="text-sm text-[#007F73]/70">Fresh groceries delivered to your doorstep</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Progress Dots -->
    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 z-30">
        <div class="flex space-x-2 bg-black/20 backdrop-blur-sm rounded-full px-3 py-2">
            <template x-for="(dot, index) in totalSlides" :key="index">
                <button @click="goToSlide(index)"
                        :class="{
                            'bg-white scale-110': currentIndex === index, 
                            'bg-white/50 hover:bg-white/70': currentIndex !== index
                        }"
                        class="w-2 h-2 sm:w-3 sm:h-3 rounded-full transition-all duration-300 ease-out hover:scale-110"></button>
            </template>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="absolute bottom-0 left-0 w-full h-1 bg-black/10">
        <div class="h-full bg-[#007F73] transition-all duration-1000 ease-out"
             :style="`width: ${((currentIndex + 1) / totalSlides) * 100}%`"></div>
    </div>
</div>

<script>
    function carousel(bannerCount) {
        return {
            currentIndex: 0,
            totalSlides: Math.max(1, bannerCount),
            autoplayInterval: null,
            isPaused: false,
            
            nextSlide() {
                // Always move forward: 0→1→2→0→1→2
                this.currentIndex = (this.currentIndex + 1) % this.totalSlides;
                console.log('Moving to slide:', this.currentIndex + 1, 'of', this.totalSlides);
            },
            
            goToSlide(index) {
                this.currentIndex = index;
                console.log('Jump to slide:', this.currentIndex + 1);
            },
            
            startAutoplay() {
                // Always stop any existing interval first to prevent duplicates
                this.stopAutoplay();
                
                if (this.totalSlides > 1 && !this.isPaused) {
                    console.log('▶️ Autoplay started - Total slides:', this.totalSlides);
                    this.autoplayInterval = setInterval(() => {
                        this.nextSlide();
                    }, 6000); // 6 seconds per slide
                }
            },
            
            stopAutoplay() {
                if (this.autoplayInterval) {
                    clearInterval(this.autoplayInterval);
                    this.autoplayInterval = null;
                    console.log('⏹️ Autoplay stopped');
                }
            },
            
            pauseAutoplay() {
                if (!this.isPaused) {
                    this.isPaused = true;
                    this.stopAutoplay();
                    console.log('⏸️ Autoplay paused');
                }
            },
            
            resumeAutoplay() {
                if (this.isPaused) {
                    this.isPaused = false;
                    this.startAutoplay();
                    console.log('▶️ Autoplay resumed');
                }
            },
            
            init() {
                console.log('🎠 Carousel initialized');
                console.log('📊 Total slides:', this.totalSlides);
                console.log('🎯 Starting from slide 1');
                
                // Start from first slide
                this.currentIndex = 0;
                
                if (this.totalSlides > 1) {
                    // Start autoplay immediately
                    this.startAutoplay();
                }
                
                // Pause when page is hidden (one-time listener)
                const visibilityHandler = () => {
                    if (document.hidden) {
                        this.pauseAutoplay();
                    } else {
                        this.resumeAutoplay();
                    }
                };
                
                document.addEventListener('visibilitychange', visibilityHandler);
                
                // Cleanup when component is destroyed
                this.$el.addEventListener('alpine:destroying', () => {
                    this.stopAutoplay();
                    document.removeEventListener('visibilitychange', visibilityHandler);
                });
            }
        }
    }
</script>
