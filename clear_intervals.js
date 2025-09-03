// Clear Intervals Script - Run this in browser console to clean up
// This helps clear any existing intervals that might be running

console.log('🧹 Clearing all intervals to prevent server overload...');

// Clear all intervals (this is a nuclear option but effective)
let highestIntervalId = window.setInterval(() => {}, 0);
for (let i = 0; i < highestIntervalId; i++) {
    window.clearInterval(i);
}

// Clear all timeouts
let highestTimeoutId = window.setTimeout(() => {}, 0);
for (let i = 0; i < highestTimeoutId; i++) {
    window.clearTimeout(i);
}

// Clear any global interval variables
if (typeof myInterval !== 'undefined') {
    clearInterval(myInterval);
    console.log('✅ Cleared myInterval');
}

if (typeof checkDataInterval !== 'undefined') {
    clearInterval(checkDataInterval);
    console.log('✅ Cleared checkDataInterval');
}

if (typeof monitoringInterval !== 'undefined') {
    clearInterval(monitoringInterval);
    console.log('✅ Cleared monitoringInterval');
}

console.log('🎉 All intervals cleared! Your server should now be much happier.');
console.log('💡 Refresh the page to use the new efficient update system.');
