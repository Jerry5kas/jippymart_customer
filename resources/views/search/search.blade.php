@include('layouts.app')
@include('layouts.header')
<div class="d-none">
    <div class="bg-primary p-3 d-flex align-items-center">
        <a class="toggle togglew toggle-2" href="#"><span></span></a>
        <h4 class="font-weight-bold m-0 text-white">{{ trans('lang.search') }}</h4>
    </div>
</div>
<div class="siddhi-popular">
    <div class="container">
        <div class="search py-5">
            <!-- Enhanced Search Bar -->
            <div class="input-group mb-4">
                <input type="text" class="form-control form-control-lg input_search border-right-0 food_search"
                       id="inlineFormInputGroup" value="{{ request('q', '') }}"
                       placeholder="{{ trans('lang.search_product_items') }}" autocomplete="off">
                <div class="input-group-prepend">
                    <div class="btn input-group-text bg-white border_search border-left-0 text-primary search_food_btn">
                        <i class="feather-search"></i>
                    </div>
                </div>
            </div>

            <!-- Popular Searches Section -->
            <div class="popular-searches-section mb-4" id="popular-searches">
                <h5 class="mb-3 text-dark font-weight-bold">
                    <i class="feather-trending-up mr-2 text-primary"></i>Popular Searches
                </h5>
                <div class="popular-tags">
                    <span class="popular-tag" data-search="pizza">🍕 Pizza</span>
                    <span class="popular-tag" data-search="burger">🍔 Burger</span>
                    <span class="popular-tag" data-search="pasta">🍝 Pasta</span>
                    <span class="popular-tag" data-search="soup">🍣 Soup</span>
                    <span class="popular-tag" data-search="chicken Biryani">🍗 Chicken Biryani</span>
                    <span class="popular-tag" data-search="salad">🥗 Salad</span>
                    <span class="popular-tag" data-search="dessert">🍰 Dessert</span>
                    <span class="popular-tag" data-search="coffee">☕ Coffee</span>
                </div>
            </div>

            <!-- Search Suggestions -->
            <div id="search-suggestions" class="search-suggestions mb-4" style="display: none;">
                <h5 class="mb-3 text-dark font-weight-bold">
                    <i class="feather-lightbulb mr-2 text-warning"></i>Suggestions
                </h5>
                <div id="suggestions-list" class="suggestions-list"></div>
            </div>

            <!-- Loading State -->
            <div id="search-loading" class="text-center py-4" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Searching...</span>
                </div>
                <p class="mt-2 text-muted">Searching for delicious food...</p>
            </div>

            <!-- Search Results Tabs -->
            <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active border-0 bg-light text-dark rounded" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                        <i class="feather-home mr-2"></i><span class="restaurant_counts"></span>
                    </a>
                </li>
            </ul>

            <!-- No Results Message-->
            <div class="text-center py-5 not_found_div" style="display:none">
                <p class="h4 mb-4"><i class="feather-search bg-primary rounded p-2"></i></p>
                <p class="font-weight-bold text-dark h5">{{ trans('lang.nothing_found') }}</p>
                <p>{{ trans('lang.please_try_again') }}</p>
                <div class="mt-3">
                    <h6 class="text-muted mb-2">Try these popular searches:</h6>
                    <div class="popular-tags">
                        <span class="popular-tag" data-search="pizza">Pizza</span>
                        <span class="popular-tag" data-search="burger">Burger</span>
                        <span class="popular-tag" data-search="pasta">Pasta</span>
                    </div>
                </div>
            </div>

            <!-- Search Results Content -->
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="container mt-4 mb-4 p-0">
                        <div id="append_list1" class="res-search-list-1"></div>

                        <!-- Pagination Controls -->
                        <div class="pagination-wrapper mt-4" id="pagination-wrapper" style="display: none;">
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="pagination-info">
                                            <span id="pagination-info">Showing 0 of 0 restaurants</span>
                                        </div>
                                        <div class="pagination-controls">
                                            <button type="button" id="prev-page" class="btn btn-outline-dark btn-sm" disabled>
                                                <i class="feather-chevron-left"></i> Previous
                                            </button>
                                            <span class="mx-3">
                                                Page <span id="current-page">1</span> of <span id="total-pages">1</span>
                                            </span>
                                            <button type="button" id="next-page" class="btn btn-outline-dark btn-sm">
                                                Next <i class="feather-chevron-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Tab -->
            <ul class="nav nav-tabs border-0 d-none" id="myTab2" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active border-0 bg-light text-dark rounded" id="products-tab" data-toggle="tab" href="#products" role="tab" aria-controls="products" aria-selected="true">
                        <i class="feather-shopping-bag mr-2"></i><span class="products_counts"></span>
                    </a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent2">
                <div class="tab-pane fade show active" id="products" role="tabpanel" aria-labelledby="products-tab">
                    <div class="container mt-4 mb-4 p-0">
                        <div id="append_list2" class="res-search-list-1"></div>

                        <!-- Products Pagination Controls -->
                        <div class="pagination-wrapper mt-4" id="products-pagination-wrapper" style="display: none;">
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="pagination-info">
                                            <span id="products-pagination-info">Showing 0 of 0 products</span>
                                        </div>
                                        <div class="pagination-controls">
                                            <button type="button" id="products-prev-page" class="btn btn-outline-dark btn-sm" disabled>
                                                <i class="feather-chevron-left"></i> Previous
                                            </button>
                                            <span class="mx-3">
                                                Page <span id="products-current-page">1</span> of <span id="products-total-pages">1</span>
                                            </span>
                                            <button type="button" id="products-next-page" class="btn btn-outline-dark btn-sm">
                                                Next <i class="feather-chevron-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search Page Styles -->
<style>
.popular-searches-section {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 20px;
    border: 1px solid #e9ecef;
}

.popular-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.popular-tag {
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 25px;
    padding: 8px 16px;
    font-size: 14px;
    font-weight: 500;
    color: #495057;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.popular-tag:hover {
    background: #007bff;
    color: white;
    border-color: #007bff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
}

.search-suggestions {
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    border-radius: 12px;
    padding: 20px;
}

.suggestions-list {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.suggestion-item {
    background: white;
    border: 1px solid #ffeaa7;
    border-radius: 20px;
    padding: 6px 12px;
    font-size: 13px;
    color: #856404;
    cursor: pointer;
    transition: all 0.2s ease;
}

.suggestion-item:hover {
    background: #856404;
    color: white;
    border-color: #856404;
}

.search-loading {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 12px;
    padding: 40px;
}

/* Enhanced search results styling */
.list-card {
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
}

.list-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    border-color: #007bff;
}

