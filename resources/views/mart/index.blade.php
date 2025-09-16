<x-layouts.app>
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Firebase for location detection -->
    <script src="{{ asset('js/geofirestore.js') }}"></script>
    <script src="https://cdn.firebase.com/libs/geofire/5.0.1/geofire.min.js"></script>
    
    <x-mart.top-cat-items :categories="$categories"/>
    {{--   --}}

    <x-mart.carousel :banners="$banners"/>
    <x-mart.banner-card :products="$spotlight" :header="'✨ In the Spotlight'"
                        :idea="'Handpicked essentials shining bright—discover the products everyone’s talking about.'"/>


    <!-- ✅ Our New Dynamic Section & Subcategories Block -->
    <div class="w-full bg-white py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="space-y-12">
                @foreach($sections as $sectionName => $subcategories)
                    <div class="bg-[#F9F9ED] rounded-2xl p-6 shadow-sm">
                        <div class="space-y-6">
                            <!-- Section Title -->
                            <h2 class="text-2xl font-semibold text-[#007F73] px-2">{{ $sectionName }}</h2>

                            <!-- Horizontal Scroll Container -->
                            <div class="flex gap-4 overflow-x-auto scrollbar-hide pb-2 px-2">
                                @foreach($subcategories as $subcategory)
                                    <div class="flex-shrink-0 w-32">
                                        <x-mart.category-card
                                            :title="$subcategory['title']"
                                            :image="$subcategory['photo']"/>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- ✅ End of New Section -->
    <x-mart.banner-card :products="$stealOfMomentProducts" :header="'⚡ Grab It Before It’s Gone'"
                        :idea="'Unbeatable deals for a limited time—get yours before the clock runs out.'"/>

    <div class="w-full max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <x-mart.small-cat-carousel :products="$featured"
                                           title="🔥 Trending Products - Stay ahead with what everyone's loving — shop the hottest picks of the season."/>
            </div>
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <x-mart.small-cat-carousel :products="$trendingProducts"
                                           title="🌟 Featured Products - Handpicked favorites just for you — premium quality, curated with extra care."/>
            </div>
        </div>
    </div>

    <!-- Section-based Items Display -->
    <div class="w-full bg-[#F9F9ED] py-12">
        <div class="max-w-7xl mx-auto">
            @if(isset($itemsBySection) && count($itemsBySection) > 0)
                <div class="space-y-16">
                    @foreach($itemsBySection as $sectionName => $items)
                        @if(count($items) > 0)
                            <div class="bg-white rounded-2xl shadow-sm p-6">
                                <x-mart.item-card
                                    :headings="$sectionName"
                                    :items="$items"
                                />
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <!-- Fallback: Show default item card if no section-based items -->
                <div class="space-y-16">
                    <div class="bg-white rounded-2xl shadow-sm p-6">
                        <x-mart.item-card headings="Get Your Home Needs"/>
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Cart Components -->
    <x-mart.cart />
    <x-mart.cart-popup />
</x-layouts.app>

<!-- Floating Button Styles -->
<style>
    .floating-btn {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background-color: #F38000;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        cursor: pointer;
        transition: transform 0.2s ease;
        z-index: 1000;
    }

    .floating-btn:hover {
        transform: scale(1.1);
    }

    .floating-btn svg {
        width: 28px;
        height: 28px;
        color: white;
    }

    /* scrollbar-hide */
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    .scrollbar-hide {
        -ms-overflow-style: none; /* IE/Edge */
        scrollbar-width: none; /* Firefox */
    }

    /* Improved responsive layout */
    @media (max-width: 640px) {
        .item-card-container {
            width: 160px;
            flex: 0 0 160px;
        }
    }

    /* Better card alignment */
    .item-card-wrapper {
        border: 1px solid #f3f4f6;
        transition: all 0.3s ease;
    }

    .item-card-wrapper:hover {
        border-color: #d1d5db;
        transform: translateY(-2px);
    }

</style>

<!-- Floating Button -->
<a href="{{ url('/') }}" class="floating-btn" title="Go to Home">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5
        0v4.5m11.356-1.993 1.263 12c.07.665-.45
        1.243-1.119 1.243H4.25a1.125 1.125 0
        0 1-1.12-1.243l1.264-12A1.125 1.125
        0 0 1 5.513 7.5h12.974c.576 0
        1.059.435 1.119 1.007ZM8.625 10.5a.375.375
        0 1 1-.75 0 .375.375 0 0 1
        .75 0Zm7.5 0a.375.375 0 1 1-.75
        0 .375.375 0 0 1 .75 0Z"/>
    </svg>
