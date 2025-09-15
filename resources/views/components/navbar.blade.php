<!-- Navbar -->
<header class="fixed z-40 w-full text-sm bg-gradient-to-b from-purple-100 to-white"
        x-data="{ mobileMenu: false, cartOpen: false, locationSet: false, currentLocation: 'Select Location' }"
        x-init="
            // Check if location is set from cookies
            function getCookie(name) {
                const value = `; ${document.cookie}`;
                const parts = value.split(`; ${name}=`);
                if (parts.length === 2) return parts.pop().split(';').shift();
                return null;
            }
            
            function updateLocationDisplay() {
                const addressLat = getCookie('address_lat');
                const addressLng = getCookie('address_lng');
                const userZoneId = getCookie('user_zone_id');
                const userAddress = getCookie('user_address');
                
                console.log('Navbar - Location check:', {
                    addressLat, addressLng, userZoneId, userAddress,
                    allCookies: document.cookie
                });
                
                if (addressLat && addressLng && userZoneId && 
                    addressLat !== 'null' && addressLng !== 'null' && userZoneId !== 'null') {
                    locationSet = true;
                    // Show actual address if available, otherwise show coordinates
                    if (userAddress && userAddress !== 'undefined' && userAddress !== 'null' && userAddress !== '') {
                        currentLocation = userAddress;
                    } else {
                        currentLocation = `${parseFloat(addressLat).toFixed(4)}, ${parseFloat(addressLng).toFixed(4)}`;
                    }
                } else {
                    locationSet = false;
                    currentLocation = 'Select Location';
                }
            }
            
            // Initial check
            updateLocationDisplay();
            
            // Update location display periodically
            setInterval(updateLocationDisplay, 2000);
            
            // Function to get current location
            window.getCurrentLocation = function() {
                if (navigator.geolocation) {
                    console.log('Requesting current location...');
                    
                    navigator.geolocation.getCurrentPosition(
                        async function(position) {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;
                            
                            console.log('Location obtained:', lat, lng);
                            
                            // Set cookies
                            setCookie('address_lat', lat, 365);
                            setCookie('address_lng', lng, 365);
                            
                            // Get address from coordinates
                            try {
                                const response = await fetch(`https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${lat}&longitude=${lng}&localityLanguage=en`);
                                const data = await response.json();
                                
                                if (data && data.localityInfo && data.localityInfo.administrative) {
                                    const address = data.localityInfo.administrative
                                        .filter(admin => admin.order <= 3)
                                        .map(admin => admin.name)
                                        .join(', ');
                                    
                                    if (address) {
                                        setCookie('user_address', address, 365);
                                        setCookie('user_zone_id', '1', 365); // Default zone
                                        console.log('Address saved:', address);
                                        
                                        // Update display
                                        updateLocationDisplay();
                                        
                                        // Show success message
                                        if (typeof Swal !== 'undefined') {
                                            Swal.fire({
                                                title: 'Location Set!',
                                                text: `Your location is set to: ${address}`,
                                                icon: 'success',
                                                timer: 2000,
                                                showConfirmButton: false
                                            });
                                        }
                                    }
                                }
                            } catch (error) {
                                console.error('Error getting address:', error);
                                // Fallback address
                                const fallbackAddress = `Location: ${lat.toFixed(4)}, ${lng.toFixed(4)}`;
                                setCookie('user_address', fallbackAddress, 365);
                                setCookie('user_zone_id', '1', 365);
                                updateLocationDisplay();
                            }
                        },
                        function(error) {
                            console.error('Geolocation error:', error);
                            let errorMessage = 'Unable to get your location';
                            
                            switch(error.code) {
                                case error.PERMISSION_DENIED:
                                    errorMessage = 'Location access denied. Please allow location access.';
                                    break;
                                case error.POSITION_UNAVAILABLE:
                                    errorMessage = 'Location information unavailable.';
                                    break;
                                case error.TIMEOUT:
                                    errorMessage = 'Location request timed out.';
                                    break;
                            }
                            
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    title: 'Location Error',
                                    text: errorMessage,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            } else {
                                alert(errorMessage);
                            }
                        },
                        {
                            enableHighAccuracy: true,
                            timeout: 15000,
                            maximumAge: 300000
                        }
                    );
                } else {
                    alert('Geolocation is not supported by your browser.');
                }
            };
            
            function setCookie(name, value, days) {
                const expires = new Date();
                expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
                document.cookie = `${name}=${value};expires=${expires.toUTCString()};path=/`;
            }
        ">

    <div class="sm:w-[90%] mx-auto w-full px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">

            <!-- Left section -->
            <div class="flex items-center space-x-4">
                <a href="/mart" 
                   class="text-2xl font-extrabold text-purple-600"
                   x-bind:class="{ 'opacity-50 cursor-not-allowed': !locationSet }"
                   x-on:click="!locationSet ? ($event.preventDefault(), window.location.href = '{{ url('set-location') }}') : null"
                   x-bind:title="locationSet ? 'Go to Mart' : 'Please set your location first'">
                    Mart
                </a>

                <!-- Location -->
                <div class="hidden md:flex items-center space-x-1 font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-4 text-gray-600 font-semibold">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                    </svg>
                    
                    <!-- Show address when location is set -->
                    <div x-show="locationSet" class="flex items-center space-x-2">
                        <span class="font-semibold text-gray-600 max-w-48 truncate" 
                              x-text="currentLocation"
                              x-bind:title="currentLocation">
                        </span>
                        <button x-on:click="getCurrentLocation()"
                                class="text-green-600 hover:text-green-800 text-xs"
                                title="Refresh Location">
                            🔄
                        </button>
                    </div>
                    
                    <!-- Show location buttons when location is not set -->
                    <div x-show="!locationSet" class="flex items-center space-x-2">
                        <button x-on:click="getCurrentLocation()"
                                class="text-green-600 hover:text-green-800 text-sm font-medium">
                            📍 Get Location
                        </button>
                        <span class="text-gray-400">|</span>
                        <button x-on:click="window.location.href = '{{ url('set-location') }}'"
                                class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Set Location
                        </button>
                    </div>
                </div>
            </div>

            <!-- Search bar with animated rotating placeholder -->
            <div class="flex-1 mx-6 hidden md:block"
                 x-data="{
        items: [
          'milk','bread','cheese slices','butter','biscuits','apples','oranges',
          'sweets','beverages','chocolate','chips','cookies','candies',
          'grains','juices','juice mix','juice concentrates','rice',
          'pasta','sauces','seasonings','spices','syrups'
        ],
        index: 0,
        init() {
            setInterval(() => {
                this.index = (this.index + 1) % this.items.length;
            }, 5000); // Changed from 2.5s to 5s to reduce server load
        }
     }">

                <div class="relative">
                    <input type="text"
                           class="w-2/3 border border-gray-300 rounded-lg py-3 pl-12 pr-4 text-sm
                      focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500
                      transition-all duration-500"
                           placeholder="">

                    <!-- Animated suggestion overlay -->
                    <div class="absolute left-12 top-3 text-gray-400 text-sm pointer-events-none select-none">
                        <template x-for="(item, i) in items" :key="i">
                <span x-show="index === i"
                      x-transition:enter="transition ease-out duration-500"
                      x-transition:enter-start="opacity-0 translate-y-2"
                      x-transition:enter-end="opacity-100 translate-y-0"
                      x-transition:leave="transition ease-in duration-500 absolute"
                      x-transition:leave-start="opacity-100 translate-y-0"
                      x-transition:leave-end="opacity-0 -translate-y-2"
                      class="whitespace-nowrap">
                    Search for '<span x-text="item"></span>'
                </span>
                        </template>
                    </div>

                    <!-- Search Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-4 h-4 absolute left-4 top-3.5 text-gray-400"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 18.5a7.5 7.5 0 006.15-3.35z"/>
                    </svg>
                </div>
            </div>


            <!-- Right section -->
            <div class="hidden md:flex  items-center space-x-6 text-sm text-gray-600 font-semibold">
                <a href="{{ url('profile') }}" class="flex flex-col items-center space-y-1.5 text-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                         stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    </svg>

                    <span class="text-xs">Profile</span>
                </a>

                <!-- Cart Trigger -->
                <div x-data="{ cartCount: 1 }" class="relative">
                    <!-- Cart Button -->
                    <button @click="cartOpen = true" class="flex flex-col items-center text-gray-800 relative">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/>
                        </svg>
                        <span class="text-xs">Cart</span>
                    </button>

                    <!-- Notification Badge -->
                    <span x-show="cartCount > 0"
                          x-text="cartCount"
                          class="absolute -top-1 -right-1 bg-purple-600 text-white text-[10px] font-bold
                 w-5 h-5 flex items-center justify-center rounded-full shadow-lg">
    </span>
                </div>

            </div>

            <!-- Hamburger for mobile -->
            <div class="md:hidden">
                <button @click="mobileMenu = !mobileMenu" class="text-gray-800 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <x-mart.cart/>
</header>
