@props([
    'src' => '',
    'alt' => 'Image',
    'class' => '',
    'placeholder' => '/img/placeholder.png',
    'lazy' => true,
    'width' => null,
    'height' => null
])

<div class="relative overflow-hidden {{ $class }}" 
     x-data="{ 
         loaded: false, 
         error: false, 
         loading: true,
         src: '{{ $src }}',
         placeholder: '{{ $placeholder }}'
     }"
     x-init="
         if (!lazy) {
             loadImage();
         } else {
             // Intersection Observer for lazy loading
             const observer = new IntersectionObserver((entries) => {
                 entries.forEach(entry => {
                     if (entry.isIntersecting && loading) {
                         loadImage();
                         observer.unobserve(entry.target);
                     }
                 });
             });
             observer.observe($el);
         }
         
         function loadImage() {
             const img = new Image();
             img.onload = () => {
                 loaded = true;
                 loading = false;
             };
             img.onerror = () => {
                 error = true;
                 loading = false;
             };
             img.src = src;
         }
     ">
    
    <!-- Loading Skeleton -->
    <div x-show="loading" class="absolute inset-0 bg-gray-200 animate-pulse">
        <div class="flex items-center justify-center h-full">
            <div class="w-8 h-8 border-4 border-gray-300 border-t-[#007F73] rounded-full animate-spin"></div>
        </div>
    </div>
    
    <!-- Error State -->
    <div x-show="error" class="absolute inset-0 bg-gray-100 flex items-center justify-center">
        <div class="text-center text-gray-400">
            <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <p class="text-xs">Image not available</p>
        </div>
    </div>
    
    <!-- Actual Image -->
    <img x-show="loaded && !error" 
         :src="src" 
         alt="{{ $alt }}"
         class="w-full h-full object-cover transition-opacity duration-300"
         :class="{ 'opacity-0': loading, 'opacity-100': loaded }"
         @if($width) width="{{ $width }}" @endif
         @if($height) height="{{ $height }}" @endif>
    
    <!-- Placeholder Image -->
    <img x-show="!loaded && !error" 
         :src="placeholder" 
         alt="{{ $alt }}"
         class="w-full h-full object-cover opacity-50"
         @if($width) width="{{ $width }}" @endif
         @if($height) height="{{ $height }}" @endif>
</div>

<style>
    /* Progressive image loading animation */
    .progressive-image {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200px 100%;
        animation: shimmer 1.5s infinite;
    }
    
    @keyframes shimmer {
        0% {
            background-position: -200px 0;
        }
        100% {
            background-position: calc(200px + 100%) 0;
        }
    }
    
    /* Smooth transitions */
    .image-transition {
        transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
    }
    
    .image-transition:hover {
        transform: scale(1.02);
    }
</style>
