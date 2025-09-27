/**
 * Shared Location Service for JippyMart
 * Manages location data across Restaurant and Mart sections
 */
class SharedLocationService {
    constructor() {
        this.locationData = null;
        this.listeners = [];
        this.init();
    }

    init() {
        // Load existing location from cookies
        this.loadLocationFromCookies();
        
        // Set up storage event listener for cross-tab communication
        window.addEventListener('storage', (e) => {
            if (e.key === 'jippymart_shared_location') {
                this.handleLocationUpdate(JSON.parse(e.newValue));
            }
        });
    }

    /**
     * Load location data from existing cookies
     */
    loadLocationFromCookies() {
        const addressName = this.getCookie('address_name');
        const userAddress = this.getCookie('user_address');
        const addressLat = this.getCookie('address_lat');
        const addressLng = this.getCookie('address_lng');
        const addressZip = this.getCookie('address_zip');
        const addressCity = this.getCookie('address_city');
        const addressState = this.getCookie('address_state');
        const addressCountry = this.getCookie('address_country');

        if (addressName || userAddress) {
            this.locationData = {
                address_name: addressName || userAddress,
                user_address: userAddress || addressName,
                address_lat: addressLat,
                address_lng: addressLng,
                address_zip: addressZip,
                address_city: addressCity,
                address_state: addressState,
                address_country: addressCountry,
                timestamp: Date.now()
            };
        }
    }

    /**
     * Get current location data
     */
    getLocation() {
        return this.locationData;
    }

    /**
     * Set location data and sync across sections
     */
    setLocation(locationData) {
        this.locationData = {
            ...locationData,
            timestamp: Date.now()
        };

        // Save to cookies (existing functionality)
        this.saveLocationToCookies(locationData);
        
        // Save to localStorage for cross-tab communication
        localStorage.setItem('jippymart_shared_location', JSON.stringify(this.locationData));
        
        // Notify listeners
        this.notifyListeners();
    }

    /**
     * Get current location using geolocation API
     */
    async getCurrentLocation() {
        return new Promise((resolve, reject) => {
            if (!navigator.geolocation) {
                reject(new Error('Geolocation is not supported by this browser.'));
                return;
            }

            navigator.geolocation.getCurrentPosition(
                async (position) => {
                    try {
                        const locationData = await this.reverseGeocode(
                            position.coords.latitude,
                            position.coords.longitude
                        );
                        this.setLocation(locationData);
                        resolve(locationData);
                    } catch (error) {
                        reject(error);
                    }
                },
                (error) => {
                    reject(new Error('Unable to get your current location. Please enter it manually.'));
                }
            );
        });
    }

    /**
     * Reverse geocode coordinates to address
     */
    async reverseGeocode(lat, lng) {
        const lat1 = lat.toFixed(4);
        const lng1 = lng.toFixed(4);
        const url = `https://nominatim.openstreetmap.org/reverse?lat=${lat1}&lon=${lng1}&format=json&addressdetails=1`;
        
        try {
            const response = await fetch(url);
            const data = await response.json();
            
            if (data && data.address) {
                const address = data.address;
                return {
                    address_name: data.display_name,
                    user_address: data.display_name,
                    address_lat: lat1,
                    address_lng: lng1,
                    address_zip: address.postcode || '',
                    address_city: address.city || address.town || address.village || '',
                    address_state: address.state || '',
                    address_country: address.country || '',
                    address_name1: address.road || '',
                    address_name2: address.neighbourhood || address.suburb || ''
                };
            } else {
                throw new Error('Unable to get address details');
            }
        } catch (error) {
            throw new Error('Unable to get address details');
        }
    }

    /**
     * Save location data to cookies (maintains existing functionality)
     */
    saveLocationToCookies(locationData) {
        const cookies = [
            { name: 'address_name', value: locationData.address_name || locationData.user_address },
            { name: 'user_address', value: locationData.user_address || locationData.address_name },
            { name: 'address_lat', value: locationData.address_lat },
            { name: 'address_lng', value: locationData.address_lng },
            { name: 'address_zip', value: locationData.address_zip },
            { name: 'address_city', value: locationData.address_city },
            { name: 'address_state', value: locationData.address_state },
            { name: 'address_country', value: locationData.address_country },
            { name: 'address_name1', value: locationData.address_name1 },
            { name: 'address_name2', value: locationData.address_name2 }
        ];

        cookies.forEach(cookie => {
            if (cookie.value) {
                this.setCookie(cookie.name, cookie.value, 365);
            }
        });
    }

    /**
     * Handle location updates from other tabs/sections
     */
    handleLocationUpdate(newLocationData) {
        if (newLocationData && newLocationData.timestamp > (this.locationData?.timestamp || 0)) {
            this.locationData = newLocationData;
            this.notifyListeners();
        }
    }

    /**
     * Add listener for location updates
     */
    addListener(callback) {
        this.listeners.push(callback);
    }

    /**
     * Remove listener
     */
    removeListener(callback) {
        this.listeners = this.listeners.filter(listener => listener !== callback);
    }

    /**
     * Notify all listeners of location updates
     */
    notifyListeners() {
        this.listeners.forEach(callback => {
            try {
                callback(this.locationData);
            } catch (error) {
                console.error('Error in location listener:', error);
            }
        });

        // Dispatch custom event for broader compatibility
        window.dispatchEvent(new CustomEvent('location-updated', {
            detail: this.locationData
        }));
    }

    /**
     * Utility function to get cookie value
     */
    getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
        return null;
    }

    /**
     * Utility function to set cookie
     */
    setCookie(name, value, days) {
        const expires = new Date();
        expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
        document.cookie = `${name}=${value};expires=${expires.toUTCString()};path=/`;
    }
}

// Make it globally available
window.SharedLocationService = SharedLocationService;
