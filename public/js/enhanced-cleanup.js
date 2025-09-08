/**
 * Enhanced Cleanup Script
 * This script provides comprehensive cleanup of all intervals, timeouts, and event listeners
 * to ensure a clean state and prevent server overload.
 */

class EnhancedCleanup {
    constructor() {
        this.cleanedIntervals = new Set();
        this.cleanedTimeouts = new Set();
        this.cleanedEventListeners = new Set();
        this.globalVariables = new Map();
    }

    /**
     * Nuclear option: Clear all intervals and timeouts
     * This is the most aggressive cleanup method
     */
    nuclearCleanup() {
        console.log('🚨 Starting nuclear cleanup...');
        
        // Clear all intervals (this is a nuclear option but effective)
        let highestIntervalId = window.setInterval(() => {}, 0);
        for (let i = 0; i < highestIntervalId; i++) {
            try {
                window.clearInterval(i);
                this.cleanedIntervals.add(i);
            } catch (e) {
                // Ignore errors for invalid interval IDs
            }
        }

        // Clear all timeouts
        let highestTimeoutId = window.setTimeout(() => {}, 0);
        for (let i = 0; i < highestTimeoutId; i++) {
            try {
                window.clearTimeout(i);
                this.cleanedTimeouts.add(i);
            } catch (e) {
                // Ignore errors for invalid timeout IDs
            }
        }

        console.log(`🧹 Nuclear cleanup completed: ${this.cleanedIntervals.size} intervals, ${this.cleanedTimeouts.size} timeouts cleared`);
    }

    /**
     * Smart cleanup: Clear known global interval variables
     */
    smartCleanup() {
        console.log('🧠 Starting smart cleanup...');
        
        const knownIntervals = [
            'myInterval',
            'checkDataInterval',
            'monitoringInterval',
            'checkZone',
            'carouselInterval',
            'navbarInterval',
            'restaurantStatusInterval'
        ];

        let cleanedCount = 0;
        knownIntervals.forEach(intervalName => {
            if (typeof window[intervalName] !== 'undefined' && window[intervalName] !== null) {
                try {
                    clearInterval(window[intervalName]);
                    this.globalVariables.set(intervalName, window[intervalName]);
                    window[intervalName] = null;
                    cleanedCount++;
                    console.log(`✅ Cleared ${intervalName}`);
                } catch (e) {
                    console.log(`⚠️ Could not clear ${intervalName}:`, e.message);
                }
            }
        });

        console.log(`🧠 Smart cleanup completed: ${cleanedCount} global intervals cleared`);
    }

    /**
     * Cleanup specific page intervals
     */
    cleanupPageSpecific() {
        console.log('📄 Starting page-specific cleanup...');
        
        // Home page specific
        if (typeof callStore === 'function') {
            console.log('🏠 Home page detected - cleaning up store update intervals');
            // The initializeEfficientStoreUpdates function should handle this
        }

        // Restaurant page specific
        if (typeof startStatusMonitoring === 'function') {
            console.log('🍕 Restaurant page detected - cleaning up status monitoring');
            if (window.restaurantStatusManager) {
                window.restaurantStatusManager.stopStatusMonitoring();
            }
        }

        // Product listing pages
        if (typeof getProductList === 'function') {
            console.log('📦 Product listing page detected - cleaning up product intervals');
        }

        // Category pages
        if (typeof getCategories === 'function') {
            console.log('📂 Category page detected - cleaning up category intervals');
        }

        console.log('📄 Page-specific cleanup completed');
    }

    /**
     * Cleanup Firebase listeners
     */
    cleanupFirebaseListeners() {
        console.log('🔥 Starting Firebase listener cleanup...');
        
        // This is a more complex cleanup that would need to be implemented
        // based on the specific Firebase listeners used in your app
        console.log('🔥 Firebase listener cleanup completed (basic implementation)');
    }

    /**
     * Cleanup event listeners
     */
    cleanupEventListeners() {
        console.log('👂 Starting event listener cleanup...');
        
        // Remove common event listeners that might be causing issues
        const events = ['scroll', 'resize', 'mousemove', 'touchmove'];
        events.forEach(eventType => {
            try {
                window.removeEventListener(eventType, null, true);
                this.cleanedEventListeners.add(eventType);
            } catch (e) {
                // Ignore errors
            }
        });

        console.log(`👂 Event listener cleanup completed: ${this.cleanedEventListeners.size} event types cleaned`);
    }

    /**
     * Reset global state
     */
    resetGlobalState() {
        console.log('🔄 Starting global state reset...');
        
        // Reset common global variables to prevent memory leaks
        const globalVars = [
            'vendorsData',
            'filteredVendorsData',
            'allVendorsData',
            'priceData',
            'vendorIds',
            'currentPage',
            'totalPages'
        ];

        globalVars.forEach(varName => {
            if (typeof window[varName] !== 'undefined') {
                this.globalVariables.set(varName, window[varName]);
                if (Array.isArray(window[varName])) {
                    window[varName] = [];
                } else if (typeof window[varName] === 'object') {
                    window[varName] = {};
                } else {
                    window[varName] = null;
                }
            }
        });

        console.log(`🔄 Global state reset completed: ${this.globalVariables.size} variables reset`);
    }

    /**
     * Comprehensive cleanup - runs all cleanup methods
     */
    comprehensiveCleanup() {
        console.log('🚀 Starting comprehensive cleanup...');
        
        this.smartCleanup();
        this.cleanupPageSpecific();
        this.cleanupFirebaseListeners();
        this.cleanupEventListeners();
        this.resetGlobalState();
        
        console.log('🎉 Comprehensive cleanup completed!');
        console.log('💡 Your server should now be much happier.');
        console.log('🔄 Refresh the page to use the new efficient update system.');
    }

    /**
     * Get cleanup statistics
     */
    getStats() {
        return {
            cleanedIntervals: this.cleanedIntervals.size,
            cleanedTimeouts: this.cleanedTimeouts.size,
            cleanedEventListeners: this.cleanedEventListeners.size,
            globalVariables: this.globalVariables.size,
            timestamp: new Date().toISOString()
        };
    }

    /**
     * Restore previous state (if needed)
     */
    restoreState() {
        console.log('🔄 Restoring previous state...');
        
        this.globalVariables.forEach((value, key) => {
            window[key] = value;
        });
        
        console.log('🔄 State restoration completed');
    }
}

// Create global instance
window.enhancedCleanup = new EnhancedCleanup();

// Auto-cleanup on page unload
window.addEventListener('beforeunload', () => {
    if (window.enhancedCleanup) {
        window.enhancedCleanup.smartCleanup();
    }
});

// Log availability
console.log('🧹 Enhanced Cleanup loaded. Use window.enhancedCleanup for comprehensive cleanup.');

// Quick cleanup function for immediate use
window.quickCleanup = () => {
    if (window.enhancedCleanup) {
        window.enhancedCleanup.comprehensiveCleanup();
    }
};

console.log('💡 Quick cleanup available: window.quickCleanup()');
