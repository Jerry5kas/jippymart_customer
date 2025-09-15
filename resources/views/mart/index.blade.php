<x-layouts.app>
    <x-mart.top-cat-items :categories="$categories"/>
    <x-mart.carousel :banners="$banners"/>
    <x-mart.banner-card :products="$spotlight"/>
    <x-mart.categories />
    <x-mart.banner-card/>

    <!-- ✅ Our New Dynamic Section & Subcategories Block -->
    <div class="sm:w-[90%] w-full mx-auto space-y-8 py-8">
        @foreach($sections as $sectionName => $subcategories)
            <div>
                <h2 class="text-2xl font-semibold mb-4">{{ $sectionName }}</h2>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 lg:grid-cols-9 gap-4 justify-center">
                    @foreach($subcategories as $subcategory)
                        <x-mart.category-card
                            :title="$subcategory['title']"
                            :image="$subcategory['photo']" />
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
    <!-- ✅ End of New Section -->

    <x-mart.categories :categories="$categories"/>

    <div class="w-full sm:w-[90%] mx-auto flex md:flex-row flex-col gap-4">
        <x-mart.small-cat-carousel :products="$featured" title="Featured Products"/>
        <x-mart.small-cat-carousel :products="$trendingProducts" title="Trending Products" />
    </div>

    <div class="w-full sm:w-[90%] mx-auto flex md:flex-row flex-col gap-4">
        <x-mart.small-cat-carousel :products="$bestSellerProducts" title="Best Seller Products" />
        <x-mart.small-cat-carousel :products="$stealOfMomentProducts" title="Steal Of Products" />

    </div>

    <div class="w-full sm:w-[90%] mx-auto flex md:flex-row flex-col gap-4">
        <x-mart.small-cat-carousel :products="$newArrivalProducts" title="New Product" />
        <x-mart.small-cat-carousel :products="$seasonalProducts" title="Seasonal Products" />
   </div>

    <div class="pb-16 space-y-8">
        <x-mart.banner-card/>
        <x-mart.item-card headings="Get Your Home Needs"/>
        <x-mart.banner-card/>
        <x-mart.item-card headings="New in Store"/>
        <x-mart.item-card headings="New in Store"/>
        <x-mart.item-card headings="New in Store"/>
        <x-mart.item-card headings="New in Store"/>
        <x-mart.item-card headings="New in Store"/>
    </div>
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
      box-shadow: 0px 4px 8px rgba(0,0,0,0.2);
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
        0 .375.375 0 0 1 .75 0Z" />
    </svg>
</a>

<!-- Location Logic for Mart Page -->
<script>
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
        
        // Check if location is properly set
        if (!address_lat || !address_lng || !user_zone_id) {
            console.log("Mart page - Location not set, redirecting to set-location page");
            redirectToSetLocation();
            return false;
        }
        
        // Update navbar with current address
        updateNavbarWithAddress(user_address);
        
        return true;
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
    document.addEventListener('DOMContentLoaded', function() {
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
        // Update the navbar location display if it exists
        const locationSpan = document.querySelector('.navbar [x-text="currentLocation"]');
        if (locationSpan && address) {
            locationSpan.textContent = address;
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
        
        console.log("Mart page - Location updated:", { address_lat, address_lng, user_zone_id });
        
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

