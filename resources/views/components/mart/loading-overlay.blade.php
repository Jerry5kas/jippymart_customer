@props([
    'show' => false,
    'message' => 'Loading...',
    'type' => 'default' // default, spinner, dots, pulse
])

@if($show)
<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" 
     x-data="{ show: @js($show) }" 
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
    
    <div class="bg-white rounded-2xl p-8 max-w-sm mx-4 text-center shadow-2xl">
        @if($type === 'spinner')
            <!-- Spinner Loading -->
            <div class="flex justify-center mb-4">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#007F73]"></div>
            </div>
        @elseif($type === 'dots')
            <!-- Dots Loading -->
            <div class="flex justify-center mb-4 space-x-1">
                <div class="w-3 h-3 bg-[#007F73] rounded-full animate-bounce"></div>
                <div class="w-3 h-3 bg-[#007F73] rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                <div class="w-3 h-3 bg-[#007F73] rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
            </div>
        @elseif($type === 'pulse')
            <!-- Pulse Loading -->
            <div class="flex justify-center mb-4">
                <div class="w-12 h-12 bg-[#007F73] rounded-full animate-pulse"></div>
            </div>
        @else
            <!-- Default Loading -->
            <div class="flex justify-center mb-4">
                <div class="relative">
                    <div class="animate-spin rounded-full h-12 w-12 border-4 border-gray-200"></div>
                    <div class="animate-spin rounded-full h-12 w-12 border-4 border-[#007F73] border-t-transparent absolute top-0 left-0"></div>
                </div>
            </div>
        @endif
        
        <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $message }}</h3>
        <p class="text-sm text-gray-600">Please wait while we load your content...</p>
        
        <!-- Progress Bar (optional) -->
        <div class="mt-4 w-full bg-gray-200 rounded-full h-2">
            <div class="bg-[#007F73] h-2 rounded-full animate-pulse" style="width: 60%"></div>
        </div>
    </div>
</div>
@endif

<style>
    @keyframes bounce {
        0%, 20%, 53%, 80%, 100% {
            transform: translate3d(0,0,0);
        }
        40%, 43% {
            transform: translate3d(0, -8px, 0);
        }
        70% {
            transform: translate3d(0, -4px, 0);
        }
        90% {
            transform: translate3d(0, -2px, 0);
        }
    }
    
    .animate-bounce {
        animation: bounce 1s infinite;
    }
</style>
