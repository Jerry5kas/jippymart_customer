<!-- Global Loading Service -->
<script>
    class LoadingService {
        constructor() {
            this.loadingStates = new Map();
            this.globalLoading = false;
            this.loadingQueue = [];
        }

        // Set loading state for a specific component
        setLoading(componentId, isLoading, message = 'Loading...') {
            this.loadingStates.set(componentId, {
                loading: isLoading,
                message: message,
                timestamp: Date.now()
            });
            
            // Update global loading state
            this.updateGlobalLoading();
            
            // Dispatch custom event
            this.dispatchLoadingEvent(componentId, isLoading, message);
        }

        // Get loading state for a component
        getLoading(componentId) {
            return this.loadingStates.get(componentId) || { loading: false, message: '' };
        }

        // Update global loading state
        updateGlobalLoading() {
            const hasLoading = Array.from(this.loadingStates.values()).some(state => state.loading);
            this.globalLoading = hasLoading;
            
            // Update global loading indicator
            this.updateGlobalIndicator();
        }

        // Update global loading indicator
        updateGlobalIndicator() {
            const indicator = document.getElementById('global-loading-indicator');
            if (indicator) {
                if (this.globalLoading) {
                    indicator.style.display = 'block';
                } else {
                    indicator.style.display = 'none';
                }
            }
        }

        // Dispatch loading event
        dispatchLoadingEvent(componentId, isLoading, message) {
            const event = new CustomEvent('loading-state-changed', {
                detail: {
                    componentId,
                    isLoading,
                    message,
                    timestamp: Date.now()
                }
            });
            document.dispatchEvent(event);
        }

        // Show loading overlay
        showOverlay(message = 'Loading...', type = 'default') {
            const overlay = document.getElementById('loading-overlay');
            if (overlay) {
                overlay.setAttribute('data-show', 'true');
                overlay.setAttribute('data-message', message);
                overlay.setAttribute('data-type', type);
            }
        }

        // Hide loading overlay
        hideOverlay() {
            const overlay = document.getElementById('loading-overlay');
            if (overlay) {
                overlay.setAttribute('data-show', 'false');
            }
        }

        // Preload images
        preloadImages(urls) {
            return Promise.all(
                urls.map(url => {
                    return new Promise((resolve, reject) => {
                        const img = new Image();
                        img.onload = () => resolve(url);
                        img.onerror = () => reject(url);
                        img.src = url;
                    });
                })
            );
        }

        // Optimize Firebase queries
        async optimizeFirebaseQuery(query, options = {}) {
            const startTime = Date.now();
            
            try {
                // Add timeout
                const timeout = options.timeout || 10000;
                const timeoutPromise = new Promise((_, reject) => {
                    setTimeout(() => reject(new Error('Query timeout')), timeout);
                });

                const result = await Promise.race([query, timeoutPromise]);
                
                const duration = Date.now() - startTime;
                console.log(`Firebase query completed in ${duration}ms`);
                
                return result;
            } catch (error) {
                console.error('Firebase query failed:', error);
                throw error;
            }
        }

        // Batch loading for better performance
        async batchLoad(loaders, options = {}) {
            const { concurrency = 3, delay = 100 } = options;
            const results = [];
            
            for (let i = 0; i < loaders.length; i += concurrency) {
                const batch = loaders.slice(i, i + concurrency);
                const batchResults = await Promise.allSettled(batch.map(loader => loader()));
                results.push(...batchResults);
                
                // Add delay between batches to prevent overwhelming
                if (i + concurrency < loaders.length) {
                    await new Promise(resolve => setTimeout(resolve, delay));
                }
            }
            
            return results;
        }

        // Progressive loading for large datasets
        async progressiveLoad(loader, options = {}) {
            const { batchSize = 10, delay = 200 } = options;
            const results = [];
            let offset = 0;
            
            while (true) {
                const batch = await loader(offset, batchSize);
                if (batch.length === 0) break;
                
                results.push(...batch);
                offset += batchSize;
                
                // Add delay between batches
                await new Promise(resolve => setTimeout(resolve, delay));
            }
            
            return results;
        }
    }

    // Create global instance
    window.loadingService = new LoadingService();

    // Global loading indicator (disabled for cleaner UI)
    document.addEventListener('DOMContentLoaded', function() {
        // Listen for loading state changes (console only)
        document.addEventListener('loading-state-changed', function(event) {
            const { componentId, isLoading, message } = event.detail;
            console.log(`Loading state changed for ${componentId}:`, isLoading, message);
        });
    });
</script>

<!-- Global Loading Overlay -->
<div id="loading-overlay" 
     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
     style="display: none;"
     x-data="{ show: false }"
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
    
    <div class="bg-white rounded-2xl p-8 max-w-sm mx-4 text-center shadow-2xl">
        <div class="flex justify-center mb-4">
            <div class="animate-spin rounded-full h-12 w-12 border-4 border-gray-200"></div>
            <div class="animate-spin rounded-full h-12 w-12 border-4 border-[#007F73] border-t-transparent absolute"></div>
        </div>
        
        <h3 class="text-lg font-semibold text-gray-800 mb-2" id="loading-message">Loading...</h3>
        <p class="text-sm text-gray-600">Please wait while we load your content...</p>
        
        <div class="mt-4 w-full bg-gray-200 rounded-full h-2">
            <div class="bg-[#007F73] h-2 rounded-full animate-pulse" style="width: 60%"></div>
        </div>
    </div>
</div>

<script>
    // Update overlay when data attributes change
    const overlay = document.getElementById('loading-overlay');
    if (overlay) {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'data-show') {
                    const show = overlay.getAttribute('data-show') === 'true';
                    const message = overlay.getAttribute('data-message') || 'Loading...';
                    
                    if (show) {
                        overlay.style.display = 'flex';
                        document.getElementById('loading-message').textContent = message;
                    } else {
                        overlay.style.display = 'none';
                    }
                }
            });
        });
        
        observer.observe(overlay, { attributes: true });
    }
</script>