</a>

<!-- Location Logic for Mart Page -->
<script>
    // Initialize Firebase (same as home page)
    var firestore = firebase.firestore();
    var database = firestore; // Alias for compatibility
    var geoFirestore = new GeoFirestore(firestore);
    
    // Location variables (same as home page)
    var address_lat, address_lng, user_zone_id;

    // Function to get cookie value
    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
    }

    // Function to set cookie
    function setCookie(name, value, days) {
        const expires = new Date();
        expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
        document.cookie = `${name}=${value};expires=${expires.toUTCString()};path=/`;
    }

    // Function to get address from coordinates using reverse geocoding
    async function getAddressFromCoordinates(lat, lng) {
        try {
            // Use a simple reverse geocoding service
            const response = await fetch(`https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${lat}&longitude=${lng}&localityLanguage=en`);
            const data = await response.json();

            if (data && data.localityInfo && data.localityInfo.administrative) {
                const address = data.localityInfo.administrative
                    .filter(admin => admin.order <= 3) // Get city, state, country
                    .map(admin => admin.name)
                    .join(', ');

                if (address) {
                    setCookie('user_address', address, 365);
                    console.log("Address saved:", address);
                    return address;
                }
            }

            // Fallback: create a simple address from coordinates
            const fallbackAddress = `Location: ${lat.toFixed(4)}, ${lng.toFixed(4)}`;
            setCookie('user_address', fallbackAddress, 365);
            console.log("Fallback address saved:", fallbackAddress);
            return fallbackAddress;

        } catch (error) {
            console.error("Error getting address from coordinates:", error);
            // Fallback: create a simple address from coordinates
            const fallbackAddress = `Location: ${lat.toFixed(4)}, ${lng.toFixed(4)}`;
            setCookie('user_address', fallbackAddress, 365);
            return fallbackAddress;
        }
    }

    // Initialize location from cookies (same logic as home page)
    function initializeLocationFromCookies() {
        address_lat = getCookie('address_lat');
        address_lng = getCookie('address_lng');
        user_zone_id = getCookie('user_zone_id');
        const user_address = getCookie('user_address');

        console.log("Mart page - Location from cookies:", {
            address_lat: address_lat,
            address_lng: address_lng,
            user_zone_id: user_zone_id,
            user_address: user_address
        });

        // Check if basic location is set (lat/lng are most important)
        if (!address_lat || !address_lng) {
            console.log("Mart page - Basic location not set, redirecting to set-location page");
            redirectToSetLocation();
            return false;
        }

        // If zone ID is not set, try to get it from the location
        if (!user_zone_id) {
            console.log("Mart page - Zone ID not found in cookies, attempting to detect zone...");
            // We'll try to detect the zone, but don't block the page if it fails
            detectZoneFromLocation();
        }

        // Update navbar with current address
        updateNavbarWithAddress(user_address);

        return true;
    }

    // Function to detect zone from location (similar to home page logic)
    async function detectZoneFromLocation() {
        if (!address_lat || !address_lng) {
            console.log("Mart page - Cannot detect zone: no location data");
            return;
        }

        try {
            console.log("Mart page - Detecting zone for location:", address_lat, address_lng);
            
            // Use the same zone detection logic as home page
            var zone_list = [];
            var snapshots = await database.collection('zone').where("publish", "==", true).get();
            
            if (snapshots.docs.length > 0) {
                snapshots.docs.forEach((snapshot) => {
                    var zone_data = snapshot.data();
                    zone_data.id = snapshot.id;
                    zone_list.push(zone_data);
                });
            }

            if (zone_list.length > 0) {
                for (let i = 0; i < zone_list.length; i++) {
                    var zone = zone_list[i];
                    var vertices_x = [];
                    var vertices_y = [];

                    if (zone.area && zone.area.length > 0) {
                        for (let j = 0; j < zone.area.length; j++) {
                            var geopoint = zone.area[j];
                            vertices_x.push(geopoint.longitude);
                            vertices_y.push(geopoint.latitude);
                        }

                        var points_polygon = (vertices_x.length) - 1;
                        var isInZone = is_in_polygon(points_polygon, vertices_x, vertices_y, address_lng, address_lat);
                        
                        if (isInZone) {
                            user_zone_id = zone.id;
                            console.log("✅ Mart page - Zone detected:", user_zone_id, "-", zone.title || "No title");
                            
                            // Save zone ID to cookies
                            setCookie('user_zone_id', user_zone_id, 365);
                            console.log("Mart page - Zone ID saved to cookies:", user_zone_id);
                            
                            return;
                        }
                    }
                }
            }
            
            console.log("Mart page - No zone found for current location");
        } catch (error) {
            console.error("Mart page - Error detecting zone:", error);
        }
    }

    // Polygon point-in-polygon test function (same as home page)
    function is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y) {
        $i = $j = $c = $point = 0;
        for ($i = 0, $j = $points_polygon; $i < $points_polygon; $j = $i++) {
            $point = $i;
            if ($point == $points_polygon)
                $point = 0;
            if ((($vertices_y[$point] > $latitude_y != ($vertices_y[$j] > $latitude_y)) && ($longitude_x < ($vertices_x[
                    $j] - $vertices_x[$point]) * ($latitude_y - $vertices_y[$point]) / ($vertices_y[$j] -
                    $vertices_y[$point]) + $vertices_x[$point])))
                $c = !$c;
        }
        return $c;
    }

    // Function to redirect to set-location page
    function redirectToSetLocation() {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Location Required',
                text: 'Please set your location first to access the Mart section.',
                icon: 'warning',
                confirmButtonText: 'Set Location',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then(() => {
                window.location.href = '{{ url('set-location') }}';
            });
        } else {
            alert('Please set your location first to access the Mart section.');
            window.location.href = '{{ url('set-location') }}';
        }
    }

    // Initialize location check when page loads
    document.addEventListener('DOMContentLoaded', function () {
        console.log("Mart page - Initializing location check...");

        // Check location immediately
        if (!initializeLocationFromCookies()) {
            return; // Will redirect to set-location
        }

        // If location is set, continue with normal page functionality
        console.log("Mart page - Location verified, page ready");

        // You can add any mart-specific initialization here
        initializeMartFeatures();
    });

    // Function to initialize mart-specific features
    function initializeMartFeatures() {
        console.log("Mart page - Initializing mart features...");

        // Add any mart-specific functionality here
        // For example: cart initialization, product loading, etc.

        // Update navbar location status
        updateNavbarLocationStatus();
    }

    // Function to update navbar with address
    function updateNavbarWithAddress(address) {
        console.log("Mart page - Updating navbar with address:", address);
        
        // Update the navbar location display
        const locationDisplay = document.getElementById('location-display');
        const locationText = document.getElementById('current-location-text');
        
        if (locationDisplay && locationText) {
            if (address && address.trim() !== '') {
                // Truncate long addresses for better display
                const displayAddress = address.length > 30 ? address.substring(0, 30) + '...' : address;
                locationText.textContent = displayAddress;
                locationDisplay.style.display = 'flex';
                console.log("Mart page - Location displayed in navbar:", displayAddress);
            } else {
                locationDisplay.style.display = 'none';
                console.log("Mart page - No address to display in navbar");
            }
        } else {
            console.log("Mart page - Navbar location elements not found");
        }

        // Update mart link in navbar if it exists
        const martLink = document.querySelector('.navbar a[href*="mart"]');
        if (martLink) {
            martLink.style.opacity = '1';
            martLink.style.cursor = 'pointer';
            martLink.title = 'Go to Mart';
        }
    }

    // Function to update navbar location status
    function updateNavbarLocationStatus() {
        const user_address = getCookie('user_address');
        updateNavbarWithAddress(user_address);
    }

    // Function to handle location changes (if needed)
    function onLocationChanged(newLat, newLng, newZoneId) {
        address_lat = newLat;
        address_lng = newLng;
        user_zone_id = newZoneId;

        // Update cookies
        setCookie('address_lat', address_lat, 365);
        setCookie('address_lng', address_lng, 365);
        setCookie('user_zone_id', user_zone_id, 365);

        console.log("Mart page - Location updated:", {address_lat, address_lng, user_zone_id});

        // Refresh page or update content as needed
        // window.location.reload();
    }

    // Make location variables globally available for other scripts
    window.martLocation = {
        address_lat: address_lat,
        address_lng: address_lng,
        user_zone_id: user_zone_id,
        updateLocation: onLocationChanged
    };
</script>

<!-- Cart Popup -->
<x-mart.cart-popup />

