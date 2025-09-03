<div x-data="carousel()" class="relative w-full sm:w-[90%] mx-auto overflow-hidden rounded-2xl shadow-lg">
    <!-- Slides -->
    <div class="relative flex transition-transform duration-700 ease-in-out"
         :style="`transform: translateX(-${active * 100}%);`">
        <x-mart.slide/>
        <x-mart.slide
            src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT14yDUeoaf-N-53xpJnSwVvsVEeR9ofHYcfA&s"/>
        <x-mart.slide
            src="https://static.vecteezy.com/system/resources/thumbnails/005/715/816/small/banner-abstract-background-board-for-text-and-message-design-modern-free-vector.jpg"/>
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
