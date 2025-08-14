/**
 * Restaurant Status System - Failproof Implementation
 * 
 * This module provides a comprehensive system for determining restaurant open/close status
 * with strict failproof logic that ensures restaurants are only marked as "OPEN" when
 * both the manual toggle is enabled AND they are within working hours.
 */

class RestaurantStatusManager {
    constructor() {
        this.currentStatus = null;
        this.monitoringInterval = null;
        this.updateCallbacks = [];
    }

    /**
     * Main function to check if restaurant is open now
     * @param {Array} workingHours - Array of working hours objects
     * @param {boolean|null} isOpen - Manual toggle status
     * @returns {boolean} - True if restaurant is open, false otherwise
     */
    isRestaurantOpenNow(workingHours, isOpen = null) {
        const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        const now = new Date();
        const currentDay = days[now.getDay()];
        let hour = now.getHours();
        let minute = now.getMinutes();
        if (hour < 10) hour = '0' + hour;
        if (minute < 10) minute = '0' + minute;
        const currentTime = hour + ':' + minute;

        let withinWorkingHours = false;

        if (Array.isArray(workingHours)) {
            for (let i = 0; i < workingHours.length; i++) {
                if (workingHours[i]['day'] === currentDay) {
                    const slots = workingHours[i]['timeslot'] || [];
                    for (let j = 0; j < slots.length; j++) {
                        const from = slots[j]['from'];
                        const to = slots[j]['to'];
                        if (currentTime >= from && currentTime <= to) {
                            withinWorkingHours = true;
                            break;
                        }
                    }
                    if (withinWorkingHours) break;
                }
            }
        }

        // Failproof logic: Only return true if BOTH isOpen is true AND within working hours
        if (isOpen === true && withinWorkingHours) {
            return true;
        }
        return false;
    }

    /**
     * Get detailed restaurant status information
     * @param {Array} workingHours - Array of working hours objects
     * @param {boolean|null} isOpen - Manual toggle status
     * @returns {Object} - Detailed status object
     */
    getRestaurantStatus(workingHours, isOpen = null) {
        const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        const now = new Date();
        const currentDay = days[now.getDay()];
        let hour = now.getHours();
        let minute = now.getMinutes();
        if (hour < 10) hour = '0' + hour;
        if (minute < 10) minute = '0' + minute;
        const currentTime = hour + ':' + minute;

        let withinWorkingHours = false;
        let workingHoursInfo = null;

        if (Array.isArray(workingHours)) {
            for (let i = 0; i < workingHours.length; i++) {
                if (workingHours[i]['day'] === currentDay) {
                    const slots = workingHours[i]['timeslot'] || [];
                    workingHoursInfo = {
                        day: currentDay,
                        slots: slots,
                        currentTime: currentTime
                    };
                    
                    for (let j = 0; j < slots.length; j++) {
                        const from = slots[j]['from'];
                        const to = slots[j]['to'];
                        if (currentTime >= from && currentTime <= to) {
                            withinWorkingHours = true;
                            break;
                        }
                    }
                    if (withinWorkingHours) break;
                }
            }
        }

        const finalStatus = (isOpen === true && withinWorkingHours);
        
        return {
            isOpen: finalStatus,
            withinWorkingHours: withinWorkingHours,
            manualToggle: isOpen,
            workingHoursInfo: workingHoursInfo,
            reason: this.getStatusReason(isOpen, withinWorkingHours, finalStatus)
        };
    }

    /**
     * Get human-readable reason for restaurant status
     * @param {boolean|null} isOpen - Manual toggle status
     * @param {boolean} withinWorkingHours - Whether within working hours
     * @param {boolean} finalStatus - Final calculated status
     * @returns {string} - Human-readable reason
     */
    getStatusReason(isOpen, withinWorkingHours, finalStatus) {
        if (finalStatus) {
            return "Restaurant is open - manual toggle enabled and within working hours";
        }

        if (isOpen === false) {
            return "Restaurant is manually closed";
        }

        if (isOpen === true && !withinWorkingHours) {
            return "Restaurant is manually set to open but outside working hours";
        }

        if (isOpen === null && !withinWorkingHours) {
            return "Restaurant is outside working hours";
        }

        if (isOpen === null && withinWorkingHours) {
            return "Restaurant is within working hours but no manual toggle set";
        }

        return "Restaurant is closed";
    }

