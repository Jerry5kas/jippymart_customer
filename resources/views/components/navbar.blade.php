<!-- Navbar -->
<header class="fixed z-50 w-full text-sm bg-gradient-to-b from-purple-100 to-white"
        x-data="{ mobileMenu: false, cartOpen: false }">

    <div class="sm:w-[90%] mx-auto w-full px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">

            <!-- Left section -->
            <div class="flex items-center space-x-4">
                <a href="/mart" class="text-2xl font-extrabold text-purple-600">Mart</a>

                <!-- Location -->
                <div class="relative hidden md:flex items-center font-medium bg-white border border-gray-200 rounded-lg px-3 py-2 shadow-sm w-full max-w-md">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="w-5 h-5 text-gray-600 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                        </svg>

                    <div id="location-container" class="flex items-center flex-1">
                        <input id="user_locationnew" type="text"
                               class="w-full px-2 py-1 border-0 focus:outline-none focus:ring-0 text-sm bg-transparent"/>
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
        searchQuery: '',
        suggestions: [],
        showSuggestions: false,
        selectedIndex: -1,
        debounceTimer: null,
        init() {
            setInterval(() => {
                this.index = (this.index + 1) % this.items.length;
            }, 5000);
        },
        async fetchSuggestions() {
            if (this.searchQuery.length < 2) {
                this.suggestions = [];
                this.showSuggestions = false;
                return;
            }

            try {
                const response = await fetch(`/mart/search-suggestions?q=${encodeURIComponent(this.searchQuery)}&type=suggestions`);
                const data = await response.json();
                this.suggestions = data;
                this.showSuggestions = data.length > 0;
                this.selectedIndex = -1;
            } catch (error) {
                console.error('Error fetching suggestions:', error);
                this.suggestions = [];
                this.showSuggestions = false;
            }
        },
        onInput() {
            clearTimeout(this.debounceTimer);
            this.debounceTimer = setTimeout(() => {
                this.fetchSuggestions();
            }, 300);
        },
        selectSuggestion(suggestion) {
            this.searchQuery = suggestion.text;
            this.showSuggestions = false;
            this.performSearch();
        },
        onKeydown(event) {
            if (!this.showSuggestions || this.suggestions.length === 0) return;

            switch(event.key) {
                case 'ArrowDown':
                    event.preventDefault();
                    this.selectedIndex = Math.min(this.selectedIndex + 1, this.suggestions.length - 1);
                    break;
                case 'ArrowUp':
                    event.preventDefault();
                    this.selectedIndex = Math.max(this.selectedIndex - 1, -1);
                    break;
                case 'Enter':
                    event.preventDefault();
                    if (this.selectedIndex >= 0) {
                        this.selectSuggestion(this.suggestions[this.selectedIndex]);
                    } else {
                        this.performSearch();
                    }
                    break;
                case 'Escape':
                    this.showSuggestions = false;
                    this.selectedIndex = -1;
                    break;
            }
        },
        performSearch() {
            if (this.searchQuery.trim() !== '') {
                this.showSuggestions = false;
                window.location.href = '/mart/search?q=' + encodeURIComponent(this.searchQuery.trim());
            }
        }
     }">

                <div class="relative">
                    <form @submit.prevent="performSearch()">
                    <input type="text"
                               x-model="searchQuery"
                               @input="onInput()"
                               @keydown="onKeydown($event)"
                               @focus="if(suggestions.length > 0) showSuggestions = true"
                               @blur="setTimeout(() => showSuggestions = false, 200)"
                           class="w-2/3 border border-gray-300 rounded-lg py-3 pl-12 pr-4 text-sm
                      focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500
                      transition-all duration-500"
                           placeholder="">

                    <!-- Animated suggestion overlay -->
                    <div class="absolute left-12 top-3 text-gray-400 text-sm pointer-events-none select-none">
                        <template x-for="(item, i) in items" :key="i">
                    <span x-show="index === i && searchQuery === ''"
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
                        <button type="submit" class="absolute left-4 top-3.5 text-gray-400 hover:text-purple-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg"
                                 class="w-4 h-4"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 18.5a7.5 7.5 0 006.15-3.35z"/>
                    </svg>
                        </button>
                    </form>

                    <!-- Search Suggestions Dropdown -->
                    <div x-show="showSuggestions && suggestions.length > 0"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute top-full left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-50 max-h-80 overflow-y-auto">

                        <template x-for="(suggestion, index) in suggestions" :key="index">
                            <div @click="selectSuggestion(suggestion)"
                                 @mouseenter="selectedIndex = index"
                                 class="px-4 py-3 cursor-pointer border-b border-gray-100 last:border-b-0 hover:bg-gray-50 transition-colors"
                                 :class="selectedIndex === index ? 'bg-purple-50' : ''">

                                <div class="flex items-center space-x-3">
                                    <!-- Icon based on type -->
                                    <div class="flex-shrink-0">
                                        <template x-if="suggestion.type === 'product'">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                        </template>
                                        <template x-if="suggestion.type === 'category'">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                            </svg>
                                        </template>
                                        <template x-if="suggestion.type === 'subcategory'">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                            </svg>
                                        </template>
                                    </div>

                                    <!-- Suggestion text -->
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate" x-text="suggestion.text"></p>
                                        <template x-if="suggestion.type === 'product' && suggestion.category">
                                            <p class="text-xs text-gray-500 truncate" x-text="suggestion.category"></p>
                                        </template>
                                        <template x-if="suggestion.type === 'subcategory' && suggestion.category">
                                            <p class="text-xs text-gray-500 truncate" x-text="suggestion.category + ' > ' + suggestion.subcategory"></p>
                                        </template>
                                    </div>

                                    <!-- Type badge -->
                                    <div class="flex-shrink-0">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium"
                                              :class="{
                                                  'bg-purple-100 text-purple-800': suggestion.type === 'product',
                                                  'bg-blue-100 text-blue-800': suggestion.type === 'category',
                                                  'bg-green-100 text-green-800': suggestion.type === 'subcategory'
                                              }"
                                              x-text="suggestion.type"></span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>


            <!-- Right section -->
            <div class="hidden md:flex items-center space-x-8 text-sm text-gray-600 font-semibold">
                <!-- Profile Section -->
                <div class="relative" x-data="{ open: false }">
                <!-- Trigger Button -->
                <button @click="open = !open" class="flex flex-col items-center space-y-1.5 text-gray-800 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                         stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    </svg>
                    <span class="text-xs">Profile</span>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="open" @click.away="open = false"
                     class="absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded-md shadow-lg z-50">
                    <a href="{{ url('profile') }}"
                       class="block px-4 py-2 text-gray-700 hover:bg-gray-100 text-sm">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 text-sm">
                            {{ trans('lang.logout') }}
                        </button>
                    </form>
                </div>
            </div>

                <!-- Cart Section -->
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

