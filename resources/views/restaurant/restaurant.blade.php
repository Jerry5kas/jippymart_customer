@include('layouts.app')
@include('layouts.header')

<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<!-- Main Content Container (do NOT wrap header) -->
<div class="max-w-4xl mx-auto bg-white text-gray-800 font-sans px-4 md:px-8 lg:px-16 py-6">
    <!-- Bread crumbs -->
    <nav class="flex items-center space-x-2 text-sm font-medium text-gray-500 mb-6 bg-gray-50 rounded-lg px-4 py-3 shadow-sm border border-gray-100" id="breadcrumb-nav" aria-label="Breadcrumb">
        <a href="/" class="hover:text-green-600 transition-colors flex items-center gap-1">
            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6"/></svg>
            Home
        </a>
        <span class="text-gray-400">/</span>
        <span id="zone-name" class="hover:text-green-600 transition-colors"></span>
        <span class="text-gray-400">/</span>
        <span class="text-black font-semibold" id="restaurant-title"></span>
    </nav>
    <!-- Content Container -->
    <div class="max-w-4xl mx-auto">
        <!-- Title -->
        <h1 class="text-xl sm:text-2xl font-semibold mb-4" id="restaurant-title-heading">Restaurant</h1>
        <!-- Info Card -->
        <div class="relative bg-white rounded-[2.5rem] shadow-xl border-0 mb-10 p-8 flex flex-col min-h-[180px] justify-center" style="box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.10);">
            <!-- Favorite Heart Button -->
            <div class="absolute top-6 right-8 z-10">
                <?php if (Auth::check()) : ?>
                <button id="restaurant-favorite-btn" class="restaurant-favorite-btn p-1.5 bg-white rounded-full shadow hover:shadow-lg transition-all duration-300 hover:scale-110 focus:outline-none">
                    <!-- Outlined Heart (not favorite) -->
                    <svg id="favorite-heart-outline" class="w-4 h-4 text-gray-400 transition-colors duration-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 21C12 21 4 13.5 4 8.5C4 5.42 6.42 3 9.5 3C11.24 3 12.91 3.81 14 5.08C15.09 3.81 16.76 3 18.5 3C21.58 3 24 5.42 24 8.5C24 13.5 16 21 16 21H12Z"/>
                    </svg>
                    <!-- Solid Heart (favorite) -->
                    <svg id="favorite-heart-solid" class="w-4 h-4 text-red-500 transition-colors duration-200 hidden" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M12 21C12 21 4 13.5 4 8.5C4 5.42 6.42 3 9.5 3C11.24 3 12.91 3.81 14 5.08C15.09 3.81 16.76 3 18.5 3C21.58 3 24 5.42 24 8.5C24 13.5 16 21 16 21H12Z"/>
                    </svg>
                </button>
                <?php else : ?>
                <button id="restaurant-favorite-login" class="restaurant-favorite-btn p-2 bg-white rounded-full shadow hover:shadow-lg transition-all duration-300 hover:scale-110 focus:outline-none">
                    <svg class="w-7 h-7 text-gray-400 transition-colors duration-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 21C12 21 4 13.5 4 8.5C4 5.42 6.42 3 9.5 3C11.24 3 12.91 3.81 14 5.08C15.09 3.81 16.76 3 18.5 3C21.58 3 24 5.42 24 8.5C24 13.5 16 21 16 21H12Z"/>
                    </svg>
                </button>
                <?php endif; ?>
            </div>
            <div class="flex flex-col gap-2">
                <div class="flex items-center gap-3 mb-1">
                    <span class="flex items-center gap-1 text-green-600 font-bold text-lg">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.388 2.46a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.388-2.46a1 1 0 00-1.175 0l-3.388 2.46c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.045 9.394c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.967z"/></svg>
                        <span id="restaurant-rating">--</span>
                    </span>
                    <span class="font-semibold text-lg text-gray-900" id="restaurant-ratings-count">(0 ratings)</span>
                    <span class="mx-2 text-gray-300 text-xl">•</span>
                    <span class="font-semibold text-lg text-gray-900" id="restaurant-price-for-two">₹-- for two</span>
                </div>
                <!-- Cuisines and Categories Row -->
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <span id="restaurant-cuisine" class="text-green-700 font-semibold"></span>
                    <span id="restaurant-cuisine-sep" class="text-gray-400"></span>
                    <span id="restaurant-category" class="text-orange-600 font-semibold"></span>
                </div>
                <div class="flex flex-col gap-2 mt-2">
                    <div class="flex items-center gap-2 text-gray-700 text-base">
                        <span class="inline-block w-3 h-3 bg-gray-300 rounded-full"></span>
                        <span class="font-bold">Outlet</span>
                        <span class="text-gray-400 ml-1" id="restaurant-location">-</span>
                    </div>
                    <div class="flex items-center gap-2 text-gray-700 text-base">
                        <span class="inline-block w-3 h-3 bg-gray-300 rounded-full"></span>
                        <span class="font-bold">20–25 mins</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Deals Section -->
        <div class="mb-10">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl sm:text-2xl font-semibold flex items-center gap-2">
                    <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3"/><circle cx="12" cy="12" r="10"/></svg>
                    <span>Deals for you</span>
                </h2>
                <span class="space-x-2 flex items-center">
                    <button @click="scrollLeft" class="w-9 h-9 flex items-center justify-center rounded-full bg-gray-200 hover:bg-gray-300 shadow transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg></button>
                    <button @click="scrollRight" class="w-9 h-9 flex items-center justify-center rounded-full bg-gray-200 hover:bg-gray-300 shadow transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg></button>
                </span>
            </div>
            <!-- Coupons -->
            <div x-ref="scrollDeals" class="flex overflow-x-auto space-x-6 pb-2 scroll-smooth" id="dynamic-coupons">
                <!-- Coupon cards will be injected here -->
            </div>
        </div>

        <!-- Menu Heading -->
        <div class="mb-8">
            <h2 class="text-center text-2xl sm:text-3xl font-semibold mb-6 flex items-center justify-center gap-3 tracking-wider text-gray-800">
                <span class="inline-block text-2xl">🍽️</span>
                <span>MENU</span>
                <span class="inline-block text-2xl">🍽️</span>
            </h2>

            <div class="flex item-center justify-between gap-x-3">
            
            <!-- Filters -->
            <div class="flex flex-wrap gap-4 items-center justify-start py-4 mb-2">
                <div class="inline-flex items-center gap-x-2">
                    <span class="text-xs text-gray-700 font-semibold">Veg</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="veg-toggle" class="sr-only peer">
                        <div class="w-12 h-7 bg-gray-200 rounded-full peer peer-checked:after:translate-x-5 after:absolute after:top-0.5 after:left-1 after:bg-green-500 after:border after:border-green-700 after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-green-100"></div>
                    </label>
                </div>
                <div class="inline-flex items-center gap-x-2">
                    <span class="text-xs text-gray-700 font-semibold">Non veg</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="nonveg-toggle" class="sr-only peer">
                        <div class="w-12 h-7 bg-gray-200 rounded-full peer peer-checked:after:translate-x-5 after:absolute after:top-0.5 after:left-1 after:bg-red-500 after:border after:border-red-700 after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-red-100"></div>
                    </label>
                </div>
                <button type="button" id="bestseller-btn" class="bg-white border border-green-500 rounded-full px-5 py-1 font-semibold shadow-sm text-sm text-green-700 hover:bg-green-50 transition-colors">
                    <svg class="w-4 h-4 inline-block mr-1 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 17.75l-6.172 3.245 1.179-6.873L2 9.755l6.908-1.004L12 2.5l3.092 6.251L22 9.755l-5.007 4.367 1.179 6.873z"/></svg>
                    Bestseller
                </button>
            </div>

            <!-- Search Bar -->
            <div class="w-1/2 flex items-center bg-white border border-gray-200 rounded-2xl shadow px-6 py-4 mb-6">
                <input type="text" id="menu-search" placeholder="Search for dishes" class="flex-grow bg-transparent focus:outline-none text-gray-700 font-medium text-lg placeholder:text-gray-400"/>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-gray-400 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M16.65 16.65A7.5 7.5 0 1116.65 2a7.5 7.5 0 010 14.65z"/>
                </svg>
            </div>

            </div>
        </div>
        <!-- Accordion Group -->
        <div class="space-y-4" id="dynamic-menu-accordion"></div>
    </div>
</div>

<div>
    <!-- View Cart Button -->
    <button id="view-cart-btn" class="fixed bottom-4 right-4 bg-gradient-to-r from-orange-500 to-red-600 text-white px-3 py-2 rounded-full shadow-xl z-50 font-extrabold text-lg flex items-center gap-2 hover:from-red-600 hover:to-orange-700 transition-all duration-200 border-4 border-white">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7A2 2 0 008.48 19h7.04a2 2 0 001.83-1.3L17 13M7 13V6h13"/></svg>
        View Cart
    </button>
    <!-- Cart Modal -->
    <div id="cart-modal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8 relative flex flex-col border border-gray-100">
            <button id="close-cart-modal" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 text-3xl font-bold rounded-full bg-white shadow-md w-10 h-10 flex items-center justify-center transition-all duration-200 focus:outline-none">
                &times;
            </button>
            <h2 class="text-2xl font-extrabold mb-6 text-gray-800 text-center tracking-wide">Cart Items</h2>
            <div id="cart-modal-content" class="flex-1 overflow-y-auto mb-4 divide-y divide-gray-100">
                <!-- Cart items will be injected here -->
            </div>
            <div id="cart-modal-totals" class="border-t pt-4 pb-2 mb-2 text-right font-semibold text-lg text-gray-700">
                <!-- Totals will be injected here -->
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <button id="clear-cart-btn" class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-5 py-2 rounded-full shadow font-bold text-base transition-all duration-200 border-2 border-white">Clear Cart</button>
                <a href="/checkout" id="modal-go-to-cart" class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-6 py-2 rounded-full shadow font-bold text-base transition-all duration-200 border-2 border-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    Go to Cart
                </a>
            </div>
        </div>
    </div>
