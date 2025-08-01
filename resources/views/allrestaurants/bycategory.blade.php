@include('layouts.app')
@include('layouts.header')
<style>
/* Clean, modern styling that matches JippyMart design */
.bg-white {
    background-color: #ffffff !important;
}

.rounded {
    border-radius: 8px !important;
}

.shadow-sm {
    box-shadow: 0 2px 4px rgba(0,0,0,0.08) !important;
}

/* Search bar styling */
.input-group-text {
    border: 1px solid #e9ecef;
    background-color: #ffffff;
}

.input-group .form-control {
    border: 1px solid #e9ecef;
}

.input-group .form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

/* Button styling */
.btn-outline-primary {
    border-color: #28a745;
    color: #28a745;
}

.btn-outline-primary:hover {
    background-color: #28a745;
    border-color: #28a745;
    color: white;
}

.btn-outline-secondary {
    border-color: #6c757d;
    color: #6c757d;
}

.btn-outline-secondary:hover {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
}

/* Checkbox styling */
.form-check-input:checked {
    background-color: #28a745;
    border-color: #28a745;
}

.form-check-label {
    color: #495057;
    font-weight: 500;
}

/* Sidebar height and scroll fixes */
.category-listing-page {
    min-height: calc(100vh - 200px);
}

.category-listing-page .col-md-3 {
    position: sticky;
    top: 20px;
    height: calc(100vh - 250px);
    overflow-y: auto;
    padding-right: 15px;
}

.category-listing-page .col-md-3::-webkit-scrollbar {
    width: 6px;
}

.category-listing-page .col-md-3::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.category-listing-page .col-md-3::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.category-listing-page .col-md-3::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Category list styling */
.vandorcat-list {
    max-height: none;
    overflow-y: visible;
}

.vandorcat-list li {
    margin-bottom: 8px;
}

.vandorcat-list li a {
    display: flex;
    align-items: center;
    padding: 8px 12px;
    border-radius: 6px;
    transition: all 0.3s ease;
    text-decoration: none;
    color: #333;
    justify-content: space-between;
    position: relative;
}

.vandorcat-list li a:hover {
    background-color: #f8f9fa;
    transform: translateX(3px);
}

.vandorcat-list li.active a {
    background-color: #28a745;
    color: white;
}

.vandorcat-list li a span {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    overflow: hidden;
    margin-right: 10px;
    flex-shrink: 0;
}

.vandorcat-list li a span img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Restaurant count badge styling */
.vandorcat-list li a .badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    background-color: #6c757d;
    color: white;
    border-radius: 12px;
    min-width: 20px;
    text-align: center;
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    z-index: 1;
}

.vandorcat-list li.active a .badge {
    background-color: rgba(255, 255, 255, 0.2);
    color: white;
}

/* Category item content wrapper */
.vandorcat-list li a .category-content {
    display: flex;
    align-items: center;
    flex: 1;
    padding-right: 40px; /* Make space for the badge */
}

/* Main content area */
.category-listing-page .col-md-9 {
    min-height: calc(100vh - 250px);
}

/* Category search styling */
#categorySearch {
    border: 1px solid #e9ecef;
    border-radius: 6px;
    padding: 8px 12px;
    background-color: #fff;
}

#categorySearch:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    outline: none;
}

/* Ensure sidebar content doesn't overflow */
.category-listing-page .col-md-3 > div {
    margin-bottom: 15px;
}

/* Pagination styling */
.pagination .page-link {
    color: #28a745;
    border: 1px solid #e9ecef;
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}

.pagination .page-item.active .page-link {
    background-color: #28a745;
    border-color: #28a745;
    color: white;
}

.pagination .page-item.disabled .page-link {
    color: #6c757d;
    background-color: #fff;
    border-color: #e9ecef;
}

.pagination .page-link:hover {
    background-color: #f8f9fa;
    border-color: #e9ecef;
    color: #1e7e34;
}

#resultsInfo {
    font-size: 0.875rem;
    color: #6c757d;
}

#itemsPerPageSelect {
    border: 1px solid #e9ecef;
    border-radius: 0.25rem;
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .category-listing-page .col-md-3 {
        position: static;
        height: auto;
        max-height: 300px;
        overflow-y: auto;
        margin-bottom: 20px;
    }
    
    .form-check-inline {
        margin-bottom: 0.5rem;
    }
}