<script>
    // Location functionality for navbar
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize location functionality
        initializeLocationInput();
    });

    function initializeLocationInput() {
        const locationInput = document.getElementById('user_locationnew');

        if (!locationInput) return;

        // Initialize with existing location if available
        const addressName = getCookie('address_name');
        if (addressName && addressName.trim() !== '') {
            locationInput.value = addressName;
        }
    }

    // Cookie helper function
    function getCookie(name) {
        const value = "; " + document.cookie;
        const parts = value.split("; " + name + "=");
        if (parts.length === 2) return parts.pop().split(";").shift();
        return '';
    }

    // Set cookie helper function
    function setCookie(name, value, days) {
        const expires = new Date();
        expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
        document.cookie = name + '=' + value + ';expires=' + expires.toUTCString() + ';path=/';
    }

    // Location fetching function (simplified version)
    async function getCurrentLocation(type = '') {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;
                    fetchLocationFromCoordinates(latitude, longitude, type);
                },
                function(error) {
                    console.error('Error getting location:', error);
                    // Fallback to manual input
                    document.getElementById('user_locationnew').placeholder = 'Enter your location manually';
                }
            );
        } else {
            console.error('Geolocation is not supported by this browser.');
            document.getElementById('user_locationnew').placeholder = 'Enter your location manually';
        }
    }

    // Fetch location details from coordinates
    function fetchLocationFromCoordinates(lat, lon, type) {
        const lat1 = lat.toFixed(4);
        const lon1 = lon.toFixed(4);
        const url = 'https://nominatim.openstreetmap.org/reverse?lat=' + lat1 + '&lon=' + lon1 + '&format=json&addressdetails=1';

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data && data.address) {
                    const placeName = data.display_name;
                    const locationInput = document.getElementById('user_locationnew');
                    if (locationInput) {
                        locationInput.value = placeName;
                    }

                    // Set cookies for location data
                    setCookie('address_name', placeName, 365);
                    setCookie('address_lat', lat1, 365);
                    setCookie('address_lng', lon1, 365);

                    // Extract address components
                    const address = data.address;
                    const address_city = address.city || address.town || address.village || '';
                    const address_state = address.state || '';
                    const address_country = address.country || '';
                    const address_zip = address.postcode || '';
                    const address_name1 = address.road || '';
                    const address_name2 = address.neighbourhood || address.suburb || '';

                    // Set additional cookies
                    setCookie('address_name1', address_name1, 365);
                    setCookie('address_name2', address_name2, 365);
                    setCookie('address_zip', address_zip, 365);
                    setCookie('address_city', address_city, 365);
                    setCookie('address_state', address_state, 365);
                    setCookie('address_country', address_country, 365);

                    if (type === 'reload') {
                        window.location.reload(true);
                    }
                } else {
                    console.error("Location not found.");
                }
            })
            .catch(error => {
                console.error('Error fetching location:', error);
            });
    }
</script>