</div>

<div></div>
<div class="vendor-page bg-white ecom-vendor-page category-listing-page" style="display: none;">
    <div class="offer-section py-3 resturant-banner">
        <div class="container position-relative">
            <div class="resturant-banner-inner">
                <div class="row">
                    <!-- <div class="col-md-12 resturant-banner-center" id="restaurant-pic"></div> -->
                    <!-- <div class="col-md-4 resturant-banner-right" id="restaurant-gallery" style="display: none;"></div> -->
                </div>
            </div>
            <!-- <div id="popup-gallary" style="display:none"></div> -->
        </div>
    </div>
    <div class="container">
        <div class="pb-3 rounded position-relative text-dark rest-basic-detail">
            <div class="d-flex align-items-start">
                <div class="text-dark">
                    <!-- <h2 class="font-weight-bold h6" id="vendor_title"></h2> -->
                    <div class="d-flex">
                        <!-- <p class="text-gray mb-1" id="vendor_address"><span class="fa fa-map-marker"></span></p> -->
                        <div class="rest-time">
                            <!-- <span class="text-dark-50 font-weight-bold m-0 pl-3 time"></span><span -->
                            <!-- class="text-dark m-0 font-weight-bold" id="vendor_open_time1"></span> -->
                        </div>
                    </div>
                    <div class="rating-wrap hidden align-items-center mt-2 " id="restaurant_ratings"></div>
                </div>
                <div class="feather_icon ml-auto">
                    <div class="row fu-review">
                        <!-- <?php if (Auth::check()) : ?>
                            <a href="javascript:void(0)"
                                class="text-decoration-none mx-1 p-2 rest-right-btn addToFavorite"><i
                                    class="font-weight-bold feather-heart"></i></a>
<?php else : ?>
                            <a href="javascript:void(0)" class="text-decoration-none mx-1 p-2 rest-right-btn loginAlert"><i
                                    class="font-weight-bold feather-heart"></i></a>
<?php endif; ?>
                            <a class="text-decoration-none mx-1 p-2 rest-right-btn restaurant_location_btn"
                                target="_blank"><i class="font-weight-bold feather-map-pin"></i></a>
                            <a href="{{ route('contact_us') }}" class="btn">{{ trans('lang.contact') }}</a> -->
                    </div>
                    <div class="row fu-time">
                        <!-- <a class="text-decoration-none mx-1 p-2 rest-right-btn" style="pointer-events: none">
                            <span class="text-dark-50 font-weight-bold m-0 pl-3 time ">{{ trans('lang.time') }} :
                            </span>
                            <span class="text-dark m-0 font-weight-bold" id="vendor_open_time"></span>
                        </a> -->
                    </div>
                    <div class="row fu-status">
                        <!-- <a class="text-decoration-none mx-1 p-2 rest-right-btn">
                            <span class="text-dark m-0 font-weight-bold" style="pointer-events: none"
                                id="vendor_shop_status"></span>
                        </a> -->
                    </div>
                    <div class="row fu-status">
                        <!-- <a class="text-decoration-none mx-1 p-2 rest-right-btn">
                            <span class="text-dark m-0 font-weight-bold" style="pointer-events: none"
                                id="vendor_shop_status"></span>
                        </a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container position-relative">
        <div class="foodies-detail-coupon">
            <!-- <div class="offers-coupons mb-4" id="offers_coupons"></div> -->
        </div>
        <div class="ecom-vendor-product-section hidden">
            <div class="row">
                <div class="col-md-3 restaurant-detail-left">
                    <div id="category-list"></div>
                </div>
                <div class="col-md-9 restaurant-detail-right">
                    <div id="product-list"></div>
                </div>
            </div>
        </div>
    </div>


