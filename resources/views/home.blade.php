@include('layouts.app')
@include('layouts.header')
<div class="siddhi-home-page">

    <!-- Mobile Filter Section -->
    <!-- <div class="bg-primary px-3 mobile-filter pb-3 section-content">
        <div class="container">
            <div class="row align-items-center py-3">
                <div class="col-md-9 col-sm-9">
                    <div class="input-group rounded-pill shadow-sm overflow-hidden bg-white">
                        <div class="input-group-prepend">
                            <button class="border-0 btn btn-outline-secondary text-dark bg-transparent px-3">
                                <i class="feather-search"></i>
                            </button>
                        </div>
                        <input type="text" class="shadow-none border-0 form-control pl-0" placeholder="Search for vendors or dishes">
                    </div>
                </div>
                <div class="col-md-3 col-sm-3 text-right">
                    <a class="btn btn-light rounded-pill font-weight-bold" href="{{ url('search') }}">
                        <i class="feather-filter mr-2"></i>{{ trans('lang.filter') }}
    </a>
</div>
</div>
</div>
</div> -->
    <!-- Banner Section -->

    <div class="ecommerce-banner multivendor-banner section-content">
        <div class="ecommerce-inner">
            <div class="" id="top_banner"></div>
        </div>
    </div>
    <div class="" style="padding: 10px; width: 100%">

    </div>
    <div class="ecommerce-content multi-vendore-content section-content">
        <!-- Top Categories Section -->
        <section class="top-categories-section">
            <div class="container">
                <div class="title d-flex align-items-center">
                    <h5>{{ trans('lang.top_categories') }}</h5>
                    <span class="see-all ml-auto">
                        <a href="{{ url('categories') }}">{{ trans('lang.see_all') }}</a>
                    </span>
                </div>
                <div class="top_categories" id="top_categories"></div>
            </div>
        </section>
        <section class="most-popular-store-section">
            <div class="container">
                <div class="title d-flex align-items-center">
                    <h5>{{ trans('lang.popular') }} {{ trans('lang.restaurants') }}</h5>
                    <span class="see-all ml-auto">
                        <a href="{{ route('restaurants', 'popular=yes') }}">{{ trans('lang.see_all') }}</a>
                    </span>
                </div>
                <div id="most_popular_store"></div>
            </div><x></x>
        </section>
        <section class="all-stores-section">
            <div class="container">
                <div class="title d-flex align-items-center">
                    <h5>{{ trans('lang.all_stores') }}</h5>
                    <div class="ml-auto d-flex align-items-center">

                        <span class="see-all">
                            <a href="{{ url('restaurants') }}">{{ trans('lang.see_all') }}</a>
                        </span>
                    </div>
                </div>
                <div id="all_stores"></div>
                <!-- Load More Button -->
                <div class="row fu-loadmore-btn" id="loadmore-wrapper">
                    <a class="page-link loadmore-btn" href="javascript:void(0);" onclick="loadMoreRestaurants()" data-dt-idx="0" tabindex="0" id="loadmore">{{ trans('lang.see') }} {{ trans('lang.more') }}</a>
                    <p class="text-danger" style="display:none;" id="noMoreCoupons">{{ trans('lang.no_results') }}</p>
                </div>
            </div>
        </section>
    </div>
    <div class="zone-error m-5 p-5" style="display: none;">
        <div class="zone-image text-center">
            <img onerror="this.onerror=null;this.src=\'' + placeholderImage + '\'" src="{{ asset('img/zone_logo.png') }}" width="100">
        </div>
        <div class="zone-content text-center text-center font-weight-bold text-danger">
            <h3 class="title">{{ trans('lang.zone_error_title') }}</h3>
            <h6 class="text">{{ trans('lang.zone_error_text') }}</h6>
        </div>
    </div>
</div>
@include('layouts.footer')

