@include('layouts.app')
@include('layouts.header')
<div class="st-brands-page pt-5 category-listing-page category">
    <div class="container">
        <div class="d-flex align-items-center mb-3 page-title">
            <h3 class="font-weight-bold text-dark" id="title"></h3>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div id="brand-list"></div>
                <div id="category-list"></div>
            </div>
            <div class="col-md-9">
                <div id="store-list"></div>
            </div>
        </div>
    </div>
</div>
@include('layouts.footer')
<script type="text/javascript">
    var id = '<?php echo $id; ?>';
    var idRef = database.collection('vendor_categories').doc(id);
    var catsRef = database.collection('vendor_categories').where('publish', '==', true);
    var placeholderImageRef = database.collection('settings').doc('placeHolderImage');
    var placeholderImageSrc = '';
    placeholderImageRef.get().then(async function(placeholderImageSnapshots) {
        var placeHolderImageData = placeholderImageSnapshots.data();
        placeholderImageSrc = placeHolderImageData.image;
    })
    idRef.get().then(async function(idRefSnapshots) {
        var idRefData = idRefSnapshots.data();
        $("#title").text(idRefData.title + ' ' + "{{ trans('lang.stores') }}");
    })
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
    var refCurrency = database.collection('currencies').where('isActive', '==', true);
    refCurrency.get().then(async function(snapshots) {
        var currencyData = snapshots.docs[0].data();
        currentCurrency = currencyData.symbol;
        currencyAtRight = currencyData.symbolAtRight;
        if (currencyData.decimal_degits) {
            decimal_degits = currencyData.decimal_degits;
        }
    });
    jQuery("#data-table_processing").show();
    $(document).ready(async function() {
        // Retrieve all invalid vendors
        await checkVendors().then(expiredStores => {
            inValidVendors = expiredStores;
        });
        getCategories();
        
        let isProcessing = false;
        $(document).on("click", ".category-item", async function(e) {
            if (isProcessing) {
                console.log('Already processing a category click, ignoring...');
                return;
            }
            
            if (!$(this).hasClass('active')) {
                isProcessing = true;
                $(this).addClass('active').siblings().removeClass('active');
                await getStores($(this).data('category-id'));
                isProcessing = false;
            }
        });
    });
    async function getCategories() {
        catsRef.get().then(async function(snapshots) {
            if (snapshots != undefined) {
                var html = '';
                html = buildCategoryHTML(snapshots);
                if (html != '') {
                    var append_list = document.getElementById('category-list');
                    append_list.innerHTML = html;
                    var category_id = $('#category-list .active').data('category-id');
                    if (category_id) {
                        getStores(category_id);
                        jQuery("#data-table_processing").hide();
                    }
                }
            }
        });
    }

    function buildCategoryHTML(snapshots) {
        var html = '';
        var alldata = [];
        snapshots.docs.forEach((listval) => {
            var datas = listval.data();
            datas.id = listval.id;
            alldata.push(datas);
        });
        html = html + '<div class="vandor-sidebar">';
        html = html + '<h3>{{ trans('lang.categories') }}</h3>';
        html = html + '<ul class="vandorcat-list">';
        alldata.forEach((listval) => {
            var val = listval;
            if (val.photo != "" && val.photo != null) {
                photo = val.photo;
            } else {
                photo = placeholderImageSrc;
            }
            if (id == val.id) {
                html = html + '<li class="category-item active" data-category-id="' + val.id + '">';
            } else {
                html = html + '<li class="category-item" data-category-id="' + val.id + '">';
            }
            html = html + '<a href="javascript:void(0)"><span><img onerror="this.onerror=null;this.src=\'' +
                placeholderImage + '\'" src="' + photo + '"></span>' + val.title + '</a>';
            html = html + '</li>';
        });
        html = html + '</ul>';
        return html;
    }
    async function getStores(id) {
        jQuery("#data-table_processing").show();
        
        var store_list = document.getElementById('store-list');
        store_list.innerHTML = '';
        var html = '';

        try {
            console.log('Starting store fetch for category:', id);
            
            // Ensure we have the zone ID
            if (!user_zone_id) {
                console.log('Waiting for zone ID...');
                await new Promise(resolve => {
                    const checkZone = setInterval(() => {
                        if (user_zone_id) {
                            clearInterval(checkZone);
                            resolve();
                        }
                    }, 1000);
                });
            }
            console.log('Using zone ID:', user_zone_id);

            // Query for both array-contains and direct equality
            const arrayQuery = database.collection('vendors')
                .where('categoryID', 'array-contains', id);
            
            const stringQuery = database.collection('vendors')
                .where('categoryID', '==', id);

            console.log('Executing queries...');
            
            // Execute both queries
            const [arrayResults, stringResults] = await Promise.all([
                arrayQuery.get(),
                stringQuery.get()
            ]);

            console.log('Query results received:', {
                arrayResults: arrayResults.size,
                stringResults: stringResults.size
            });

            // Combine results, removing duplicates
            const vendorIds = new Set();
            const allVendors = [];

            // Add array results
            arrayResults.docs.forEach(doc => {
                const data = doc.data();
                console.log('Array result vendor:', {
                    id: doc.id,
                    categoryID: data.categoryID,
                    zoneId: data.zoneId
                });
                if (!vendorIds.has(doc.id)) {
                    vendorIds.add(doc.id);
                    allVendors.push(doc);
                }
            });

            // Add string results
            stringResults.docs.forEach(doc => {
                const data = doc.data();
                console.log('String result vendor:', {
                    id: doc.id,
                    categoryID: data.categoryID,
                    zoneId: data.zoneId
                });
                if (!vendorIds.has(doc.id)) {
                    vendorIds.add(doc.id);
                    allVendors.push(doc);
                }
            });

            console.log('Combined unique vendors:', allVendors.length);

            // Filter by zone
            const zoneFilteredVendors = allVendors.filter(doc => {
                const data = doc.data();
                const matches = data.zoneId === user_zone_id;
                console.log('Zone check for vendor:', {
                    vendorId: doc.id,
                    vendorZone: data.zoneId,
                    userZone: user_zone_id,
                    matches: matches
                });
                return matches;
            });

            console.log('Final vendors after zone filter:', zoneFilteredVendors.length);

            // Build HTML with filtered vendors
            if (zoneFilteredVendors.length > 0) {
                // Create a snapshot-like object for buildStoresHTML
                const filteredSnapshot = {
                    docs: zoneFilteredVendors,
                    size: zoneFilteredVendors.length
                };
                html = buildStoresHTML(filteredSnapshot);
            } else {
                html = "<h5 class='text-center font-weight-bold mt-3'>{{ trans('lang.no_results') }}</h5>";
            }
            
            store_list.innerHTML = html;
        } catch(error) {
            console.error('Error in getStores:', error);
            console.error('Error details:', {
                message: error.message,
                code: error.code,
                stack: error.stack
            });
            store_list.innerHTML = "<h5 class='text-center font-weight-bold mt-3'>Error loading stores</h5>";
        } finally {
            jQuery("#data-table_processing").hide();
        }
    }

    function buildStoresHTML(snapshots) {
        var html = '';
        var alldata = [];
		
        snapshots.docs.forEach((listval) => {
            var datas = listval.data();
            datas.id = listval.id;
            if (!inValidVendors.has(listval.id)) {
                // Assign random rating and count if zero or missing
                var rating = 0;
                var reviewsCount = 0;
                if (datas.hasOwnProperty('reviewsSum') && datas.reviewsSum != 0 && datas.reviewsSum != null && datas.reviewsSum != '' && datas.hasOwnProperty('reviewsCount') && datas.reviewsCount != 0 && datas.reviewsCount!=null && datas.reviewsCount != '') {
                    rating = (datas.reviewsSum / datas.reviewsCount);
                    rating = Math.round(rating * 10) / 10;
                    reviewsCount = datas.reviewsCount;
                } else {
                    rating = (Math.random() * (5.0 - 4.1) + 4.1).toFixed(1);
                    reviewsCount = Math.floor(Math.random() * (25 - 11 + 1)) + 11;
                }
                datas.rating = rating;
                datas.reviewsCount = reviewsCount;
                alldata.push(datas);
            }
        });
        var count = 0;
        var popularFoodCount = 0;
        if (alldata.length > 0) {
            html = html + '<div class="row">';
            alldata.forEach((listval) => {
                var val = listval;
                html = html +
                    '<div class="col-md-4 pb-3 product-list"><div class="list-card position-relative"><div class="list-card-image">';
                var status = 'Closed';
                var statusclass = "closed";
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
                                        status = 'Open';
                                        statusclass = "open";
                                    }
                                }
                            }
                        }
                    }
                }
                if (val.photo != "" && val.photo != null) {
                    photo = val.photo;
                } else {
                    photo = placeholderImageSrc;
                }
                var view_vendor_details = "{{ route('restaurant', ':id') }}";
                view_vendor_details = view_vendor_details.replace(':id', 'id=' + val.id);
                html = html + '<div class="member-plan position-absolute"><span class="badge badge-dark ' +
                    statusclass + '">' + status + '</span></div><div class="offer-icon position-absolute free-delivery-'+val.id+'"></div><a href="' + view_vendor_details +
                    '"><img onerror="this.onerror=null;this.src=\'' + placeholderImage + '\'" alt="#" src="' +
                    photo +
                    '" class="img-fluid item-img w-100"></a></div><div class="py-2 position-relative"><div class="list-card-body"><h6 class="mb-1"><a href="' +
                    view_vendor_details + '" class="text-black">' + val.title + '</a></h6><h6>' + val.location +
                    '</h6>';
                html = html +
                    '<div class="star position-relative mt-3"><span class="badge badge-success"><i class="feather-star"></i>' +
                    val.rating + ' (' + val.reviewsCount + ')</span></div>';
                html = html + '</div>';
                html = html + '</div></div></div>';
                checkSelfDeliveryForVendor(val.id);
            });
            html = html + '</div>';
        } else {
            html = html + "<h5 class='text-center font-weight-bold mt-3'>{{ trans('lang.no_results') }}</h5>";
        }
        return html;
    }
    function checkSelfDeliveryForVendor(vendorId){
        setTimeout(function() {
        database.collection('vendors').doc(vendorId).get().then(async function(snapshots){
            if(snapshots.exists){
                var data=snapshots.data();
                if(data.hasOwnProperty('isSelfDelivery') && data.isSelfDelivery!=null && data.isSelfDelivery!=''){
                    if(data.isSelfDelivery && isSelfDeliveryGlobally){
                        console.log(vendorId)
                        $('.free-delivery-'+vendorId).html('<span><img src="{{asset('img/free_delivery.png')}}" width="100px"> {{trans("lang.free_delivery")}}</span>');
                    }
                }
            }
        })
        }, 3000);
    }
</script>
@include('layouts.nav')