</div>
<input type="hidden" name="restaurant_id" id="restaurant_id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>">
<input type="hidden" name="restaurant_name_url" id="restaurant_name_url" value="<?php echo isset($restaurantName) ? $restaurantName : ''; ?>">
<input type="hidden" name="zone_name_url" id="zone_name_url" value="<?php echo isset($zoneName) ? $zoneName : ''; ?>">
<input type="hidden" name="restaurant_name" id="restaurant_name" value="">
<input type="hidden" name="restaurant_location" id="restaurant_location" value="">
<input type="hidden" name="restaurant_latitude" id="restaurant_latitude" value="">
<input type="hidden" name="restaurant_longitude" id="restaurant_longitude" value="">
<input type="hidden" name="restaurant_image" id="restaurant_image" value="">
@include('layouts.footer')
@include('layouts.nav')
<!-- GeoFirestore -->
<script src="https://unpkg.com/geofirestore/dist/geofirestore.js"></script>
<script src="https://cdn.firebase.com/libs/geofire/5.0.1/geofire.min.js"></script>
<script type="text/javascript">
    var vendorId = "{{ $restaurantId }}";
    var restaurantSlug = "{{ $restaurantSlug }}";
    var zoneSlug = "{{ $zoneSlug }}";
    var takeaway = "<?php echo Session::get('takeawayOption'); ?>";
    var currentCurrency = '';
    var currencyAtRight = false;
    var decimal_degits = 0;
    var refCurrency = database.collection('currencies').where('isActive', '==', true);
    refCurrency.get().then(async function (snapshots) {
        var currencyData = snapshots.docs[0].data();
        currentCurrency = currencyData.symbol;
        currencyAtRight = currencyData.symbolAtRight;
        if (currencyData.decimal_degits) {
            decimal_degits = currencyData.decimal_degits;
        }
    });
    var placeholderImageRef = database.collection('settings').doc('placeHolderImage');
    var placeholderImageSrc = '';
    placeholderImageRef.get().then(async function (placeholderImageSnapshots) {
        var placeHolderImageData = placeholderImageSnapshots.data();
        placeholderImageSrc = placeHolderImageData.image;
    })
    var enableDinein = false;
    var refDineinForRestaurant = database.collection('settings').doc("DineinForRestaurant");
    refDineinForRestaurant.get().then(async function (snapshotsDineinForRestaurant) {
        var dineinForRestaurantData = snapshotsDineinForRestaurant.data();
        enableDinein = dineinForRestaurantData.isEnabledForCustomer;
    });
    var specialOfferVendor = [];
    let specialOfferForHour = [];
    var enableSpecialOffer = false;
    var specialOfferRef = database.collection('settings').doc('specialDiscountOffer');
    specialOfferRef.get().then(async function (snapShots) {
        var specialOfferData = snapShots.data();
        if (specialOfferData.isEnable) {
            enableSpecialOffer = specialOfferData.isEnable;
        }
    });
    var catsRef = database.collection('vendor_categories').where("publish", "==", true);
    var vendorDetailsRef = database.collection('vendors').where('id', "==", vendorId);
    var vendorProductsRef = database.collection('vendor_products').where('vendorID', "==", vendorId).where("publish",
        "==", true).orderBy('createdAt', 'asc');
    var productLimit = 0;
    if (takeaway == 'false' || takeaway == false) {
        vendorProductsRef = vendorProductsRef.where('takeawayOption', '==', false);
    }

    var priceData = {};
    jQuery("#data-table_processing").show();
    let vegFilter = false;
    let nonVegFilter = false;
    let bestsellerFilter = false;
    let allCategories = [];
    let allProducts = [];

    async function resolveVendorIdFromName() {
        try {
            // First, get the zone ID from zone name
            const zoneRef = database.collection('zone').where('name', '==', zoneNameUrl);
            const zoneSnapshot = await zoneRef.get();

            if (!zoneSnapshot.empty) {
                const zoneData = zoneSnapshot.docs[0].data();
                const zoneId = zoneSnapshot.docs[0].id;

                // Then, get the vendor ID from restaurant name and zone ID
                const vendorRef = database.collection('vendors')
                    .where('title', '==', restaurantNameUrl)
                    .where('zoneId', '==', zoneId);
                const vendorSnapshot = await vendorRef.get();

                if (!vendorSnapshot.empty) {
                    const vendorData = vendorSnapshot.docs[0].data();
                    vendorId = vendorData.id;
                    console.log('Resolved vendor ID:', vendorId, 'for restaurant:', restaurantNameUrl, 'in zone:', zoneNameUrl);
                } else {
                    console.error('Vendor not found for:', restaurantNameUrl, 'in zone:', zoneNameUrl);
                }
            } else {
                console.error('Zone not found:', zoneNameUrl);
            }
        } catch (error) {
            console.error('Error resolving vendor ID:', error);
        }
    }

    // Helper function to generate SEO-friendly restaurant URL
    function generateRestaurantUrl(restaurantName, zoneName) {
        // Convert to URL-friendly format
        const cleanRestaurantName = restaurantName
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '') // Remove special characters
            .replace(/\s+/g, '-') // Replace spaces with hyphens
            .replace(/-+/g, '-') // Replace multiple hyphens with single
            .trim();

        const cleanZoneName = zoneName
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim();

        return `/restaurant/${cleanRestaurantName}/${cleanZoneName}`;
    }
    $(document).ready(async function () {
        // Show the vendor page content
        $('.vendor-page').show();

        // If we have restaurant name and zone name but no vendor ID, resolve the vendor ID
        if (!vendorId && restaurantNameUrl && zoneNameUrl) {
            await resolveVendorIdFromName();
        }

        var subscriptionModel = localStorage.getItem('subscriptionModel');

        if (subscriptionModel == true || subscriptionModel == "true") {

            await database.collection('vendors').doc(vendorId).get().then(async function (snapshots) {
                var vendorData = snapshots.data();
                if (vendorData && vendorData.hasOwnProperty('subscription_plan') && vendorData
                    .subscription_plan != null) {
                    if (vendorData.subscription_plan.itemLimit != "-1") {
                        vendorProductsRef = vendorProductsRef.limit(Number(vendorData
                            .subscription_plan.itemLimit));
                        productLimit = Number(vendorData.subscription_plan.itemLimit);
                    }

                }
            });
        }


        /* Add to favorite Code start*/
        priceData = await fetchVendorPriceData();
        var store_id = vendorId;
        if (user_uuid != undefined) {
            var user_id = user_uuid;
        } else {
            var user_id = '';
        }
        database.collection('favorite_restaurant').where('restaurant_id', '==', store_id).where('user_id',
            '==',
            user_id).get().then(async function (favoritevendorsnapshots) {
            if (favoritevendorsnapshots.docs.length > 0) {
                $('.addToFavorite').html(
                    '<i class="font-weight-bold fa fa-heart" style="color:red"></i>');
            } else {
                $('.addToFavorite').html('<i class="font-weight-bold feather-heart" ></i>');
            }
        });
        $('.loginAlert').on('click', function () {
            Swal.fire({
                text: "{{ trans('lang.login_to_favorite') }}",
                icon: "error"
            });
        });
        $('.addToFavorite').on('click', function () {
            var user_id = user_uuid;
            database.collection('favorite_restaurant').where('restaurant_id', '==', store_id).where(
                'user_id', '==', user_id).get().then(async function (favoritevendorsnapshots) {
                if (favoritevendorsnapshots.docs.length > 0) {
                    var id = favoritevendorsnapshots.docs[0].id;
                    database.collection('favorite_restaurant').doc(id).delete().then(
                        function () {
                            $('.addToFavorite').html(
                                '<i class="font-weight-bold feather-heart" ></i>'
                            );
                        });
                } else {
                    var id = "<?php echo uniqid(); ?>";
                    database.collection('favorite_restaurant').doc(id).set({
                        'restaurant_id': store_id,
                        'user_id': user_id
                    }).then(function (result) {
                        $('.addToFavorite').html(
                            '<i class="font-weight-bold fa fa-heart" style="color:red"></i>'
                        );
                    });
                }
            });
        });
        /* Add to favorite Code End*/
        getVendorDetails();
        getCategories();
        $(document).on("click", ".category-item", function () {
            if (!$(this).hasClass('active')) {
                $(this).addClass('active').siblings().removeClass('active');
                getProducts($(this).data('category-id'));
            }
        });
        getCouponDetails();

        // Filter event handlers
        $("#veg-toggle").on("change", function() {
            console.log("Veg toggle changed:", this.checked);
            if (this.checked) {
                vegFilter = true;
                nonVegFilter = false;
                $("#nonveg-toggle").prop("checked", false);
            } else {
                vegFilter = false;
            }
            console.log("Filters - Veg:", vegFilter, "NonVeg:", nonVegFilter, "Bestseller:", bestsellerFilter);
            renderMenuAccordionWithFilters();
        });

        $("#nonveg-toggle").on("change", function() {
            console.log("Non-veg toggle changed:", this.checked);
            if (this.checked) {
                nonVegFilter = true;
                vegFilter = false;
                $("#veg-toggle").prop("checked", false);
            } else {
                nonVegFilter = false;
            }
            console.log("Filters - Veg:", vegFilter, "NonVeg:", nonVegFilter, "Bestseller:", bestsellerFilter);
            renderMenuAccordionWithFilters();
        });

        $("#bestseller-btn").on("click", function() {
            console.log("Bestseller button clicked");
            bestsellerFilter = !bestsellerFilter;
            if (bestsellerFilter) {
                $(this).addClass("bg-green-600 text-white").removeClass("bg-white");
            } else {
                $(this).removeClass("bg-green-600 text-white").addClass("bg-white");
            }
            console.log("Filters - Veg:", vegFilter, "NonVeg:", nonVegFilter, "Bestseller:", bestsellerFilter);
            renderMenuAccordionWithFilters();
        });
    });

    async function getVendorDetails() {
        vendorDetailsRef.get().then(async function (vendorSnapshots) {
            if (!vendorSnapshots.empty) {
                var vendorDetails = vendorSnapshots.docs[0].data();
                $("#vendor_title").append(vendorDetails.title);
                $("#vendor_address").append(vendorDetails.location);

                // Set current restaurant for favorite functionality
                setCurrentRestaurant(vendorId, vendorDetails.title);
                $("#vendor_shop_status").html("{{ trans('lang.closed') }}");
                $("#vendor_shop_status").addClass('close');
                var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday',
                    'Saturday'
                ];
                var currentdate = new Date();
                var currentDay = days[currentdate.getDay()];
                var hour = currentdate.getHours();
                var minute = currentdate.getMinutes();
                if (hour < 10) {
                    hour = '0' + hour
                }
                if (minute < 10) {
                    minute = '0' + minute
                }
                var currentHours = hour + ':' + minute;
                if (vendorDetails.hasOwnProperty('workingHours')) {
                    for (i = 0; i < vendorDetails.workingHours.length; i++) {
                        var day = vendorDetails.workingHours[i]['day'];
                        if (vendorDetails.workingHours[i]['day'] == currentDay) {
                            if (vendorDetails.workingHours[i]['timeslot'].length != 0) {
                                for (j = 0; j < vendorDetails.workingHours[i]['timeslot'].length; j++) {
                                    var timeslot = vendorDetails.workingHours[i]['timeslot'][j];
                                    var TimeslotHourVar = {
                                        'from': timeslot[`from`],
                                        'to': timeslot[`to`],
                                        'closeingType': timeslot[`closeingType`]
                                    };
                                    var [h, m] = timeslot[`from`].split(":");
                                    var from = ((h % 12 ? h % 12 : 12) + ":" + m, h >= 12 ? 'PM' :
                                        'AM');
                                    var from_time = (h % 12 ? h % 12 : 12) + ":" + m;
                                    var [h2, m2] = timeslot[`to`].split(":");
                                    var to = ((h2 % 12 ? h2 % 12 : 12) + ":" + m2, h2 >= 12 ? 'PM' :
                                        'AM');
                                    var time = (h2 % 12 ? h2 % 12 : 12) + ":" + m2;
                                    $('#vendor_open_time').append(from_time + ' ' + from + ' - ' +
                                        time + ' ' + to +
                                        '<br/><span class="margine" style="margin-right: 65px;"></span>'
                                    );
                                    if (currentHours >= timeslot[`from`] && currentHours <= timeslot[
                                        `to`]) {
                                        $("#vendor_shop_status").html("{{ trans('lang.open') }}");
                                        $("#vendor_shop_status").removeClass('close');
                                        $("#vendor_shop_status").addClass('open');
                                    }
                                }
                            } else {
                                $('.time').html('');
                            }
                        }
                    }
                }
                if (vendorDetails.hasOwnProperty('isOpen') && vendorDetails.isOpen == true) {
                    vendorOpen = vendorDetails.isOpen;
                } else {
                }
                var newdeliveryCharge = [];
                try {
                    if (deliveryChargemain.vendor_can_modify) {
                        if (vendorDetails.deliveryCharge) {
                            if (vendorDetails.deliveryCharge.delivery_charges_per_km && vendorDetails
                                .deliveryCharge.minimum_delivery_charges && vendorDetails.deliveryCharge
                                .minimum_delivery_charges_within_km) {
                                deliveryChargemain = vendorDetails.deliveryCharge;
                            }
                        }
                    }
                } catch (error) {
                }
                if (vendorDetails.hasOwnProperty('specialDiscount')) {
                    specialOfferVendor = vendorDetails.specialDiscount;
                }
                var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday',
                    'Saturday'
                ];
                var currentdate = new Date();
                var currentDay = days[currentdate.getDay()];
                var currentTime = currentdate.getHours() + ":" + currentdate.getMinutes();
                if (enableSpecialOffer) {
                    if (specialOfferVendor.length != 0) {
                        for (i = 0; i < specialOfferVendor.length; i++) {
                            if (specialOfferVendor[i]['day'] == currentDay) {
                                if (specialOfferVendor[i]['timeslot'].length > 0) {
                                    for (j = 0; j < specialOfferVendor[i]['timeslot'].length; j++) {
                                        if (currentTime >= specialOfferVendor[i]['timeslot'][j][
                                            'from'
                                            ] && currentTime <= specialOfferVendor[i]['timeslot'][
                                            j
                                            ]['to']) {
                                            if (specialOfferVendor[i]['timeslot'][j]['discount_type'] ==
                                                'delivery') {
                                                specialOfferForHour = [];
                                                specialOfferForHour.push(specialOfferVendor[i][
                                                    'timeslot'
                                                    ][j]);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                setCookie('specialOfferForHourMain', JSON.stringify(specialOfferForHour), 365);
                $(".restaurant_location_btn").attr("href", "http://maps.google.com?q=" + vendorDetails
                    .latitude + "," + vendorDetails.longitude);
                if (vendorDetails.hasOwnProperty('photo') && vendorDetails.photo != '' && vendorDetails
                    .photo != null) {
                    photo = vendorDetails.photo;
                } else {
                    photo = placeholderImageSrc;
                }
                $("#restaurant-pic").html('<img onerror="this.onerror=null;this.src=\'' +
                    placeholderImageSrc + '\'" alt="#" class="restaurant-pic" src="' + photo + '">');
                if (vendorDetails.photos.length > 0) {
                    var gallery = '<div class="row">';
                    gallery += '<div class="col-md-6">';
                    gallery += '<div class="resturant-banner-right-block">';
                    if (vendorDetails.photos[0] != "" && vendorDetails.photos[0] != null) {
                        gallery += '<img onerror="this.onerror=null;this.src=\'' + placeholderImageSrc +
                            '\'" src="' + vendorDetails.photos[0] + '" class="banner-small-pic">';
                    } else {
                        gallery += '<img src="' + placeholderImageSrc + '" class="banner-small-pic">';
                    }
                    gallery += '</div>';
                    gallery += '<div class="resturant-banner-right-block">';
                    if (vendorDetails.photos[1] != "" && vendorDetails.photos[1] != null) {
                        gallery += '<img onerror="this.onerror=null;this.src=\'' + placeholderImageSrc +
                            '\'" src="' + vendorDetails.photos[1] + '" class="banner-small-pic">';
                    } else {
                        gallery += '<img src="' + placeholderImageSrc + '" class="banner-small-pic">';
                    }
                    gallery += '</div>';
                    gallery += '</div>';
                    gallery += '<div class="col-md-6">';
                    gallery += '<div class="resturant-banner-right-block view-all-blc">';
                    gallery += '<span class="see-gallary">{{ trans('lang.see_gallary') }}</span>';
                    if (vendorDetails.photos[2] != "" && vendorDetails.photos[2] != null) {
                        gallery += '<img onerror="this.onerror=null;this.src=\'' + placeholderImageSrc +
                            '\'" src="' + vendorDetails.photos[2] + '" class="banner-small-pic">';
                    } else {
                        gallery += '<img src="' + placeholderImageSrc + '" class="banner-small-pic">';
                    }
                    gallery += '</div>';
                    gallery += '</div>';
                    gallery += '</div>';
                    $("#restaurant-gallery").html(gallery);
                    var popup_gallery = '';
                    $.each(vendorDetails.photos, function (key, value) {
                        popup_gallery += '<a href="' + value +
                            '"><img onerror="this.onerror=null;this.src=\'' +
                            placeholderImageSrc + '\'" src="' + value + '"></a>';
                    });
                    $("#popup-gallary").html(popup_gallery);
                    $('#popup-gallary').slickLightbox();
                    $('.see-gallary').click(function () {
                        $('#popup-gallary a:first-child').click();
                    });
                } else {
                    var gallery = '<div class="row">';
                    gallery += '<div class="col-md-6">';
                    gallery += '<div class="resturant-banner-right-block">';
                    gallery += '<img src="' + placeholderImageSrc + '" class="banner-small-pic">';
                    gallery += '</div>';
                    gallery += '<div class="resturant-banner-right-block">';
                    gallery += '<img src="' + placeholderImageSrc + '" class="banner-small-pic">';
                    gallery += '</div>';
                    gallery += '</div>';
                    gallery += '<div class="col-md-6">';
                    gallery += '<div class="resturant-banner-right-block view-all-blc">';
                    gallery += '<img src="' + placeholderImageSrc + '" class="banner-small-pic">';
                    gallery += '</div>';
                    gallery += '</div>';
                    gallery += '</div>';
                    $("#restaurant-gallery").html(gallery);
                }
                if (vendorDetails.hasOwnProperty('reviewsCount') && vendorDetails.reviewsCount != '' &&
                    vendorDetails.reviewsCount != null) {
                    rating = Math.round(parseFloat(vendorDetails.reviewsSum) / parseInt(vendorDetails
                        .reviewsCount));
                    reviewsCount = vendorDetails.reviewsCount;
                } else {
                    reviewsCount = 0;
                    rating = 0;
                }
                var html_rating = '<ul class="rating" data-rating="' + rating + '">';
                html_rating = html_rating + '<li class="rating__item"></li>';
                html_rating = html_rating + '<li class="rating__item"></li>';
                html_rating = html_rating + '<li class="rating__item"></li>';
                html_rating = html_rating + '<li class="rating__item"></li>';
                html_rating = html_rating + '<li class="rating__item"></li>';
                html_rating = html_rating +
                    '</ul><p class="label-rating ml-2 small" id="vendor_reviews">(' + reviewsCount +
                    ' {{ trans('lang.review') }})</p>';
                $("#restaurant_ratings").html(html_rating);
                if ($("#restaurant_place").length) {
                    $("#vendor_name_place").html(vendorDetails.title);
                    if (vendorDetails.photo) {
                        $("#restaurant_image_place").show()
                    } else {
                        $("#restaurant_image_place").remove();
                    }
                    $("#restaurant_location_place").html('<i class="feather-map-pin"></i>' +
                        vendorDetails.location);
                    $("#restaurant_place").show();
                }
                // Fetch and set zone name using zoneId
                var zoneId = vendorDetails.zoneId;
                if (zoneId) {
                    database.collection('zone').doc(zoneId).get().then(function(zoneDoc) {
                        if (zoneDoc.exists) {
                            var zoneData = zoneDoc.data();
                            document.getElementById('zone-name').textContent = zoneData.name || 'Zone';

                            // Update URL to SEO-friendly format if not already
                            if (!restaurantNameUrl || !zoneNameUrl) {
                                const seoUrl = generateRestaurantUrl(vendorDetails.title, zoneData.name);
                                if (window.location.pathname !== seoUrl) {
                                    window.history.replaceState({}, '', seoUrl);
                                }
                            }
                        } else {
                            document.getElementById('zone-name').textContent = 'Zone';
                        }
                    }).catch(function(error) {
                        document.getElementById('zone-name').textContent = 'Zone';
                    });
                } else {
                    document.getElementById('zone-name').textContent = 'Zone';
                }
                document.getElementById('restaurant-title').textContent = vendorDetails.title || 'Restaurant';
                document.getElementById('restaurant-title-heading').textContent = vendorDetails.title || 'Restaurant';
                // Set restaurant rating in the info card
                var rating = 0;
                if (vendorDetails.hasOwnProperty('reviewsCount') && vendorDetails.reviewsCount && vendorDetails.hasOwnProperty('reviewsSum') && vendorDetails.reviewsSum) {
                    rating = (parseFloat(vendorDetails.reviewsSum) / parseInt(vendorDetails.reviewsCount));
                    rating = Math.round(rating * 10) / 10;
                }
                document.getElementById('restaurant-rating').textContent = rating ? rating : '--';
                // Set number of ratings dynamically
                var reviewsCount = 0;
                if (vendorDetails.hasOwnProperty('reviewsCount') && vendorDetails.reviewsCount) {
                    reviewsCount = vendorDetails.reviewsCount;
                }
                if (reviewsCount > 0) {
                    document.getElementById('restaurant-ratings-count').textContent = '(' + reviewsCount + ' ratings)';
                } else {
                    // If no ratings, show random rating and (New)
                    var randomRating = (Math.random() * (4.8 - 3.8) + 3.8).toFixed(1);
                    document.getElementById('restaurant-rating').textContent = randomRating;
                    document.getElementById('restaurant-ratings-count').textContent = '(New)';
                }
                // Set price for two dynamically between 380 and 600
                var priceForTwo = Math.floor(Math.random() * (600 - 380 + 1)) + 380;
                document.getElementById('restaurant-price-for-two').textContent = '₹' + priceForTwo + ' for two';
                // Set cuisine and category dynamically (array or string)
                var cuisine = '';
                var category = '';
                if (Array.isArray(vendorDetails.cuisineTitle)) {
                    cuisine = vendorDetails.cuisineTitle.join(', ');
                } else if (typeof vendorDetails.cuisineTitle === 'string') {
                    cuisine = vendorDetails.cuisineTitle;
                }
                if (Array.isArray(vendorDetails.categoryTitle)) {
                    category = vendorDetails.categoryTitle.slice(0, 6).join(', ');
                } else if (typeof vendorDetails.categoryTitle === 'string') {
                    category = vendorDetails.categoryTitle;
                }
                var cuisineCategoryText = '';
                if (cuisine && category) {
                    cuisineCategoryText = cuisine + ', ' + category;
                } else if (cuisine) {
                    cuisineCategoryText = cuisine;
                } else if (category) {
                    cuisineCategoryText = category;
                } else {
                    cuisineCategoryText = '--';
                }
                var cuisineCategoryElem = document.getElementById('restaurant-cuisine-category');
                console.log('Cuisine:', cuisine, 'Category:', category, 'Text:', cuisineCategoryText, 'Elem:', cuisineCategoryElem);
                if (cuisineCategoryElem) {
                    cuisineCategoryElem.textContent = cuisineCategoryText;
                }
                // Set cuisine and category with color for cuisine
                var cuisineElem = document.getElementById('restaurant-cuisine');
                var cuisineSepElem = document.getElementById('restaurant-cuisine-sep');
                var categoryElem = document.getElementById('restaurant-category');
                if (cuisineElem && cuisineSepElem && categoryElem) {
                    cuisineElem.textContent = cuisine;
                    if (cuisine && category) {
                        cuisineSepElem.textContent = ', ';
                    } else {
                        cuisineSepElem.textContent = '';
                    }
                    categoryElem.textContent = category;
                }
                var locationElem = document.getElementById('restaurant-location');
                if (locationElem) {
                    locationElem.textContent = vendorDetails.location || '-';
                }
                restaurantWorkingHours = vendorDetails.workingHours || [];
            }
        })
    }

    async function getCategories() {

        var vendorCategoryIds = [];
        await vendorProductsRef.get().then(async function (snapshots) {

            snapshots.docs.forEach((listval) => {
                var datas = listval.data();
                datas.id = listval.id;
                if (jQuery.inArray(datas.categoryID, vendorCategoryIds) == -1) {
                    vendorCategoryIds.push(datas.categoryID);
                }
            });
        });
        await catsRef.get().then(async function (snapshots) {
            if (snapshots != undefined) {
                var html = '';
                var alldata = [];
                snapshots.docs.forEach((listval) => {
                    var datas = listval.data();
                    for (var i = 0; i < vendorCategoryIds.length; i++) {
                        if (vendorCategoryIds[i] == datas.id) {
                            datas.id = listval.id;
                            alldata.push(datas);
                        }
                    }
                });
                html = html + '<div class="vandor-sidebar">';
                html = html + '<h3>{{ trans('lang.categories') }}</h3>';
                if (alldata.length > 0) {
                    html = html + '<ul class="vandorcat-list">';
                    alldata.forEach((listval) => {
                        var val = listval;
                        if (val.photo != "" && val.photo != null) {
                            photo = val.photo;
                        } else {
                            photo = placeholderImageSrc;
                        }
                        html = html + '<li class="category-item" data-category-id="' + val.id +
                            '">';
                        html = html +
                            '<a href="javascript:void(0)"><span><img onerror="this.onerror=null;this.src=\'' +
                            placeholderImageSrc + '\'" src="' + photo + '"></span>' + val
                                .title + '</a>';
                        html = html + '</li>';
                    });
                    html = html + '</ul>';
                } else {
                    html = html + '<p>{{ trans('lang.no_results') }}</p>';
                }
                if (html != '') {
                    var append_list = document.getElementById('category-list');
                    append_list.innerHTML = html;
                    var category_id = $('#category-list .category-item').first().addClass('active')
                        .data('category-id');
                    if (category_id) {
                        getProducts(category_id);
                    }
                }
            }
            jQuery("#data-table_processing").hide();
        });
    }

    async function getCouponDetails() {
        var date = new Date();
        var couponRef = database.collection('coupons')
            .where('isEnabled', '==', true)
            .where("isPublic", '==', true)
            .where("resturant_id", "in", [vendorId, "ALL"])
            .where('expiresAt', '>=', date);
        var couponHtml = '';
        let menuHtmlx = couponRef.get().then(async function (couponRefSnapshots) {
            if (couponRefSnapshots.docs.length > 0) {
                couponHtml +=
                    '<div class="coupon-code"><label>{{ trans('lang.available_coupon') }}</label><span></span></div>';
                couponHtml += '<div class="copupon-list">';
                couponHtml += '<ul>';
                couponRefSnapshots.docs.forEach((doc) => {
                    coupon = doc.data();
                    if (coupon.expiresAt) {
                        var date1 = coupon.expiresAt.toDate().toDateString();
                        var date = new Date(date1);
                        var dd = String(date.getDate()).padStart(2, '0');
                        var mm = String(date.getMonth() + 1).padStart(2, '0'); //January is 0!
                        var yyyy = date.getFullYear();
                        var expiresDate = yyyy + '-' + mm + '-' + dd;
                    }
                    if (coupon.discountType == 'Percentage') {
                        var discount = coupon.discount + '%'
                    } else {
                        coupon.discount = parseFloat(coupon.discount);
                        if (currencyAtRight) {
                            var discount = coupon.discount.toFixed(decimal_degits) + "" +
                                currentCurrency;
                        } else {
                            var discount = currentCurrency + "" + coupon.discount.toFixed(
                                decimal_degits);
                        }
                    }
                    couponHtml += '<li value="' + coupon.code + '"><span class="per-off">' +
                        discount + ' OFF </span><span>' + coupon.code + ' | Valid till ' +
                        expiresDate + '</span></li>';
                });
                couponHtml += '</ul></div>';
            }
            return couponHtml;
        })
        let menuHtml = await menuHtmlx.then(function (html) {
            if (html != undefined) {
                return html;
            }
        })
        $('#offers_coupons').html(menuHtml);

        // Render dynamic coupons in the deals section
        var dealsContainer = document.getElementById('dynamic-coupons');
        if (dealsContainer) {
            dealsContainer.innerHTML = '';
            couponRef.get().then(function(couponRefSnapshots) {
                couponRefSnapshots.docs.forEach(function(doc) {
                    var coupon = doc.data();
                    var discountText = '';
                    if (coupon.discountType === 'Percentage') {
                        discountText = coupon.discount + '% Off';
                    } else {
                        discountText = '₹' + parseFloat(coupon.discount).toFixed(2) + ' OFF';
                    }
                    var code = coupon.code || '';
                    var couponCard = `
                        <div class="flex-shrink-0 w-56 sm:w-64 bg-gray-50 border border-dashed rounded-xl p-3 flex items-center gap-3 shadow-sm hover:shadow hover:shadow-xl hover:bg-slate-100">
                            <div class="bg-orange-500  text-white text-lg font-bold px-2 py-2 rounded-full">%</div>
                            <div>
                                <p class="font-bold">${discountText}</p>
                                <p class="text-xs text-gray-500 mt-1">USE <span class="font-bold text-orange-600"> ${code} </span></p>
                            </div>
                        </div>
                    `;
                    dealsContainer.innerHTML += couponCard;
                });
            });
        }

        // Render menu accordion dynamically
        renderMenuAccordionWithFilters();
    }

    function renderStars(rating, uniqueId = '') {
        let stars = '';
        for (let i = 1; i <= 5; i++) {
            if (i <= Math.floor(rating)) {
                // Full star
                stars += `<svg class="w-4 h-4 text-yellow-500 fill-current inline" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.488 6.91l6.561-.955L10 0l2.951 5.955 6.561.955-4.756 4.635 1.122 6.545z"/></svg>`;
            } else if (i - rating <= 0.5 && i - rating > 0) {
                // Half star
                const gradId = `half-grad${uniqueId}${i}`;
                stars += `<svg class="w-4 h-4 text-yellow-500 fill-current inline" viewBox="0 0 20 20">
            <defs>
              <linearGradient id="${gradId}">
                <stop offset="50%" stop-color="#f59e42"/>
                <stop offset="50%" stop-color="#d1d5db"/>
              </linearGradient>
            </defs>
            <path fill="url(#${gradId})" d="M10 15l-5.878 3.09 1.122-6.545L.488 6.91l6.561-.955L10 0l2.951 5.955 6.561.955-4.756 4.635 1.122 6.545z"/>
          </svg>`;
            } else {
                // Empty star
                stars += `<svg class="w-4 h-4 text-gray-300 fill-current inline" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.488 6.91l6.561-.955L10 0l2.951 5.955 6.561.955-4.756 4.635 1.122 6.545z"/></svg>`;
            }
        }
        return stars;
    }

    function renderMenuAccordionWithFilters() {
        console.log("renderMenuAccordionWithFilters called with:", vegFilter, nonVegFilter, bestsellerFilter);
        renderMenuAccordion(vegFilter, nonVegFilter, bestsellerFilter);
    }

    async function renderMenuAccordion(veg = false, nonveg = false, bestseller = false) {
        // Fetch categories and products
        let categories = [];
        let products = [];
        let coupons = [];
        // Get categories
        await catsRef.get().then(function(snapshots) {
            snapshots.docs.forEach(function(doc) {
                let data = doc.data();
                data.id = doc.id;
                categories.push(data);
            });
        });
        // Get products
        await vendorProductsRef.get().then(function(snapshots) {
            console.log("Fetched", snapshots.docs.length, "products");
            snapshots.docs.forEach(function(doc) {
                let data = doc.data();
                data.id = doc.id;
                console.log("Product:", data.name, "NonVeg:", data.nonveg, "Rating:", data.reviewsSum, data.reviewsCount);
                products.push(data);
            });
        });
        // Get coupons
        await database.collection('coupons')
            .where('isEnabled', '==', true)
            .where('isPublic', '==', true)
            .where('resturant_id', 'in', [vendorId, 'ALL'])
            .get().then(function(snapshots) {
                snapshots.docs.forEach(function(doc) {
                    coupons.push(doc.data());
                });
            });

        // Filter products based on toggles
        console.log("Filtering products - Veg:", veg, "NonVeg:", nonveg, "Bestseller:", bestseller);
        console.log("Total products before filtering:", products.length);

        products = products.filter(function(product) {
            let shouldInclude = true;

            if (veg) {
                if (product.nonveg === true) {
                    shouldInclude = false;
                    console.log("Filtered out non-veg product:", product.name);
                }
            } else if (nonveg) {
                if (product.nonveg !== true) {
                    shouldInclude = false;
                    console.log("Filtered out veg product:", product.name);
                }
            }

            if (bestseller && shouldInclude) {
                let rating = 0;
                if (product.hasOwnProperty('reviewsCount') && product.reviewsCount && product.hasOwnProperty('reviewsSum') && product.reviewsSum) {
                    rating = (parseFloat(product.reviewsSum) / parseInt(product.reviewsCount));
                } else {
                    rating = (Math.random() * (5 - 4.3) + 4.3); // fallback for demo
                }
                if (rating <= 4.0) {
                    shouldInclude = false;
                    console.log("Filtered out low-rated product:", product.name, "Rating:", rating);
                }
            }

            return shouldInclude;
        });

        console.log("Total products after filtering:", products.length);

        // Group products by categoryID
        let productsByCategory = {};
        products.forEach(function(product) {
            if (!productsByCategory[product.categoryID]) {
                productsByCategory[product.categoryID] = [];
            }
            productsByCategory[product.categoryID].push(product);
        });

        // Render accordions
        let accordionHtml = '';
        categories.forEach(function(category) {
            let catProducts = productsByCategory[category.id] || [];
            if (catProducts.length === 0) return;
            accordionHtml += `
            <div x-data="{ open: true }" class="rounded-2xl bg-white shadow-lg overflow-hidden">
                <button @click="open = !open" class="w-full text-left px-4 py-4 flex justify-between items-center font-semibold text-lg sm:text-xl bg-gradient-to-r from-orange-50 to-white sticky top-0 z-10 transition-colors focus:outline-none">
                    <span class="flex items-center gap-2">
                        <svg :class="{ 'rotate-90': open }" class="w-5 h-5 text-green-500 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        <span>${category.title} <span class="ml-2 text-base font-medium text-gray-400">(${catProducts.length})</span></span>
                    </span>
                </button>
                <div x-show="open" x-collapse class="p-4 sm:p-6 text-sm text-gray-700 space-y-6 bg-white">
            `;
            catProducts.forEach(function(product) {
                // Coupon logic
                let couponText = '';
                let matchedCoupon = coupons.find(coupon => coupon.productIDs && Array.isArray(coupon.productIDs) && coupon.productIDs.includes(product.id));
                if (matchedCoupon) {
                    if (matchedCoupon.discountType === 'Percentage') {
                        couponText = `<span class='text-gray-600 text-sm'>${matchedCoupon.discount}% OFF USE <span class='font-bold text-orange-600'>${matchedCoupon.code}</span></span>`;
                    } else {
                        couponText = `<span class='text-gray-600 text-sm'>₹${parseFloat(matchedCoupon.discount).toFixed(2)} OFF USE <span class='font-bold text-orange-600'>${matchedCoupon.code}</span></span>`;
                    }
                }
                // Rating logic
                let rating = 0;
                let reviewsCount = 0;
                if (product.hasOwnProperty('reviewsCount') && product.reviewsCount && product.hasOwnProperty('reviewsSum') && product.reviewsSum) {
                    rating = (parseFloat(product.reviewsSum) / parseInt(product.reviewsCount));
                    rating = Math.round(rating * 10) / 10;
                    reviewsCount = product.reviewsCount;
                }
                if (!rating || !reviewsCount) {
                    rating = (Math.random() * (5 - 4.3) + 4.3).toFixed(1);
                    reviewsCount = 'New';
                }
                // Image logic
                let imgSrc = product.photo || '/img/default-product.png';
                // Description
                let desc = product.description || '';
                // Price
                let price = product.price ? `₹${parseFloat(product.price).toFixed(2)}` : '';
                accordionHtml += `
                    <div class="flex flex-col sm:flex-row bg-white rounded-2xl shadow-lg p-4 border border-gray-100 hover:shadow-2xl transition-shadow duration-300 group relative overflow-hidden">
                        <div class="flex-1 pr-0 sm:pr-6 mb-4 sm:mb-0 flex flex-col justify-between">
                            <div>
                                <h4 class="text-lg font-semibold mb-2 flex items-center gap-2">
                                    ${product.nonveg === true ?
                        '<span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-red-100"><svg class="w-3 h-3" viewBox="0 0 8 8" fill="#dc2626" xmlns="http://www.w3.org/2000/svg"><circle cx="4" cy="4" r="4"/></svg></span>' :
                        '<span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-green-100"><svg class="w-3 h-3" viewBox="0 0 8 8" fill="#16a34a" xmlns="http://www.w3.org/2000/svg"><circle cx="4" cy="4" r="4"/></svg></span>'
                    }
                                    <span>${product.name}</span>
                                </h4>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-xl font-bold text-gray-900">${price}</span>
                                    ${couponText ? `<span class="bg-orange-50 text-orange-600 text-xs font-semibold px-2 py-1 rounded-full ml-2">${couponText}</span>` : ''}
                                </div>
                                <p class="text-gray-600 text-sm mb-3 line-clamp-2">${desc}</p>
                            </div>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="flex items-center">${renderStars(rating, product.id)}</span>
                                <span class="ml-1 text-gray-800 text-sm font-semibold">${rating}</span>
                                <span class="ml-1 text-gray-500 text-xs">(${reviewsCount})</span>
                            </div>
                        </div>
                        <div class="flex flex-col items-center justify-between w-full sm:w-36">
                            <div class="relative w-full h-32 sm:h-28 rounded-xl overflow-hidden shadow-sm mb-3">
                                <img src="${imgSrc}" alt="${product.name}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                            ${isRestaurantOpenNow(restaurantWorkingHours) ? `
                            <button
                                class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white text-sm font-bold px-6 py-2 rounded-full shadow hover:from-green-600 hover:to-green-700 focus:outline focus:outline-2 focus:outline-orange-600 add-to-cart-btn transition-colors duration-200"
                                data-product-id="${product.id}"
                                data-vendor-id="${vendorId}"
                                data-category-id="${product.categoryID}"
                                data-product-name="${product.name}"
                                data-product-price="${product.price}"
                                data-product-photo="${imgSrc}"
                            >
                                <span class="inline-flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7A2 2 0 008.48 19h7.04a2 2 0 001.83-1.3L17 13M7 13V6h13"/></svg> ADD</span>
                            </button>
                            ` : `<span class="block w-full text-center text-xs text-gray-400 font-semibold py-2 bg-gray-100 rounded-full cursor-not-allowed">Closed</span>`}
                        </div>
                    </div>
                `;
            });
            accordionHtml += `</div></div>`;
        });
        document.getElementById('dynamic-menu-accordion').innerHTML = accordionHtml;
        allCategories = categories;
        allProducts = products;
    }

    async function getProducts(category_id) {
        jQuery("#data-table_processing").show();
        var product_list = document.getElementById('product-list');
        product_list.innerHTML = '';
        var html = '';
        vendorProductsRef.get().then(async function (snapshots) {

            html = buildProductsHTML(snapshots, category_id);
            if (html != '') {
                product_list.innerHTML = html;
                jQuery("#data-table_processing").hide();
            }
        });
    }

    function buildProductsHTML(snapshots, category_id) {
        var html = '';
        var alldata = [];
        snapshots.docs.forEach((listval) => {

            var datas = listval.data();
            if (datas.categoryID == category_id) {
                datas.id = listval.id;
                alldata.push(datas);
            }
        });
        var count = 0;
        var popularFoodCount = 0;
        html = html + '<div class="row">';
        alldata.forEach((listval) => {
            var val = listval;
            var vendor_id_single = val.id;
            var view_vendor_details = "{{ route('productDetail', ':id') }}";
            view_vendor_details = view_vendor_details.replace(':id', vendor_id_single);
            var rating = 0;
            var reviewsCount = 0;
            if (val.hasOwnProperty('reviewsSum') && val.reviewsSum != 0 && val.hasOwnProperty('reviewsCount') &&
                val.reviewsCount != 0 && val.reviewsCount != null) {
                rating = (val.reviewsSum / val.reviewsCount);
                rating = Math.round(rating * 10) / 10;
                reviewsCount = val.reviewsCount;
            }
            html = html +
                '<div class="col-md-4 product-list"><div class="list-card position-relative"><div class="list-card-image">';
            if (val.photo != "" && val.photo != null) {
                photo = val.photo;
            } else {
                photo = placeholderImageSrc;
            }
            status = '{{ trans('lang.veg') }}';
            statusclass = 'open';
            if (val.hasOwnProperty('nonveg')) {
                if (val.nonveg == true) {
                    status = '{{ trans('lang.non_veg') }}';
                    statusclass = 'closed';
                }
            }
            html = html + '<div class="member-plan position-absolute"><span class="badge badge-dark ' +
                statusclass + '">' + status + '</span></div><a href="' + view_vendor_details +
                '"><img onerror="this.onerror=null;this.src=\'' + placeholderImageSrc + '\'" alt="#" src="' +
                photo +
                '" class="img-fluid item-img w-100"></a></div><div class="py-2 position-relative"><div class="list-card-body position-relative"><h6 class="product-title mb-1"><a href="' +
                view_vendor_details + '" class="text-black">' + val.name + '</a></h6>';
            html = html + '<h6 class="mb-1 popular_food_category_ pro-cat" id="popular_food_category_' + val
                .categoryID + '_' + val.id + '" ></h6>';
            let final_price = priceData[val.id];

            if (val.disPrice && val.disPrice !== '0' && !val.item_attribute) {
                let or_price = getProductFormattedPrice(parseFloat(final_price.price));
                let dis_price = getProductFormattedPrice(parseFloat(final_price.dis_price));
                html = html + '<span class="pro-price">' + dis_price + '   ' + or_price + '   </span>';
            } else if (val.item_attribute && val.item_attribute.variants?.length > 0) {
                let variantPrices = val.item_attribute.variants.map(v => v.variant_price);
                let minPrice = Math.min(...variantPrices);
                let maxPrice = Math.max(...variantPrices);
                let or_price = minPrice !== maxPrice ?
                    getProductFormattedPrice(final_price.min) + ' - ' + getProductFormattedPrice(final_price.max) :
                    getProductFormattedPrice(final_price.max);
                html = html + '<span class="pro-price">' + or_price + '</span>'
            } else {
                let or_price = getProductFormattedPrice(final_price.price);
                html = html + '<span class="pro-price">' + or_price + '</span>'
            }
            html = html +
                '<div class="star position-relative mt-3"><span class="badge badge-success"><i class="feather-star"></i>' +
                rating + ' (' + reviewsCount + ')</span></div>';
            html = html + '</div>';
            html = html + '</div></div></div>';
        });
        html = html + '</div>';
        return html;
    }

    $('#menu-search').on('input', function() {
        const query = $(this).val().toLowerCase().trim();
        liveSearchMenu(query);
    });


    function liveSearchMenu(query) {
        // If no query, show all
        if (!query) {
            renderMenuAccordionWithFilters();
            return;
        }

        // Filter products: allow partial (substring) match for product name (case-insensitive, trimmed)
        const filteredProducts = allProducts.filter(product => {
            const name = (product.name || '').toLowerCase().trim();
            return name.includes(query);
        });

        // Group filtered products by category
        let productsByCategory = {};
        filteredProducts.forEach(function(product) {
            if (!productsByCategory[product.categoryID]) {
                productsByCategory[product.categoryID] = [];
            }
            productsByCategory[product.categoryID].push(product);
        });

        // Render accordions for filtered products
        let accordionHtml = '';
        allCategories.forEach(function(category) {
            let catProducts = productsByCategory[category.id] || [];
            if (catProducts.length === 0) return;
            accordionHtml += `
            <div x-data="{ open: true }">
                <button @click="open = !open" class="w-full text-left px-2 sm:px-4 py-3 flex justify-between items-center font-semibold border-b bg-gray-100">
                    <span class="text-base sm:text-lg font-bold">${category.title} (${catProducts.length})</span>
                    <svg :class="{ 'rotate-180': open }" class="w-5 h-5 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-collapse class="p-2 sm:p-4 text-sm text-gray-700 space-y-4">
            `;
            catProducts.forEach(function(product) {
                // Coupon logic
                let couponText = '';
                let matchedCoupon = [];
                let rating = 0;
                let reviewsCount = 0;
                if (product.hasOwnProperty('reviewsCount') && product.reviewsCount && product.hasOwnProperty('reviewsSum') && product.reviewsSum) {
                    rating = (parseFloat(product.reviewsSum) / parseInt(product.reviewsCount));
                    rating = Math.round(rating * 10) / 10;
                    reviewsCount = product.reviewsCount;
                }
                if (!rating || !reviewsCount) {
                    rating = (Math.random() * (5 - 4.3) + 4.3).toFixed(1);
                    reviewsCount = 'New';
                }
                let imgSrc = product.photo || '/img/default-product.png';
                let desc = product.description || '';
                let price = product.price ? `₹${parseFloat(product.price).toFixed(2)}` : '';
                accordionHtml += `
                    <div class="flex flex-col sm:flex-row bg-white rounded-2xl shadow-lg p-4 border border-gray-100 hover:shadow-2xl transition-shadow duration-300 group relative overflow-hidden">
                        <div class="flex-1 pr-0 sm:pr-6 mb-4 sm:mb-0 flex flex-col justify-between">
                            <div>
                                <h4 class="text-lg font-semibold mb-2 flex items-center gap-2">
                                    ${product.nonveg === true ?
                        '<span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-red-100"><svg class="w-3 h-3" viewBox="0 0 8 8" fill="#dc2626" xmlns="http://www.w3.org/2000/svg"><circle cx="4" cy="4" r="4"/></svg></span>' :
                        '<span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-green-100"><svg class="w-3 h-3" viewBox="0 0 8 8" fill="#16a34a" xmlns="http://www.w3.org/2000/svg"><circle cx="4" cy="4" r="4"/></svg></span>'
                    }
                                    <span>${product.name}</span>
                                </h4>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-xl font-bold text-gray-900">${price}</span>
                                    ${couponText ? `<span class="bg-orange-50 text-orange-600 text-xs font-semibold px-2 py-1 rounded-full ml-2">${couponText}</span>` : ''}
                                </div>
                                <p class="text-gray-600 text-sm mb-3 line-clamp-2">${desc}</p>
                            </div>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="flex items-center">${renderStars(rating, product.id)}</span>
                                <span class="ml-1 text-gray-800 text-sm font-semibold">${rating}</span>
                                <span class="ml-1 text-gray-500 text-xs">(${reviewsCount})</span>
                            </div>
                        </div>
                        <div class="flex flex-col items-center justify-between w-full sm:w-36">
                            <div class="relative w-full h-32 sm:h-28 rounded-xl overflow-hidden shadow-sm mb-3">
                                <img src="${imgSrc}" alt="${product.name}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                            ${isRestaurantOpenNow(restaurantWorkingHours) ? `
                            <button
                                class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white text-sm font-bold px-6 py-2 rounded-full shadow hover:from-green-600 hover:to-green-700 focus:outline focus:outline-2 focus:outline-orange-600 add-to-cart-btn transition-colors duration-200"
                                data-product-id="${product.id}"
                                data-vendor-id="${vendorId}"
                                data-category-id="${product.categoryID}"
                                data-product-name="${product.name}"
                                data-product-price="${product.price}"
                                data-product-photo="${imgSrc}"
                            >
                                <span class="inline-flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7A2 2 0 008.48 19h7.04a2 2 0 001.83-1.3L17 13M7 13V6h13"/></svg> ADD</span>
                            </button>
                            ` : `<span class="block w-full text-center text-xs text-gray-400 font-semibold py-2 bg-gray-100 rounded-full cursor-not-allowed">Closed</span>`}
                        </div>
                    </div>
                `;
            });
            accordionHtml += `</div></div>`;
        });
        document.getElementById('dynamic-menu-accordion').innerHTML = accordionHtml;
    }

    // Cart utility functions
    function getCart() {
        let cart = localStorage.getItem('cart');
        try {
            cart = JSON.parse(cart);
            if (!Array.isArray(cart)) return [];
            console.log('getCart:', cart);
            return cart;
        } catch {
            console.log('getCart: failed to parse, returning []');
            return [];
        }
    }
    function setCart(cart) {
        localStorage.setItem('cart', JSON.stringify(cart));
        console.log('setCart:', cart);
    }
    function addToCart(item) {
        let cart = getCart();

        // Check if cart already has items from a different restaurant
        if (cart.length > 0) {
            const existingRestaurantId = cart[0].vendor_id;
            if (existingRestaurantId !== item.vendor_id) {
                // Show restaurant restriction error
                Swal.fire({
                    title: 'Restaurant Restriction',
                    text: 'Your cart contains items from another restaurant. Please clear the cart to add items from a new restaurant.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Clear Cart & Continue',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Clear cart and add the new item
                        setCart([item]);
                        console.log('Cleared cart and added new item:', item);
                        alert('Cart cleared and item added!');
                    }
                });
                return; // Don't add the item
            }
        }

        // If we reach here, either cart is empty or same restaurant
        const idx = cart.findIndex(i => i.product_id === item.product_id);
        if (idx > -1) {
            cart[idx].quantity += 1;
        } else {
            cart.push(item);
        }
        setCart(cart);
        console.log('Added to cart:', item);
        alert('Added to cart!');
    }

    // Event delegation for add-to-cart buttons
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-to-cart-btn')) {
            if (!isRestaurantOpenNow(restaurantWorkingHours)) {
                Swal.fire({
                    title: 'Restaurant Closed',
                    text: 'You cannot add items to the cart because the restaurant is currently closed.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return;
            }
            const btn = e.target;
            const item = {
                product_id: btn.getAttribute('data-product-id'),
                vendor_id: btn.getAttribute('data-vendor-id'),
                category_id: btn.getAttribute('data-category-id'),
                name: btn.getAttribute('data-product-name'),
                price: btn.getAttribute('data-product-price'),
                photo: btn.getAttribute('data-product-photo'),
                quantity: 1
            };
            addToCart(item);
        }
    });

    // View Cart Modal Logic
    function renderCartModal() {
        const cart = getCart();
        const content = document.getElementById('cart-modal-content');
        const totals = document.getElementById('cart-modal-totals');
        if (cart.length === 0) {
            content.innerHTML = '<p>Your cart is empty.</p>';
            totals.innerHTML = '';
            return;
        }
        let html = '<ul class="divide-y divide-gray-200">';
        let totalQty = 0;
        let totalAmount = 0;
        cart.forEach((item, idx) => {
            const itemTotal = parseFloat(item.price) * item.quantity;
            totalQty += item.quantity;
            totalAmount += itemTotal;
            html += `<li class="py-2 flex items-center group">
                    <img src="${item.photo}" alt="${item.name}" class="w-12 h-12 object-cover rounded mr-3 border">
                    <div class="flex-1">
                        <div class="font-semibold">${item.name}</div>
                        <div class="text-sm text-gray-600 flex items-center gap-2 mt-1">
                            <button class="cart-qty-btn bg-gray-200 px-2 rounded text-lg font-bold" data-action="decrease" data-idx="${idx}">-</button>
                            <span class="mx-2">${item.quantity}</span>
                            <button class="cart-qty-btn bg-gray-200 px-2 rounded text-lg font-bold" data-action="increase" data-idx="${idx}">+</button>
                            <span class="ml-4">₹${(itemTotal).toFixed(2)}</span>
                        </div>
                    </div>
                    <button class="cart-remove-btn ml-4 text-gray-400 hover:text-red-600 text-xl" data-idx="${idx}" title="Remove">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </li>`;
        });
        html += '</ul>';
        content.innerHTML = html;
        totals.innerHTML = `<div class="font-semibold">Total Qty: <span id='cart-total-qty'>${totalQty}</span> &nbsp; | &nbsp; Total: <span id='cart-total-amount'>₹${totalAmount.toFixed(2)}</span></div>`;
        console.log('Cart modal rendered:', cart);
    }

    document.getElementById('view-cart-btn').addEventListener('click', function() {
        console.log('View Cart button clicked');
        renderCartModal();
        document.getElementById('cart-modal').classList.remove('hidden');
        console.log('Cart modal opened');
    });
    document.getElementById('close-cart-modal').addEventListener('click', function() {
        document.getElementById('cart-modal').classList.add('hidden');
        console.log('Cart modal closed');
    });
    document.getElementById('cart-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
            console.log('Cart modal closed by clicking backdrop');
        }
    });

    // Cart modal actions (qty, remove, clear)
    document.getElementById('cart-modal-content').addEventListener('click', function(e) {
        if (e.target.classList.contains('cart-qty-btn')) {
            const idx = parseInt(e.target.getAttribute('data-idx'));
            const action = e.target.getAttribute('data-action');
            let cart = getCart();
            if (action === 'increase') {
                cart[idx].quantity += 1;
                console.log('Increased qty for', cart[idx]);
            } else if (action === 'decrease') {
                if (cart[idx].quantity > 1) {
                    cart[idx].quantity -= 1;
                    console.log('Decreased qty for', cart[idx]);
                } else {
                    // Optionally remove if qty goes to 0
                    // cart.splice(idx, 1);
                    // console.log('Removed item (qty 0):', idx);
                }
            }
            setCart(cart);
            renderCartModal();
        } else if (e.target.closest('.cart-remove-btn')) {
            const btn = e.target.closest('.cart-remove-btn');
            const idx = parseInt(btn.getAttribute('data-idx'));
            let cart = getCart();
            const removed = cart.splice(idx, 1);
            setCart(cart);
            renderCartModal();
            console.log('Removed item:', removed[0]);

            // If cart is now empty, also clear session cart
            if (cart.length === 0) {
                fetch('/clear-cart', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Session cart cleared (empty):', data);
                    })
                    .catch(error => {
                        console.error('Error clearing session cart:', error);
                    });
            }
        }
    });
    document.getElementById('clear-cart-btn').addEventListener('click', function() {
        // Clear localStorage cart
        setCart([]);
        renderCartModal();
        console.log('Cleared localStorage cart');

        // Also clear session cart via AJAX
        fetch('/clear-cart', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
            }
        })
            .then(response => response.json())
            .then(data => {
                console.log('Session cart cleared:', data);
            })
            .catch(error => {
                console.error('Error clearing session cart:', error);
            });
    });

    // --- Cart Modal: Send to Backend and Redirect ---
    function sendCartToBackendAndRedirect() {
        const cart = getCart();
        if (!cart.length) {
            alert('Cart is empty!');
            return;
        }
        // Group by vendor_id as backend expects
        const grouped = {};
        cart.forEach(item => {
            if (!grouped[item.vendor_id]) grouped[item.vendor_id] = {};
            const id = item.product_id;
            grouped[item.vendor_id][id] = {
                name: item.name,
                quantity: item.quantity,
                stock_quantity: item.stock_quantity || '',
                item_price: item.price, // assuming price is item_price
                price: item.price,
                dis_price: item.dis_price || '',
                extra_price: item.extra_price || 0,
                extra: item.extra || [],
                size: item.size || '',
                image: item.photo,
                veg: item.veg || '',
                iteam_extra_price: item.iteam_extra_price || 0,
                variant_info: item.variant_info || '',
                category_id: item.category_id
            };
        });
        // Prepare restaurant info (if available)
        const restaurant = {
            id: cart[0].vendor_id,
            name: cart[0].vendor_name || '',
            location: cart[0].vendor_location || '',
            image: cart[0].vendor_image || ''
        };
        const payload = {
            item: grouped,
            restaurant: restaurant
        };
        console.log('Sending cart to backend:', payload);
        fetch('/cart/sync', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
            },
            body: JSON.stringify(payload)
        })
            .then(res => res.json())
            .then(data => {
                console.log('Backend /cart/sync response:', data);
                if (data.status) {
                    window.location.href = '/checkout';
                } else {
                    alert('Failed to sync cart!');
                }
            })
            .catch(err => {
                console.error('Error sending cart to backend:', err);
            });
    }
    // Wire up Go to Cart button in modal
    document.getElementById('modal-go-to-cart').addEventListener('click', function(e) {
        e.preventDefault();
        console.log('Go to Cart button clicked');
        sendCartToBackendAndRedirect();
    });

    // Restaurant Favorite Functionality - Integrated with existing Firebase system
    let currentRestaurantId = null;
    let currentRestaurantName = null;

    // Function to check if restaurant is in favorites (using Firebase) - Optimized
    function checkRestaurantFavorite(restaurantId) {
        if (!restaurantId || !user_uuid) return;
        const outline = document.getElementById('favorite-heart-outline');
        const solid = document.getElementById('favorite-heart-solid');
        database.collection('favorite_restaurant')
            .where('restaurant_id', '==', restaurantId)
            .where('user_id', '==', user_uuid)
            .get()
            .then(function(favoritevendorsnapshots) {
                if (favoritevendorsnapshots.docs.length > 0) {
                    if (outline) outline.classList.add('hidden');
                    if (solid) solid.classList.remove('hidden');
                } else {
                    if (outline) outline.classList.remove('hidden');
                    if (solid) solid.classList.add('hidden');
                }
            });
    }

    // Function to add/remove restaurant from favorites (using Firebase) - Optimized for speed
    function toggleRestaurantFavorite(restaurantId, restaurantName) {
        if (!restaurantId || !user_uuid) return;
        const outline = document.getElementById('favorite-heart-outline');
        const solid = document.getElementById('favorite-heart-solid');
        database.collection('favorite_restaurant')
            .where('restaurant_id', '==', restaurantId)
            .where('user_id', '==', user_uuid)
            .get()
            .then(async function(favoritevendorsnapshots) {
                if (favoritevendorsnapshots.docs.length > 0) {
                    var id = favoritevendorsnapshots.docs[0].id;
                    await database.collection('favorite_restaurant').doc(id).delete();
                    if (outline) outline.classList.remove('hidden');
                    if (solid) solid.classList.add('hidden');
                } else {
                    var id = "<?php echo uniqid(); ?>";
                    await database.collection('favorite_restaurant').doc(id).set({
                        'restaurant_id': restaurantId,
                        'user_id': user_uuid
                    });
                    if (outline) outline.classList.add('hidden');
                    if (solid) solid.classList.remove('hidden');
                }
            });
    }

    // Event listener for favorite button (authenticated users)
    document.addEventListener('click', function(e) {
        if (e.target.closest('#restaurant-favorite-btn')) {
            e.preventDefault();
            if (currentRestaurantId && currentRestaurantName) {
                toggleRestaurantFavorite(currentRestaurantId, currentRestaurantName);
            }
        }
    });

    // Event listener for login alert (non-authenticated users)
    document.addEventListener('click', function(e) {
        if (e.target.closest('#restaurant-favorite-login')) {
            e.preventDefault();
            Swal.fire({
                text: "{{ trans('lang.login_to_favorite') }}",
                icon: "error"
            });
        }
    });

    // Function to set current restaurant info (call this when restaurant data is loaded)
    function setCurrentRestaurant(id, name) {
        currentRestaurantId = id;
        currentRestaurantName = name;
        checkRestaurantFavorite(id);
    }

    // Initialize favorite status when page loads
    if (vendorId) {
        setCurrentRestaurant(vendorId, 'Restaurant');
    }

    // Utility function to check if restaurant is open based on workingHours
    function isRestaurantOpenNow(workingHours) {
        if (!Array.isArray(workingHours)) return false;
        const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        const now = new Date();
        const currentDay = days[now.getDay()];
        let hour = now.getHours();
        let minute = now.getMinutes();
        if (hour < 10) hour = '0' + hour;
        if (minute < 10) minute = '0' + minute;
        const currentTime = hour + ':' + minute;
        for (let i = 0; i < workingHours.length; i++) {
            if (workingHours[i]['day'] === currentDay) {
                const slots = workingHours[i]['timeslot'] || [];
                for (let j = 0; j < slots.length; j++) {
                    const from = slots[j]['from'];
                    const to = slots[j]['to'];
                    if (currentTime >= from && currentTime <= to) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
    let restaurantWorkingHours = [];
</script>

<!-- Google Fonts: Outfit -->
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;700;900&display=swap" rel="stylesheet">
<style>
  html, body, .font-sans, * {
    font-family: 'Outfit', 'Inter', 'ui-sans-serif', 'system-ui', 'sans-serif' !important;
  }
</style>

<script>
// Show location prompt if address_name cookie is missing
function getCookie(name) {
    let matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}()\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}
document.addEventListener('DOMContentLoaded', function() {
    if (!getCookie('address_name')) {
        var banner = document.createElement('div');
        banner.innerHTML = `
            <div style="background: #fffbe6; color: #856404; border: 1px solid #ffeeba; padding: 16px; text-align: center; font-weight: 500; font-size: 1rem;">
                <span>Set your location for accurate delivery and offers.</span>
                <a href="/set-location" style="margin-left: 16px; background: #ffc107; color: #212529; padding: 6px 16px; border-radius: 4px; text-decoration: none; font-weight: bold;">Set Location</a>
            </div>
        `;
        document.body.insertBefore(banner, document.body.firstChild);
    }
});
</script>
