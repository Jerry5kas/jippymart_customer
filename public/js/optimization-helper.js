/**
 * Optimization Helper Script
 * This script provides utilities to optimize server load and reduce process usage
 */

class OptimizationHelper {
    constructor() {
        this.activeIntervals = new Set();
        this.activeTimeouts = new Set();
        this.lastUpdateTimes = new Map();
        this.minUpdateIntervals = new Map();
    }

    /**
     * Create an optimized interval that respects minimum update intervals
     * @param {Function} callback - Function to execute
     * @param {number} interval - Interval in milliseconds
     * @param {string} key - Unique key for this interval
     * @param {number} minUpdateInterval - Minimum time between updates (default: 30000ms)
     * @returns {number} Interval ID
     */
    createOptimizedInterval(callback, interval, key, minUpdateInterval = 30000) {
        // Clear existing interval if it exists
        this.clearIntervalByKey(key);
        
        // Set minimum update interval
        this.minUpdateIntervals.set(key, minUpdateInterval);
        
        const intervalId = setInterval(() => {
            const now = Date.now();
            const lastUpdate = this.lastUpdateTimes.get(key) || 0;
            const minInterval = this.minUpdateIntervals.get(key) || 0;
            
            if (now - lastUpdate >= minInterval) {
                callback();
                this.lastUpdateTimes.set(key, now);
            }
        }, interval);
        
        this.activeIntervals.add(intervalId);
        return intervalId;
    }

    /**
     * Create an optimized timeout with debouncing
     * @param {Function} callback - Function to execute
     * @param {number} delay - Delay in milliseconds
     * @param {string} key - Unique key for this timeout
     * @returns {number} Timeout ID
     */
    createOptimizedTimeout(callback, delay, key) {
        // Clear existing timeout if it exists
        this.clearTimeoutByKey(key);
        
        const timeoutId = setTimeout(() => {
            callback();
            this.activeTimeouts.delete(timeoutId);
        }, delay);
        
        this.activeTimeouts.add(timeoutId);
        return timeoutId;
    }

    /**
     * Clear interval by key
     * @param {string} key - Key to identify the interval
     */
    clearIntervalByKey(key) {
        // This would need to be implemented with a mapping system
        // For now, we'll use the standard clearInterval
    }

    /**
     * Clear timeout by key
     * @param {string} key - Key to identify the timeout
     */
    clearTimeoutByKey(key) {
        // This would need to be implemented with a mapping system
        // For now, we'll use the standard clearTimeout
    }

    /**
     * Clear all active intervals and timeouts
     */
    clearAll() {
        this.activeIntervals.forEach(intervalId => {
            clearInterval(intervalId);
        });
        this.activeTimeouts.forEach(timeoutId => {
            clearTimeout(timeoutId);
        });
        
        this.activeIntervals.clear();
        this.activeTimeouts.clear();
        this.lastUpdateTimes.clear();
        this.minUpdateIntervals.clear();
        
        console.log('🧹 All intervals and timeouts cleared');
    }

    /**
     * Get performance statistics
     * @returns {Object} Performance statistics
     */
    getStats() {
        return {
            activeIntervals: this.activeIntervals.size,
            activeTimeouts: this.activeTimeouts.size,
            lastUpdateTimes: Object.fromEntries(this.lastUpdateTimes),
            minUpdateIntervals: Object.fromEntries(this.minUpdateIntervals)
        };
    }

    /**
     * Optimize Firebase queries by implementing intelligent caching
     * @param {Object} database - Firebase database reference
     * @param {string} collection - Collection name
     * @param {Object} query - Query parameters
     * @param {number} cacheTime - Cache time in milliseconds (default: 5 minutes)
     * @returns {Promise} Query result
     */
    async optimizedFirebaseQuery(database, collection, query, cacheTime = 300000) {
        const cacheKey = `${collection}_${JSON.stringify(query)}`;
        const cached = this.getCachedData(cacheKey);
        
        if (cached && (Date.now() - cached.timestamp) < cacheTime) {
            console.log(`📦 Using cached data for ${collection}`);
            return cached.data;
        }
        
        try {
            const result = await database.collection(collection).get();
            const data = result.docs.map(doc => ({ id: doc.id, ...doc.data() }));
            
            this.setCachedData(cacheKey, data);
            console.log(`🔄 Fresh data fetched for ${collection}`);
            return data;
        } catch (error) {
            console.error(`❌ Error fetching ${collection}:`, error);
            // Return cached data if available, even if expired
            if (cached) {
                console.log(`📦 Using expired cached data for ${collection}`);
                return cached.data;
            }
            throw error;
        }
    }

    /**
     * Get cached data
     * @param {string} key - Cache key
     * @returns {Object|null} Cached data or null
     */
    getCachedData(key) {
        try {
            const cached = localStorage.getItem(`opt_cache_${key}`);
            return cached ? JSON.parse(cached) : null;
        } catch (error) {
            console.error('Error reading cache:', error);
            return null;
        }
    }

    /**
     * Set cached data
     * @param {string} key - Cache key
     * @param {Object} data - Data to cache
     */
    setCachedData(key, data) {
        try {
            const cacheData = {
                data: data,
                timestamp: Date.now()
            };
            localStorage.setItem(`opt_cache_${key}`, JSON.stringify(cacheData));
        } catch (error) {
            console.error('Error setting cache:', error);
        }
    }

    /**
     * Clear all cached data
     */
    clearCache() {
        try {
            const keys = Object.keys(localStorage);
            keys.forEach(key => {
                if (key.startsWith('opt_cache_')) {
                    localStorage.removeItem(key);
                }
            });
            console.log('🗑️ Cache cleared');
        } catch (error) {
            console.error('Error clearing cache:', error);
        }
    }
}

// Create global instance
window.optimizationHelper = new OptimizationHelper();

// Auto-cleanup on page unload
window.addEventListener('beforeunload', () => {
    if (window.optimizationHelper) {
        window.optimizationHelper.clearAll();
    }
});

// Log optimization helper availability
console.log('🚀 Optimization Helper loaded. Use window.optimizationHelper for advanced optimizations.');
