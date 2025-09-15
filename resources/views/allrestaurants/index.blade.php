@include('layouts.app')
@include('layouts.header')
<div class="d-none">
    <div class="bg-primary p-3 d-flex align-items-center">
        <a class="toggle togglew toggle-2" href="#"><span></span></a>
    </div>
</div>
<div class="siddhi-popular">
    <div class="container">
        <div class="search py-5">
            <div class="input-group mb-4"></div>
            <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active border-0 bg-light text-dark rounded" id="home-tab" data-toggle="tab"
                        href="#home" role="tab" aria-controls="home" aria-selected="true"><i
                            class="feather-home mr-2"></i><span class="restaurant_counts">All Restaurants</span></a>
                </li>
            </ul>
            <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="container mt-4 mb-4 p-0">
                        <!-- Loading Spinner -->
                        <div id="loading-spinner" class="text-center py-5" style="display: none;">
                            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <p class="mt-3 text-muted">Loading restaurants...</p>
                        </div>

                        <!-- Context Loading Message -->
                        <div id="context-loading" class="text-center py-3" style="display: none;">
                            <div class="alert alert-info">
                                <i class="feather-info"></i>
                                <span id="context-message">Detecting your location and finding nearby restaurants...</span>
                            </div>
                        </div>

                        <div id="all_stores" class="res-search-list-1"></div>

                        <!-- Pagination Controls -->
                        <div class="pagination-wrapper mt-4" id="pagination-wrapper" style="display: none;">
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="pagination-info">
                                            <span id="pagination-info">Showing 0 of 0 restaurants</span>
                                        </div>
                                        <div class="pagination-controls">
                                            <button type="button" id="prev-page" class="btn btn-outline-primary btn-sm" disabled>
                                                <i class="feather-chevron-left"></i> Previous
                                            </button>
                                            <span class="mx-3">
                                                Page <span id="current-page">1</span> of <span id="total-pages">1</span>
                                            </span>
                                            <button type="button" id="next-page" class="btn btn-outline-primary btn-sm">
                                                Next <i class="feather-chevron-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Load More Button (for backward compatibility) -->
                        <div class="row fu-loadmore-btn" id="loadmore-wrapper" style="display: none;">
                            <a class="page-link loadmore-btn" href="javascript:void(0);" onclick="loadMoreRestaurants()" data-dt-idx="0" tabindex="0" id="loadmore">{{ trans('lang.see') }} {{ trans('lang.more') }}</a>
                            <p class="text-danger" style="display:none;" id="noMoreCoupons">{{ trans('lang.no_results') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <div class="row d-flex align-items-center justify-content-center py-5">
                    <div class="col-md-4 py-5">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.footer')
@include('layouts.nav')
<script src="https://unpkg.com/geofirestore/dist/geofirestore.js"></script>
<script src="https://cdn.firebase.com/libs/geofire/5.0.1/geofire.min.js"></script>
<script src="{{ asset('js/restaurant-status.js') }}"></script>
<script type="text/javascript">
    var firestore=firebase.firestore();
    var geoFirestore=new GeoFirestore(firestore);
    var placeholderImage='';
    var placeholder=database.collection('settings').doc('placeHolderImage');
    placeholder.get().then(async function(snapshotsimage) {
        var placeholderImageData=snapshotsimage.data();
        placeholderImage=placeholderImageData.image;
    })
    var end=null;
    var endarray=[];
    var start=null;
    var vendorsref=database.collection('vendors');
    var currentDate=new Date();
    var expiresVendor=database.collection('vendors').where("subscriptionExpiryDate","<",currentDate);
    var RestaurantNearBy='';
    var DriverNearByRef=database.collection('settings').doc('RestaurantNearBy');
    var pagesize=20;
    var nearestRestauantRefnew='';
    var append_list='';
    var placeholderImageRef=database.collection('settings').doc('placeHolderImage');
    var placeholderImageSrc='';
    var all_stores=document.getElementById('all_stores');
    all_stores.innerHTML='';
    var inValidVendors = new Set();

    // Show loading indicators
    function showLoading() {
        $('#loading-spinner').show();
        $('#context-loading').show();
        $('#all_stores').hide();
        $('#pagination-wrapper').hide();
    }

    // Hide loading indicators
    function hideLoading() {
        $('#loading-spinner').hide();
        $('#context-loading').hide();
        $('#all_stores').show();
    }

    // Update context message
    function updateContextMessage(message) {
        $('#context-message').text(message);
    }
    var isSelfDeliveryGlobally = false;
    var refGlobal = database.collection('settings').doc("globalSettings");

    // Pagination variables
    var currentPage = 1;
    var totalPages = 1;
    var totalRestaurants = 0;
    var allVendorsData = []; // Store all vendors data
    var filteredVendorsData = []; // Store filtered vendors data
    var paginationEnabled = true; // Toggle for pagination vs load more

    refGlobal.get().then(async function(
        settingSnapshots) {
        if (settingSnapshots.data()) {
            var settingData = settingSnapshots.data();
            if (settingData.isSelfDelivery) {
                isSelfDeliveryGlobally = true;
            }
        }
    })
    placeholderImageRef.get().then(async function(placeholderImageSnapshots) {
        var placeHolderImageData=placeholderImageSnapshots.data();
        placeholderImageSrc=placeHolderImageData.image;
    })
    var radiusUnit = 'km';

    // Initialize randomized ratings object if it doesn't exist
    if (typeof window.randomizedRatings === 'undefined') {
        window.randomizedRatings = {};
    }

    // Show initial loading state
    showLoading();
    updateContextMessage('Initializing restaurant search...');

    callRestaurant();
    async function callRestaurant() {
        if(address_lat==''||address_lng==''||address_lng==NaN||address_lat==NaN||address_lat==null||address_lng==null) {
            updateContextMessage('Location not detected. Please check your location settings.');
            hideLoading();
            return false;
        }

        updateContextMessage('Getting restaurant search radius...');
        DriverNearByRef.get().then(async function(DriverNearByRefSnapshots) {
            var DriverNearByRefData=DriverNearByRefSnapshots.data();
            RestaurantNearBy=parseInt(DriverNearByRefData.radios);
            address_lat=parseFloat(address_lat);
            address_lng=parseFloat(address_lng);
            radiusUnit=DriverNearByRefData.distanceType;

            if (radiusUnit == 'miles') {
                RestaurantNearBy = parseInt(RestaurantNearBy * 1.60934)
            }
            getNearestRestaurants();
        })
    }
    async function getNearestRestaurants() {
        updateContextMessage('Checking restaurant availability...');

        // Retrieve all invalid vendors
        await checkVendors().then(expiredStores => {
           inValidVendors=expiredStores;
        });

        updateContextMessage('Searching for restaurants in your area...');

        if(RestaurantNearBy) {
            nearestRestauantRefnew=geoFirestore.collection('vendors').near({
                center: new firebase.firestore.GeoPoint(address_lat,address_lng),
                radius: RestaurantNearBy
            }).where('zoneId','==',user_zone_id);
        } else {
            nearestRestauantRefnew=geoFirestore.collection('vendors').where('zoneId','==',user_zone_id);
        }
        <?php if (isset($_GET['dinein']) && @$_GET['dinein'] == 1) { ?>
        nearestRestauantRefnew=nearestRestauantRefnew.where('enabledDiveInFuture','==',true).get();
        <?php } else { ?>
        nearestRestauantRefnew=nearestRestauantRefnew.get();
        <?php } ?>
        nearestRestauantRefnew.then(async function(nearestRestauantSnapshot) {
            updateContextMessage('Processing restaurant data...');

            if(nearestRestauantSnapshot.docs.length>0) {
                // Store the data globally for pagination
                window.vendorsData = nearestRestauantSnapshot;

                // Process all vendors data
                let vendors = [];
                nearestRestauantSnapshot.docs.forEach((listval) => {
                    var datas = listval.data();
                    datas.id = listval.id;
                    if (!inValidVendors.has(listval.id)) {
                        vendors.push(datas);
                    }
                });

                updateContextMessage(`Found ${vendors.length} restaurants. Loading...`);

                // Initialize pagination with all vendors
                filteredVendorsData = vendors;
                totalRestaurants = vendors.length;
                totalPages = Math.ceil(totalRestaurants / pagesize);
                currentPage = 1;

                // Initialize pagination system
                initializePagination();

                // Display first page
                displayCurrentPage();

                // Add interactive functionality to restaurant cards
                addRestaurantCardInteractivity();

                // Hide loading and show results
                hideLoading();

                start=nearestRestauantSnapshot.docs[nearestRestauantSnapshot.docs.length-1];
                endarray.push(nearestRestauantSnapshot.docs[0]);
            } else {
                updateContextMessage('No restaurants found in your area.');
                all_stores.innerHTML="<div class='text-center py-5'><h5 class='font-weight-bold text-muted'>{{trans('lang.no_results')}}</h5><p class='text-muted'>Try expanding your search radius or check back later.</p></div>";
                hideLoading();
            }
            jQuery("#data-table_processing").hide();
        }).catch(function(error) {
            console.error('Error loading restaurants:', error);
            updateContextMessage('Error loading restaurants. Please try again.');
            all_stores.innerHTML="<div class='text-center py-5'><h5 class='font-weight-bold text-danger'>Error Loading Restaurants</h5><p class='text-muted'>Please refresh the page or try again later.</p></div>";
            hideLoading();
        })
    }
    sortArrayOfObjects=(arr,key) => {
        return arr.sort((a,b) => {
            return a[key]-b[key];
        });
    };

    // ==================== PAGINATION FUNCTIONS ====================

    // Function to initialize pagination
    function initializePagination() {
        if (!paginationEnabled) {
            $('#pagination-wrapper').hide();
            $('#loadmore-wrapper').show();
            return;
        }

        $('#pagination-wrapper').show();
        $('#loadmore-wrapper').hide();

        // Reset pagination state
        currentPage = 1;
        updatePaginationControls();
    }

    // Function to update pagination controls
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

    // Function to go to specific page
    function goToPage(page) {
        if (page < 1 || page > totalPages) return;

        currentPage = page;
        displayCurrentPage();
        updatePaginationControls();
    }

    // Function to display current page
    function displayCurrentPage() {
        const startIndex = (currentPage - 1) * pagesize;
        const endIndex = startIndex + pagesize;
        const pageData = filteredVendorsData.slice(startIndex, endIndex);

        const html = buildHTMLNearestRestaurantFromArray(pageData);
        all_stores.innerHTML = html;

        // Add interactive functionality to the new cards
        addRestaurantCardInteractivity();
    }

    // Unified Restaurant Card HTML Builder - Same as home.blade.php
    function buildRestaurantHTML(restaurant) {
                    var rating = 0;
                    var reviewsCount = 0;
        if (restaurant.hasOwnProperty('reviewsSum') && restaurant.reviewsSum != 0 && restaurant.reviewsSum != null &&
            restaurant.reviewsSum != '' && restaurant.hasOwnProperty('reviewsCount') &&
            restaurant.reviewsCount != 0 && restaurant.reviewsCount != null && restaurant.reviewsCount != '') {
            rating = (restaurant.reviewsSum / restaurant.reviewsCount);
                        rating = Math.round(rating * 10) / 10;
            reviewsCount = restaurant.reviewsCount;
                    } else {
            // Apply global ratings fallback (same as popular restaurants)
            if (window.randomizedRatings[restaurant.id]) {
                rating = window.randomizedRatings[restaurant.id].rating;
                reviewsCount = window.randomizedRatings[restaurant.id].reviewsCount;
                    } else {
                        rating = (Math.random() * (5.0 - 4.1) + 4.1).toFixed(1);
                        reviewsCount = Math.floor(Math.random() * (25 - 11 + 1)) + 11;
                window.randomizedRatings[restaurant.id] = { rating, reviewsCount };
                    }
                    }

        // Determine restaurant status
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
                        // Fallback to old logic
            var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                        var currentdate = new Date();
                        var currentDay = days[currentdate.getDay()];
            var hour = currentdate.getHours();
            var minute = currentdate.getMinutes();
            if (hour < 10) {
                hour = '0' + hour;
            }
            if (minute < 10) {
                minute = '0' + minute;
                        }
                        var currentHours = hour + ':' + minute;
            if (restaurant.hasOwnProperty('workingHours')) {
                for (var i = 0; i < restaurant.workingHours.length; i++) {
                    var day = restaurant.workingHours[i]['day'];
                    if (restaurant.workingHours[i]['day'] == currentDay) {
                        if (restaurant.workingHours[i]['timeslot'].length != 0) {
                            for (var j = 0; j < restaurant.workingHours[i]['timeslot'].length; j++) {
                                var timeslot = restaurant.workingHours[i]['timeslot'][j];
                                var from = timeslot['from'];
                                var to = timeslot['to'];
                                if (currentHours >= from && currentHours <= to) {
                                                status = '{{trans("lang.open")}}';
                                                statusclass = "open";
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

        // Calculate distance if coordinates are available
        var distance = '';
        if (restaurant.hasOwnProperty('latitude') && restaurant.hasOwnProperty('longitude') &&
            typeof address_lat !== 'undefined' && typeof address_lng !== 'undefined') {
            var dist = calculateDistance(address_lat, address_lng, restaurant.latitude, restaurant.longitude);
            distance = radiusUnit === 'miles'
                ? (dist / 1.60934).toFixed(1) + ' mi'
                : dist.toFixed(1) + ' km';
        }

        // Build restaurant URL
                    <?php if (isset($_GET['dinein']) && @$_GET['dinein'] == 1) { ?>
                    var view_vendor_details = "{{ route('dyiningrestaurant', ':id')}}";
        view_vendor_details = view_vendor_details.replace(':id','id=' + restaurant.id);
                    <?php } else { ?>
        var view_vendor_details = "/restaurant/" + restaurant.id + "/" + restaurant.restaurant_slug + "/" + restaurant.zone_slug;
                    <?php } ?>

        // Use placeholder image if restaurant photo is not available
        var photo = (restaurant.photo != "" && restaurant.photo != null) ? restaurant.photo : placeholderImageSrc;

        // Build the unified restaurant card HTML
        var html = `
            <div class="restaurant-card" data-restaurant-id="${restaurant.id}">
                <div class="restaurant-image">
                    <img src="${photo}" alt="${restaurant.title}" loading="lazy" onerror="this.onerror=null;this.src='${placeholderImage}'">
                    <div class="restaurant-status ${statusclass}">${status}</div>
                    ${distance ? `<div class="distance">${distance}</div>` : ''}
                </div>
                <div class="restaurant-info">
                    <h3 class="restaurant-title">
                        <a href="${view_vendor_details}" class="restaurant-link">${restaurant.title}</a>
                    </h3>
                    <div class="restaurant-location">
                        <svg class="location-icon" viewBox="0 0 24 24" width="16" height="16">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                        </svg>
                        <span class="location-text">${restaurant.location}</span>
                    </div>
                    <div class="restaurant-rating">
                        <div class="rating-stars">
                            <span class="star-icon">★</span>
                            <span class="rating-value">${rating}</span>
                        </div>
                        <div class="rating-badges">
                            <div class="rating-badge" data-badge="reviewsCount">
                                <svg class="badge-icon" viewBox="0 0 24 24" width="14" height="14">
                                    <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zm4 18v-6h2.5l-2.54-7.63A1.5 1.5 0 0 0 18.54 8H16c-.8 0-1.54.37-2.01 1.01L12 11l-1.99-1.99C9.54 8.37 8.8 8 8 8H5.46c-.8 0-1.54.37-2.01 1.01L.95 16.63A1.5 1.5 0 0 0 2.5 18H5v4h2v-6h2v6h2v-6h2v6h2v-6h2v6h2z"/>
                                </svg>
                                <span class="badge-text">${reviewsCount}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        return html;
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

    // Function to build HTML from array (for pagination) - Using Unified UI
    function buildHTMLNearestRestaurantFromArray(alldata) {
        var html = '';

        if(alldata.length) {
            alldata.forEach((val) => {
                var checkDineinPlan = true;
                <?php if (isset($_GET['dinein']) && @$_GET['dinein'] == 1) { ?>
                    if(val.hasOwnProperty('subscription_plan')) {
                        if(val.subscription_plan.hasOwnProperty('features')) {
                            checkDineinPlan = val.subscription_plan.features.dineIn
                        }
                    }
                <?php } ?>
                if(<?php echo isset($_GET['dinein']) && $_GET['dinein'] == 1 ? 'checkDineinPlan' : 'true'; ?>) {
                    // Use the unified buildRestaurantHTML function
                    html += buildRestaurantHTML(val);
                }
                checkSelfDeliveryForVendor(val.id);
            });
        } else {
            html = html + "<h5 class='font-weight-bold text-center mt-3'>{{trans('lang.no_results')}}</h5>";
        }
        return html;
    }

    // Event handlers for pagination
    $(document).ready(function() {
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
    });

    // Backward compatibility function for load more
    function loadMoreRestaurants() {
        if (paginationEnabled) {
            if (currentPage < totalPages) {
                goToPage(currentPage + 1);
            }
        } else {
            // Original load more logic
            moreload();
        }
    }

    function buildHTMLNearestRestaurant(nearestRestauantSnapshot) {
        var html='';
        var alldata=[];
        nearestRestauantSnapshot.docs.forEach((listval) => {
            var datas=listval.data();
            datas.id=listval.id;
            var rating=0;
            var reviewsCount=0;
            if('<?php echo @$_GET['popular'] && @$_GET['popular'] == "yes" ?>'&&!inValidVendors.has(listval.id)) {
                if(datas.hasOwnProperty('reviewsSum')&&datas.reviewsSum!=0 && datas.reviewsSum!=null && datas.reviewsSum!='' &&datas.hasOwnProperty('reviewsCount')&&datas.reviewsCount!=0  &&datas.reviewsCount!=null  &&datas.reviewsCount!='') {
                    rating=(datas.reviewsSum/datas.reviewsCount);
                    rating=Math.round(rating*10)/10;
                    reviewsCount=datas.reviewsCount;
                } else {
                    // Assign random rating and count if zero or missing
                    rating = (Math.random() * (5.0 - 4.1) + 4.1).toFixed(1);
                    reviewsCount = Math.floor(Math.random() * (25 - 11 + 1)) + 11;
                }
                datas.rating=rating;
                datas.reviewsCount=reviewsCount;
                alldata.push(datas);
            } else {
                if(!inValidVendors.has(listval.id)) {
                    // Assign random rating and count if zero or missing
                    if(datas.hasOwnProperty('reviewsSum')&&datas.reviewsSum!=0 && datas.reviewsSum!=null && datas.reviewsSum!='' &&datas.hasOwnProperty('reviewsCount')&&datas.reviewsCount!=0  &&datas.reviewsCount!=null  &&datas.reviewsCount!='') {
                        rating=(datas.reviewsSum/datas.reviewsCount);
                        rating=Math.round(rating*10)/10;
                        reviewsCount=datas.reviewsCount;
                    } else {
                        rating = (Math.random() * (5.0 - 4.1) + 4.1).toFixed(1);
                        reviewsCount = Math.floor(Math.random() * (25 - 11 + 1)) + 11;
                    }
                    datas.rating=rating;
                    datas.reviewsCount=reviewsCount;
                    alldata.push(datas);
                }
            }
        });
        if('<?php echo @$_GET['popular'] && @$_GET['popular'] == "yes" ?>') {
            if(alldata.length) {
                alldata=sortArrayOfObjects(alldata,"rating");
                alldata=alldata.reverse();
            }
            $('.restaurant_counts').text('{{trans('lang.popular_restaurant_store')}}');
        }
        var count=0;
        var popularFoodCount=0;
        if(alldata.length) {
            html=html+'<div class="row">';
            alldata.forEach((listval) => {
                var val=listval;
                var checkDineinPlan=true;
                 <?php if (isset($_GET['dinein']) && @$_GET['dinein'] == 1) { ?>
                    if(val.hasOwnProperty('subscription_plan')) {
                        if(val.subscription_plan.hasOwnProperty('features')) {
                            checkDineinPlan=val.subscription_plan.features.dineIn
                        }
                    }
               <?php } ?>
                if(<?php echo isset($_GET['dinein']) && $_GET['dinein'] == 1 ? 'checkDineinPlan' : 'true'; ?>) {
                    var rating=0;
                    var reviewsCount=0;
                    if(val.hasOwnProperty('reviewsSum')&&val.reviewsSum!=0 && val.reviewsSum!=null && val.reviewsSum!='' &&val.hasOwnProperty('reviewsCount')&&val.reviewsCount!=0 && val.reviewsCount!=null && val.reviewsCount!='') {
                        rating=(val.reviewsSum/val.reviewsCount);
                        rating=Math.round(rating*10)/10;
                        reviewsCount=val.reviewsCount;
                    }
                    // Use failproof status logic
                    var status='{{trans("lang.closed")}}';
                    var statusclass="closed";

                    if (window.restaurantStatusManager) {
                        const workingHours = val.workingHours || [];
                        const isOpen = val.isOpen !== undefined ? val.isOpen : null;
                        const isOpenNow = window.restaurantStatusManager.isRestaurantOpenNow(workingHours, isOpen);
                        if (isOpenNow) {
                            status='{{trans("lang.open")}}';
                            statusclass="open";
                        }
                    } else {
                        // Fallback to old logic
                        var days=['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
                        var currentdate=new Date();
                        var currentDay=days[currentdate.getDay()];
                        hour=currentdate.getHours();
                        minute=currentdate.getMinutes();
                        if(hour<10) {
                            hour='0'+hour
                        }
                        if(minute<10) {
                            minute='0'+minute
                        }
                        var currentHours=hour+':'+minute;
                        if(val.hasOwnProperty('workingHours')) {
                            for(i=0;i<val.workingHours.length;i++) {
                                var day=val.workingHours[i]['day'];
                                if(val.workingHours[i]['day']==currentDay) {
                                    if(val.workingHours[i]['timeslot'].length!=0) {
                                        for(j=0;j<val.workingHours[i]['timeslot'].length;j++) {
                                            var timeslot=val.workingHours[i]['timeslot'][j];
                                            var from=timeslot[`from`];
                                            var to=timeslot[`to`];
                                            if(currentHours>=from&&currentHours<=to) {
                                                status='{{trans("lang.open")}}';
                                                statusclass="open";
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    var vendor_id_single=val.id;
                    <?php if (isset($_GET['dinein']) && @$_GET['dinein'] == 1) { ?>
                    var view_vendor_details="{{ route('dyiningrestaurant', ':id')}}";
                    <?php } else { ?>
                    var view_vendor_details = "/restaurant/" + val.id + "/" + val.restaurant_slug + "/" + val.zone_slug;
                    <?php } ?>
                    view_vendor_details=view_vendor_details.replace(':id','id='+vendor_id_single);
                    count++;
                    html=html+'<div class="col-md-3 pb-3"><div class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm"><div class="list-card-image">';
                    if(val.photo!=""&&val.photo!=null) {
                        photo=val.photo;
                    } else {
                        photo=placeholderImageSrc;
                    }
                    html=html+'<div class="member-plan position-absolute"><span class="badge badge-dark '+statusclass+'">'+status+'</span></div><div class="offer-icon position-absolute free-delivery-'+val.id+'"></div><a href="'+view_vendor_details+'"><img onerror="this.onerror=null;this.src=\''+placeholderImage+'\'" alt="#" src="'+photo+'" class="img-fluid item-img w-100"></a></div><div class="p-3 position-relative"><div class="list-card-body"><h6 class="mb-1"><a href="'+view_vendor_details+'" class="text-black">'+val.title+'</a></h6>';
                    html=html+'<p class="text-gray mb-1 small"><span class="fa fa-map-marker"></span> '+val.location+'</p>';
                    html=html+'<div class="star position-relative mt-3"><span class="badge badge-success"><i class="feather-star"></i>'+rating+' ('+reviewsCount+')</span></div>';
                    html=html+'</div>';
                    html=html+'</div></div></div>';
                }
                checkSelfDeliveryForVendor(val.id);
            });
            html=html+'</div>';
        } else {
            html=html+"<h5 class='font-weight-bold text-center mt-3'>{{trans('lang.no_results')}}</h5>";
        }
        return html;
    }
    async function moreload() {
        if(start!=undefined||start!=null) {
            jQuery("#data-table_processing").hide();
            listener=nearestRestauantRefnew.startAfter(start).limit(pagesize).get();
            listener.then(async (snapshots) => {
                html='';
                html=await buildHTMLNearestRestaurant(snapshots);
                jQuery("#data-table_processing").hide();
                if(html!='') {
                    all_stores.innerHTML+=html;
                    start=snapshots.docs[snapshots.docs.length-1];
                    if(endarray.indexOf(snapshots.docs[0])!=-1) {
                        endarray.splice(endarray.indexOf(snapshots.docs[0]),1);
                    }
                    endarray.push(snapshots.docs[0]);
                    if(snapshots.docs.length<pagesize) {
                        jQuery("#loadmore").hide();
                    } else {
                        jQuery("#loadmore").show();
                    }
                }
            });
        }
    }
    async function prev() {
        if(endarray.length==1) {
            return false;
        }
        end=endarray[endarray.length-2];
        if(end!=undefined||end!=null) {
            jQuery("#data-table_processing").show();
            listener=ref.startAt(end).limit(pagesize).get();
            listener.then(async (snapshots) => {
                html='';
                html=await buildHTML(snapshots);
                jQuery("#data-table_processing").hide();
                if(html!='') {
                    append_list.innerHTML=html;
                    start=snapshots.docs[snapshots.docs.length-1];
                    endarray.splice(endarray.indexOf(endarray[endarray.length-1]),1);
                    if(snapshots.docs.length<pagesize) {
                        jQuery("#users_table_previous_btn").hide();
                    }
                }
            });
        }
    }
     function checkSelfDeliveryForVendor(vendorId){
        setTimeout(function() {
        database.collection('vendors').doc(vendorId).get().then(async function(snapshots){
            if(snapshots.exists){
                var data=snapshots.data();
                if(data.hasOwnProperty('isSelfDelivery') && data.isSelfDelivery!=null && data.isSelfDelivery!=''){
                    if(data.isSelfDelivery && isSelfDeliveryGlobally){
                        $('.free-delivery-'+vendorId).html('<span><img src="{{asset('img/free_delivery.png')}}" width="100px" > {{trans("lang.free_delivery")}}</span>');
                    }
                }
            }
        })
        }, 3000);
    }
</script>

<style>
    /* Unified Restaurant Card Styles - Same as home.blade.php */
    #all_stores {
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
        letter-spacing: 0.8px;
        color: white;
        border: 2px solid rgba(255,255,255,0.3);
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
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
        justify-content: flex-start;
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
        color: #2c3e50;
        font-size: 1rem;
    }

    .rating-badges {
        display: flex;
        gap: 8px;
    }

    .rating-badge {
        display: flex;
        align-items: center;
        gap: 4px;
        padding: 6px 10px;
        background: linear-gradient(135deg, #27ae60, #2ecc71);
        color: white;
        border-radius: 12px;
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
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
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
        #all_stores {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        .restaurant-card {
            margin: 0 0.5rem;
        }
    }

    @media (min-width: 769px) and (max-width: 1024px) {
        #all_stores {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (min-width: 1025px) {
        #all_stores {
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        }
    }

    /* Pagination Styles */
    .pagination-wrapper {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-top: 30px;
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
    }

    .pagination-controls button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .pagination-controls button:not(:disabled):hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .pagination-info {
        font-size: 14px;
        color: #666;
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
            background: #2d3238;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .pagination-info {
            color: #ccc;
        }
    }
</style>