.list-card-image {
    position: relative;
    overflow: hidden;
}

.list-card-image img {
    transition: transform 0.3s ease;
}

.list-card:hover .list-card-image img {
    transform: scale(1.05);
}

 /* Responsive design */
 @media (max-width: 768px) {
     .popular-tags {
         gap: 8px;
     }

     .popular-tag {
         font-size: 13px;
         padding: 6px 12px;
     }

     .search-suggestions {
         padding: 15px;
     }

     .product-list {
         margin-bottom: 20px;
     }
 }

 /* Enhanced search input styling */
 .input_search {
     border-radius: 25px 0 0 25px !important;
     border: 2px solid #e9ecef;
     transition: all 0.3s ease;
 }

 .input_search:focus {
     border-color: #007bff;
     box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
 }

 .border_search {
     border-radius: 0 25px 25px 0 !important;
     border: 2px solid #e9ecef;
     border-left: none;
     transition: all 0.3s ease;
 }

 .input_search:focus + .input-group-prepend .border_search {
     border-color: #007bff;
 }

 /* Product card enhancements */
 .product-list .list-card {
     border-radius: 12px;
     overflow: hidden;
     margin-bottom: 20px;
 }

 .product-list .list-card-body {
     padding: 15px;
 }

 .product-list .arv-title {
     color: #333;
     font-weight: 600;
     text-decoration: none;
     transition: color 0.3s ease;
 }

 .product-list .arv-title:hover {
     color: #007bff;
     text-decoration: none;
 }

 .product-list .pro-price {
     font-weight: 600;
     color: #28a745;
     font-size: 16px;
 }

 /* Loading animation */
 .spinner-border {
     width: 3rem;
     height: 3rem;
 }

 /* Pagination Styles - Updated for white background and black text */
 .pagination-wrapper {
     background: #ffffff;
     padding: 20px;
     border-radius: 8px;
     box-shadow: 0 2px 8px rgba(0,0,0,0.1);
     margin-top: 30px;
     border: 1px solid #e9ecef;
 }

 .pagination-controls {
     display: flex;
     align-items: center;
     gap: 10px;
 }

 .pagination-controls button {
     min-width: 100px;
     padding: 8px 16px;
     border-radius: 6px;
     font-weight: 500;
     transition: all 0.3s ease;
     background: #ffffff;
     color: #000000;
     border: 2px solid #000000;
 }

 .pagination-controls button:hover:not(:disabled) {
     background: #000000;
     color: #ffffff;
     transform: translateY(-1px);
     box-shadow: 0 4px 8px rgba(0,0,0,0.15);
 }

 .pagination-controls button:disabled {
     opacity: 0.5;
     cursor: not-allowed;
     background: #f8f9fa;
     color: #6c757d;
     border-color: #dee2e6;
 }

 .pagination-info {
     font-size: 14px;
     color: #000000;
     font-weight: 500;
 }

 /* Mobile Responsive Pagination */
 @media (max-width: 768px) {
     .pagination-wrapper {
         padding: 15px;
     }

     .pagination-controls {
         flex-direction: column;
         gap: 15px;
     }

     .pagination-controls button {
         width: 100%;
         min-width: unset;
     }

     .pagination-info {
         text-align: center;
         margin-bottom: 10px;
     }
 }

 /* Dark Mode Support for Pagination */
 @media (prefers-color-scheme: dark) {
     .pagination-wrapper {
         background: #ffffff;
         box-shadow: 0 2px 8px rgba(0,0,0,0.2);
         border-color: #e9ecef;
     }

     .pagination-info {
         color: #000000;
     }

     .pagination-controls button {
         background: #ffffff;
         color: #000000;
         border-color: #000000;
     }

     .pagination-controls button:hover:not(:disabled) {
         background: #000000;
         color: #ffffff;
     }
 }

 /* Unified Restaurant Card Styles - Same as home.blade.php */
 #append_list1 {
     display: grid;
     grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
     gap: 1.5rem;
     margin-top: 1rem;
 }

 .restaurant-card {
     background: #fff;
     border-radius: 16px;
     overflow: hidden;
     box-shadow: 0 4px 12px rgba(0,0,0,0.1);
     transition: all 0.3s ease;
     cursor: pointer;
 }

 .restaurant-card:hover {
     transform: translateY(-4px);
     box-shadow: 0 8px 25px rgba(0,0,0,0.15);
 }

 .restaurant-card:active {
     transform: translateY(-2px);
 }

 .restaurant-card.card-clicked {
     transform: scale(0.98);
     box-shadow: 0 2px 8px rgba(0,0,0,0.2);
 }

 .restaurant-image {
     position: relative;
     height: 200px;
     overflow: hidden;
 }

 .restaurant-image img {
     width: 100%;
     height: 100%;
     object-fit: cover;
     transition: transform 0.3s ease;
 }

 .restaurant-card:hover .restaurant-image img {
     transform: scale(1.05);
 }

 .restaurant-status {
     position: absolute;
     top: 12px;
     left: 12px;
     padding: 8px 16px;
     border-radius: 20px;
     font-size: 0.8rem;
     font-weight: 700;
     text-transform: uppercase;
     color: white;
     border: 2px solid rgba(255,255,255,0.3);
     backdrop-filter: blur(10px);
 }

 .restaurant-status.open {
     background: linear-gradient(135deg, #28a745, #20c997);
     border-color: rgba(255,255,255,0.2);
 }

 .restaurant-status.closed {
     background: linear-gradient(135deg, #dc3545, #c82333);
     border-color: rgba(255,255,255,0.2);
 }

 .restaurant-card:hover .restaurant-status {
     transform: scale(1.05);
     box-shadow: 0 4px 12px rgba(0,0,0,0.2);
 }

 .restaurant-card:hover .restaurant-status.open {
     background: linear-gradient(135deg, #20c997, #28a745);
 }

 .restaurant-card:hover .restaurant-status.closed {
     background: linear-gradient(135deg, #c82333, #dc3545);
 }

 .distance {
     position: absolute;
     bottom: 12px;
     right: 12px;
     background: rgba(0,0,0,0.8);
     color: white;
     padding: 6px 12px;
     border-radius: 15px;
     font-size: 0.8rem;
     font-weight: 600;
 }

 .restaurant-info {
     padding: 20px;
 }

 .restaurant-title {
     margin: 0 0 12px 0;
     font-size: 1.2rem;
     font-weight: 700;
     color: #2c3e50;
 }

 .restaurant-title a {
     color: inherit;
     text-decoration: none;
     transition: color 0.3s ease;
 }

 .restaurant-title a:hover {
     color: #3498db;
 }

 .restaurant-location {
     display: flex;
     align-items: center;
     margin-bottom: 15px;
     color: #7f8c8d;
     font-size: 0.9rem;
     cursor: pointer;
     transition: color 0.3s ease;
 }

 .restaurant-location:hover {
     color: #3498db;
 }

 .restaurant-location:hover .location-icon svg path {
     fill: #3498db;
 }

 .location-icon {
     margin-right: 8px;
     flex-shrink: 0;
 }

 .location-icon svg path {
     fill: #95a5a6;
     transition: fill 0.3s ease;
 }

 .location-text {
     flex: 1;
     overflow: hidden;
     text-overflow: ellipsis;
     white-space: nowrap;
 }

 .restaurant-rating {
     display: flex;
     align-items: center;
     justify-content: start;
     gap: 12px;
 }

 .rating-stars {
     display: flex;
     align-items: center;
     gap: 4px;
 }

 .star-icon {
     color: #f39c12;
     font-size: 1.1rem;
 }

.rating-value {
    font-weight: 700;
    /*color: #2c3e50;*/
    color: white;
    font-size: 1rem;
    background-color: #f39c12;
    padding: 3px 4px;
    border-radius: 10px;
}

 .rating-badges {
     display: flex;
     gap: 8px;
 }

 .rating-badge {
     display: flex;
     align-items: center;
     gap: 6px;
     background: linear-gradient(135deg, #27ae60, #2ecc71);
     color: white;
     padding: 8px 12px;
     border-radius: 20px;
     font-size: 0.8rem;
     font-weight: 600;
     cursor: pointer;
     transition: all 0.3s ease;
     position: relative;
     overflow: hidden;
 }

 .rating-badge::before {
     content: '';
     position: absolute;
     top: 0;
     left: -100%;
     width: 100%;
     height: 100%;
     background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
     transition: left 0.5s ease;
 }

 .rating-badge:hover::before {
     left: 100%;
 }

 .rating-badge:hover {
     transform: scale(1.05);
     box-shadow: 0 4px 12px rgba(39, 174, 96, 0.3);
 }

 .rating-badge:active {
     transform: scale(0.95);
 }

 .rating-badge.badge-clicked {
     transform: scale(0.9);
     background: linear-gradient(135deg, #229954, #27ae60);
 }

 .badge-icon {
     flex-shrink: 0;
 }

 .badge-icon svg path {
     fill: white;
     transition: fill 0.3s ease;
 }

 .badge-icon:hover {
     transform: scale(1.1);
 }

 .badge-text {
     font-weight: 700;
 }

 /* Grid responsive adjustments */
 @media (max-width: 768px) {
     #append_list1 {
         grid-template-columns: 1fr;
         gap: 1rem;
     }
 }

 @media (min-width: 769px) and (max-width: 1024px) {
     #append_list1 {
         grid-template-columns: repeat(2, 1fr);
     }
 }

 @media (min-width: 1025px) {
     #append_list1 {
         grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
     }
 }
</style>

@include('layouts.footer')
@include('layouts.nav')
<script type="text/javascript">
    jQuery("#data-table_processing").show();
    var currentCurrency = '';
    var currencyAtRight = false;
    var placeholderImage = '';
    var placeholder = database.collection('settings').doc('placeHolderImage');
    placeholder.get().then(async function(snapshotsimage) {
        var placeholderImageData = snapshotsimage.data();
        placeholderImage = placeholderImageData.image;
    })
    var currentDate = new Date();
    var inValidVendors = new Set();
    var decimal_degits = 0;
    var refCurrency = database.collection('currencies').where('isActive', '==', true);
    refCurrency.get().then(async function(snapshots) {
        var currencyData = snapshots.docs[0].data();
        currentCurrency = currencyData.symbol;
        currencyAtRight = currencyData.symbolAtRight;
        if (currencyData.decimal_degits) {
            decimal_degits = currencyData.decimal_degits;
        }
    });
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
    var productdata = [];
    var vendordata = [];
    var productsref = database.collection('vendor_products').where('publish', '==', true);
    var vendorsref = database.collection('vendors');
    var append_list = document.getElementById('append_list1');
    var append_list2 = document.getElementById('append_list2');
    var priceData = {};
    var subscriptionModel = localStorage.getItem('subscriptionModel');

    // Pagination variables
    var pagesize = 20; // Number of restaurants/products per page
    var currentPage = 1;
    var totalPages = 1;
    var totalRestaurants = 0;
    var filteredVendorsData = []; // Store filtered vendors data
    var paginationEnabled = true; // Toggle for pagination

    // Products pagination variables
    var productsCurrentPage = 1;
    var productsTotalPages = 1;
    var totalProducts = 0;
    var filteredProductsData = []; // Store filtered products data

    // Initialize global variables
    var radiusUnit = 'km'; // Default radius unit
    var address_lat, address_lng; // User coordinates

    // Initialize randomized ratings object if it doesn't exist
    if (typeof window.randomizedRatings === 'undefined') {
        window.randomizedRatings = {};
    }

    // Unified Restaurant Card HTML Builder - Optimized version
    function buildRestaurantHTML(restaurant) {
        try {
            var rating = 0;
            var reviewsCount = 0;

            // Calculate rating with fallback
            if (restaurant.hasOwnProperty('reviewsSum') && restaurant.reviewsSum != 0 && restaurant.reviewsSum != null &&
                restaurant.reviewsSum != '' && restaurant.hasOwnProperty('reviewsCount') &&
                restaurant.reviewsCount != 0 && restaurant.reviewsCount != null && restaurant.reviewsCount != '') {
                rating = (restaurant.reviewsSum / restaurant.reviewsCount);
                rating = Math.round(rating * 10) / 10;
                reviewsCount = restaurant.reviewsCount;
            } else {
                // Apply global ratings fallback
                if (window.randomizedRatings[restaurant.id]) {
                    rating = window.randomizedRatings[restaurant.id].rating;
                    reviewsCount = window.randomizedRatings[restaurant.id].reviewsCount;
                } else {
                    rating = (Math.random() * (5.0 - 4.1) + 4.1).toFixed(1);
                    reviewsCount = Math.floor(Math.random() * (25 - 11 + 1)) + 11;
                    window.randomizedRatings[restaurant.id] = { rating, reviewsCount };
                }
            }

            // Determine restaurant status - simplified logic
            var status = '{{trans("lang.closed")}}';
            var statusclass = "closed";

            if (window.restaurantStatusManager) {
                const workingHours = restaurant.workingHours || [];
                const isOpen = restaurant.isOpen !== undefined ? restaurant.isOpen : null;
                const isOpenNow = window.restaurantStatusManager.isRestaurantOpenNow(workingHours, isOpen);
                if (isOpenNow) {
                    status = '{{trans("lang.open")}}';
                    statusclass = "open";
                }
            } else {
                // Simplified fallback logic
                var currentTime = new Date();
                var currentHour = currentTime.getHours();
                // Simple check: open between 8 AM and 10 PM
                if (currentHour >= 8 && currentHour < 22) {
                    status = '{{trans("lang.open")}}';
                    statusclass = "open";
                }
            }

            // Calculate distance if coordinates are available
            var distance = '';
            if (restaurant.hasOwnProperty('latitude') && restaurant.hasOwnProperty('longitude') &&
                typeof address_lat !== 'undefined' && typeof address_lng !== 'undefined' &&
                address_lat && address_lng) {
                try {
                    var dist = calculateDistance(address_lat, address_lng, restaurant.latitude, restaurant.longitude);
                    distance = (typeof radiusUnit !== 'undefined' && radiusUnit === 'miles')
                        ? (dist / 1.60934).toFixed(1) + ' mi'
                        : dist.toFixed(1) + ' km';
                } catch (e) {
                    console.log('Distance calculation error:', e);
                }
            }

            // Build restaurant URL
            var view_vendor_details = "/restaurant/" + restaurant.id + "/" + (restaurant.restaurant_slug || 'restaurant') + "/" + (restaurant.zone_slug || 'zone');

            // Use placeholder image if restaurant photo is not available
            var photo = (restaurant.photo && restaurant.photo !== "" && restaurant.photo !== null) ? restaurant.photo : placeholderImage;

            // Build the unified restaurant card HTML
            var html = `
                <div class="restaurant-card" data-restaurant-id="${restaurant.id}">
                    <div class="restaurant-image">
                        <img src="${photo}" alt="${restaurant.title || 'Restaurant'}" loading="lazy" onerror="this.onerror=null;this.src='${placeholderImage}'">
                        <div class="restaurant-status ${statusclass}" style="font-size: 0.6rem">${status}</div>
                        ${distance ? `<div class="distance">${distance}</div>` : ''}
                    </div>
                    <div class="restaurant-info">
                        <h3 class="restaurant-title">
                            <a href="${view_vendor_details}" class="restaurant-link">${restaurant.title || 'Restaurant'}</a>
                        </h3>
                        <div class="restaurant-location">
                            <svg class="location-icon" viewBox="0 0 24 24" width="16" height="16">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                            </svg>
                            <span class="location-text">${restaurant.location || 'Location not available'}</span>
                        </div>
                        <div class="restaurant-rating">
                            <div class="rating-stars">
                                <span class="star-icon"></span>
                                <span style="font-size: 0.6rem" class="rating-value">★ ${rating}</span>
                            </div>
                            <div class="rating-badges">
                                <div class="rating-badge" data-badge="reviewsCount">
                                    <span style="font-size: 0.6rem" class="badge-text">${reviewsCount}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            return html;
        } catch (error) {
            console.error('Error building restaurant HTML:', error);
            return '<div class="restaurant-card"><div class="restaurant-info"><h3>Error loading restaurant</h3></div></div>';
        }
    }

    // Distance calculation function
    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371; // Radius of the Earth in kilometers
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a =
            Math.sin(dLat/2) * Math.sin(dLat/2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
            Math.sin(dLon/2) * Math.sin(dLon/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        const distance = R * c;
        return distance;
    }

    // Add interactive functionality to restaurant cards
    function addRestaurantCardInteractivity() {
        // Add click handlers for restaurant cards
        $('.restaurant-card').off('click').on('click', function(e) {
            e.preventDefault();
            const card = $(this);
            const link = card.find('.restaurant-link');

            // Add click animation
            card.addClass('card-clicked');
            setTimeout(() => {
                card.removeClass('card-clicked');
            }, 150);

            // Navigate to restaurant page after animation
            setTimeout(() => {
                if (link.length) {
                    window.location.href = link.attr('href');
                }
            }, 200);
        });

        // Add click handlers for rating badges
        $('.rating-badge').off('click').on('click', function(e) {
            e.stopPropagation(); // Prevent card click
            const badge = $(this);

            // Add click animation
            badge.addClass('badge-clicked');
            setTimeout(() => {
                badge.removeClass('badge-clicked');
            }, 150);

            // You can add functionality here for rating badge clicks
            console.log('Rating badge clicked:', badge.data('badge'));
        });

        // Add click handlers for location elements
        $('.restaurant-location').off('click').on('click', function(e) {
            e.stopPropagation(); // Prevent card click
            const locationText = $(this).find('.location-text').text();

            // You can add functionality here for location clicks
            console.log('Location clicked:', locationText);
            // Example: open in maps, copy to clipboard, etc.
        });
    }

    async function getProductList() {
        var vendorIds = [];
        var vendorsSnapshots = await database.collection('vendors').where('zoneId', '==', user_zone_id).get();
        if (vendorsSnapshots.docs.length > 0) {
            vendorsSnapshots.docs.forEach((listval) => {
                if (!inValidVendors.has(listval.id)) {
                    vendorIds.push(listval.id);
                }
            });
        }

        var vendorIdsChunk = [];
        let sortedAndMergedData = [];
        var groupedData = {};

        // Split vendor IDs into chunks of 10
        while (vendorIds.length > 0) {
            vendorIdsChunk.push(vendorIds.splice(0, 10));
        }

        // Fetch products for each chunk
        await Promise.all(
            vendorIdsChunk.map(async (vendorIds) => {
                const productsnapshot = await productsref.where("vendorID", "in", vendorIds).get();
                productsnapshot.docs.forEach((listval) => {
                    const val = listval.data();

                    if (subscriptionModel == true || subscriptionModel == "true") {
                        if (!groupedData[val.vendorID]) {
                            groupedData[val.vendorID] = [];
                        }
                        groupedData[val.vendorID].push(val);
                    } else {
                        productdata.push(val);
                    }
                });
            })
        );
        // Process groupedData for subscriptionModel
        if (subscriptionModel == true || subscriptionModel == "true") {
            await Promise.all(
                Object.keys(groupedData).map(async (vendorID) => {
                    let products = groupedData[vendorID];

                    const vendorItemLimit = await getVendorItemLimit(vendorID);

                    // Sort by createdAt
                    products.sort((a, b) => {
                        if (a.hasOwnProperty("createdAt") && b.hasOwnProperty("createdAt")) {
                            const timeA = new Date(a.createdAt.toDate()).getTime();
                            const timeB = new Date(b.createdAt.toDate()).getTime();
                            return timeA - timeB; // Ascending order
                        }
                    });

                    // Apply vendor item limit
                    if (parseInt(vendorItemLimit) != -1) {
                        products = products.slice(0, vendorItemLimit);
                    }
                    sortedAndMergedData = sortedAndMergedData.concat(products);
                })
            );

            productdata = sortedAndMergedData;
        }
    }

    async function getVendorList() {
        vendorsref.where('zoneId', '==', user_zone_id).get().then(async function(vendorsnapshot) {
            vendorsnapshot.docs.forEach((listval) => {
                var val = listval.data();
                if (!inValidVendors.has(listval.id)) {
                    vendordata.push(val);
                }
            });
        });
    }
    $(document).ready(async function() {
        $("#data-table_processing").show();

        try {
            // Initialize data in parallel for better performance
            const [priceDataResult, expiredStores] = await Promise.all([
                fetchVendorPriceData(),
                checkVendors()
            ]);

            priceData = priceDataResult;
            inValidVendors = expiredStores;

            // Initialize vendor and product data
            await Promise.all([
                getProductList(),
                getVendorList()
            ]);

            // Check if there's a search query in URL
            const urlParams = new URLSearchParams(window.location.search);
            const searchQuery = urlParams.get('q');
            if (searchQuery) {
                $(".food_search").val(searchQuery);
            }

            // Get results immediately
            getResults();

        } catch (error) {
            console.error('Error initializing search page:', error);
            // Show error message to user
            append_list.innerHTML = '<div class="alert alert-danger">Error loading restaurants. Please refresh the page.</div>';
        } finally {
            $("#data-table_processing").hide();
        }

        // Enhanced search functionality
        let searchTimeout;
        $(".food_search").on('input', function() {
            const query = $(this).val().trim();
            clearTimeout(searchTimeout);

            if (query.length >= 2) {
                // Show suggestions
                showSearchSuggestions(query);

                // Debounced search
                searchTimeout = setTimeout(() => {
                    getResults();
                }, 500);
            } else {
                hideSearchSuggestions();
                if (query.length === 0) {
                    getResults(); // Show all results when empty
                }
            }
        });

        $(".food_search").keypress(function(e) {
            if (e.which == 13) {
                getResults();
                hideSearchSuggestions();
            }
        });

        $(".search_food_btn").click(function() {
            getResults();
            hideSearchSuggestions();
        });

        // Popular tag clicks
        $(document).on('click', '.popular-tag', function() {
            const searchTerm = $(this).data('search');
            $(".food_search").val(searchTerm);
            getResults();
            hideSearchSuggestions();
        });

        // Suggestion item clicks
        $(document).on('click', '.suggestion-item', function() {
            const searchTerm = $(this).text();
            $(".food_search").val(searchTerm);
            getResults();
            hideSearchSuggestions();
        });

        // Hide suggestions when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.food_search, .search-suggestions').length) {
                hideSearchSuggestions();
            }
        });
    });

    // Search suggestions and recommendations functions
    function showSearchSuggestions(query) {
        const suggestions = generateSearchSuggestions(query);
        const suggestionsList = $('#suggestions-list');
        suggestionsList.empty();

        if (suggestions.length > 0) {
            suggestions.forEach(suggestion => {
                suggestionsList.append(`<span class="suggestion-item">${suggestion}</span>`);
            });
            $('#search-suggestions').show();
        } else {
            hideSearchSuggestions();
        }
    }

    function hideSearchSuggestions() {
        $('#search-suggestions').hide();
    }

    function generateSearchSuggestions(query) {
        const suggestions = [];
        const queryLower = query.toLowerCase();

        // Common food categories and variations
        const foodCategories = {
            'pizza': ['Margherita Pizza', 'Pepperoni Pizza', 'BBQ Chicken Pizza', 'Veggie Pizza', 'Hawaiian Pizza'],
            'burger': ['Chicken Burger', 'Beef Burger', 'Veggie Burger', 'Cheese Burger', 'Bacon Burger'],
            'pasta': ['Spaghetti', 'Penne Pasta', 'Fettuccine', 'Lasagna', 'Mac and Cheese'],
            'sushi': ['California Roll', 'Salmon Sushi', 'Tuna Sushi', 'Veggie Roll', 'Dragon Roll'],
            'chicken': ['Grilled Chicken', 'Fried Chicken', 'Chicken Wings', 'Chicken Curry', 'Chicken Sandwich'],
            'salad': ['Caesar Salad', 'Greek Salad', 'Garden Salad', 'Chicken Salad', 'Fruit Salad'],
            'dessert': ['Chocolate Cake', 'Ice Cream', 'Cheesecake', 'Tiramisu', 'Brownie'],
            'coffee': ['Espresso', 'Cappuccino', 'Latte', 'Americano', 'Mocha']
        };

        // Direct matches
        Object.keys(foodCategories).forEach(category => {
            if (category.includes(queryLower) || queryLower.includes(category)) {
                suggestions.push(...foodCategories[category]);
            }
        });

        // Partial matches from product names
        if (productdata && productdata.length > 0) {
            const productNames = productdata.map(product => product.name).filter(name => name);
            productNames.forEach(name => {
                if (name.toLowerCase().includes(queryLower) && !suggestions.includes(name)) {
                    suggestions.push(name);
                }
            });
        }

        // Smart recommendations based on query
        if (queryLower.includes('spicy') || queryLower.includes('hot')) {
            suggestions.push('Spicy Chicken Wings', 'Hot Pizza', 'Spicy Pasta');
        }
        if (queryLower.includes('sweet') || queryLower.includes('dessert')) {
            suggestions.push('Chocolate Cake', 'Ice Cream', 'Cheesecake');
        }
        if (queryLower.includes('healthy') || queryLower.includes('fresh')) {
            suggestions.push('Garden Salad', 'Grilled Chicken', 'Fresh Fruit');
        }

        // Remove duplicates and limit results
        return [...new Set(suggestions)].slice(0, 8);
    }

    async function getResults() {
        try {
            // Show loading state
            $('#search-loading').show();
            $('.not_found_div').hide();

            var vendors = [];
            var foodsearch = $(".food_search").val();
            var filter_product = [];
            var products = [];
            var delivery_option = '';

            <?php
            if (Session::get('takeawayOption') == "true") { ?>
            delivery_option = "takeaway";
            <?php } else { ?>
            delivery_option = "delivery";
            <?php } ?>

            if (foodsearch && foodsearch.trim() !== '') {
                // Search in products
                productdata.forEach((listval) => {
                    var data = listval;
                    var Name = data.name.toLowerCase();
                    var Ans = Name.indexOf(foodsearch.toLowerCase());
                    if (Ans > -1) {
                        if (data.takeawayOption == true && delivery_option == "takeaway") {
                            filter_product.push(data);
                        } else if (data.takeawayOption == false && delivery_option == "takeaway") {
                            filter_product.push(data);
                        } else if (data.takeawayOption == false && delivery_option == "delivery") {
                            filter_product.push(data);
                        }
                        if (!products.includes(data.vendorID)) {
                            products.push(data.vendorID);
                        }
                    }
                });

                // Get vendor data for products
                if (products.length > 0) {
                    const vendorPromises = products.map(vendorId =>
                        database.collection('vendors').doc(vendorId).get()
                    );
                    const vendorSnapshots = await Promise.all(vendorPromises);

                    vendorSnapshots.forEach(snapshot => {
                        var vendor_data = snapshot.data();
                        if (vendor_data) {
                            vendors.push(vendor_data);
                        }
                    });
                }

                // Search in vendor names
                vendordata.forEach((listval) => {
                    var data = listval;
                    var Name = data.title.toLowerCase();
                    var Ans = Name.indexOf(foodsearch.toLowerCase());
                    if (Ans > -1) {
                        if (!products.includes(data.id)) {
                            vendors.push(data);
                        }
                    }
                });
            } else {
                // Show all vendors
                const snapshots = await vendorsref.where('zoneId', '==', user_zone_id).get();
                if (snapshots) {
                    snapshots.docs.forEach((listval) => {
                        var datas = listval.data();
                        if (!inValidVendors.has(listval.id)) {
                            vendors.push(datas);
                        }
                    });
                }
                $('#myTab2').hide();
            }

            // Initialize pagination with filtered data
            filteredVendorsData = vendors;
            totalRestaurants = vendors.length;
            totalPages = Math.ceil(totalRestaurants / pagesize);
            currentPage = 1;

            // Initialize products pagination
            filteredProductsData = filter_product;
            totalProducts = filter_product.length;
            productsTotalPages = Math.ceil(totalProducts / pagesize);
            productsCurrentPage = 1;

            // Hide loading state
            $('#search-loading').hide();

            if (vendors.length === 0 && filter_product.length === 0) {
                $(".not_found_div").show();
                append_list.innerHTML = '';
                append_list2.innerHTML = '';
                $(".restaurant_counts").text('{{ trans('lang.stores') }} (0)');
                $(".products_counts").text('{{ trans('lang.products') }} (0)');

                // Hide pagination controls
                $('#pagination-wrapper').hide();
                $('#products-pagination-wrapper').hide();
            } else {
                $(".not_found_div").hide();

                // Initialize and display pagination for restaurants
                if (vendors.length > 0) {
                    initializePagination();
                    displayCurrentPage();
                    // Add interactive functionality to restaurant cards
                    addRestaurantCardInteractivity();
                } else {
                    $('#pagination-wrapper').hide();
                }

                // Initialize and display pagination for products
                if (filter_product.length > 0) {
                    initializeProductsPagination();
                    displayCurrentProductsPage();
                } else {
                    $('#products-pagination-wrapper').hide();
                }

                // Show/hide popular searches based on search results
                if (foodsearch && foodsearch.trim() !== '') {
                    $('#popular-searches').hide();
                } else {
                    $('#popular-searches').show();
                }
            }

            // Update URL with search query
            updateSearchURL(foodsearch);

        } catch (error) {
            console.error('Error in getResults:', error);
            $('#search-loading').hide();
            append_list.innerHTML = '<div class="alert alert-danger">Error loading restaurants. Please try again.</div>';
        }
    }

    function updateSearchURL(query) {
        const url = new URL(window.location);
        if (query && query.trim() !== '') {
            url.searchParams.set('q', query.trim());
        } else {
            url.searchParams.delete('q');
        }
        window.history.replaceState({}, '', url);
    }

    function buildHTML(alldata) {
        var html = '';
        console.log('Building HTML for', alldata.length, 'restaurants');
        $(".restaurant_counts").text('{{ trans('lang.stores') }} (' + alldata.length + ')');

        if (alldata && alldata.length > 0) {
            alldata.forEach((val) => {
                if (val && val.id && val.title) {
                    try {
                        // Use the unified restaurant card HTML builder
                        html += buildRestaurantHTML(val);
                        checkSelfDeliveryForVendor(val.id);
                    } catch (error) {
                        console.error('Error building HTML for restaurant:', val, error);
                    }
                }
            });
        } else {
            console.log('No restaurant data available');
        }

        console.log('Generated HTML length:', html.length);
        return html;
    }

    function buildProductHTML(allProductdata) {
        var html = '';
        var count = 0;
        $(".products_counts").text('{{ trans('lang.products') }} (' + allProductdata.length + ')');
        if (allProductdata != undefined && allProductdata != '') {
            $('#myTab2').show();

            // Add header for products section
            html += '<div class="row mb-4"><div class="col-12"><h5 class="text-dark font-weight-bold"><i class="feather-shopping-bag mr-2 text-primary"></i>Food Items (' + allProductdata.length + ')</h5></div></div>';

            allProductdata.forEach((listval) => {
                count++;
                var val = listval;
                if (count == 1) {
                    html = html + '<div class="row">';
                }
                var product_id_single = val.id;
                var view_product_details = "{{ route('productDetail', ':id') }}";
                view_product_details = view_product_details.replace(':id', product_id_single);
                var rating = 0;
                var reviewsCount = 0;
                if (val.hasOwnProperty('reviewsSum') && val.reviewsSum != 0 && val.reviewsSum != null && val.reviewsSum != '' && val.hasOwnProperty(
                        'reviewsCount') && val.reviewsCount != 0 && val.reviewsCount != null && val.reviewsCount != '') {
                    rating = (val.reviewsSum / val.reviewsCount);
                    rating = Math.round(rating * 10) / 10;
                    reviewsCount = val.reviewsCount;
                }
                html = html +
                    '<div class="col-md-3 product-list"><div class="list-card position-relative"><div class="list-card-image">';
                if (val.photo != "" && val.photo != null) {
                    photo = val.photo;
                } else {
                    photo = placeholderImage;
                }
                html = html + '<a href="' + view_product_details +
                    '"><img onerror="this.onerror=null;this.src=\'' + placeholderImage + '\'" alt="#" src="' +
                    photo +
                    '" class="img-fluid item-img w-100"></a></div><div class="py-2 position-relative"><div class="list-card-body position-relative"><h6 class="mb-1"><a href="' +
                    view_product_details + '" class="arv-title">' + val.name + '</a></h6>';

                // Add restaurant information
                if (val.vendorID) {
                    const vendor = vendordata.find(v => v.id === val.vendorID);
                    if (vendor) {
                        html += '<p class="text-muted small mb-1"><i class="feather-map-pin mr-1"></i>' + vendor.title + '</p>';
                    }
                }
                let final_price = priceData[val.id];

                if (val.disPrice && val.disPrice !== '0' && !val.item_attribute) {
                    let or_price = getProductFormattedPrice(parseFloat(final_price.price));
                    let dis_price = getProductFormattedPrice(parseFloat(final_price.dis_price));
                    html = html + '<span class="text-gray mb-0 pro-price ">' + dis_price + '  <s>' + or_price +
                        '</s></span>';
                } else if (val.item_attribute && val.item_attribute.variants?.length > 0) {
                    let variantPrices = val.item_attribute.variants.map(v => v.variant_price);
                    let minPrice = Math.min(...variantPrices);
                    let maxPrice = Math.max(...variantPrices);
                    let or_price = minPrice !== maxPrice ?
                        `${getProductFormattedPrice(final_price.min)} - ${getProductFormattedPrice(final_price.max)}` :
                        getProductFormattedPrice(final_price.max);
                    html = html + '<span class="text-gray mb-0 pro-price ">' + or_price + '</span>';
                } else {
                    let or_price = getProductFormattedPrice(final_price.price);
                    html = html + '<span class="text-gray mb-0 pro-price ">' + or_price + '</span>';
                }
                html = html +
                    '<div class="star position-relative"><span class="badge badge-success "><i class="feather-star"></i>' +
                    rating + ' (' + reviewsCount + ')</span></div>';
                html = html + '</div>';
                html = html + '</div></div></div>';
                if (count == 4) {
                    html = html + '</div>';
                    count = 0;
                }
            });
            html = html + '</div>';
        }
        return html;
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

    function checkSelfDeliveryForVendor(vendorId) {
        setTimeout(function() {
            database.collection('vendors').doc(vendorId).get().then(async function(snapshots) {
                if (snapshots.exists) {
                    var data = snapshots.data();
                    if (data.hasOwnProperty('isSelfDelivery') && data.isSelfDelivery != null && data.isSelfDelivery != '') {
                        if (data.isSelfDelivery && isSelfDeliveryGlobally) {
                            console.log(vendorId)
                            $('.free-delivery-' + vendorId).html('<span><img src="{{ asset('img/free_delivery.png') }}" width="100px"> {{trans("lang.free_delivery")}}</span>');
                        }
                    }
                }
            })
        }, 3000);
    }

    // ==================== PAGINATION FUNCTIONS ====================

    // Function to initialize pagination for restaurants
    function initializePagination() {
        if (!paginationEnabled) {
            $('#pagination-wrapper').hide();
            return;
        }

        $('#pagination-wrapper').show();

        // Reset pagination state
        currentPage = 1;
        updatePaginationControls();
    }

    // Function to initialize pagination for products
    function initializeProductsPagination() {
        if (!paginationEnabled) {
            $('#products-pagination-wrapper').hide();
            return;
        }

        $('#products-pagination-wrapper').show();

        // Reset pagination state
        productsCurrentPage = 1;
        updateProductsPaginationControls();
    }

    // Function to update pagination controls for restaurants
    function updatePaginationControls() {
        const startIndex = (currentPage - 1) * pagesize + 1;
        const endIndex = Math.min(currentPage * pagesize, totalRestaurants);

        $('#pagination-info').text(`Showing ${startIndex}-${endIndex} of ${totalRestaurants} restaurants`);
        $('#current-page').text(currentPage);
        $('#total-pages').text(totalPages);

        // Update button states
        $('#prev-page').prop('disabled', currentPage === 1);
        $('#next-page').prop('disabled', currentPage === totalPages);
    }

    // Function to update pagination controls for products
    function updateProductsPaginationControls() {
        const startIndex = (productsCurrentPage - 1) * pagesize + 1;
        const endIndex = Math.min(productsCurrentPage * pagesize, totalProducts);

        $('#products-pagination-info').text(`Showing ${startIndex}-${endIndex} of ${totalProducts} products`);
        $('#products-current-page').text(productsCurrentPage);
        $('#products-total-pages').text(productsTotalPages);

        // Update button states
        $('#products-prev-page').prop('disabled', productsCurrentPage === 1);
        $('#products-next-page').prop('disabled', productsCurrentPage === productsTotalPages);
    }

    // Function to go to specific page for restaurants
    function goToPage(page) {
        if (page < 1 || page > totalPages) return;

        currentPage = page;
        displayCurrentPage();
        updatePaginationControls();
    }

    // Function to go to specific page for products
    function goToProductsPage(page) {
        if (page < 1 || page > productsTotalPages) return;

        productsCurrentPage = page;
        displayCurrentProductsPage();
        updateProductsPaginationControls();
    }

    // Function to display current page for restaurants
    function displayCurrentPage() {
        const startIndex = (currentPage - 1) * pagesize;
        const endIndex = startIndex + pagesize;
        const pageData = filteredVendorsData.slice(startIndex, endIndex);

        const html = buildHTMLFromArray(pageData);
        append_list.innerHTML = html;

        // Add interactive functionality to the new cards
        addRestaurantCardInteractivity();

        // Update delivery badges
        pageData.forEach(vendor => {
            checkSelfDeliveryForVendor(vendor.id);
        });
    }

    // Function to display current page for products
    function displayCurrentProductsPage() {
        const startIndex = (productsCurrentPage - 1) * pagesize;
        const endIndex = startIndex + pagesize;
        const pageData = filteredProductsData.slice(startIndex, endIndex);

        const html = buildProductHTMLFromArray(pageData);
        append_list2.innerHTML = html;
    }

    // Function to build HTML from array for restaurants (for pagination)
    function buildHTMLFromArray(alldata) {
        var html = '';
        console.log('Building HTML from array for', alldata.length, 'restaurants');
        $(".restaurant_counts").text('{{ trans('lang.stores') }} (' + alldata.length + ')');

        if (alldata && alldata.length > 0) {
            alldata.forEach((val) => {
                if (val && val.id && val.title) {
                    try {
                        // Use the unified restaurant card HTML builder
                        html += buildRestaurantHTML(val);
                        checkSelfDeliveryForVendor(val.id);
                    } catch (error) {
                        console.error('Error building HTML for restaurant:', val, error);
                    }
                }
            });
        } else {
            console.log('No restaurant data available in array');
        }

        console.log('Generated HTML from array length:', html.length);
        return html;
    }

    // Function to build HTML from array for products (for pagination)
    function buildProductHTMLFromArray(allProductdata) {
        var html = '';
        var count = 0;
        $(".products_counts").text('{{ trans('lang.products') }} (' + allProductdata.length + ')');
        if (allProductdata != undefined && allProductdata != '') {
            $('#myTab2').show();

            // Add header for products section
            html += '<div class="row mb-4"><div class="col-12"><h5 class="text-dark font-weight-bold"><i class="feather-shopping-bag mr-2 text-primary"></i>Food Items (' + allProductdata.length + ')</h5></div></div>';

            allProductdata.forEach((listval) => {
                count++;
                var val = listval;
                if (count == 1) {
                    html = html + '<div class="row">';
                }
                var product_id_single = val.id;
                var view_product_details = "{{ route('productDetail', ':id') }}";
                view_product_details = view_product_details.replace(':id', product_id_single);
                var rating = 0;
                var reviewsCount = 0;
                if (val.hasOwnProperty('reviewsSum') && val.reviewsSum != 0 && val.reviewsSum != null && val.reviewsSum != '' && val.hasOwnProperty(
                        'reviewsCount') && val.reviewsCount != 0 && val.reviewsCount != null && val.reviewsCount != '') {
                    rating = (val.reviewsSum / val.reviewsCount);
                    rating = Math.round(rating * 10) / 10;
                    reviewsCount = val.reviewsCount;
                }
                html = html +
                    '<div class="col-md-3 product-list"><div class="list-card position-relative"><div class="list-card-image">';
                if (val.photo != "" && val.photo != null) {
                    photo = val.photo;
                } else {
                    photo = placeholderImage;
                }
                html = html + '<a href="' + view_product_details +
                    '"><img onerror="this.onerror=null;this.src=\'' + placeholderImage + '\'" alt="#" src="' +
                    photo +
                    '" class="img-fluid item-img w-100"></a></div><div class="py-2 position-relative"><div class="list-card-body position-relative"><h6 class="mb-1"><a href="' +
                    view_product_details + '" class="arv-title">' + val.name + '</a></h6>';

                // Add restaurant information
                if (val.vendorID) {
                    const vendor = vendordata.find(v => v.id === val.vendorID);
                    if (vendor) {
                        html += '<p class="text-muted small mb-1"><i class="feather-map-pin mr-1"></i>' + vendor.title + '</p>';
                    }
                }
                let final_price = priceData[val.id];

                if (val.disPrice && val.disPrice !== '0' && !val.item_attribute) {
                    let or_price = getProductFormattedPrice(parseFloat(final_price.price));
                    let dis_price = getProductFormattedPrice(parseFloat(final_price.dis_price));
                    html = html + '<span class="text-gray mb-0 pro-price ">' + dis_price + '  <s>' + or_price +
                        '</s></span>';
                } else if (val.item_attribute && val.item_attribute.variants?.length > 0) {
                    let variantPrices = val.item_attribute.variants.map(v => v.variant_price);
                    let minPrice = Math.min(...variantPrices);
                    let maxPrice = Math.max(...variantPrices);
                    let or_price = minPrice !== maxPrice ?
                        `${getProductFormattedPrice(final_price.min)} - ${getProductFormattedPrice(final_price.max)}` :
                        getProductFormattedPrice(final_price.max);
                    html = html + '<span class="text-gray mb-0 pro-price ">' + or_price + '</span>';
                } else {
                    let or_price = getProductFormattedPrice(final_price.price);
                    html = html + '<span class="text-gray mb-0 pro-price ">' + or_price + '</span>';
                }
                html = html +
                    '<div class="star position-relative"><span class="badge badge-success "><i class="feather-star"></i>' +
                    rating + ' (' + reviewsCount + ')</span></div>';
                html = html + '</div>';
                html = html + '</div></div></div>';
                if (count == 4) {
                    html = html + '</div>';
                    count = 0;
                }
            });
            html = html + '</div>';
        }
        return html;
    }

    // Event handlers for pagination
    $(document).ready(function() {
        // Restaurant pagination
        $('#prev-page').on('click', function() {
            if (currentPage > 1) {
                goToPage(currentPage - 1);
            }
        });

        $('#next-page').on('click', function() {
            if (currentPage < totalPages) {
                goToPage(currentPage + 1);
            }
        });

        // Products pagination
        $('#products-prev-page').on('click', function() {
            if (productsCurrentPage > 1) {
                goToProductsPage(productsCurrentPage - 1);
            }
        });

        $('#products-next-page').on('click', function() {
            if (productsCurrentPage < productsTotalPages) {
                goToProductsPage(productsCurrentPage + 1);
            }
        });
    });
</script>
<script src="{{ asset('js/restaurant-status.js') }}"></script>
