// Performance Testing Script for Customer API
// Run this in browser console to measure performance improvements

class PerformanceMonitor {
    constructor() {
        this.metrics = {
            pageLoad: 0,
            dataFetch: 0,
            imageLoad: 0,
            renderTime: 0,
            totalQueries: 0,
            imageCount: 0
        };
        this.startTime = performance.now();
    }

    // Start monitoring
    start() {
        console.log('🚀 Performance monitoring started...');
        this.startTime = performance.now();
        
        // Monitor Firebase queries
        this.monitorFirebaseQueries();
        
        // Monitor image loading
        this.monitorImageLoading();
        
        // Monitor render time
        this.monitorRenderTime();
    }

    // Monitor Firebase queries
    monitorFirebaseQueries() {
        const originalGet = firebase.firestore.CollectionReference.prototype.get;
        firebase.firestore.CollectionReference.prototype.get = function(...args) {
            PerformanceMonitor.instance.metrics.totalQueries++;
            console.log(`📊 Firebase query #${PerformanceMonitor.instance.metrics.totalQueries}: ${this.path}`);
            return originalGet.apply(this, args);
        };
    }

    // Monitor image loading
    monitorImageLoading() {
        const images = document.querySelectorAll('img');
        this.metrics.imageCount = images.length;
        
        let loadedImages = 0;
        images.forEach(img => {
            if (img.complete) {
                loadedImages++;
            } else {
                img.addEventListener('load', () => {
                    loadedImages++;
                    if (loadedImages === this.metrics.imageCount) {
                        this.metrics.imageLoad = performance.now() - this.startTime;
                        console.log(`🖼️ All images loaded in ${this.metrics.imageLoad.toFixed(2)}ms`);
                    }
                });
            }
        });
    }

    // Monitor render time
    monitorRenderTime() {
        const observer = new MutationObserver(() => {
            this.metrics.renderTime = performance.now() - this.startTime;
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }

    // Get performance report
    getReport() {
        const totalTime = performance.now() - this.startTime;
        
        console.log('📈 PERFORMANCE REPORT');
        console.log('====================');
        console.log(`⏱️  Total Load Time: ${totalTime.toFixed(2)}ms`);
        console.log(`📊 Firebase Queries: ${this.metrics.totalQueries}`);
        console.log(`🖼️  Images Loaded: ${this.metrics.imageCount}`);
        console.log(`🎨 Render Time: ${this.metrics.renderTime.toFixed(2)}ms`);
        console.log(`🖼️  Image Load Time: ${this.metrics.imageLoad.toFixed(2)}ms`);
        
        // Performance assessment
        if (totalTime < 3000) {
            console.log('✅ EXCELLENT: Page loads in under 3 seconds');
        } else if (totalTime < 5000) {
            console.log('🟡 GOOD: Page loads in under 5 seconds');
        } else if (totalTime < 10000) {
            console.log('🟠 FAIR: Page loads in under 10 seconds');
        } else {
            console.log('🔴 POOR: Page takes more than 10 seconds to load');
        }
        
        return this.metrics;
    }

    // Compare with baseline
    compareWithBaseline(baselineMetrics) {
        const currentMetrics = this.getReport();
        const totalTime = performance.now() - this.startTime;
        
        console.log('📊 COMPARISON WITH BASELINE');
        console.log('==========================');
        console.log(`⏱️  Baseline: ${baselineMetrics.totalTime}ms`);
        console.log(`⏱️  Current: ${totalTime.toFixed(2)}ms`);
        
        const improvement = ((baselineMetrics.totalTime - totalTime) / baselineMetrics.totalTime * 100);
        console.log(`📈 Improvement: ${improvement.toFixed(1)}%`);
        
        if (improvement > 0) {
            console.log('🎉 Performance improved!');
        } else {
            console.log('⚠️  Performance degraded');
        }
    }
}

// Initialize performance monitor
PerformanceMonitor.instance = new PerformanceMonitor();

// Auto-start monitoring when page loads
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        PerformanceMonitor.instance.start();
    });
} else {
    PerformanceMonitor.instance.start();
}

// Export for manual use
window.PerformanceMonitor = PerformanceMonitor;

// Usage examples:
// 
// 1. Start monitoring:
//    PerformanceMonitor.instance.start();
//
// 2. Get report:
//    PerformanceMonitor.instance.getReport();
//
// 3. Compare with baseline (12000ms = 12 seconds):
//    PerformanceMonitor.instance.compareWithBaseline({totalTime: 12000});
//
// 4. Monitor specific operations:
//    const start = performance.now();
//    // ... your code ...
//    const end = performance.now();
//    console.log(`Operation took ${end - start}ms`);

console.log('🔧 Performance Monitor loaded. Use PerformanceMonitor.instance.getReport() to see results.'); 