<!-- lib styles -->
<style>
    .top-categories-slider .slide-item {
        padding: 10px;
    }
    .top-categories-slider .slick-prev,
    .top-categories-slider .slick-next {
        top: 45%;
        transform: translateY(-50%);
        z-index: 1;
        width: 25px;
        height: 25px;
        background: #fff;
        border-radius: 10%;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        opacity: 1;
        display: flex !important;
        align-items: center;
        justify-content: center;
    }
    .top-categories-slider .slick-prev {
        left: -30px;
    }
    .top-categories-slider .slick-next {
        right: -30px;
    }
    .top-categories-slider .slick-prev:before,
    .top-categories-slider .slick-next:before {
        font-family: "FontAwesome";
        color: #484848;
        font-size: 20px;
        opacity: 1;
        line-height: 1;
        display: inline-block;
    }
    .top-categories-slider .slick-prev:before {
        content: "\f104";
    }
    .top-categories-slider .slick-next:before {
        content: "\f105";
    }
    .top-categories-slider .slick-prev:hover,
    .top-categories-slider .slick-next:hover {
        background: #fff;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }
    .top-categories-slider .top-cat-list {
        margin: 0 5px;
    }
    .top-categories-slider .cat-img {
        display: block;
        margin-bottom: 10px;
        padding: 0;
        border: none;
    }
    .top-categories-slider .cat-img img {
        width: 100%;
        height: 160px;
        object-fit: cover;
        border-radius: 12px;
        transition: all 0.3s ease;
        border: none;
        /* box-shadow: 0 6px 20px rgba(0,0,0,0.15); */
    }
    .top-categories-slider .cat-link:hover .cat-img img {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(0,0,0,0.2);
    }
    .top-categories-slider h4 {
        margin-top: 10px;
        font-size: 14px;
        color: #333;
        font-weight: 600;
    }
    .ml-auto {
        margin-left: auto;
    }
    .mr-2 {
        margin-right: 0.5rem;
    }
    .free-delivery-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 1;
        background: rgba(255, 255, 255, 0.9);
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
        color: #28a745;
    }
    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-menu {
        position: absolute;
        top: 100%;
        left: 0;
        z-index: 1000;
        display: none;
        min-width: 200px;
        padding: 0.5rem 0;
        margin: 0.125rem 0 0;
        background-color: #fff;
        border: 1px solid rgba(0,0,0,.15);
        border-radius: 0.25rem;
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,.175);
    }

    .dropdown-menu.show {
        display: block;
        animation: fadeIn 0.2s ease-in-out;
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        color: #212529;
        text-decoration: none;
        transition: background-color 0.2s;
    }

    .dropdown-item:hover {
        background-color: #f8f9fa;
        text-decoration: none;
    }

    .btn {
        min-width: 140px;
        justify-content: space-between;
        transition: all 0.2s;
    }

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .gap-2 {
        gap: 0.5rem !important;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Mobile Responsiveness */
    @media (max-width: 768px) {

        .btn {
            width: calc(50% - 0.5rem);
            min-width: unset;
        }

        .dropdown-menu {
            width: 100%;
            min-width: unset;
        }
    }

    /* Dark mode support */
    @media (prefers-color-scheme: dark) {
        .dropdown-menu {
            background-color: #2d3238;
            border-color: #444;
        }

        .dropdown-item {
            color: #fff;
        }

        .dropdown-item:hover {
            background-color: #3a4147;
        }

        .btn-outline-secondary {
            border-color: #444;
            color: #fff;
        }
    }


    /* Custom Distance Input Styling */
    #custom-distance-container {
        min-width: 200px;
    }

    #custom-distance-container .input-group {
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        overflow: hidden;
    }

    #custom-distance-container input {
        border: none;
        padding: 8px 12px;
    }

    #custom-distance-container .input-group-text {
        background-color: #f5f5f5;
        border: none;
        color: #666;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        #custom-distance-container {
            min-width: calc(50% - 10px);
        }
    }

    @media (max-width: 480px) {
        #custom-distance-container {
            min-width: 100%;
        }
    }

    /* Dark Mode Support */
    @media (prefers-color-scheme: dark) {

        #custom-distance-container .input-group {
            border-color: #444;
        }

        #custom-distance-container input {
            background-color: #2d3238;
            color: #fff;
        }

        #custom-distance-container .input-group-text {
            background-color: #3a4147;
            color: #fff;
        }
    }

    /* .footer-about .footer-social {
        display: flex;
        gap: 16px;
        margin-top: 30px;
    } */
    /* .footer-about .footer-social .btn {
        width: 40px;
        height: 40px;
        border-radius: 50px;
        font-size: 20px;
        background: #fff;
        color: #fff !important;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 0 !important; /* gap handles spacing */
        /* box-shadow: 0 2px 8px rgba(0,0,0,0.08); */
        /* transition: transform 0.2s; */
    /* } */ */
    /* .footer-about .footer-social .btn:hover {
        transform: translateY(-2px) scale(1.08);
        opacity: 0.85;
    } */
    /* .footer-about .footer-social .btn.fb-icon { background: #3b5998; border-color: #3b5998; }
    .footer-about .footer-social .btn.insta-icon { background: linear-gradient(45deg, #405de6, #5851db, #833ab4, #c13584, #e1306c, #fd1d1d); }
    .footer-about .footer-social .btn.youtube-icon { background: #c4302b; border-color: #c4302b; } */
    /* .footer-about .footer-social .btn.twitter-icon { background: #1DA1F2; border-color: #1DA1F2; } */

    .footer-about .footer-social {
        display: flex;
        gap: 12px; /* space between icons */
        margin-top: 30px;
        flex-wrap: wrap;
    }

    .footer-about .footer-social .btn {
        width: 40px;         /* or 32px for even smaller */
        height: 40px;
        min-width: 0;
        min-height: 0;
        padding: 0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;     /* icon size */
        background: #fff;    /* or your brand color */
        color: #fff !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: transform 0.2s, box-shadow 0.2s;
        border: none;
    }

    .footer-about .footer-social .btn.fb-icon { background: #3b5998; }
    .footer-about .footer-social .btn.insta-icon { background: linear-gradient(45deg, #405de6, #5851db, #833ab4, #c13584, #e1306c, #fd1d1d); }
    .footer-about .footer-social .btn.youtube-icon { background: #c4302b; }
    .footer-about .footer-social .btn.twitter-icon { background: #1DA1F2; }

    .footer-about .footer-social .btn:hover {
        transform: scale(1.08);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        opacity: 0.85;
    }

</style>
<script src="{{ asset('js/geofirestore.js') }}"></script>
<script src="https://cdn.firebase.com/libs/geofire/5.0.1/geofire.min.js"></script>
<script type="text/javascript" src="{{ asset('vendor/swiper/swiper.min.js') }}"></script>
<script src="{{ asset('js/restaurant-status.js') }}"></script>

<script type="text/javascript">
    jQuery("#data-table_processing").show();

    var firestore = firebase.firestore();
    var database = firestore; // Alias for compatibility
    var geoFirestore = new GeoFirestore(firestore);
    var vendorId;
    var ref;
    var append_list = '';
    var top_categories = '';
    var most_popular = '';
    var most_sale = '';
    var new_product = '';
    var offers_coupons = '';
    var appName = '';
    var popularStoresList = [];
    var currentCurrency = '';
    var currencyAtRight = false;
    var VendorNearBy = '';
    var pagesize = 12; // Reduced from 20000 to 12 for initial load
    var initialLoadSize = 12; // Load only 12 restaurants initially
    var loadMoreSize = 12; // Load 12 more each time
    var offest = 1;
    var end = null;
    var endarray = [];
    var start = null;
    var allVendorsData = []; // Store all vendors data
    var loadMoreEnabled = true; // Enable load more functionality
    var vendorIds = [];
    var priceData = {};
    var DriverNearByRef = database.collection('settings').doc('RestaurantNearBy');
    var itemCategoriesref = database.collection('vendor_categories').where('publish', '==', true).limit(6);
    var vendorsref = geoFirestore.collection('vendors');
    var productref = database.collection('vendor_products').where('publish', '==', true);
    var bannerref = database.collection('menu_items').where("is_publish", "==", true).orderBy('set_order', 'asc');
    var refCurrency = database.collection('currencies').where('isActive', '==', true);
    var currentDate = new Date();
    var inValidVendors = new Set();
    var decimal_degits = 0;
    var isSelfDeliveryGlobally = false;
    var refGlobal = database.collection('settings').doc("globalSettings");
    refGlobal.get().then(async function(
        settingSnapshots) {
        if (settingSnapshots.data()) {
            var settingData = settingSnapshots.data();
            if (settingData.isSelfDelivery) {
                isSelfDeliveryGlobally = true;
            }
        }
    })
    refCurrency.get().then(async function(snapshots) {
        var currencyData = snapshots.docs[0].data();
        currentCurrency = currencyData.symbol;
        currencyAtRight = currencyData.symbolAtRight;
        if (currencyData.decimal_degits) {
            decimal_degits = currencyData.decimal_degits;
        }
    });

    var placeholderImageRef = database.collection('settings').doc('placeHolderImage');
    var placeholderImageSrc = '';
    placeholderImageRef.get().then(async function(placeholderImageSnapshots) {
        var placeHolderImageData = placeholderImageSnapshots.data();
        placeholderImageSrc = placeHolderImageData.image;
    })
    var subscriptionModel = localStorage.getItem('subscriptionModel');
    var refs = database.collection('vendors').where('title', '!=', '').orderBy('title').limit(pagesize);
    var couponsRef = database.collection('coupons').where('isEnabled', '==', true).orderBy("expiresAt").startAt(
        new Date()).limit(4);

    // Add these variables at the top with other declarations
    var deliveryOptionsCache = new Map();
    var globalDeliverySettings = null;
    var deliveryChargeSettings = null;

    // Function to fetch global delivery settings
    async function fetchGlobalDeliverySettings() {
        try {
            const globalSettingsDoc = await database.collection('settings').doc('globalSettings').get();
            const deliveryChargeDoc = await database.collection('settings').doc('DeliveryCharge').get();

            if (globalSettingsDoc.exists) {
                globalDeliverySettings = globalSettingsDoc.data();
            }

            if (deliveryChargeDoc.exists) {
                deliveryChargeSettings = deliveryChargeDoc.data();
            }

        } catch (error) {
            console.error('Error fetching delivery settings:', error);
        }
    }


    // Function to get vendor delivery details
    async function getVendorDeliveryDetails(vendorId) {
        if (deliveryOptionsCache.has(vendorId)) {
            return deliveryOptionsCache.get(vendorId);
        }

        try {
            const vendorDoc = await database.collection('vendors').doc(vendorId).get();
            if (!vendorDoc.exists) return null;

            const vendorData = vendorDoc.data();
            const deliveryDetails = {
                isSelfDelivery: vendorData.isSelfDelivery || false,
                deliveryCharge: vendorData.deliveryCharge || 0,
                minimumDeliveryCharge: vendorData.minimumDeliveryCharge || 0,
                minimumDeliveryChargeKM: vendorData.minimumDeliveryChargeKM || 0,
                deliveryChargePerKm: vendorData.deliveryChargePerKm || 0,
                freeDelivery: vendorData.freeDelivery || false,
                freeDeliveryMinimumOrder: vendorData.freeDeliveryMinimumOrder || 0,
                thirdPartyDelivery: vendorData.thirdPartyDelivery || false,
                thirdPartyDeliveryService: vendorData.thirdPartyDeliveryService || '',
            };

            // Cache the delivery details
            deliveryOptionsCache.set(vendorId, deliveryDetails);
            return deliveryDetails;
        } catch (error) {
            console.error('Error fetching vendor delivery details:', error);
            return null;
        }
    }


    // Function to calculate delivery charge
    function calculateDeliveryCharge(vendorDeliveryDetails, distance) {
        if (!vendorDeliveryDetails) return 0;

        if (vendorDeliveryDetails.freeDelivery) return 0;

        let charge = vendorDeliveryDetails.minimumDeliveryCharge;

        if (distance > vendorDeliveryDetails.minimumDeliveryChargeKM) {
            const extraDistance = distance - vendorDeliveryDetails.minimumDeliveryChargeKM;
            charge += extraDistance * vendorDeliveryDetails.deliveryChargePerKm;
        }

        return charge;
    }


    // Function to format price with currency
    function formatPrice(price) {
        const formattedPrice = price.toFixed(decimal_degits);
        return currencyAtRight
            ? formattedPrice + currentCurrency
            : currentCurrency + formattedPrice;
    }

    // Update event handlers
    $(document).ready(async function() {
        // ... existing ready handler code ...

        // Fetch delivery settings
        await fetchGlobalDeliverySettings();

    });

    function getBanners() {
        var available_stores = [];
        geoFirestore.collection('vendors').where('zoneId', '==', user_zone_id).get().then(async function(snapshots) {
            snapshots.docs.forEach((doc) => {
                if (!inValidVendors.has(doc.id)) {
                    available_stores.push(doc.id);
                }
            });
        });
        var position1_banners = [];
        bannerref.get().then(async function(banners) {
            banners.docs.forEach((banner) => {
                var bannerData = banner.data();
                var redirect_type = '';
                var redirect_id = '';
                if (bannerData.position == 'top') {
                    if (bannerData.hasOwnProperty('redirect_type')) {
                        redirect_type = bannerData.redirect_type;
                        redirect_id = bannerData.redirect_id;
                    }
                    var object = {
                        'photo': bannerData.photo,
                        'redirect_type': redirect_type,
                        'redirect_id': redirect_id,
                    }
                    position1_banners.push(object);
                }
            });
            if (position1_banners.length > 0) {
                var html = '';
                for (banner of position1_banners) {
                    html += '<div class="banner-item">';
                    html += '<div class="banner-img">';
                    var redirect_id = '#';
                    if (banner.redirect_type != '') {
                        if (banner.redirect_type == "store") {
                            if (jQuery.inArray(banner.redirect_id, available_stores) === -1) {
                                redirect_id = '#';
                            }
                            redirect_id = "/restaurant/" + banner.redirect_id + "/" + banner.restaurant_slug + "/" + banner.zone_slug;
                        } else if (banner.redirect_type == "product") {
                            redirect_id = "/productDetail/" + banner.redirect_id;
                        } else if (banner.redirect_type == "external_link") {
                            redirect_id = banner.redirect_id;
                        }
                    }
                    html += '<a href="' + redirect_id + '"><img onerror="this.onerror=null;this.src=\'' +
                        placeholderImage + '\'" src="' + banner.photo + '"></a>';
                    html += '</div>';
                    html += '</div>';
                }
                $("#top_banner").html(html);
            } else {
                $('.ecommerce-banner').remove();
            }
            setTimeout(function() {
                slickcatCarousel();
            }, 200)
        });
    }


    var myInterval = '';
    $(document).ready(async function() {
        console.log("Initial user_zone_id:", typeof user_zone_id, user_zone_id);
        console.log("Initial address_lat:", typeof address_lat, address_lat);
        console.log("Initial address_lng:", typeof address_lng, address_lng);

        // Retrieve all invalid vendors
        await checkVendors().then(expiredStores => {
            inValidVendors = expiredStores;
        });



        // Fetch and render top banners
        getBanners();
        
        // Replace constant polling with event-driven updates
        // This prevents the server from hitting process limits
        initializeEfficientStoreUpdates();
    });

    // Function to initialize efficient store updates instead of constant polling
    function initializeEfficientStoreUpdates() {
        let updateTimeout = null;
        let lastUpdateTime = 0;
        const MIN_UPDATE_INTERVAL = 30000; // Minimum 30 seconds between updates

        // Update store data when page becomes visible
        document.addEventListener('visibilitychange', function() {
            if (document.visibilityState === 'visible') {
                // Debounce rapid visibility changes
                if (updateTimeout) clearTimeout(updateTimeout);
                updateTimeout = setTimeout(() => {
                    const now = Date.now();
                    if (now - lastUpdateTime > MIN_UPDATE_INTERVAL) {
                        callStore();
                        lastUpdateTime = now;
                    }
                }, 1000);
            }
        });

        // Update when user location changes (if you have location change detection)
        if (typeof window.addEventListener === 'function') {
            window.addEventListener('locationChanged', function() {
                const now = Date.now();
                if (now - lastUpdateTime > MIN_UPDATE_INTERVAL) {
                    callStore();
                    lastUpdateTime = now;
                }
            });
        }

        // Initial call with delay to prevent immediate resource spike
        setTimeout(() => {
            callStore();
            lastUpdateTime = Date.now();
        }, 2000);
        
        // Retry mechanism for location detection
        let locationRetryCount = 0;
        const maxLocationRetries = 10;
        const locationRetryInterval = setInterval(() => {
            if (typeof address_lat !== 'undefined' && typeof address_lng !== 'undefined' && typeof user_zone_id !== 'undefined') {
                clearInterval(locationRetryInterval);
                console.log("Location detected, initializing data...");
                callStore();
            } else if (locationRetryCount >= maxLocationRetries) {
                clearInterval(locationRetryInterval);
                console.log("Location detection timeout, showing error...");
                jQuery(".section-content").remove();
                jQuery(".zone-error").show();
            }
            locationRetryCount++;
        }, 2000);

        // Fallback update every 5 minutes (much better than every 1 second)
        setInterval(() => {
            const now = Date.now();
            if (now - lastUpdateTime > MIN_UPDATE_INTERVAL) {
                callStore();
                lastUpdateTime = now;
            }
        }, 300000); // 5 minutes instead of 1 second
    }

    function myStopTimer() {
        // No longer needed with new approach, but keeping for compatibility
        console.log('Timer stopped - using efficient updates now');
    }

    async function callStore() {
        console.log("callStore - address_lat:", typeof address_lat, address_lat, "address_lng:", typeof address_lng, address_lng, "user_zone_id:", typeof user_zone_id, user_zone_id);
        
        // Check if location variables are defined and valid
        if (typeof address_lat === 'undefined' || typeof address_lng === 'undefined' || typeof user_zone_id === 'undefined' ||
            address_lat == '' || address_lng == '' || address_lng == NaN || address_lat == NaN || address_lat == null || address_lng == null) {
            console.log("Location not detected yet, waiting...");
            return false;
        }
        DriverNearByRef.get().then(async function(DriverNearByRefSnapshots) {
            var DriverNearByRefData = DriverNearByRefSnapshots.data();
            VendorNearBy = parseInt(DriverNearByRefData.radios);
            radiusUnit = DriverNearByRefData.distanceType;

            if (radiusUnit == 'miles') {
                VendorNearBy = parseInt(VendorNearBy * 1.60934)
            }
            address_lat = parseFloat(address_lat);
            address_lng = parseFloat(address_lng);
            if (user_zone_id == null) {
                jQuery(".section-content").remove();
                jQuery(".zone-error").show();
                jQuery("#data-table_processing").hide();
                return false;
            }
            priceData = await fetchVendorPriceData();
            // No need to stop timer anymore - using efficient updates
            // Load critical content first
            getItemCategories();
            getMostPopularStores();
            
            // Load restaurants after a short delay
            setTimeout(() => {
                getAllStore();
            }, 500);

        })
    }


    function slickcatCarousel() {
        if ($("#top_banner").length > 0 && $("#top_banner").html().trim() !== "") {
            $('#top_banner').slick({
                slidesToShow: 1,
                dots: true,
                arrows: true,
                autoplay: true, // Optional: autoplay
                autoplaySpeed: 3000, // Optional: 3 seconds autoplay delay
            });
        } else {
            console.log("Top banner element not found or empty.");
        }
    }

    async function getAllStore() {
        console.log("Loading restaurants with optimized query...");
        
        // Simplified query to avoid complex index requirements
        var nearestRestauantRefnew = geoFirestore.collection('vendors')
            .where('zoneId', '==', user_zone_id)
            .where('vType', '==', 'restaurant')
            .limit(initialLoadSize);
        
        nearestRestauantRefnew.get().then(async function(snapshots) {
            if (snapshots.docs.length > 0) {
                console.log("Initial restaurants loaded:", snapshots.docs.length);
                
                // Initialize vendors array for initial display
                let vendors = [];
                snapshots.docs.forEach((listval) => {
                    var datas = listval.data();
                    datas.id = listval.id;
                    if (!inValidVendors.has(listval.id)) {
                        datas.currentStatus = getVendorStatus(datas);
                        datas.minPrice = 0; // Initialize with 0
                        vendors.push(datas);
                    }
                });

                // Calculate prices for initial vendors only (much faster)
                const minPrices = await getAllVendorMinPrices(vendors);
                vendors.forEach(vendor => {
                    vendor.minPrice = minPrices.get(vendor.id) || 0;
                });

                // Store vendors data
                allVendorsData = vendors;
                console.log("Initial vendors stored:", allVendorsData.length);
                
                // Display initial restaurants immediately
                displayRestaurants();
                
                // Load more data in background (non-blocking)
                setTimeout(() => {
                    loadMoreRestaurantsInBackground();
                }, 1000);

            } else {
                console.log("No restaurants found for this zone");
                $(".all-stores-section").remove();
                $(".section-content").remove();
                jQuery(".zone-error").show();
                jQuery(".zone-error").find('.title').text('{{ trans('lang.restaurant_error_title') }}');
                jQuery(".zone-error").find('.text').text('{{ trans('lang.restaurant_error_text') }}');
            }
        }).catch(function(error) {
            console.error("Error loading restaurants:", error);
            // Fallback: try without vType filter
            console.log("Trying fallback query without vType filter...");
            geoFirestore.collection('vendors')
                .where('zoneId', '==', user_zone_id)
                .limit(initialLoadSize)
                .get()
                .then(async function(fallbackSnapshots) {
                    if (fallbackSnapshots.docs.length > 0) {
                        console.log("Fallback query successful, filtering restaurants client-side");
                        let vendors = [];
                        fallbackSnapshots.docs.forEach((listval) => {
                            var datas = listval.data();
                            datas.id = listval.id;
                            // Filter restaurants client-side
                            if (!inValidVendors.has(listval.id) && datas.vType === 'restaurant') {
                                datas.currentStatus = getVendorStatus(datas);
                                datas.minPrice = 0;
                                vendors.push(datas);
                            }
                        });
                        
                        if (vendors.length > 0) {
                            const minPrices = await getAllVendorMinPrices(vendors);
                            vendors.forEach(vendor => {
                                vendor.minPrice = minPrices.get(vendor.id) || 0;
                            });
                            
                            allVendorsData = vendors;
                            console.log("Fallback vendors stored:", allVendorsData.length);
                            displayRestaurants();
                        } else {
                            console.log("No restaurants found in fallback query");
                            $(".all-stores-section").remove();
                        }
                    }
                })
                .catch(function(fallbackError) {
                    console.error("Fallback query also failed:", fallbackError);
                    $(".all-stores-section").remove();
                });
        });
    }

    // Cache for vendor delivery status
    let vendorDeliveryCache = new Map();
    let initialDataLoaded = false;
    
    // Background loading function for additional restaurants
    async function loadMoreRestaurantsInBackground() {
        console.log("Loading more restaurants in background...");
        try {
            let additionalRestaurants = [];
            // Simplified query to avoid complex index requirements
            var moreRestaurantsRef = geoFirestore.collection('vendors')
                .where('zoneId', '==', user_zone_id)
                .where('vType', '==', 'restaurant')
                .limit(50);
            
            const moreSnapshots = await moreRestaurantsRef.get();
            moreSnapshots.docs.forEach((doc) => {
                const data = doc.data();
                data.id = doc.id;
                if (!inValidVendors.has(doc.id) && !allVendorsData.find(v => v.id === doc.id)) {
                    data.currentStatus = getVendorStatus(data);
                    data.minPrice = 0;
                    additionalRestaurants.push(data);
                }
            });
            
            // Add to existing data
            allVendorsData = allVendorsData.concat(additionalRestaurants);
            console.log("Background loading complete. Total restaurants:", allVendorsData.length);
            
            // Update the load more button visibility after background loading
            if (allVendorsData.length > currentDisplayCount) {
                $('#loadmore').show();
                console.log("Showing load more button after background loading");
            }
            
        } catch (error) {
            console.log("Background loading failed:", error);
        }
    }

    // Function to preload vendor data
    async function preloadVendorData() {
        if (initialDataLoaded || !window.vendorsData) return;

        try {
            const vendorIds = window.vendorsData.docs.map(doc => doc.id);
            
            // Firebase 'in' operator has a limit of 10 elements, so we need to batch the queries
            const batchSize = 10;
            const batches = [];
            
            for (let i = 0; i < vendorIds.length; i += batchSize) {
                batches.push(vendorIds.slice(i, i + batchSize));
            }

            // Execute all batches in parallel
            const batchPromises = batches.map(batch => 
                database.collection('vendors')
                    .where(firebase.firestore.FieldPath.documentId(), 'in', batch)
                    .get()
            );

            const snapshots = await Promise.all(batchPromises);

            // Process all results
            snapshots.forEach(vendorsSnapshot => {
            vendorsSnapshot.docs.forEach(doc => {
                const data = doc.data();
                vendorDeliveryCache.set(doc.id, {
                    isSelfDelivery: data.isSelfDelivery || false,
                    hasFreeSelfDelivery: data.isSelfDelivery && isSelfDeliveryGlobally
                    });
                });
            });

            initialDataLoaded = true;
        } catch (error) {
            console.error('Error preloading vendor data:', error);
        }
    }


    // Call preload when document is ready
    $(document).ready(async function() {
        // ... existing ready handler code ...

        // Preload vendor data after initial data is loaded - increased interval to reduce server load
        const checkDataInterval = setInterval(() => {
            if (window.vendorsData) {
                clearInterval(checkDataInterval);
                preloadVendorData();
            }
        }, 1000); // Changed from 100ms to 1000ms to reduce server load
    });

    // Optimized function to get minimum prices for vendors (with caching)
    const priceCache = new Map();
    
    async function getAllVendorMinPrices(vendors) {
        const vendorIds = vendors.map(v => v.id);
        const minPrices = new Map();

        try {
            // Check cache first
            const uncachedVendors = vendorIds.filter(id => !priceCache.has(id));
            
            if (uncachedVendors.length === 0) {
                // All prices are cached
                vendorIds.forEach(id => {
                    minPrices.set(id, priceCache.get(id));
                });
                return minPrices;
            }

            // Firebase 'in' operator has a limit of 10 elements, so we need to batch the queries
            const batchSize = 10;
            const batches = [];
            
            for (let i = 0; i < uncachedVendors.length; i += batchSize) {
                batches.push(uncachedVendors.slice(i, i + batchSize));
            }

            // Execute all batches in parallel
            const batchPromises = batches.map(batch => 
                database.collection('vendor_products')
                    .where('vendorID', 'in', batch)
                    .where('publish', '==', true)
                    .limit(50) // Limit products per vendor for faster query
                    .get()
            );

            const snapshots = await Promise.all(batchPromises);

            // Group products by vendor
            const vendorProducts = new Map();
            snapshots.forEach(productsSnapshot => {
                productsSnapshot.docs.forEach((doc) => {
                    const product = doc.data();
                    if (!vendorProducts.has(product.vendorID)) {
                        vendorProducts.set(product.vendorID, []);
                    }
                    vendorProducts.get(product.vendorID).push(product);
                });
            });

            // Calculate min price for each vendor
            uncachedVendors.forEach(vendorId => {
                let minPrice = Infinity;
                const products = vendorProducts.get(vendorId) || [];

                products.forEach(product => {
                    let price = parseFloat(product.price);

                    // Check if there's a discount price
                    if (product.disPrice && parseFloat(product.disPrice) > 0) {
                        price = parseFloat(product.disPrice);
                    }

                    // If product has variants, get the minimum variant price
                    if (product.item_attribute && product.item_attribute.variants) {
                        const variantPrices = product.item_attribute.variants.map(v =>
                            parseFloat(v.variant_price || 0)
                        ).filter(p => p > 0);

                        if (variantPrices.length > 0) {
                            price = Math.min(...variantPrices);
                        }
                    }

                    if (price < minPrice && price > 0) {
                        minPrice = price;
                    }
                });

                const finalPrice = minPrice === Infinity ? 0 : minPrice;
                minPrices.set(vendorId, finalPrice);
                priceCache.set(vendorId, finalPrice); // Cache the result
            });

            // Add cached prices
            vendorIds.forEach(id => {
                if (priceCache.has(id)) {
                    minPrices.set(id, priceCache.get(id));
                }
            });

        } catch (error) {
            console.error('Error getting vendor minimum prices:', error);
        }
        return minPrices;
    }

    // Function to get minimum price from vendor's products (legacy - kept for compatibility)
    async function getVendorMinPrice(vendor) {
        let minPrice = Infinity;
        try {
            const productsSnapshot = await database.collection('vendor_products')
                .where('vendorID', '==', vendor.id)
                .where('publish', '==', true)
                .get();

            productsSnapshot.docs.forEach((doc) => {
                const product = doc.data();
                let price = parseFloat(product.price);

                // Check if there's a discount price
                if (product.disPrice && parseFloat(product.disPrice) > 0) {
                    price = parseFloat(product.disPrice);
                }

                // If product has variants, get the minimum variant price
                if (product.item_attribute && product.item_attribute.variants) {
                    const variantPrices = product.item_attribute.variants.map(v =>
                        parseFloat(v.variant_price || 0)
                    ).filter(p => p > 0);

                    if (variantPrices.length > 0) {
                        price = Math.min(...variantPrices);
                    }
                }

                if (price < minPrice && price > 0) {
                    minPrice = price;
                }
            });
        } catch (error) {
            console.error('Error getting vendor minimum price:', error);
        }
        return minPrice === Infinity ? 0 : minPrice;
    }

    // Function to get vendor's current status using failproof logic
    function getVendorStatus(vendorData) {
        // Use the failproof restaurant status manager
        if (window.restaurantStatusManager) {
            const workingHours = vendorData.workingHours || [];
            const isOpen = vendorData.isOpen !== undefined ? vendorData.isOpen : null;
            const status = window.restaurantStatusManager.isRestaurantOpenNow(workingHours, isOpen);
            return status ? 'Open' : 'Closed';
        }

        // Fallback to old logic if restaurant status manager is not available
        var status = 'Closed';
        var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        var currentdate = new Date();
        var currentDay = days[currentdate.getDay()];
        var hour = currentdate.getHours();
        var minute = currentdate.getMinutes();

        if (hour < 10) hour = '0' + hour;
        if (minute < 10) minute = '0' + minute;

        var currentHours = hour + ':' + minute;

        if (vendorData.hasOwnProperty('workingHours')) {
            for (var i = 0; i < vendorData.workingHours.length; i++) {
                if (vendorData.workingHours[i]['day'] == currentDay) {
                    if (vendorData.workingHours[i]['timeslot'].length != 0) {
                        for (var j = 0; j < vendorData.workingHours[i]['timeslot'].length; j++) {
                            var timeslot = vendorData.workingHours[i]['timeslot'][j];
                            var from = timeslot['from'];
                            var to = timeslot['to'];
                            if (currentHours >= from && currentHours <= to) {
                                status = 'Open';
                                break;
                            }
                        }
                    }
                    break;
                }
            }
        }
        return status;
    }

    // Update buildAllStoresHTMLFromArray to match Popular Restaurants UI exactly
    function buildAllStoresHTMLFromArray(alldata) {
        var html = '';
        if (alldata.length > 0) {
            html = html + '<div class="row">';
            alldata.forEach((val) => {
                var rating = 0;
                var reviewsCount = 0;
                if (val.hasOwnProperty('reviewsSum') && val.reviewsSum != 0 && val.reviewsSum != null && val
                    .reviewsSum != '' && val.hasOwnProperty(
                    'reviewsCount') && val.reviewsCount != 0 && val.reviewsCount != null && val
                    .reviewsCount != '') {
                    rating = (val.reviewsSum / val.reviewsCount);
                    rating = Math.round(rating * 10) / 10;
                    reviewsCount = val.reviewsCount;
                } else {
                    if (window.randomizedRatings[val.id]) {
                        rating = window.randomizedRatings[val.id].rating;
                        reviewsCount = window.randomizedRatings[val.id].reviewsCount;
                    } else {
                        rating = (Math.random() * (5.0 - 4.1) + 4.1).toFixed(1);
                        reviewsCount = Math.floor(Math.random() * (25 - 11 + 1)) + 11;
                        window.randomizedRatings[val.id] = { rating, reviewsCount };
                    }
                }

                // Use failproof status logic (same as Popular Restaurants)
                var status = 'Closed';
                var statusclass = "closed";

                if (window.restaurantStatusManager) {
                    const workingHours = val.workingHours || [];
                    const isOpen = val.isOpen !== undefined ? val.isOpen : null;
                    const isOpenNow = window.restaurantStatusManager.isRestaurantOpenNow(workingHours, isOpen);
                    if (isOpenNow) {
                        status = '{{ trans('lang.open') }}';
                        statusclass = "open";
                    }
                } else {
                    // Fallback to old logic
                    var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                    var currentdate = new Date();
                    var currentDay = days[currentdate.getDay()];
                    hour = currentdate.getHours();
                    minute = currentdate.getMinutes();
                    if (hour < 10) {
                        hour = '0' + hour
                    }
                    if (minute < 10) {
                        minute = '0' + minute
                    }
                    var currentHours = hour + ':' + minute;
                    if (val.hasOwnProperty('workingHours')) {
                        for (i = 0; i < val.workingHours.length; i++) {
                            var day = val.workingHours[i]['day'];
                            if (val.workingHours[i]['day'] == currentDay) {
                                if (val.workingHours[i]['timeslot'].length != 0) {
                                    for (j = 0; j < val.workingHours[i]['timeslot'].length; j++) {
                                        var timeslot = val.workingHours[i]['timeslot'][j];
                                        var from = timeslot[`from`];
                                        var to = timeslot[`to`];
                                        if (currentHours >= from && currentHours <= to) {
                                            status = '{{ trans('lang.open') }}';
                                            statusclass = "open";
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                var vendor_id_single = val.id;
                var view_vendor_details = "/restaurant/" + vendor_id_single + "/" + val.restaurant_slug + "/" + val.zone_slug;

                getMinDiscount(val.id);
                html = html +
                    '<div class="col-md-3 product-list"><div class="list-card position-relative"><div class="list-card-image"><span class="discount-price vendor_dis_' +
                    val.id + ' " ></span>';
                if (val.photo != "" && val.photo != null) {
                    photo = val.photo;
                } else {
                    photo = placeholderImageSrc;
                }
                html = html + '<div class="member-plan position-absolute"><span class="badge badge-dark ' +
                    statusclass + '">' + status + '</span></div><div class="offer-icon position-absolute free-delivery-' + val.id + '"></div><a href="' + view_vendor_details +
                    '"><img onerror="this.onerror=null;this.src=\'' + placeholderImage + '\'" alt="#" src="' +
                    photo +
                    '" class="img-fluid item-img w-100"></a></div><div class="py-2 position-relative"><div class="list-card-body position-relative"><h6 class="mb-1 popul-title"><a href="' +
                    view_vendor_details + '" class="text-black">' + val.title +
                    '</a></h6><p class="text-gray mb-1 small address"><span class="fa fa-map-marker"></span>' +
                    val.location + '</p>';
                html = html +
                    '<div class="star position-relative mt-3"><span class="badge badge-success "><i class="feather-star"></i>' +
                    rating + ' (' + reviewsCount + ')</span></div>';
                html = html + '</div>';
                html = html + '</div></div></div>';

                // Keep the existing checkSelfDeliveryForVendor call
                checkSelfDeliveryForVendor(val.id);
            });
            html = html + '</div>';
        }
        return html;
    }

    async function getItemCategories() {
        console.log("Fetching categories...");
        itemCategoriesref.get().then(async function(foodCategories) {
            console.log("Categories fetched:", foodCategories.docs.length);
            top_categories = document.getElementById('top_categories');
            top_categories.innerHTML = '';
            foodCategorieshtml = await buildHTMLItemCategory(foodCategories);
            top_categories.innerHTML = foodCategorieshtml;
            initTopCategoriesSlider(); // Initialize the slider
            jQuery("#data-table_processing").hide();
        })
    }

    async function getHomepageCategory() {
        var home_cat_ref = database.collection('vendor_categories').where("publish", "==", true).where(
            'show_in_homepage', '==', true).limit(5);
        home_cat_ref.get().then(async function(homeCategories) {
            home_categories = document.getElementById('home_categories');
            home_categories.innerHTML = '';
            var homeCategorieshtml = '';
            var alldata = [];
            homeCategories.docs.forEach((listval) => {
                var datas = listval.data();
                datas.id = listval.id;
                alldata.push(datas);
            });
            for (listval of alldata) {
                var val = listval;
                var category_id = val.id;
                var category_route = "/restaurants/category/" + category_id;
                if (val.photo != "" && val.photo != null) {
                    photo = val.photo;
                } else {
                    photo = placeholderImageSrc;
                }
                var haveStores = await catHaveStores(category_id);

                if (haveStores == true) {
                    var productHtml = await buildHTMLHomeCategoryStores(category_id);
                    if (productHtml != '') {
                        homeCategorieshtml += '<div class="category-content mb-5" id="category-content-' + category_id + '">';
                        homeCategorieshtml += '<div class="title d-flex align-items-center">';
                        homeCategorieshtml += '<h5>' + val.title + '</h5>';
                        homeCategorieshtml += '<span class="see-all ml-auto"><a href="' + category_route +
                            '">{!! trans('lang.see_all') !!}</a></span>';
                        homeCategorieshtml += '</div>';
                        homeCategorieshtml += productHtml;
                        homeCategorieshtml += '</div>';
                    }
                }
            }
            if (homeCategorieshtml != '') {
                home_categories.innerHTML = homeCategorieshtml;
            }
        })
    }

    async function catHaveStores(categoryId) {
        console.log("Checking stores for category:", categoryId);
        console.log("Current user zone:", user_zone_id);
        var snapshots = await database.collection('vendors').where("categoryID", "array-contains", categoryId).where('zoneId',
            '==', user_zone_id).get();
        console.log("Found stores:", snapshots.docs.length);
        if (snapshots.docs.length > 0) {
            return true;
        } else {
            return false;
        }
    }

    async function buildHTMLHomeCategoryStores(category_id) {
        var html = '';
        var snapshots = await database.collection('vendors').where('categoryID', "array-contains", category_id).where('zoneId',
            '==', user_zone_id).limit(4).get();
        var alldata = [];
        snapshots.docs.forEach((listval) => {
            var datas = listval.data();
            datas.id = listval.id;

            if (!inValidVendors.has(listval.id)) {
                alldata.push(datas);
            }
        });



        if (alldata.length > 0) {
            var count = 0;
            html = html + '<div class="row">';
            alldata.forEach((listval) => {
                var val = listval;

                var rating = 0;
                var reviewsCount = 0;
                if (val.hasOwnProperty('reviewsSum') && val.reviewsSum != 0 && val.reviewsSum != null && val
                    .reviewsSum != '' && val.hasOwnProperty(
                    'reviewsCount') && val.reviewsCount != 0 && val.reviewsCount != null && val
                    .reviewsCount != '') {
                    rating = (val.reviewsSum / val.reviewsCount);
                    rating = Math.round(rating * 10) / 10;
                    reviewsCount = val.reviewsCount;
                }
                // Use failproof status logic
                var status = 'Closed';
                var statusclass = "closed";

                if (window.restaurantStatusManager) {
                    const workingHours = val.workingHours || [];
                    const isOpen = val.isOpen !== undefined ? val.isOpen : null;
                    const isOpenNow = window.restaurantStatusManager.isRestaurantOpenNow(workingHours, isOpen);
                    if (isOpenNow) {
                        status = '{{ trans('lang.open') }}';
                        statusclass = "open";
                    }
                } else {
                    // Fallback to old logic
                    var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                    var currentdate = new Date();
                    var currentDay = days[currentdate.getDay()];
                    hour = currentdate.getHours();
                    minute = currentdate.getMinutes();
                    if (hour < 10) {
                        hour = '0' + hour
                    }
                    if (minute < 10) {
                        minute = '0' + minute
                    }
                    var currentHours = hour + ':' + minute;
                    if (val.hasOwnProperty('workingHours')) {
                        for (i = 0; i < val.workingHours.length; i++) {
                            var day = val.workingHours[i]['day'];
                            if (val.workingHours[i]['day'] == currentDay) {
                                if (val.workingHours[i]['timeslot'].length != 0) {
                                    for (j = 0; j < val.workingHours[i]['timeslot'].length; j++) {
                                        var timeslot = val.workingHours[i]['timeslot'][j];
                                        var from = timeslot[`from`];
                                        var to = timeslot[`to`];
                                        if (currentHours >= from && currentHours <= to) {
                                            status = '{{ trans('lang.open') }}';
                                            statusclass = "open";
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                var vendor_id_single = val.id;
                var view_vendor_details = "/restaurant/" + vendor_id_single + "/" + val.restaurant_slug + "/" + val.zone_slug;
                count++;
                getMinDiscount(val.id);
                html = html +
                    '<div class="col-md-3 product-list"><div class="list-card position-relative"><div class="list-card-image">';
                if (val.photo != "" && val.photo != null) {
                    photo = val.photo;
                } else {
                    photo = placeholderImageSrc;
                }
                html = html + '<div class="member-plan position-absolute"><span class="badge badge-dark ' +
                    statusclass + '">' + status + '</span></div><div class="offer-icon position-absolute free-delivery-' + val.id + '"></div><a href="' + view_vendor_details +
                    '"><img  onerror="this.onerror=null;this.src=\'' + placeholderImage + '\'" alt="#" src="' +
                    photo +
                    '" class="img-fluid item-img w-100"></a></div><div class="py-2 position-relative"><div class="list-card-body"><h6 class="mb-1 popul-title"><a href="' +
                    view_vendor_details + '" class="text-black">' + val.title +
                    '</a></h6><p class="text-gray mb-1 small address"><span class="fa fa-map-marker"></span>' +
                    val.location + '</p>';
                html = html + '<span class="pro-price vendor_dis_' + val.id + ' " ></span>';
                html = html +
                    '<div class="star position-relative mt-3"><span class="badge badge-success "><i class="feather-star"></i>' +
                    rating + ' (' + reviewsCount + ')</span></div>';
                html = html + '</div>';
                html = html + '</div></div></div>';
                checkSelfDeliveryForVendor(val.id);
            });
            html = html + '</div>';
        }
        return html;
    }

    async function buildHTMLItemCategory(foodCategories) {
        console.log("Building HTML for categories:", foodCategories.docs.length);
        var html = '';
        var alldata = [];
        for (const listval of foodCategories.docs) {
            var datas = listval.data();
            datas.id = listval.id;
            // Temporarily show all categories for testing
            alldata.push(datas);
            // Log category data for debugging
            console.log("Category:", datas.id, datas.title);
        }
        console.log("Total categories:", alldata.length);

        // Create slider container
        html += '<div class="top-categories-slider">';
        alldata.forEach((listval) => {
            var val = listval;
            var category_id = val.id;
            var trending_route = "/restaurants/category/" + category_id;
            if (val.photo != "" && val.photo != null) {
                photo = val.photo;
            } else {
                photo = placeholderImageSrc;
            }
            html += '<div class="slide-item">';
            html += '<div class="top-cat-list">';
            html += '<a class="d-block text-center cat-link" href="' + trending_route + '">';
            html += '<span class="cat-img"><img onerror="this.onerror=null;this.src=\'' + placeholderImage + '\'" alt="#" src="' + photo + '" class="img-fluid mb-2"></span>';
            html += '<h4 class="m-0">' + val.title + '</h4>';
            html += '</a>';
            html += '</div>';
            html += '</div>';
        });
        html += '</div>';
        return html;
    }

    // Add this function to initialize the slider
    function initTopCategoriesSlider() {
        if($('.top-categories-slider').length > 0) {
            $('.top-categories-slider').slick({
                dots: false,
                infinite: true,
                speed: 300,
                slidesToShow: 6,
                slidesToScroll: 1,
                arrows: true,
                autoplay: true,
                autoplaySpeed: 3000,
                cssEase: 'linear',
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 4,
                            slidesToScroll: 1,
                            infinite: true
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 1,
                            infinite: true
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1,
                            infinite: true
                        }
                    }
                ]
            });
        }
    }

    async function getPopularItem() {

        if (popularStoresList.length > 0) {
            var popularStoresListnw = [];
            most_popular_item = document.getElementById('most_popular_item');
            
            // Check if element exists (Popular Items section was removed)
            if (!most_popular_item) {
                console.log("Popular Items section not found, skipping...");
                return;
            }
            
            most_popular_item.innerHTML = '';
            var from = 0;
            var total = 0;
            for (let i = 0; i < (popularStoresList.length / 10); i++) {
                from = i * 10;
                popularStoresListnw = [];
                total = 0;
                for (let j = 0; j < popularStoresList.length; j++) {
                    if (j > from && total < 10) {
                        total++;
                        popularStoresListnw.push(popularStoresList[j]);
                    }
                }

                if (popularStoresListnw.length) {
                    var refpopularItem = database.collection('vendor_products').where("vendorID", "in",
                        popularStoresListnw).where('publish', '==', true)
                    refpopularItem.get().then(async function(snapshotsPopularItem) {

                        var trendingStorehtml = await buildHTMLPopularItem(snapshotsPopularItem);
                        if (most_popular_item) {
                            most_popular_item.innerHTML = trendingStorehtml;
                        }
                    });
                }
            }
        }
    }

    async function getMostPopularStores() {
        // Simplified query to avoid complex index requirements
        var popularRestauantRefnew = geoFirestore.collection('vendors')
            .where('zoneId', '==', user_zone_id)
            .where('vType', '==', 'restaurant')
            .limit(4);

        await popularRestauantRefnew.get().then(async function(popularRestauantSnapshot) {
            if (popularRestauantSnapshot.docs.length > 0) {
                console.log("Popular restaurants loaded:", popularRestauantSnapshot.docs.length);
                var most_popular_store = document.getElementById('most_popular_store');
                most_popular_store.innerHTML = '';
                var popularStorehtml = await buildHTMLPopularStore(popularRestauantSnapshot);
                most_popular_store.innerHTML = popularStorehtml;
            } else {
                console.log("No popular restaurants found");
                $(".most-popular-store-section").remove();
            }
        }).catch(function(error) {
            console.error("Error loading popular restaurants:", error);
            // Fallback: try without vType filter
            console.log("Trying fallback query for popular restaurants...");
            geoFirestore.collection('vendors')
                .where('zoneId', '==', user_zone_id)
                .limit(4)
                .get()
                .then(async function(fallbackSnapshot) {
                    if (fallbackSnapshot.docs.length > 0) {
                        console.log("Fallback popular restaurants query successful");
                        let restaurants = [];
                        fallbackSnapshot.docs.forEach((doc) => {
                            const data = doc.data();
                            data.id = doc.id;
                            if (data.vType === 'restaurant') {
                                restaurants.push({ id: doc.id, data: () => data });
                            }
                        });
                        
                        if (restaurants.length > 0) {
                            // Create a mock snapshot object
                            const mockSnapshot = {
                                docs: restaurants.slice(0, 4)
                            };
                            var most_popular_store = document.getElementById('most_popular_store');
                            most_popular_store.innerHTML = '';
                            var popularStorehtml = await buildHTMLPopularStore(mockSnapshot);
                            most_popular_store.innerHTML = popularStorehtml;
                        } else {
                            $(".most-popular-store-section").remove();
                        }
                    }
                })
                .catch(function(fallbackError) {
                    console.error("Fallback popular restaurants query failed:", fallbackError);
                    $(".most-popular-store-section").remove();
                });
        });
    }

    function buildHTMLMostSaleStore(mostSaleSnapshot) {
        var html = '';
        var alldata = [];
        mostSaleSnapshot.docs.forEach((listval) => {
            var datas = listval.data();
            if (!inValidVendors.has(listval.id)) {
                alldata.push(datas);
            }
            var rating = 0;
            var reviewsCount = 0;
            if (datas.hasOwnProperty('reviewsSum') && datas.reviewsSum != 0 && datas.hasOwnProperty(
                'reviewsCount') && datas.reviewsCount != 0) {
                rating = (datas.reviewsSum / datas.reviewsCount);
                rating = Math.round(rating * 10) / 10;
            }
            datas.rating = rating;
            alldata.push(datas);
        });
        if (alldata.length) {
            alldata = sortArrayOfObjects(alldata, "rating");
            alldata = alldata.slice(0, 4);
        }
        html = html + '<div class="row">';
        alldata.forEach((listval) => {
            var val = listval;
            var vendor_id_single = val.id;
            var view_vendor_details = "/restaurant/" + val.id + "/" + val.restaurant_slug + "/" + val.zone_slug;
            var rating = 0;
            var reviewsCount = 0;
            if (val.hasOwnProperty('reviewsSum') && val.reviewsSum != 0 && val.reviewsSum != null && val
                    .reviewsSum != '' && val.hasOwnProperty(
                    'reviewsCount') && val.reviewsCount != 0 && val.reviewsCount != null && val
                    .reviewsCount != '') {
                rating = (val.reviewsSum / val.reviewsCount);
                rating = Math.round(rating * 10) / 10;
                reviewsCount = val.reviewsCount;
            } else {
                if (window.randomizedRatings[val.id]) {
                    rating = window.randomizedRatings[val.id].rating;
                    reviewsCount = window.randomizedRatings[val.id].reviewsCount;
                } else {
                    rating = (Math.random() * (5.0 - 4.1) + 4.1).toFixed(1);
                    reviewsCount = Math.floor(Math.random() * (25 - 11 + 1)) + 11;
                    window.randomizedRatings[val.id] = { rating, reviewsCount };
                }
            }
            // Use failproof status logic
            var status = 'Closed';
            var statusclass = "closed";

            if (window.restaurantStatusManager) {
                const workingHours = val.workingHours || [];
                const isOpen = val.isOpen !== undefined ? val.isOpen : null;
                const isOpenNow = window.restaurantStatusManager.isRestaurantOpenNow(workingHours, isOpen);
                if (isOpenNow) {
                    status = '{{ trans('lang.open') }}';
                    statusclass = "open";
                }
            } else {
                // Fallback to old logic
                var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                var currentdate = new Date();
                var currentDay = days[currentdate.getDay()];
                hour = currentdate.getHours();
                minute = currentdate.getMinutes();
                if (hour < 10) {
                    hour = '0' + hour
                }
                if (minute < 10) {
                    minute = '0' + minute
                }
                var currentHours = hour + ':' + minute;
                if (val.hasOwnProperty('workingHours')) {
                    for (i = 0; i < val.workingHours.length; i++) {
                        var day = val.workingHours[i]['day'];
                        if (val.workingHours[i]['day'] == currentDay) {
                            if (val.workingHours[i]['timeslot'].length != 0) {
                                for (j = 0; j < val.workingHours[i]['timeslot'].length; j++) {
                                    var timeslot = val.workingHours[i]['timeslot'][j];
                                    var from = timeslot[`from`];
                                    var to = timeslot[`to`];
                                    if (currentHours >= from && currentHours <= to) {
                                        status = '{{ trans('lang.open') }}';
                                        statusclass = "open";
                                    }
                                }
                            }
                        }
                    }
                }
            }
            getMinDiscount(val.id);
            html = html + '<div class="col-md-3 pro-list">' +
                '<div class="list-card position-relative">' +
                '<div class="py-2 position-relative">' +
                '<div class="list-card-body">' +
                '<div class="list-card-top">' +
                '<h6 class="mb-1 popul-title"><a href="' + view_vendor_details + '" class="text-black">' + val
                    .title + '</a></h6><h6>' + val.location + '</h6>';
            html = html + '<span class="pro-price vendor_dis_' + val.id + ' " ></span>';
            html = html +
                '<div class="star position-relative mt-3"><span class="badge badge-success "><i class="feather-star"></i>' +
                rating + ' (' + reviewsCount + ')</span></div>';
            html = html + '</div><div class="list-card-image">';
            if (val.photo != "" && val.photo != null) {
                photo = val.photo;
            } else {
                photo = placeholderImageSrc;
            }
            html = html + '<div class="member-plan position-absolute"><span class="badge badge-dark ' +
                statusclass + '">' + status + '</span></div><a href="' + view_vendor_details +
                '"><img onerror="this.onerror=null;this.src=\'' + placeholderImage + '\'" alt="#" src="' +
                photo + '" class="img-fluid item-img w-100"></a></div>';
            html = html + '</div>';
            html = html + '</div></div></div>';
        });
        html = html + '</div>';
        return html;
    }

    async function buildHTMLNewProducts(newProductSnapshot) {
        var html = '';
        var alldata = [];
        newProductSnapshot.docs.forEach((listval) => {
            var datas = listval.data();
            datas.id = listval.id;
            if ($.inArray(datas.vendorID, vendorIds) !== -1) {
                const exists = alldata.some(record => record.vendorID === datas.vendorID);
                if (!exists) {
                    alldata.push(datas);
                }
            }
        });
        alldata = alldata.slice(0, 4);
        html = html + '<div class="row">';
        await Promise.all(alldata.map(async (listval) => {
            var val = listval;
            var vendor_id_single = val.id;
            var view_vendor_details = "/productDetail/" + vendor_id_single;
            // Compute rating and reviews
            let rating = val.reviewsSum && val.reviewsCount ? (val.reviewsSum / val.reviewsCount)
                .toFixed(1) : 0;
            let reviewsCount = val.reviewsCount || 0;
            // Determine veg/non-veg status
            let status = val.veg ? '{{ trans('lang.veg') }}' : '{{ trans('lang.non_veg') }}';
            let statusclass = val.veg ? "open" : "closed";
            // Fallback for image
            let photo = val.photo && val.photo !== "" ? val.photo : placeholderImageSrc;
            // Append product card
            html += `
        <div class="col-md-3 product-list">
            <div class="list-card position-relative">
                <div class="list-card-image">
                    <div class="member-plan position-absolute">
                        <span class="badge badge-dark ${statusclass}">${status}</span>
                    </div>
                    <a href="${view_vendor_details}">
                        <img onerror="this.onerror=null;this.src='${placeholderImage}'" alt="#" src="${photo}" class="img-fluid item-img w-100">
                    </a>
                </div>
                <div class="py-2 position-relative">
                    <div class="list-card-body">
                        <h6 class="mb-1 popul-title">
                            <a href="${view_vendor_details}" class="text-black">${val.name}</a>
                        </h6>
                        <h6 class="text-gray mb-1 cat-title" id="popular_food_category_${val.categoryID}_${val.id}"></h6>
    `;
            // Append price information
            let final_price = priceData[val.id];
            if (val.disPrice && val.disPrice !== '0' && !val.item_attribute) {
                let or_price = getProductFormattedPrice(parseFloat(final_price.price));
                let dis_price = getProductFormattedPrice(parseFloat(final_price.dis_price));
                html += `<h6 class="text-gray mb-1 pro-price">${dis_price}  ${or_price}  </h6>`;
            } else if (val.item_attribute && val.item_attribute.variants?.length > 0) {
                let variantPrices = val.item_attribute.variants.map(v => v.variant_price);
                let minPrice = Math.min(...variantPrices);
                let maxPrice = Math.max(...variantPrices);
                let or_price = minPrice !== maxPrice ?
                    `${getProductFormattedPrice(final_price.min)} - ${getProductFormattedPrice(final_price.max)}` :
                    getProductFormattedPrice(minPrice);
                html += `<h6 class="text-gray mb-1 pro-price">${or_price}</h6>`;
            } else {
                let or_price = getProductFormattedPrice(final_price.price);
                html += `<h6 class="text-gray mb-1 pro-price">${or_price}</h6>`;
            }
            // Append rating information
            html += `
                        <div class="star position-relative mt-3">
                            <span class="badge badge-success"><i class="feather-star"></i>${rating} (${reviewsCount})</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
        }));
        html = html + '</div>';
        return html;
    }

    sortArrayOfObjects = (arr, key) => {
        return arr.sort((a, b) => {
            return b[key] - a[key];
        });
    };

    function copyToClipboard(text) {
        var tempInput = document.createElement("input");
        document.body.appendChild(tempInput);
        tempInput.value = text;
        tempInput.select();
        tempInput.setSelectionRange(0, 99999);
        document.execCommand("copy");
        document.body.removeChild(tempInput);
    }

    function buildHTMLPopularStore(popularRestauantSnapshot) {
        var html = '';
        var alldata = [];
        popularRestauantSnapshot.docs.forEach((listval) => {
            var datas = listval.data();
            checkSelfDeliveryForVendor(datas.id);
            var rating = 0;
            var reviewsCount = 0;
            if (datas.hasOwnProperty('reviewsSum') && datas.reviewsSum != 0 && datas.reviewsSum != null && datas.hasOwnProperty(
                'reviewsCount') && datas.reviewsCount != 0 && datas.reviewsCount != null) {
                rating = (datas.reviewsSum / datas.reviewsCount);
                rating = Math.round(rating * 10) / 10;
                reviewsCount = datas.reviewsCount;
            } else {
                if (window.randomizedRatings[datas.id]) {
                    rating = window.randomizedRatings[datas.id].rating;
                    reviewsCount = window.randomizedRatings[datas.id].reviewsCount;
                } else {
                    rating = (Math.random() * (5.0 - 4.1) + 4.1).toFixed(1);
                    reviewsCount = Math.floor(Math.random() * (25 - 11 + 1)) + 11;
                    window.randomizedRatings[datas.id] = { rating, reviewsCount };
                }
            }
            datas.rating = rating;
            datas.reviewsCount = reviewsCount;
            if (datas.title != '' && !inValidVendors.has(datas.id)) {
                alldata.push(datas);
            }
        });
        if (alldata.length) {
            alldata = sortArrayOfObjects(alldata, "rating");
            alldata = alldata.slice(0, 4);
            var count = 0;
            var popularItemCount = 0;
            html = html + '<div class="row">';
            alldata.forEach((listval) => {
                var val = listval;
                var rating = 0;
                var reviewsCount = 0;
                if (val.hasOwnProperty('reviewsSum') && val.reviewsSum != 0 && val.reviewsSum != null && val
                    .reviewsSum != '' && val.hasOwnProperty(
                    'reviewsCount') && val.reviewsCount != 0 && val.reviewsCount != null && val
                    .reviewsCount != '') {
                    rating = (val.reviewsSum / val.reviewsCount);
                    rating = Math.round(rating * 10) / 10;
                    reviewsCount = val.reviewsCount;
                } else {
                    if (window.randomizedRatings[val.id]) {
                        rating = window.randomizedRatings[val.id].rating;
                        reviewsCount = window.randomizedRatings[val.id].reviewsCount;
                    } else {
                        rating = (Math.random() * (5.0 - 4.1) + 4.1).toFixed(1);
                        reviewsCount = Math.floor(Math.random() * (25 - 11 + 1)) + 11;
                        window.randomizedRatings[val.id] = { rating, reviewsCount };
                    }
                }
                if (popularItemCount < 10) {
                    popularItemCount++;
                    popularStoresList.push(val.id);
                }
                // Use failproof status logic
                var status = 'Closed';
                var statusclass = "closed";

                if (window.restaurantStatusManager) {
                    const workingHours = val.workingHours || [];
                    const isOpen = val.isOpen !== undefined ? val.isOpen : null;
                    const isOpenNow = window.restaurantStatusManager.isRestaurantOpenNow(workingHours, isOpen);
                    if (isOpenNow) {
                        status = '{{ trans('lang.open') }}';
                        statusclass = "open";
                    }
                } else {
                    // Fallback to old logic
                    var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                    var currentdate = new Date();
                    var currentDay = days[currentdate.getDay()];
                    hour = currentdate.getHours();
                    minute = currentdate.getMinutes();
                    if (hour < 10) {
                        hour = '0' + hour
                    }
                    if (minute < 10) {
                        minute = '0' + minute
                    }
                    var currentHours = hour + ':' + minute;
                    if (val.hasOwnProperty('workingHours')) {
                        for (i = 0; i < val.workingHours.length; i++) {
                            var day = val.workingHours[i]['day'];
                            if (val.workingHours[i]['day'] == currentDay) {
                                if (val.workingHours[i]['timeslot'].length != 0) {
                                    for (j = 0; j < val.workingHours[i]['timeslot'].length; j++) {
                                        var timeslot = val.workingHours[i]['timeslot'][j];
                                        var from = timeslot[`from`];
                                        var to = timeslot[`to`];
                                        if (currentHours >= from && currentHours <= to) {
                                            status = '{{ trans('lang.open') }}';
                                            statusclass = "open";
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                var vendor_id_single = val.id;
                var view_vendor_details = "/restaurant/" + vendor_id_single + "/" + val.restaurant_slug + "/" + val.zone_slug;
                count++;
                getMinDiscount(val.id);
                html = html +
                    '<div class="col-md-3 product-list"><div class="list-card position-relative"><div class="list-card-image"><span class="discount-price vendor_dis_' +
                    val.id + ' " ></span>';
                if (val.photo != "" && val.photo != null) {
                    photo = val.photo;
                } else {
                    photo = placeholderImageSrc;
                }
                html = html + '<div class="member-plan position-absolute"><span class="badge badge-dark ' +
                    statusclass + '">' + status + '</span></div><div class="offer-icon position-absolute free-delivery-' + val.id + '"></div><a href="' + view_vendor_details +
                    '"><img onerror="this.onerror=null;this.src=\'' + placeholderImage + '\'" alt="#" src="' +
                    photo +
                    '" class="img-fluid item-img w-100"></a></div><div class="py-2 position-relative"><div class="list-card-body position-relative"><h6 class="mb-1 popul-title"><a href="' +
                    view_vendor_details + '" class="text-black">' + val.title +
                    '</a></h6><p class="text-gray mb-1 small address"><span class="fa fa-map-marker"></span>' +
                    val.location + '</p>';
                html = html +
                    '<div class="star position-relative mt-3"><span class="badge badge-success "><i class="feather-star"></i>' +
                    rating + ' (' + reviewsCount + ')</span></div>';
                html = html + '</div>';
                html = html + '</div></div></div>';
            });
            html = html + '</div>';
        } else {
            html = '<p class="text-danger text-center">{{ trans('lang.no_results') }}</p>';
        }
        getPopularItem();
        getCouponsList();
        return html;
    }

    async function buildHTMLPopularItem(popularItemsnapshot) {
        var html = '';
        var alldata = [];
        let sortedAndMergedData = [];
        var groupedData = {};
        popularItemsnapshot.docs.forEach((listval) => {
            var datas = listval.data();
            var rating = 0;
            var reviewsCount = 0;
            if (datas.hasOwnProperty('reviewsSum') && datas.reviewsSum != 0 && datas.reviewsSum != null && datas.hasOwnProperty(
                'reviewsCount') && datas.reviewsCount != 0 && datas.reviewsCount != null) {
                rating = (datas.reviewsSum / datas.reviewsCount);
                rating = Math.round(rating * 10) / 10;
            }
            datas.rating = rating;


            if (subscriptionModel == true || subscriptionModel == "true") {

                if (!groupedData[datas.vendorID]) {
                    groupedData[datas.vendorID] = [];
                }
                groupedData[datas.vendorID].push(datas);
            } else {

                alldata.push(datas);
            }
        });

        if (subscriptionModel == true || subscriptionModel == "true") {
            await Promise.all(Object.keys(groupedData).map(async (vendorID) => {
                let products = groupedData[vendorID];

                var vendorItemLimit = await getVendorItemLimit(vendorID);
                await products.sort((a, b) => {
                    if (a.hasOwnProperty('createdAt') && b.hasOwnProperty('createdAt')) {
                        const timeA = new Date(a.createdAt.toDate()).getTime();
                        const timeB = new Date(b.createdAt.toDate()).getTime();
                        return timeA - timeB; // Ascending order
                    }
                });

                if (parseInt(vendorItemLimit) != -1) {
                    products = products.slice(0, vendorItemLimit);
                }

                sortedAndMergedData = sortedAndMergedData.concat(products);
            }));

            sortedAndMergedData = sortArrayOfObjects(sortedAndMergedData, "rating");
            alldata = sortedAndMergedData.slice(0, 5);
        } else {
            alldata = sortArrayOfObjects(alldata, "rating");

            alldata = alldata.slice(0, 5);
        }
        var count = 1;
        html += '<div class="row">';
        await Promise.all(alldata.map(async (listval, index) => {
            //if(index>=5) return; // Limit to 5 items
            let val = listval;


            let vendor_id_single = val.id;
            let view_vendor_details = "/productDetail/" + vendor_id_single;
            // Compute rating and reviews
            let rating = val.reviewsSum && val.reviewsCount ? (val.reviewsSum / val.reviewsCount)
                .toFixed(1) : 0;
            let reviewsCount = val.reviewsCount || 0;
            // Determine veg/non-veg status
            let status = val.veg ? '{{ trans('lang.veg') }}' : '{{ trans('lang.non_veg') }}';
            let statusclass = val.veg ? "open" : "closed";
            // Fallback for image
            let photo = val.photo && val.photo !== "" ? val.photo : placeholderImageSrc;
            // Append product card
            html += `
        <div class="col-md-3 product-list">
            <div class="list-card position-relative">
                <div class="list-card-image">
                    <div class="member-plan position-absolute">
                        <span class="badge badge-dark ${statusclass}">${status}</span>
                    </div>
                    <a href="${view_vendor_details}">
                        <img onerror="this.onerror=null;this.src='${placeholderImage}'" alt="#" src="${photo}" class="img-fluid item-img w-100">
                    </a>
                </div>
                <div class="py-2 position-relative">
                    <div class="list-card-body">
                        <h6 class="mb-1 popul-title">
                            <a href="${view_vendor_details}" class="text-black">${val.name}</a>
                        </h6>
                        <h6 class="text-gray mb-1 cat-title" id="popular_food_category_${val.categoryID}_${val.id}"></h6>
    `;
            // Append price information
            let final_price = priceData[val.id];
            if (val.disPrice && val.disPrice !== '0' && !val.item_attribute) {
                let or_price = getProductFormattedPrice(parseFloat(final_price.price));
                let dis_price = getProductFormattedPrice(parseFloat(final_price.dis_price));
                html += `<h6 class="text-gray mb-1 pro-price">${dis_price}  ${or_price}  </h6>`;
            } else if (val.item_attribute && val.item_attribute.variants?.length > 0) {
                let variantPrices = val.item_attribute.variants.map(v => v.variant_price);
                let minPrice = Math.min(...variantPrices);
                let maxPrice = Math.max(...variantPrices);
                let or_price = minPrice !== maxPrice ?
                    `${getProductFormattedPrice(final_price.min)} - ${getProductFormattedPrice(final_price.max)}` :
                    getProductFormattedPrice(minPrice);
                html += `<h6 class="text-gray mb-1 pro-price">${or_price}</h6>`;
            } else {
                let or_price = getProductFormattedPrice(final_price.price);
                html += `<h6 class="text-gray mb-1 pro-price">${or_price}</h6>`;
            }
            // Append rating information
            html += `
                        <div class="star position-relative mt-3">
                            <span class="badge badge-success"><i class="feather-star"></i>${rating} (${reviewsCount})</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
        }));
        html += '</div>';
        return html;
    }

    async function popularItemCategory(categoryId, foodId) {
        var popularItemCategory = '';
        await database.collection('vendor_categories').where("id", "==", categoryId).get().then(async function(
            categorySnapshots) {
            if (categorySnapshots.docs[0]) {
                var categoryData = categorySnapshots.docs[0].data();
                popularItemCategory = categoryData.title;
                jQuery("#popular_food_category_" + categoryId + "_" + foodId).text(popularItemCategory);
            }
        });
        return popularItemCategory;
    }

    async function getMinDiscount(vendorId) {
        var min_discount = '';
        var disdata = [];
        var couponSnapshots = await couponsRef.where('resturant_id', '==', vendorId).get();
        if (couponSnapshots.docs.length > 0) {
            couponSnapshots.docs.forEach((coupon) => {
                var cdata = coupon.data();
                disdata.push(parseInt(cdata.discount));
            });
            if (disdata.length > 0) {
                discount = Math.min.apply(Math, disdata);
                min_discount = "Min " + discount + "% off";
            }
        }
        if (min_discount) {
            $('.vendor_dis_' + vendorId).text(min_discount);
        } else {
            $('.vendor_dis_' + vendorId).hide();
        }
    }

    async function getCouponsList() {
        if (popularStoresList.length > 0) {
            var popularStoresList2 = popularStoresList.slice(0, 4);
            var couponsRef2 = database.collection('coupons').where('resturant_id', 'in', popularStoresList2).where(
                'isEnabled', '==', true).where('isPublic', '==', true).where('expiresAt', '>=', new Date());
            couponsRef2.get().then(async function(couponListSnapshot) {
                if (couponListSnapshot.docs.length > 0) {
                    offers_coupons = document.getElementById('offers_coupons');
                    
                    // Check if element exists (Offers & Coupons section was removed)
                    if (!offers_coupons) {
                        console.log("Offers & Coupons section not found, skipping...");
                        return;
                    }
                    
                    offers_coupons.innerHTML = '';
                    var couponlistHTML = buildHTMLCouponList(couponListSnapshot);
                    offers_coupons.innerHTML = couponlistHTML;
                }
            })
        }
    }

    function buildHTMLCouponList(couponListSnapshot) {
        var html = '';
        var alldata = [];
        couponListSnapshot.docs.forEach((listval) => {
            var datas = listval.data();
            datas.id = listval.id;
            alldata.push(datas);
        });
        if (alldata.length > 0) {
            html = html + '<div class="row">';
            alldata.forEach((listval) => {
                var val = listval;
                var status = '{{ trans('lang.closed') }}';
                var statusclass = "closed";
                if (val.hasOwnProperty('reststatus') && val.reststatus) {
                    status = '{{ trans('lang.open') }}';
                    statusclass = "open";
                }
                var vendor_id_single = val.resturant_id;
                var view_vendor_details = "/restaurant/" + vendor_id_single + "/" + val.restaurant_slug + "/" + val.zone_slug;
                html = html +
                    '<div class="col-md-3 pro-list"><div class="list-card position-relative"><div class="list-card-image">';
                if (val.image != "" && val.image != null) {
                    photo = val.image;
                } else {
                    photo = placeholderImageSrc;
                }
                getVendorName(vendor_id_single);
                html = html + '<a href="' + view_vendor_details +
                    '"><img onerror="this.onerror=null;this.src=\'' + placeholderImage + '\'" alt="#" src="' +
                    photo +
                    '" class="img-fluid item-img w-100"></a></div><div class="py-2 position-relative"><div class="list-card-body"><h6 class="mb-1 popul-title"><a href="' +
                    view_vendor_details + '" class="text-black vendor_title_' + vendor_id_single +
                    '"></a></h6>';
                html = html +
                    '<div class="text-gray mb-1 small offer-code"><a href="javascript:void(0)" onclick="copyToClipboard(`' +
                    val.code + '`)"><i class="fa fa-file-text-o"></i> ' + val.code + '</a></div>';
                html = html + '</div>';
                html = html + '</div></div></div>';
            });
            html = html + '</div>';
        }
        return html;
    }

    async function getVendorName(vendorId) {
        await database.collection('vendors').where("id", "==", vendorId).get().then(async function(
            categorySnapshots) {
            if (categorySnapshots.docs[0]) {
                var categoryData = categorySnapshots.docs[0].data();
                vendorName = categoryData.title;
                jQuery(".vendor_title_" + vendorId).text(vendorName);
            }
        });
    }

    async function getVendorItemLimit(vendorID) {
        var itemLimit = 0;
        await database.collection('vendors').where('id', '==', vendorID).get().then(async function(snapshots) {
            if (snapshots.docs.length > 0) {
                var data = snapshots.docs[0].data();
                if (data.hasOwnProperty('subscription_plan') && data.subscription_plan != null && data.subscription_plan != '') {
                    itemLimit = data.subscription_plan.itemLimit;
                }
            }
        })
        return itemLimit;
    }


    async function checkFavVendor(vendorId) {
        var user_id = user_uuid;
        database.collection('favorite_restaurant').where('restaurant_id', '==', vendorId).where('user_id', '==', user_id).get().then(async function(favoritevendorsnapshots) {
            if (favoritevendorsnapshots.docs.length > 0) {
                $('.addToFavorite[id="' + vendorId + '"]').html(
                    '<i class="font-weight-bold fa fa-heart" style="color:red"></i>');
            } else {
                $('.addToFavorite[id="' + vendorId + '"]').html('<i class="font-weight-bold feather-heart" ></i>');
            }
        });
    }
    $(document).on('click', '.loginAlert', function() {
        Swal.fire({
            text: "{{ trans('lang.login_to_favorite') }}",
            icon: "error"
        });
    });

    $(document).on('click', '.addToFavorite', function() {

        var user_id = user_uuid;
        var vendorId = this.id;
        database.collection('favorite_restaurant').where('restaurant_id', '==', vendorId).where(
            'user_id', '==', user_id).get().then(async function(favoritevendorsnapshots) {
            if (favoritevendorsnapshots.docs.length > 0) {
                var id = favoritevendorsnapshots.docs[0].id;
                database.collection('favorite_restaurant').doc(id).delete().then(
                    function() {
                        $('.addToFavorite[id="' + vendorId + '"]').html(
                            '<i class="font-weight-bold feather-heart" ></i>'
                        );
                    });
            } else {
                var id = database.collection('tmp').doc().id;
                database.collection('favorite_restaurant').doc(id).set({
                    'restaurant_id': vendorId,
                    'user_id': user_id
                }).then(function(result) {
                    $('.addToFavorite[id="' + vendorId + '"]').html(
                        '<i class="font-weight-bold fa fa-heart" style="color:red"></i>'
                    );
                });
            }
        });
    });

    // Preserve existing checkSelfDeliveryForVendor function
    function checkSelfDeliveryForVendor(vendorId) {
        setTimeout(function() {
            database.collection('vendors').doc(vendorId).get().then(async function(snapshots) {
                if (snapshots.exists) {
                    var data = snapshots.data();
                    if (data.hasOwnProperty('isSelfDelivery') && data.isSelfDelivery != null && data.isSelfDelivery != '') {
                        if (data.isSelfDelivery && isSelfDeliveryGlobally) {
                            $('.free-delivery-' + vendorId).html('<span><img src="{{ asset('img/free_delivery.png') }}" width="100px"> {{trans("lang.free_delivery")}}</span> ');
                        }
                    }
                }
            })
        }, 3000);
    }






    // Function to calculate distance between two points using Haversine formula
    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371; // Earth's radius in kilometers
        const dLat = toRad(lat2 - lat1);
        const dLon = toRad(lon2 - lon1);
        const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
            Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
            Math.sin(dLon/2) * Math.sin(dLon/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        const distance = R * c;
        return distance;
    }

    function toRad(degrees) {
        return degrees * (Math.PI/180);
    }





    window.randomizedRatings = {};

    // ==================== LOAD MORE FUNCTIONS ====================

    var currentDisplayCount = 12; // Show 12 restaurants initially
    var loadMoreStep = 12; // Load 12 more each time

    // Function to display restaurants with load more functionality
    function displayRestaurants() {
        console.log("Displaying restaurants. Total vendors:", allVendorsData.length);
        console.log("Current display count:", currentDisplayCount);
        
        // Since we're already filtering restaurants in the query, just use allVendorsData directly
        const restaurants = allVendorsData; // Already filtered to restaurants only
        const displayData = restaurants.slice(0, currentDisplayCount);
        
        console.log("Displaying restaurants:", displayData.length);
        console.log("Remaining restaurants:", restaurants.length - currentDisplayCount);
        
        const html = buildAllStoresHTMLFromArray(displayData);
        $('#all_stores').html(html);

        // Update delivery badges and distance information
        displayData.forEach(vendor => {
            const deliveryStatus = vendorDeliveryCache.get(vendor.id);
            if (deliveryStatus?.hasFreeSelfDelivery) {
                $('.free-delivery-' + vendor.id).html('<span><img src="{{ asset('img/free_delivery.png') }}" width="100px"> {{trans("lang.free_delivery")}}</span>');
            }

            if (vendor.hasOwnProperty('distance')) {
                const distanceText = radiusUnit === 'miles'
                    ? (vendor.distance / 1.60934).toFixed(1) + ' mi'
                    : vendor.distance.toFixed(1) + ' km';
                $('.vendor-distance-' + vendor.id).text(distanceText);
            }
        });

        // Show/hide load more button based on remaining restaurants
        const remainingCount = restaurants.length - currentDisplayCount;
        console.log("Remaining restaurants for load more:", remainingCount);
        
        if (remainingCount > 0) {
            $('#loadmore').show();
            console.log("Showing load more button");
        } else {
            $('#loadmore').hide();
            console.log("Hiding load more button - no more restaurants");
        }
    }

    // Function to load more restaurants
    function loadMoreRestaurants() {
        console.log("Load more clicked. Current count:", currentDisplayCount);
        console.log("Total available restaurants:", allVendorsData.length);
        
        // Since we're already filtering restaurants in the query, just use allVendorsData directly
        const restaurants = allVendorsData; // Already filtered to restaurants only
        const remainingCount = restaurants.length - currentDisplayCount;
        
        console.log("Remaining restaurants:", remainingCount);
        
        if (remainingCount > 0) {
            currentDisplayCount += Math.min(loadMoreStep, remainingCount);
            console.log("New display count:", currentDisplayCount);
            displayRestaurants();
        } else {
            console.log("No more restaurants to load");
            $('#loadmore').hide();
        }
    }

    // Function to calculate distance between two points
    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371; // Radius of the Earth in kilometers
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLon/2) * Math.sin(dLon/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return R * c;
    }

    // Performance optimization: Image preloading and caching
    const imageCache = new Map();
    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                const src = img.dataset.src;
                if (src && !img.src) {
                    img.src = src;
                    img.removeAttribute('data-src');
                    imageObserver.unobserve(img);
                }
            }
        });
    }, {
        rootMargin: '50px 0px',
        threshold: 0.1
    });

    // Function to preload critical images
    function preloadCriticalImages() {
        const criticalImages = [
            placeholderImage,
            // Add other critical images here
        ];

        criticalImages.forEach(src => {
            if (src && !imageCache.has(src)) {
                const img = new Image();
                img.onload = () => imageCache.set(src, true);
                img.src = src;
            }
        });
    }

    // Enhanced lazy loading for images
    function setupLazyLoading() {
        const images = document.querySelectorAll('img[data-src]');
        images.forEach(img => imageObserver.observe(img));
    }

    // Call preload function when page loads
    document.addEventListener('DOMContentLoaded', () => {
        preloadCriticalImages();
        setupLazyLoading();
    });

</script>
@include('layouts.nav')
