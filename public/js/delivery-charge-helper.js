/**
 * Delivery Charge Helper for Frontend
 * Handles new delivery charge calculation and display logic
 */

class DeliveryChargeHelper {
    constructor() {
        this.settings = {
            base_delivery_charge: 23,
            item_total_threshold: 299,
            free_delivery_distance_km: 7,
            per_km_charge_above_free_distance: 8
        };
        this.isEnabled = true;
    }

    /**
     * Initialize delivery charge helper
     */
    async init() {
        try {
            const response = await fetch('/api/delivery-settings');
            const data = await response.json();
            if (data.success) {
                this.settings = { ...this.settings, ...data.data };
            }
        } catch (error) {
            console.warn('Could not fetch delivery settings, using defaults:', error);
        }
    }

    /**
     * Calculate delivery charge
     */
    calculateDeliveryCharge(itemTotal, distance) {
        if (!this.isEnabled) {
            return this.calculateLegacyDeliveryCharge(itemTotal, distance);
        }

        const {
            base_delivery_charge,
            item_total_threshold,
            free_delivery_distance_km,
            per_km_charge_above_free_distance
        } = this.settings;

        // Calculate original fee
        let originalFee;
        if (distance <= free_delivery_distance_km) {
            originalFee = base_delivery_charge;
        } else {
            const extraDistance = distance - free_delivery_distance_km;
            originalFee = base_delivery_charge + (extraDistance * per_km_charge_above_free_distance);
        }

        // Calculate actual fee based on business rules
        let actualFee;
        let isFreeDelivery = false;

        if (itemTotal < item_total_threshold) {
            actualFee = originalFee;
        } else {
            if (distance <= free_delivery_distance_km) {
                actualFee = 0;
                isFreeDelivery = true;
            } else {
                const extraDistance = distance - free_delivery_distance_km;
                actualFee = extraDistance * per_km_charge_above_free_distance;
            }
        }

        return {
            originalFee: originalFee,
            actualFee: actualFee,
            isFreeDelivery: isFreeDelivery,
            savings: originalFee - actualFee,
            showStrikethrough: originalFee > actualFee
        };
    }

    /**
     * Calculate legacy delivery charge (fallback)
     */
    calculateLegacyDeliveryCharge(itemTotal, distance) {
        // Your existing delivery charge logic here
        return {
            originalFee: 0,
            actualFee: 0,
            isFreeDelivery: false,
            savings: 0,
            showStrikethrough: false
        };
    }

    /**
     * Get delivery charge display HTML
     */
    getDeliveryChargeDisplayHTML(calculation) {
        if (calculation.isFreeDelivery) {
            return `
                <div class="text-right">
                    <div class="text-success font-weight-bold">Free Delivery</div>
                    <div class="text-danger" style="text-decoration: line-through;">₹${calculation.originalFee.toFixed(2)}</div>
                    <div class="text-muted">₹0.00</div>
                </div>
            `;
        } else if (calculation.showStrikethrough) {
            return `
                <div class="text-right">
                    <div class="text-success font-weight-bold">Free Delivery</div>
                    <div class="text-danger" style="text-decoration: line-through;">₹${calculation.originalFee.toFixed(2)}</div>
                    <div>₹${calculation.actualFee.toFixed(2)}</div>
                </div>
            `;
        } else {
            return `<span>₹${calculation.actualFee.toFixed(2)}</span>`;
        }
    }

    /**
     * Update delivery charge display in cart
     */
    updateCartDeliveryCharge(itemTotal, distance) {
        const calculation = this.calculateDeliveryCharge(itemTotal, distance);
        const displayHTML = this.getDeliveryChargeDisplayHTML(calculation);
        
        // Update the delivery fee display
        const deliveryFeeElement = document.querySelector('.delivery-fee-display');
        if (deliveryFeeElement) {
            deliveryFeeElement.innerHTML = displayHTML;
        }

        // Update hidden input values
        const deliveryChargeInput = document.querySelector('input[name="delivery_charge"]');
        if (deliveryChargeInput) {
            deliveryChargeInput.value = calculation.actualFee;
        }

        return calculation;
    }

    /**
     * Enable/disable new delivery system
     */
    setEnabled(enabled) {
        this.isEnabled = enabled;
    }

    /**
     * Update settings
     */
    updateSettings(newSettings) {
        this.settings = { ...this.settings, ...newSettings };
    }
}

// Global instance
window.deliveryChargeHelper = new DeliveryChargeHelper();

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.deliveryChargeHelper.init();
});

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = DeliveryChargeHelper;
}