    /**
     * Update restaurant status UI elements
     * @param {Object} status - Status object from getRestaurantStatus
     */
    updateRestaurantStatusUI(status) {
        const statusElement = document.getElementById('vendor_shop_status');
        const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
        
        if (statusElement) {
            if (status.isOpen) {
                statusElement.innerHTML = '<span class="text-green-600 font-semibold">Open</span>';
                statusElement.className = 'text-green-600 font-semibold';
            } else {
                statusElement.innerHTML = '<span class="text-red-600 font-semibold">Closed</span>';
                statusElement.className = 'text-red-600 font-semibold';
            }
        }

        // Enable/disable add to cart buttons
        addToCartButtons.forEach(button => {
            if (status.isOpen) {
                button.disabled = false;
                button.classList.remove('opacity-50', 'cursor-not-allowed');
                button.classList.add('cursor-pointer');
            } else {
                button.disabled = true;
                button.classList.add('opacity-50', 'cursor-not-allowed');
                button.classList.remove('cursor-pointer');
            }
        });

        // Store current status
        this.currentStatus = status;

        // Trigger update callbacks
        this.updateCallbacks.forEach(callback => callback(status));
    }

    /**
     * Start monitoring restaurant status
     * @param {number} intervalMinutes - Check interval in minutes (default: 5)
     */
    startStatusMonitoring(intervalMinutes = 5) {
        if (this.monitoringInterval) {
            clearInterval(this.monitoringInterval);
        }

        this.monitoringInterval = setInterval(() => {
            this.checkAndUpdateStatus();
        }, intervalMinutes * 60 * 1000);
    }

    /**
     * Stop monitoring restaurant status
     */
    stopStatusMonitoring() {
        if (this.monitoringInterval) {
            clearInterval(this.monitoringInterval);
            this.monitoringInterval = null;
        }
    }

    /**
     * Check and update status (to be implemented based on data source)
     */
    checkAndUpdateStatus() {
        // This should be implemented based on how you fetch restaurant data
        // For now, it's a placeholder that can be overridden
        console.log('Status check triggered - implement based on your data source');
    }

    /**
     * Add callback for status updates
     * @param {Function} callback - Function to call when status updates
     */
    onStatusUpdate(callback) {
        this.updateCallbacks.push(callback);
    }

    /**
     * Remove callback for status updates
     * @param {Function} callback - Function to remove
     */
    removeStatusUpdateCallback(callback) {
        const index = this.updateCallbacks.indexOf(callback);
        if (index > -1) {
            this.updateCallbacks.splice(index, 1);
        }
    }

    /**
     * Get current status
     * @returns {Object|null} - Current status object
     */
    getCurrentStatus() {
        return this.currentStatus;
    }

    /**
     * Validate working hours format
     * @param {Array} workingHours - Working hours to validate
     * @returns {boolean} - True if valid, false otherwise
     */
    validateWorkingHours(workingHours) {
        if (!Array.isArray(workingHours)) {
            return false;
        }

        for (const day of workingHours) {
            if (!day.day || !Array.isArray(day.timeslot)) {
                return false;
            }

            for (const slot of day.timeslot) {
                if (!slot.from || !slot.to) {
                    return false;
                }

                // Validate time format (HH:MM)
                const timeRegex = /^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/;
                if (!timeRegex.test(slot.from) || !timeRegex.test(slot.to)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Format time for display
     * @param {string} time - Time in HH:MM format
     * @returns {string} - Formatted time (e.g., "2:30 PM")
     */
    formatTimeForDisplay(time) {
        const [hours, minutes] = time.split(':');
        const hour = parseInt(hours);
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const displayHour = hour % 12 || 12;
        return `${displayHour}:${minutes} ${ampm}`;
    }

    /**
     * Get next opening time
     * @param {Array} workingHours - Working hours array
     * @returns {string|null} - Next opening time or null if not found
     */
    getNextOpeningTime(workingHours) {
        const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        const now = new Date();
        const currentDay = days[now.getDay()];
        const currentTime = now.toTimeString().slice(0, 5);

        // Check today's remaining slots
        const todayHours = workingHours.find(d => d.day === currentDay);
        if (todayHours && todayHours.timeslot) {
            for (const slot of todayHours.timeslot) {
                if (slot.from > currentTime) {
                    return `Today at ${this.formatTimeForDisplay(slot.from)}`;
                }
            }
        }

        // Check next days
        const currentDayIndex = now.getDay();
        for (let i = 1; i <= 7; i++) {
            const nextDayIndex = (currentDayIndex + i) % 7;
            const nextDay = days[nextDayIndex];
            const nextDayHours = workingHours.find(d => d.day === nextDay);
            
            if (nextDayHours && nextDayHours.timeslot && nextDayHours.timeslot.length > 0) {
                const nextOpening = nextDayHours.timeslot[0].from;
                const nextDayName = nextDay;
                return `${nextDayName} at ${this.formatTimeForDisplay(nextOpening)}`;
            }
        }

        return null;
    }
}

// Create global instance
window.RestaurantStatusManager = RestaurantStatusManager;
window.restaurantStatusManager = new RestaurantStatusManager();

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = RestaurantStatusManager;
}