/* Smooth transitions */
.form-control, .btn, .form-check-input {
    transition: all 0.2s ease;
}

/* Compact form controls */
.form-control-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    line-height: 1.2;
}

.form-control-sm.py-0 {
    padding: 0.125rem 0.5rem;
    font-size: 0.8rem;
    line-height: 1.1;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    line-height: 1.2;
}

.btn-sm.py-0 {
    padding: 0.125rem 0.5rem;
    font-size: 0.8rem;
    line-height: 1.1;
}

/* Active filters styling */
#activeFiltersSummary {
    background-color: #e7f3ff;
    border: 1px solid #b3d9ff;
    border-radius: 4px;
    padding: 6px 10px;
}

#activeFiltersSummary small {
    color: #0066cc;
}

/* Reduce spacing for compact layout */
.mb-1 {
    margin-bottom: 0.25rem !important;
}

.mb-2 {
    margin-bottom: 0.5rem !important;
}

.mb-3 {
    margin-bottom: 1rem !important;
}

.p-1 {
    padding: 0.25rem !important;
}

.p-2 {
    padding: 0.5rem !important;
}

/* Ultra-compact spacing */
.mt-1 {
    margin-top: 0.25rem !important;
}

.py-0 {
    padding-top: 0.125rem !important;
    padding-bottom: 0.125rem !important;
}
</style>
<div class="st-brands-page pt-5 category-listing-page category">
    <div class="container">
        <div class="d-flex align-items-center mb-3 page-title">
            <h3 class="font-weight-bold text-dark" id="title"></h3>
        </div>
        
        <!-- Clean Search and Filter Bar -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="bg-white rounded shadow-sm p-2">
                    <div class="row align-items-center">
                        <!-- Search -->
                        <div class="col-lg-4 col-md-6 mb-2 mb-md-0">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white border-right-0">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control border-left-0" id="searchInput" placeholder="Search restaurants...">
                            </div>
                        </div>
                        
                        <!-- Sort -->
                        <div class="col-lg-3 col-md-6 mb-2 mb-md-0">
                            <select class="form-control" id="sortSelect">
                                <option value="default">Sort by</option>
                                <option value="rating">Rating (High to Low)</option>
                                <option value="rating_asc">Rating (Low to High)</option>
                                <option value="name">Name (A-Z)</option>
                                <option value="name_desc">Name (Z-A)</option>
                                <option value="reviews">Most Reviews</option>
                            </select>
                        </div>
                        
                        <!-- Quick Filters -->
                        <div class="col-lg-5 col-md-12">
                            <div class="d-flex flex-wrap align-items-center">
                                <div class="form-check form-check-inline mr-3">
                                    <input class="form-check-input" type="checkbox" id="openNowFilter">
                                    <label class="form-check-label small" for="openNowFilter">
                                        <i class="fas fa-clock mr-1"></i>Open Now
                                    </label>
                                </div>
                                <div class="form-check form-check-inline mr-3">
                                    <input class="form-check-input" type="checkbox" id="freeDeliveryFilter">
                                    <label class="form-check-label small" for="freeDeliveryFilter">
                                        <i class="fas fa-truck mr-1"></i>Free Delivery
                                    </label>
                                </div>
                                <div class="form-check form-check-inline mr-3">
                                    <input class="form-check-input" type="checkbox" id="hasDiscountFilter">
                                    <label class="form-check-label small" for="hasDiscountFilter">
                                        <i class="fas fa-percent mr-1"></i>Has Discount
                                    </label>
                                </div>
                                <!-- Hidden for cleaner UI -->
                                <!-- <button class="btn btn-sm btn-outline-primary" id="moreFiltersBtn">
                                    <i class="fa fa-filter mr-1"></i>More Filters
                                </button> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Advanced Filters (Hidden by default for cleaner UI) -->
        <div class="row mb-2" id="advancedFiltersSection" style="display: none;">
            <div class="col-12">
                <div class="bg-white rounded shadow-sm p-1">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-dark small font-weight-bold">
                            <i class="fas fa-sliders-h mr-1"></i>Advanced Filters
                        </span>
                        <button class="btn btn-sm btn-outline-secondary py-0 px-2" id="clearFiltersBtn">
                            <i class="fas fa-times mr-1"></i>Clear All
                        </button>
                    </div>
                    
                    <div class="row">
                        <!-- Rating Filter -->
                        <div class="col-md-3 mb-1">
                            <label class="small text-muted mb-0">Rating Range</label>
                            <div class="d-flex align-items-center mt-1">
                                <input type="number" class="form-control form-control-sm py-0" id="minRating" placeholder="Min" min="0" max="5" step="0.1" style="width: 70px;">
                                <span class="mx-1 text-muted">-</span>
                                <input type="number" class="form-control form-control-sm py-0" id="maxRating" placeholder="Max" min="0" max="5" step="0.1" style="width: 70px;">
                            </div>
                        </div>
                        
                        <!-- Price Range Filter -->
                        <div class="col-md-3 mb-1">
                            <label class="small text-muted mb-0">Price Range</label>
                            <div class="d-flex align-items-center mt-1">
                                <input type="number" class="form-control form-control-sm py-0" id="minPrice" placeholder="Min" min="0" style="width: 80px;">
                                <span class="mx-1 text-muted">-</span>
                                <input type="number" class="form-control form-control-sm py-0" id="maxPrice" placeholder="Max" min="0" style="width: 80px;">
                            </div>
                        </div>
                        
                        <!-- Delivery Time Filter -->
                        <div class="col-md-3 mb-1">
                            <label class="small text-muted mb-0">Delivery Time</label>
                            <select class="form-control form-control-sm py-0 mt-1" id="deliveryTimeFilter">
                                <option value="any">Any Time</option>
                                <option value="fast">Fast (Under 30 min)</option>
                                <option value="medium">Medium (30-60 min)</option>
                                <option value="slow">Slow (Over 60 min)</option>
                            </select>
                        </div>
                        
                        <!-- Active Filters Display -->
                        <div class="col-md-3 mb-1 d-flex align-items-end">
                            <div id="activeFiltersSummary" style="display: none;">
                                <small class="text-info">
                                    <i class="fa fa-info-circle mr-1"></i>
                                    <span id="activeFiltersText"></span>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-3">
                <div id="brand-list"></div>
                <!-- Category Search -->
                <div class="mb-2">
                    <input type="text" class="form-control form-control-sm" id="categorySearch" placeholder="Search categories..." style="font-size: 14px;">
                </div>
                <div id="category-list">
                    <div class="text-center py-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading categories...</span>
                        </div>
                        <p class="mt-2 text-muted small">Sorting categories by restaurant count...</p>
                    </div>
                </div>
                <!-- Back to Top Button for Categories -->
                <div class="text-center mt-2" id="backToTopBtn" style="display: none;">
                    <button class="btn btn-sm btn-outline-primary" onclick="scrollToTop()">
                        <i class="fa fa-arrow-up"></i> Back to Top
                    </button>
                </div>
            </div>
            <div class="col-md-9">
                <div id="store-list"></div>
                <!-- Pagination Controls -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <label class="mr-2 mb-0">Show:</label>
                            <select class="form-control form-control-sm" id="itemsPerPageSelect" style="width: 80px;">
                                <option value="6">6</option>
                                <option value="12" selected>12</option>
                                <option value="24">24</option>
                                <option value="48">48</option>
                            </select>
                            <span class="ml-2 text-muted" id="resultsInfo">Showing 0 of 0 restaurants</span>
                        </div>
                        <!-- Load More Button (Mobile friendly) -->
                        <div class="mt-2 d-md-none">
                            <button class="btn btn-outline-primary btn-sm" id="loadMoreBtn" style="display: none;">
                                Load More Restaurants
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <nav aria-label="Restaurant pagination">
                            <ul class="pagination pagination-sm justify-content-end mb-0" id="paginationControls">
                                <!-- Pagination buttons will be generated here -->
                            </ul>
                        </nav>
                    </div>
                </div>
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
    
    // Search and Sort variables
    var currentVendors = []; // Store current vendors for filtering/sorting
    var searchTerm = '';
    var sortBy = 'default';
    
    // Pagination variables
    var currentPage = 1;
    var itemsPerPage = 12; // Number of restaurants per page
    var totalPages = 1;
    
    // Advanced Filter variables
    var minRating = 0;
    var maxRating = 5;
    var minPrice = 0;
    var maxPrice = 1000;
    var deliveryTime = 'any'; // any, fast (under 30min), medium (30-60min), slow (over 60min)
    var isOpenNow = false;
    var hasFreeDelivery = false;
    var hasDiscount = false;
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
        
        // Search functionality
        $('#searchBtn').on('click', function() {
            searchTerm = $('#searchInput').val().toLowerCase().trim();
            applyFiltersAndSort();
        });
        
        // Search on Enter key
        $('#searchInput').on('keypress', function(e) {
            if (e.which === 13) {
                searchTerm = $(this).val().toLowerCase().trim();
                applyFiltersAndSort();
            }
        });
        
        // Sort functionality
        $('#sortSelect').on('change', function() {
            sortBy = $(this).val();
            applyFiltersAndSort();
        });
        
        // Category search functionality
        $('#categorySearch').on('input', function() {
            var searchTerm = $(this).val().toLowerCase().trim();
            $('.category-item').each(function() {
                var categoryName = $(this).find('a').text().toLowerCase();
                if (categoryName.includes(searchTerm)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
        
        // Back to top button functionality
        $('.col-md-3').on('scroll', function() {
            if ($(this).scrollTop() > 100) {
                $('#backToTopBtn').show();
            } else {
                $('#backToTopBtn').hide();
            }
        });
        
        // Pagination functionality
        $('#itemsPerPageSelect').on('change', function() {
            itemsPerPage = parseInt($(this).val());
            currentPage = 1; // Reset to first page
            applyFiltersAndSort();
        });
        
        // Pagination click handlers
        $(document).on('click', '.page-link', function(e) {
            e.preventDefault();
            var page = $(this).data('page');
            if (page && page !== currentPage) {
                currentPage = page;
                applyFiltersAndSort();
            }
        });
        
        // Load more functionality
        $('#loadMoreBtn').on('click', function() {
            if (currentPage < totalPages) {
                currentPage++;
                applyFiltersAndSort(true); // true = append mode
            }
        });
        
        // Advanced Filter Event Handlers
        $('#minRating, #maxRating').on('input', function() {
            minRating = parseFloat($('#minRating').val()) || 0;
            maxRating = parseFloat($('#maxRating').val()) || 5;
            currentPage = 1;
            applyFiltersAndSort();
        });
        
        $('#minPrice, #maxPrice').on('input', function() {
            minPrice = parseFloat($('#minPrice').val()) || 0;
            maxPrice = parseFloat($('#maxPrice').val()) || 1000;
            currentPage = 1;
            applyFiltersAndSort();
        });
        
        $('#deliveryTimeFilter').on('change', function() {
            deliveryTime = $(this).val();
            currentPage = 1;
            applyFiltersAndSort();
        });
        
        $('#openNowFilter').on('change', function() {
            isOpenNow = $(this).is(':checked');
            currentPage = 1;
            applyFiltersAndSort();
        });
        
        $('#freeDeliveryFilter').on('change', function() {
            hasFreeDelivery = $(this).is(':checked');
            currentPage = 1;
            applyFiltersAndSort();
        });
        
        $('#hasDiscountFilter').on('change', function() {
            hasDiscount = $(this).is(':checked');
            currentPage = 1;
            applyFiltersAndSort();
        });
        
        // More filters toggle
        $('#moreFiltersBtn').on('click', function() {
            var isVisible = $('#advancedFiltersSection').is(':visible');
            if (isVisible) {
                $('#advancedFiltersSection').slideUp(300);
                $(this).html('<i class="fa fa-filter mr-1"></i>More Filters');
            } else {
                $('#advancedFiltersSection').slideDown(300);
                $(this).html('<i class="fa fa-times mr-1"></i>Hide Filters');
            }
        });
        
        // Clear all filters
        $('#clearFiltersBtn').on('click', function() {
            // Reset all filter inputs
            $('#minRating, #maxRating').val('');
            $('#minPrice, #maxPrice').val('');
            $('#deliveryTimeFilter').val('any');
            $('#openNowFilter, #freeDeliveryFilter, #hasDiscountFilter').prop('checked', false);
            
            // Reset filter variables
            minRating = 0;
            maxRating = 5;
            minPrice = 0;
            maxPrice = 1000;
            deliveryTime = 'any';
            isOpenNow = false;
            hasFreeDelivery = false;
            hasDiscount = false;
            
            currentPage = 1;
            applyFiltersAndSort();
        });
    });
    async function getCategories() {
        // Wait for user_zone_id to be available
        if (!user_zone_id) {
            console.log('Waiting for zone ID...');
            await new Promise(resolve => {
                const checkZone = setInterval(() => {
                    if (user_zone_id) {
                        clearInterval(checkZone);
                        resolve();
                    }
                }, 100);
            });
        }
        
        catsRef.get().then(async function(snapshots) {
            if (snapshots != undefined) {
                var html = '';
                html = await buildCategoryHTML(snapshots);
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

    async function buildCategoryHTML(snapshots) {
        var html = '';
        var alldata = [];
        snapshots.docs.forEach((listval) => {
            var datas = listval.data();
            datas.id = listval.id;
            alldata.push(datas);
        });

        // Get restaurant count for each category
        var categoriesWithCount = [];
        
        // Create all queries at once for better performance
        const queries = [];
        for (let category of alldata) {
            const arrayQuery = database.collection('vendors')
                .where('categoryID', 'array-contains', category.id)
                .where('zoneId', '==', user_zone_id);
            
            const stringQuery = database.collection('vendors')
                .where('categoryID', '==', category.id)
                .where('zoneId', '==', user_zone_id);
            
            queries.push({ category, arrayQuery, stringQuery });
        }
        
        // Execute all queries in parallel
        const results = await Promise.allSettled(
            queries.map(async ({ category, arrayQuery, stringQuery }) => {
                try {
                    const [arrayResults, stringResults] = await Promise.all([
                        arrayQuery.get(),
                        stringQuery.get()
                    ]);

                    // Combine results, removing duplicates
                    const vendorIds = new Set();
                    arrayResults.docs.forEach(doc => {
                        if (!vendorIds.has(doc.id)) {
                            vendorIds.add(doc.id);
                        }
                    });
                    stringResults.docs.forEach(doc => {
                        if (!vendorIds.has(doc.id)) {
                            vendorIds.add(doc.id);
                        }
                    });

                    category.restaurantCount = vendorIds.size;
                    return category;
                } catch (error) {
                    console.error('Error getting restaurant count for category:', category.id, error);
                    category.restaurantCount = 0;
                    return category;
                }
            })
        );
        
        // Collect successful results
        results.forEach(result => {
            if (result.status === 'fulfilled') {
                categoriesWithCount.push(result.value);
            } else {
                console.error('Failed to get category data:', result.reason);
            }
        });

        // Sort categories: selected category first, then by restaurant count (descending)
        categoriesWithCount.sort((a, b) => {
            // First priority: selected category (id matches the current category)
            if (a.id === id) return -1;
            if (b.id === id) return 1;
            
            // Second priority: restaurant count (descending)
            return b.restaurantCount - a.restaurantCount;
        });

        html = html + '<div class="vandor-sidebar">';
        html = html + '<h3>{{ trans('lang.categories') }}</h3>';
        html = html + '<ul class="vandorcat-list">';
        categoriesWithCount.forEach((listval) => {
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
            html = html + '<a href="javascript:void(0)"><div class="category-content"><span><img onerror="this.onerror=null;this.src=\'' +
                placeholderImage + '\'" src="' + photo + '"></span>' + val.title + '</div><span class="badge badge-secondary">' + val.restaurantCount + '</span></a>';
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

            // Store vendors for filtering/sorting
            currentVendors = zoneFilteredVendors;
            
            // Apply filters and sort
            applyFiltersAndSort();
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
    
    // Apply search filter and sorting
    function applyFiltersAndSort(appendMode = false) {
        var store_list = document.getElementById('store-list');
        var html = '';
        
        if (currentVendors.length === 0) {
            store_list.innerHTML = "<h5 class='text-center font-weight-bold mt-3'>{{ trans('lang.no_results') }}</h5>";
            updatePaginationInfo(0, 0);
            return;
        }
        
        // Filter by search term
        var filteredVendors = currentVendors.filter(doc => {
            const data = doc.data();
            const searchFields = [
                data.title || '',
                data.location || '',
                data.description || ''
            ].join(' ').toLowerCase();
            
            return searchTerm === '' || searchFields.includes(searchTerm);
        });
        
        // Apply advanced filters
        filteredVendors = filteredVendors.filter(doc => {
            const data = doc.data();
            
            // Rating filter
            const rating = data.rating || 0;
            if (rating < minRating || rating > maxRating) {
                return false;
            }
            
            // Price filter (if vendor has price data)
            if (data.hasOwnProperty('averagePrice')) {
                const price = data.averagePrice || 0;
                if (price < minPrice || price > maxPrice) {
                    return false;
                }
            }
            
            // Delivery time filter (if vendor has delivery time data)
            if (deliveryTime !== 'any' && data.hasOwnProperty('deliveryTime')) {
                const deliveryTimeMinutes = data.deliveryTime || 60;
                switch(deliveryTime) {
                    case 'fast':
                        if (deliveryTimeMinutes > 30) return false;
                        break;
                    case 'medium':
                        if (deliveryTimeMinutes < 30 || deliveryTimeMinutes > 60) return false;
                        break;
                    case 'slow':
                        if (deliveryTimeMinutes < 60) return false;
                        break;
                }
            }
            
            // Open now filter
            if (isOpenNow) {
                const isCurrentlyOpen = checkIfOpenNow(data);
                if (!isCurrentlyOpen) return false;
            }
            
            // Free delivery filter
            if (hasFreeDelivery) {
                const hasFreeDeliveryOption = data.hasOwnProperty('isSelfDelivery') && data.isSelfDelivery;
                if (!hasFreeDeliveryOption) return false;
            }
            
            // Has discount filter
            if (hasDiscount) {
                const hasDiscountOption = data.hasOwnProperty('hasDiscount') && data.hasDiscount;
                if (!hasDiscountOption) return false;
            }
            
            return true;
        });
        
        // Sort vendors
        filteredVendors.sort((a, b) => {
            const dataA = a.data();
            const dataB = b.data();
            
            switch(sortBy) {
                case 'rating':
                    return (dataB.rating || 0) - (dataA.rating || 0);
                case 'rating_asc':
                    return (dataA.rating || 0) - (dataB.rating || 0);
                case 'name':
                    return (dataA.title || '').localeCompare(dataB.title || '');
                case 'name_desc':
                    return (dataB.title || '').localeCompare(dataA.title || '');
                case 'reviews':
                    return (dataB.reviewsCount || 0) - (dataA.reviewsCount || 0);
                default:
                    return 0; // No sorting
            }
        });
        
        // Calculate pagination
        totalPages = Math.ceil(filteredVendors.length / itemsPerPage);
        if (currentPage > totalPages) {
            currentPage = totalPages || 1;
        }
        
        // Get vendors for current page
        var startIndex = (currentPage - 1) * itemsPerPage;
        var endIndex = startIndex + itemsPerPage;
        var paginatedVendors = filteredVendors.slice(startIndex, endIndex);
        
        // Update pagination info
        updatePaginationInfo(filteredVendors.length, startIndex + 1, endIndex);
        
        // Build HTML with paginated vendors
        if (paginatedVendors.length > 0) {
            const paginatedSnapshot = {
                docs: paginatedVendors,
                size: paginatedVendors.length
            };
            html = buildStoresHTML(paginatedSnapshot);
        } else {
            html = "<h5 class='text-center font-weight-bold mt-3'>No restaurants found matching your search.</h5>";
        }
        
        if (appendMode) {
            // Append to existing content for load more
            store_list.innerHTML += html;
        } else {
            // Replace content for normal pagination
            store_list.innerHTML = html;
        }
        
        generatePaginationControls();
        updateLoadMoreButton();
        updateActiveFiltersSummary();
    }
    
    // Scroll to top function for category list
    function scrollToTop() {
        $('.col-md-3').animate({
            scrollTop: 0
        }, 300);
    }
    
    // Update pagination info display
    function updatePaginationInfo(totalItems, startItem, endItem) {
        if (totalItems === 0) {
            $('#resultsInfo').text('Showing 0 of 0 restaurants');
        } else {
            $('#resultsInfo').text(`Showing ${startItem}-${endItem} of ${totalItems} restaurants`);
        }
    }
    
    // Generate pagination controls
    function generatePaginationControls() {
        var paginationHtml = '';
        
        if (totalPages <= 1) {
            $('#paginationControls').html('');
            return;
        }
        
        // Previous button
        if (currentPage > 1) {
            paginationHtml += `<li class="page-item">
                <a class="page-link" href="#" data-page="${currentPage - 1}" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>`;
        } else {
            paginationHtml += `<li class="page-item disabled">
                <span class="page-link" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </span>
            </li>`;
        }
        
        // Page numbers
        var startPage = Math.max(1, currentPage - 2);
        var endPage = Math.min(totalPages, currentPage + 2);
        
        if (startPage > 1) {
            paginationHtml += `<li class="page-item">
                <a class="page-link" href="#" data-page="1">1</a>
            </li>`;
            if (startPage > 2) {
                paginationHtml += `<li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>`;
            }
        }
        
        for (var i = startPage; i <= endPage; i++) {
            if (i === currentPage) {
                paginationHtml += `<li class="page-item active">
                    <span class="page-link">${i}</span>
                </li>`;
            } else {
                paginationHtml += `<li class="page-item">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>`;
            }
        }
        
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                paginationHtml += `<li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>`;
            }
            paginationHtml += `<li class="page-item">
                <a class="page-link" href="#" data-page="${totalPages}">${totalPages}</a>
            </li>`;
        }
        
        // Next button
        if (currentPage < totalPages) {
            paginationHtml += `<li class="page-item">
                <a class="page-link" href="#" data-page="${currentPage + 1}" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>`;
        } else {
            paginationHtml += `<li class="page-item disabled">
                <span class="page-link" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </span>
            </li>`;
        }
        
        $('#paginationControls').html(paginationHtml);
    }
    
    // Update load more button visibility
    function updateLoadMoreButton() {
        if (currentPage < totalPages) {
            $('#loadMoreBtn').show();
        } else {
            $('#loadMoreBtn').hide();
        }
    }
    
    // Check if restaurant is currently open
    function checkIfOpenNow(vendorData) {
        if (!vendorData.hasOwnProperty('workingHours')) {
            return false;
        }
        
        const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        const currentDate = new Date();
        const currentDay = days[currentDate.getDay()];
        const currentHours = currentDate.getHours();
        const currentMinutes = currentDate.getMinutes();
        const currentTime = (currentHours * 100) + currentMinutes; // Convert to HHMM format
        
        for (let i = 0; i < vendorData.workingHours.length; i++) {
            const day = vendorData.workingHours[i];
            if (day.day === currentDay && day.timeslot && day.timeslot.length > 0) {
                for (let j = 0; j < day.timeslot.length; j++) {
                    const timeslot = day.timeslot[j];
                    const fromTime = convertTimeToMinutes(timeslot.from);
                    const toTime = convertTimeToMinutes(timeslot.to);
                    const currentTimeMinutes = (currentHours * 60) + currentMinutes;
                    
                    if (currentTimeMinutes >= fromTime && currentTimeMinutes <= toTime) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
    
    // Convert time string (HH:MM) to minutes
    function convertTimeToMinutes(timeStr) {
        const parts = timeStr.split(':');
        return (parseInt(parts[0]) * 60) + parseInt(parts[1]);
    }
    
    // Update active filters summary
    function updateActiveFiltersSummary() {
        var activeFilters = [];
        
        // Check rating filter
        if (minRating > 0 || maxRating < 5) {
            activeFilters.push(`Rating: ${minRating}-${maxRating}★`);
        }
        
        // Check price filter
        if (minPrice > 0 || maxPrice < 1000) {
            activeFilters.push(`Price: $${minPrice}-$${maxPrice}`);
        }
        
        // Check delivery time filter
        if (deliveryTime !== 'any') {
            const deliveryLabels = {
                'fast': 'Fast (Under 30 min)',
                'medium': 'Medium (30-60 min)',
                'slow': 'Slow (Over 60 min)'
            };
            activeFilters.push(`Delivery: ${deliveryLabels[deliveryTime]}`);
        }
        
        // Check quick filters
        if (isOpenNow) activeFilters.push('Open Now');
        if (hasFreeDelivery) activeFilters.push('Free Delivery');
        if (hasDiscount) activeFilters.push('Has Discount');
        
        // Show/hide summary and update text
        if (activeFilters.length > 0) {
            $('#activeFiltersSummary').show();
            $('#activeFiltersText').text(activeFilters.join(', '));
        } else {
            $('#activeFiltersSummary').hide();
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
                var view_vendor_details = "/restaurant/" + val.id + "/" + val.restaurant_slug + "/" + val.zone_slug;
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
