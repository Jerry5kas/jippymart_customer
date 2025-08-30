
<div x-data="carousel()" class="relative w-full max-w-6xl mx-auto overflow-hidden rounded-2xl shadow-lg">
    <!-- Slides -->
    <div class="relative flex transition-transform duration-700 ease-in-out"
         :style="`transform: translateX(-${active * 100}%);`">

        <!-- Slide 1 -->
        <div class="w-full flex-shrink-0 bg-pink-100 bg-cover bg-center"
             style="background-image: url('https://i.ibb.co/3pBMcTr/makeup.png')">
            <div class="bg-black/30 w-full h-full flex items-center p-6 md:p-12">
                <div class="max-w-lg text-white">
                    <p class="text-sm uppercase font-semibold">Powered by Lakmé</p>
                    <h1 class="text-3xl md:text-5xl font-bold mt-2">Beauty LIT Fest</h1>
                    <p class="mt-4 text-lg">UP TO <span class="font-bold">60% OFF</span></p>
                    <button class="mt-6 px-5 py-2 bg-pink-600 text-white rounded-xl shadow hover:bg-pink-700">
                        Shop Now
                    </button>
                </div>
            </div>
        </div>

        <!-- Slide 2 -->
        <div class="w-full flex-shrink-0 bg-pink-50 bg-cover bg-center"
             style="background-image: url('https://i.ibb.co/3WQf3Lg/eyemakeup.png')">
            <div class="bg-black/30 w-full h-full flex items-center p-6 md:p-12">
                <div class="max-w-lg text-white">
                    <h2 class="text-2xl md:text-4xl font-bold">Dazzling Eyes</h2>
                    <p class="mt-3">Get UP TO <span class="font-bold">60% OFF</span> on eyeliners, kajals & more.</p>
                    <button class="mt-6 px-5 py-2 bg-gray-900 text-white rounded-xl shadow hover:bg-gray-800">
                        Shop Now
                    </button>
                </div>
            </div>
        </div>

        <!-- Slide 3 -->
        <div class="w-full flex-shrink-0 bg-pink-200 bg-cover bg-center"
             style="background-image: url('https://i.ibb.co/4NTK9Fd/foundation.png')">
            <div class="bg-black/30 w-full h-full flex items-center p-6 md:p-12">
                <div class="max-w-lg text-white">
                    <h2 class="text-2xl md:text-4xl font-bold">Flawless Face</h2>
                    <p class="mt-3">UP TO <span class="font-bold">45% OFF</span> on foundation & concealers.</p>
                    <button class="mt-6 px-5 py-2 bg-pink-700 text-white rounded-xl shadow hover:bg-pink-800">
                        Shop Now
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Arrows -->
    <button @click="prev"
            class="absolute left-3 top-1/2 -translate-y-1/2 bg-white/70 hover:bg-white p-2 rounded-full shadow">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </button>
    <button @click="next"
            class="absolute right-3 top-1/2 -translate-y-1/2 bg-white/70 hover:bg-white p-2 rounded-full shadow">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
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
                }, 6000);
            },
            init() {
                this.autoplay();
            }
        }
    }
</script>
