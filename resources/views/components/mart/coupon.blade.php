<div x-data="martCouponComponent()" class="space-y-4">

    <!-- Coupon Trigger Card -->
    <div @click="openModal()"
         class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 flex items-center justify-between cursor-pointer hover:bg-yellow-100 transition">
        <div>
            <p class="font-medium text-gray-800">
                You have unlocked <span class="text-[#007F73]" x-text="coupons.length + ' new coupons'"></span>
            </p>
            <p class="text-xs text-gray-600">Explore Now</p>
        </div>
        <span class="text-gray-400 text-xl">›</span>
    </div>

    <!-- Modal -->
    <div
        x-show="isOpen"
        @keydown.escape.window="closeModal()"
        class="fixed inset-0 flex items-center justify-center z-50"
        x-cloak
    >
        <!-- Overlay -->
        <div
            class="fixed inset-0 bg-black bg-opacity-40"
            @click="closeModal()"
        ></div>

        <!-- Modal Box -->
        <div class="relative bg-white w-full max-w-md mx-4 rounded-2xl shadow-lg p-5 overflow-y-auto max-h-[90vh]">
            <!-- Header -->
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Apply Coupons</h2>
                <button @click="closeModal()" class="text-gray-500 hover:text-gray-700">&times;</button>
            </div>

            <!-- Input -->
            <div class="flex gap-2 mb-4">
                <input type="text" 
                       x-model="manualCouponCode"
                       placeholder="Enter Coupon Code"
                       class="flex-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-[#007F73] focus:outline-none">
                <button @click="applyManualCoupon()" 
                        :disabled="loading"
                        class="px-4 py-2 bg-[#007F73] text-white rounded-lg text-sm font-medium hover:bg-[#005f56] disabled:opacity-50">
                    <span x-show="!loading">APPLY</span>
                    <span x-show="loading">...</span>
                </button>
            </div>

            <!-- Message Display -->
            <div x-show="message" 
                 :class="messageType === 'success' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200'"
                 class="p-3 border rounded-lg mb-4 text-sm"
                 x-text="message">
            </div>

            <!-- Loading State -->
            <div x-show="loading" class="text-center py-4">
                <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-[#007F73]"></div>
                <p class="text-sm text-gray-600 mt-2">Loading coupons...</p>
            </div>

            <!-- Coupon List -->
            <div x-show="!loading" class="space-y-4">
                <template x-for="coupon in coupons" :key="coupon.id">
                    <div class="p-4 border rounded-lg flex flex-col gap-2">
                        <div class="flex justify-between items-center">
                            <span class="px-2 py-1 text-xs bg-[#E8F8DB] text-[#007F73] font-bold rounded" 
                                  x-text="coupon.code"></span>
                            <button @click="applyCoupon(coupon.code)" 
                                    :disabled="loading"
                                    class="text-[#007F73] font-semibold text-sm hover:underline disabled:opacity-50">
                                APPLY
                            </button>
                        </div>
                        <p class="font-semibold text-gray-800" x-text="getCouponTitle(coupon)"></p>
                        <p class="text-sm text-gray-600" x-text="getCouponDescription(coupon)"></p>
                        <div class="flex justify-between items-center text-xs text-gray-500">
                            <span x-text="'Min. order: ₹' + coupon.item_value"></span>
                            <span x-text="'Expires: ' + formatDate(coupon.expiresAt)"></span>
                        </div>
                    </div>
                </template>

                <!-- No Coupons Message -->
                <div x-show="coupons.length === 0 && !loading" class="text-center py-8">
                    <p class="text-gray-500">No coupons available at the moment</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function martCouponComponent() {
    return {
        isOpen: false,
        loading: false,
        coupons: [],
        manualCouponCode: '',
        message: '',
        messageType: 'success',
        appliedCoupon: null,

        init() {
            this.loadCoupons();
        },

        async loadCoupons() {
            this.loading = true;
            try {
                const response = await fetch('/api/mart/coupons');
                const data = await response.json();
                
                if (data.status) {
                    this.coupons = data.coupons || [];
                } else {
                    this.showMessage('Failed to load coupons', 'error');
                }
            } catch (error) {
                console.error('Error loading coupons:', error);
                this.showMessage('Failed to load coupons', 'error');
            } finally {
                this.loading = false;
            }
        },

        openModal() {
            this.isOpen = true;
            this.message = '';
            if (this.coupons.length === 0) {
                this.loadCoupons();
            }
        },

        closeModal() {
            this.isOpen = false;
            this.message = '';
            this.manualCouponCode = '';
        },

        async applyCoupon(couponCode) {
            this.loading = true;
            this.message = '';
            
            try {
                const cartTotal = this.getCartTotal();
                const response = await fetch('/api/mart/coupons/apply', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        coupon_code: couponCode,
                        cart_total: cartTotal
                    })
                });

                const data = await response.json();
                
                if (data.status) {
                    this.appliedCoupon = data.coupon;
                    this.showMessage(data.message, 'success');
                    this.updateCartWithCoupon(data.coupon);
                    setTimeout(() => this.closeModal(), 1500);
                } else {
                    this.showMessage(data.message, 'error');
                }
            } catch (error) {
                console.error('Error applying coupon:', error);
                this.showMessage('Failed to apply coupon', 'error');
            } finally {
                this.loading = false;
            }
        },

        async applyManualCoupon() {
            if (!this.manualCouponCode.trim()) {
                this.showMessage('Please enter a coupon code', 'error');
                return;
            }
            await this.applyCoupon(this.manualCouponCode.trim());
        },

        getCartTotal() {
            // Get cart total from Alpine store or calculate from cart items
            const cartStore = Alpine.store('cart');
            return cartStore ? cartStore.grandTotal : 0;
        },

        updateCartWithCoupon(coupon) {
            // Update the cart with applied coupon
            const cartStore = Alpine.store('cart');
            if (cartStore) {
                cartStore.appliedCoupon = coupon;
                cartStore.save();
            }
            
            // Trigger cart update event
            window.dispatchEvent(new CustomEvent('coupon-applied', { 
                detail: { coupon: coupon } 
            }));
        },

        getCouponTitle(coupon) {
            if (coupon.discountType === 'Percentage') {
                return `Get ${coupon.discount}% off on your order`;
            } else {
                return `Get flat ₹${coupon.discount} discount`;
            }
        },

        getCouponDescription(coupon) {
            return coupon.description || `Valid on orders above ₹${coupon.item_value}`;
        },

        formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-IN', { 
                day: '2-digit', 
                month: 'short', 
                year: 'numeric' 
            });
        },

        showMessage(text, type) {
            this.message = text;
            this.messageType = type;
            setTimeout(() => {
                this.message = '';
            }, 5000);
        }
    }
}
</script>
