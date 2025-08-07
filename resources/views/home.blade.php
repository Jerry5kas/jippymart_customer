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
        <section class="restaurant_stories">
            <div class="container swiper-stories">
                <div id="stories" class="storiesWrapper swiper-wrapper"></div>
            </div>
        </section>
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
        <section class="top-categories highlights-section d-none" style="display: none;">
            <div class="container">
                <div class="highlights-section-inner">
                    <div class="title d-flex align-items-center border-0 mb-0">
                        <h5>{{ trans('lang.highlights_for_you') }}</h5>
                    </div>
                    <div class="row">
                        <div class="highlights-slider highlights" id="highlights">

                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="most-popular-item-section" style="display: none;">
            <div class="container">
                <div class="title d-flex align-items-center">
                    <h5>{{ trans('lang.popular') }} {{ trans('lang.item') }}</h5>
                    <span class="see-all ml-auto">
                        <a href="{{ route('productlist.all') }}">{{ trans('lang.see_all') }}</a>
                    </span>
                </div>
                <div id="most_popular_item"></div>
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
        <section class="new-arrivals-section" style="display: none;">
            <div class="container">
                <div class="title d-flex align-items-center">
                    <h5>{{ trans('lang.new_arrivals') }}</h5>
                    <span class="see-all ml-auto">
                        <a href="{{ route('productlist.all') }}">{{ trans('lang.see_all') }}</a>
                    </span>
                </div>
                <div id="new_arrival"></div>
            </div>
        </section>
        <section class="offers-coupons-section" style="display: none;">
            <div class="container">
                <div class="title d-flex align-items-center">
                    <h5>{{ trans('lang.offers') }} {{ trans('lang.for_you') }}</h5>
                    <span class="see-all ml-auto">
                        <a href="{{ route('offers') }}">{{ trans('lang.see_all') }}</a>
                    </span>
                </div>
                <div style="display:none" class="coupon_code_copied_div mt-4 error_top text-center">
                    <p>{{ trans('lang.coupon_code_copied') }}</p>
                </div>
                <div id="offers_coupons"></div>
            </div>
        </section>
        <section class="middle-banners-section">
            <div class="container">
                <div id="middle_banner"></div>
            </div>
        </section>
        <section class="home-categories-section" style="display:none;">
            <div class="container" id="home_categories"></div>
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
                <div class="filters-section mb-4">
                    <div class="row">
                        <div class="col-12">
                            <div class="filter-wrapper">
                                <div class="d-flex align-items-center w-100 filter-bar-row">
                                    <select id="restaurant-sort" class="form-control form-control-sm rounded-select flex-fill">
                                        <option value="default">{{ trans('lang.sort_by') }}</option>
                                        <option value="asc">{{ trans('lang.a_to_z') }}</option>
                                        <option value="desc">{{ trans('lang.z_to_a') }}</option>
                                    </select>
                                    <select id="restaurant-status" class="form-control form-control-sm rounded-select flex-fill">
                                        <option value="default">{{ trans('lang.all_status') }}</option>
                                        <option value="open">{{ trans('lang.open') }}</option>
                                        <option value="closed">{{ trans('lang.closed') }}</option>
                                    </select>
                                    <select id="restaurant-price" class="form-control form-control-sm rounded-select flex-fill">
                                        <option value="default">{{ trans('lang.price_range') }}</option>
                                        <option value="1">{{ trans('lang.low_to_high') }}</option>
                                        <option value="2">{{ trans('lang.high_to_low') }}</option>
                                    </select>
                                    <select id="restaurant-rating" class="form-control form-control-sm rounded-select flex-fill">
                                        <option value="default">{{ trans('lang.rating') }}</option>
                                        <option value="5">5 Stars</option>
                                        <option value="4">4 Stars</option>
                                        <option value="3">3 Stars</option>
                                        <option value="2">2 Stars</option>
                                        <option value="1">1 Star</option>
                                    </select>
                                    <select id="restaurant-category" class="form-control form-control-sm rounded-select flex-fill">
                                        <option value="default">{{ trans('lang.select_category') }}</option>
                                    </select>
                                    <!-- <select id="restaurant-delivery" class="form-control form-control-sm rounded-select flex-fill">
                                        <option value="default">{{ trans('lang.all_delivery_option') }}</option>
                                        <option value="free_delivery">{{ trans('lang.free_delivery') }}</option>
                                        <option value="paid_delivery">{{ trans('lang.paid_delivery') }}</option>
                                    </select> -->
                                    <select id="restaurant-offers" class="form-control form-control-sm rounded-select flex-fill">
                                        <option value="default">{{ trans('lang.all_offers') }}</option>
                                        <option value="active_discounts">{{ trans('lang.active_discounts') }}</option>
                                        <option value="active_coupons">{{ trans('lang.active_coupons') }}</option>
                                        <option value="special_offers">{{ trans('lang.special_offers') }}</option>
                                    </select>
                                    <select id="restaurant-distance" class="form-control form-control-sm rounded-select flex-fill">
                                        <option value="default">{{ trans('lang.all_distances') }}</option>
                                        <option value="1">Within 1 km</option>
                                        <option value="3">Within 3 km</option>
                                        <option value="5">Within 5 km</option>
                                        <option value="10">Within 10 km</option>
                                        <option value="20">Within 20 km</option>
                                    </select>
                                    <button type="button" id="clear-filters" class="btn btn-outline-secondary btn-sm flex-fill">
                                        <i class="feather-x mr-2"></i>{{ trans('lang.clear_filters') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="all_stores"></div>
{{--                <!-- Pagination Controls -->--}}
{{--                <div class="pagination-wrapper mt-4" id="pagination-wrapper" style="display: none;">--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-12">--}}
{{--                            <div class="d-flex justify-content-between align-items-center">--}}
{{--                                <div class="pagination-info">--}}
{{--                                    <span id="pagination-info">Showing 0 of 0 restaurants</span>--}}
{{--                                </div>--}}
{{--                                <div class="pagination-controls">--}}
{{--                                    <button type="button" id="prev-page" class="btn btn-outline-none btn-sm" disabled>--}}
{{--                                        <i class="feather-chevron-left"></i> Previous--}}
{{--                                    </button>--}}
{{--                                    <span class="mx-3">--}}
{{--                                        Page <span id="current-page">1</span> of <span id="total-pages">1</span>--}}
{{--                                    </span>--}}
{{--                                    <button type="button" id="next-page" class="btn btn-outline-primary btn-sm">--}}
{{--                                        Next <i class="feather-chevron-right"></i>--}}
{{--                                    </button>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>

                <!-- Load More Button (for backward compatibility) -->
                <div class="row fu-loadmore-btn" id="loadmore-wrapper" style="display: none;">
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
<link rel="stylesheet" href="{{ asset('css/dist/zuck.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/dist/skins/snapssenger.css') }}">
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
    /* Add new styles for the filter dropdown */
    #restaurant-sort {
        padding: 5px 10px;
        border-radius: 4px;
        border: 1px solid #ddd;
        background-color: white;
        font-size: 14px;
        min-width: 120px;
    }
    #restaurant-sort:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
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
        .filters-section .d-flex {
            flex-wrap: wrap;
            justify-content: center;
        }

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

    /* Enhanced Filter Styles */
    .filters-section {
        background: #fff;
        padding: 15px;
        border-radius: 8px;
        /* box-shadow: 0 2px 8px rgba(0,0,0,0.1); */
        margin-bottom: 20px;
    }

    @media (prefers-color-scheme: dark) {
        .filters-section {
            background: #2d3238;
            /* box-shadow: 0 2px 8px rgba(0,0,0,0.2); */
        }
    }

    .filter-item {
        min-width: 180px;
        position: relative;
    }

    .filter-item select {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        background-color: #fff;
        font-size: 14px;
        color: #333;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .filter-item select:hover {
        border-color: #bdbdbd;
    }

    .filter-item select:focus {
        border-color: #2196F3;
        box-shadow: 0 0 0 2px rgba(33,150,243,0.1);
        outline: none;
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
        .filter-item {
            min-width: calc(50% - 10px);
            margin-bottom: 10px;
        }

        #custom-distance-container {
            min-width: calc(50% - 10px);
        }
    }

    @media (max-width: 480px) {
        .filter-item {
            min-width: 100%;
        }

        #custom-distance-container {
            min-width: 100%;
        }
    }

    /* Dark Mode Support */
    @media (prefers-color-scheme: dark) {
        .filters-section {
            background: #2d3238;
            /* box-shadow: 0 2px 8px rgba(0,0,0,0.2); */
        }

        .filter-item select {
            background-color: #2d3238;
            border-color: #444;
            color: #fff;
        }

        .filter-item select:hover {
            border-color: #555;
        }

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

    .filter-wrapper {
        background: none !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        padding: 0 !important;
        border: none !important;
    }

    .filter-wrapper.full-rounded {
        width: 100% !important;
        max-width: 100% !important;
        border-radius: 999px !important;
        overflow: hidden !important;
        background: #fff;
        box-shadow: none;
        padding: 0;
        border: none;
    }

    .filter-wrapper .d-flex {
        gap: 0.75rem;
        flex-wrap: nowrap;
        overflow-x: auto;
    }
    @media (max-width: 767.98px) {
        .filter-wrapper .d-flex {
            flex-wrap: wrap;
            flex-direction: column;
            align-items: stretch;
        }
        .filter-wrapper .btn {
            width: 100%;
            margin-left: 0 !important;
        }
    }
    @media (max-width: 400px) {
        .filter-wrapper .d-flex {
            flex-wrap: wrap;
            flex-direction: column;
            align-items: stretch;
        }
        .filter-wrapper .btn {
            width: 100%;
            margin-left: 0 !important;
        }
    }
    .filter-bar-row > * {
        margin-right: 0.5rem;
    }
    .filter-bar-row > *:last-child {
        margin-right: 0;
    }
    .filter-bar-row {
        flex-wrap: nowrap !important;
        overflow-x: unset !important;
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

 /*  !* Pagination Styles - Updated for white background and black text *!*/
 /*.pagination-wrapper {*/
 /*    background: #ffffff;*/
 /*    padding: 20px;*/
 /*    border-radius: 8px;*/
 /*    box-shadow: 0 2px 8px rgba(0,0,0,0.1);*/
 /*    margin-top: 30px;*/
 /*    border: 1px solid #e9ecef;*/
 /*}*/

 /*.pagination-controls {*/
 /*    display: flex;*/
 /*    align-items: center;*/
 /*    gap: 10px;*/
 /*}*/

 /*.pagination-controls button {*/
 /*    min-width: 100px;*/
 /*    padding: 8px 16px;*/
 /*    border-radius: 6px;*/
 /*    font-weight: 500;*/
 /*    transition: all 0.3s ease;*/
 /*    background: #ffffff;*/
 /*    color: #000000;*/
 /*    border: 2px solid #000000;*/
 /*}*/

 /*.pagination-controls button:hover:not(:disabled) {*/
 /*    background: #ffffff;*/
 /*    color: #000000;*/
 /*    transform: translateY(-1px);*/
 /*    box-shadow: 0 4px 8px rgba(0,0,0,0.15);*/
 /*}*/

 /*.pagination-controls button:disabled {*/
 /*    opacity: 0.5;*/
 /*    cursor: not-allowed;*/
 /*    background: #f8f9fa;*/
 /*    color: #000000;*/
 /*    border-color: #dee2e6;*/
 /*}*/

 /*.pagination-info {*/
 /*    font-size: 14px;*/
 /*    color: #000000;*/
 /*    font-weight: 500;*/
 /*}*/

 /*!* Mobile Responsive Pagination *!*/
 /*@media (max-width: 768px) {*/
 /*    .pagination-wrapper {*/
 /*        padding: 15px;*/
 /*    }*/

 /*    .pagination-controls {*/
 /*        flex-direction: column;*/
 /*        gap: 15px;*/
 /*    }*/

 /*    .pagination-controls button {*/
 /*        width: 100%;*/
 /*        min-width: unset;*/
 /*    }*/

 /*    .pagination-info {*/
 /*        text-align: center;*/
 /*        margin-bottom: 10px;*/
 /*    }*/
 /*}*/

 /*!* Dark Mode Support for Pagination *!*/
 /*@media (prefers-color-scheme: dark) {*/
 /*    .pagination-wrapper {*/
 /*        background: #ffffff;*/
 /*        box-shadow: 0 2px 8px rgba(0,0,0,0.2);*/
 /*        border-color: #e9ecef;*/
 /*    }*/

 /*    .pagination-info {*/
 /*        color: #000000;*/
 /*    }*/

 /*    .pagination-controls button {*/
 /*        background: #ffffff;*/
 /*        color: #000000;*/
 /*        border-color: #000000;*/
 /*    }*/

 /*    .pagination-controls button:hover:not(:disabled) {*/
 /*        background: #000000;*/
 /*        color: #ffffff;*/
 /*    }*/
 /*}*/
</style>
<script src="{{ asset('js/dist/zuck.min.js') }}"></script>
<script src="{{ asset('js/geofirestore.js') }}"></script>
<script src="https://cdn.firebase.com/libs/geofire/5.0.1/geofire.min.js"></script>
<script type="text/javascript" src="{{ asset('vendor/swiper/swiper.min.js') }}"></script>

<script type="text/javascript">
    jQuery("#data-table_processing").show();

    var firestore = firebase.firestore();
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
    var storyEnabled = false;
    var VendorNearBy = '';
    var pagesize = 20000;
    var offest = 1;
    var end = null;
    var endarray = [];
    var start = null;
    // Pagination variables
    var currentPage = 1;
    var totalPages = 1;
    var totalRestaurants = 0;
    var allVendorsData = []; // Store all vendors data
    var filteredVendorsData = []; // Store filtered vendors data
    var paginationEnabled = true; // Toggle for pagination vs load more
    var nearByVendorsForStory = [];
    var vendorIds = [];
    var priceData = {};
    var enableAdvertisement = false;
    var highlightsSetting = database.collection('settings').doc('globalSettings');
    var DriverNearByRef = database.collection('settings').doc('RestaurantNearBy');
    var itemCategoriesref = database.collection('vendor_categories').where('publish', '==', true).limit(10);
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
    highlightsSetting.get().then(async function(settingSnapshots) {
        if (settingSnapshots.data()) {
            var settingData = settingSnapshots.data();
            if (settingData.isEnableAdsFeature) {
                enableAdvertisement = true;
            }
        }
    })
    database.collection('settings').doc("story").get().then(async function(snapshots) {
        var story_data = snapshots.data();
        if (story_data.isEnabled) {
            storyEnabled = true;
        } else {
            $(".restaurant_stories").remove();
        }
    });
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

            // Initialize delivery filter options
            initializeDeliveryFilter();
        } catch (error) {
            console.error('Error fetching delivery settings:', error);
        }
    }

    // Function to initialize delivery filter options
    function initializeDeliveryFilter() {
        const deliverySelect = $('#restaurant-delivery');
        deliverySelect.empty();
        deliverySelect.append(`<option value="default">{{ trans('lang.all_delivery_option') }}</option>`);

        if (globalDeliverySettings) {
            if (globalDeliverySettings.hasOwnProperty('delivery_option')) {
                if (globalDeliverySettings.delivery_option.includes('free')) {
                    deliverySelect.append(`<option value="free_delivery">{{ trans('lang.free_delivery') }}</option>`);
                }
                if (globalDeliverySettings.delivery_option.includes('paid')) {
                    deliverySelect.append(`<option value="paid_delivery">{{ trans('lang.paid_delivery') }}</option>`);
                }
                if (globalDeliverySettings.delivery_option.includes('self')) {
                    deliverySelect.append(`<option value="self_delivery">{{ trans('lang.self_delivery') }}</option>`);
                }
                if (globalDeliverySettings.delivery_option.includes('third_party')) {
                    deliverySelect.append(`<option value="third_party">{{ trans('lang.third_party_delivery') }}</option>`);
                }
            }
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

    // Function to check if vendor matches delivery filter
    function vendorMatchesDeliveryFilter(vendorDeliveryDetails, filterValue) {
        if (!vendorDeliveryDetails || filterValue === 'default') return true;

        switch (filterValue) {
            case 'free_delivery':
                return vendorDeliveryDetails.freeDelivery;
            case 'paid_delivery':
                return !vendorDeliveryDetails.freeDelivery &&
                    (vendorDeliveryDetails.isSelfDelivery || vendorDeliveryDetails.thirdPartyDelivery);
            case 'self_delivery':
                return vendorDeliveryDetails.isSelfDelivery;
            case 'third_party':
                return vendorDeliveryDetails.thirdPartyDelivery;
            default:
                return true;
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

    // Update the existing filter handler
    async function updateVendorsList() {
        if (!window.vendorsData) return;

        const sortOrder = $('#restaurant-sort').val();
        const statusFilter = $('#restaurant-status').val();
        const priceFilter = $('#restaurant-price').val();
        const ratingFilter = $('#restaurant-rating').val();
        const categoryFilter = $('#restaurant-category').val();
        const deliveryFilter = $('#restaurant-delivery').val();
        const offersFilter = $('#restaurant-offers').val();
        let distanceFilter = $('#restaurant-distance').val();

        // Show loading indicator
        $('#all_stores').html('<div class="text-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>');

        try {
            let vendors = [];

            for (const listval of window.vendorsData.docs) {
                const datas = listval.data();
                datas.id = listval.id;

                if (!inValidVendors.has(listval.id)) {
                    let includeVendor = true;

                    // Get vendor delivery details
                    const deliveryDetails = await getVendorDeliveryDetails(datas.id);

                    // Apply filters
                    if (statusFilter !== 'default') {
                        const currentStatus = getVendorStatus(datas);
                        if (statusFilter === 'open' && currentStatus !== 'Open') {
                            includeVendor = false;
                        } else if (statusFilter === 'closed' && currentStatus !== 'Closed') {
                            includeVendor = false;
                        }
                    }

                    if (includeVendor && ratingFilter !== 'default') {
                        const avgRating = datas.reviewsCount ? (datas.reviewsSum / datas.reviewsCount) : 0;
                        if (avgRating < parseFloat(ratingFilter)) {
                            includeVendor = false;
                        }
                    }

                    if (includeVendor && categoryFilter !== 'default') {
                        if (!datas.categoryID || !datas.categoryID.includes(categoryFilter)) {
                            includeVendor = false;
                        }
                    }

                    if (includeVendor && deliveryFilter !== 'default') {
                        if (!vendorMatchesDeliveryFilter(deliveryDetails, deliveryFilter)) {
                            includeVendor = false;
                        }
                    }

                    if (includeVendor && offersFilter !== 'default') {
                        if (offersFilter === 'active_discounts' && (!datas.hasOwnProperty('discount') || !datas.discount)) {
                            includeVendor = false;
                        } else if (offersFilter === 'active_coupons' && (!datas.hasOwnProperty('coupons') || !datas.coupons.length)) {
                            includeVendor = false;
                        } else if (offersFilter === 'special_offers' && (!datas.hasOwnProperty('specialOffers') || !datas.specialOffers)) {
                            includeVendor = false;
                        }
                    }

                    if (includeVendor && distanceFilter !== 'default') {
                        const distance = calculateDistance(
                            address_lat,
                            address_lng,
                            datas.latitude,
                            datas.longitude
                        );

                        if (distance > distanceFilter) {
                            includeVendor = false;
                        }

                        // Add distance and delivery charge to vendor data
                        datas.distance = distance;
                        datas.deliveryCharge = calculateDeliveryCharge(deliveryDetails, distance);
                    }

                    if (includeVendor) {
                        vendors.push(datas);
                    }
                }
            }

            // Apply sorting
            if (sortOrder !== 'default' || priceFilter !== 'default') {
                vendors.sort((a, b) => {
                    if (sortOrder === 'asc') return (a.title || '').localeCompare(b.title || '');
                    if (sortOrder === 'desc') return (b.title || '').localeCompare(a.title || '');
                    if (priceFilter === '1') return (a.minPrice || 0) - (b.minPrice || 0);
                    if (priceFilter === '2') return (b.minPrice || 0) - (a.minPrice || 0);
                    return 0;
                });
            }

            // Update UI
            const html = buildAllStoresHTMLFromArray(vendors);
            $('#all_stores').html(html);

            // Update delivery information
            vendors.forEach(vendor => {
                if (vendor.hasOwnProperty('deliveryCharge')) {
                    const deliveryText = vendor.deliveryCharge === 0
                        ? '<span class="text-success">Free Delivery</span>'
                        : `Delivery: ${formatPrice(vendor.deliveryCharge)}`;
                    $('.delivery-info-' + vendor.id).html(deliveryText);
                }

                if (vendor.hasOwnProperty('distance')) {
                    const distanceText = radiusUnit === 'miles'
                        ? (vendor.distance / 1.60934).toFixed(1) + ' mi'
                        : vendor.distance.toFixed(1) + ' km';
                    $('.vendor-distance-' + vendor.id).text(distanceText);
                }
            });

        } catch (error) {
            console.error('Error updating vendors list:', error);
            $('#all_stores').html('<div class="text-center text-danger">Error loading vendors</div>');
        }
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

        // Update filter handler
        $('#restaurant-sort, #restaurant-status, #restaurant-price, #restaurant-rating, #restaurant-category, #restaurant-delivery, #restaurant-offers, #restaurant-distance, #custom-distance').on('change', updateVendorsList);
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
        var position2_banners = [];
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
                if (bannerData.position == 'middle') {
                    if (bannerData.hasOwnProperty('redirect_type')) {
                        redirect_type = bannerData.redirect_type;
                        redirect_id = bannerData.redirect_id;
                    }
                    var object = {
                        'photo': bannerData.photo,
                        'redirect_type': redirect_type,
                        'redirect_id': redirect_id,
                    }
                    position2_banners.push(object);
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
            if (position2_banners.length > 0) {
                var html = '';
                for (banner of position2_banners) {
                    html += '<div class="banner-item">';
                    html += '<div class="banner-img">';
                    var redirect_id = 'javascript::void()';
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
                $("#middle_banner").html(html);
            } else {
                $('.middle-banners').remove();
            }
            setTimeout(function() {
                slickcatCarousel();
            }, 200)
        });
    }


    var myInterval = '';
    $(document).ready(async function() {
        console.log("Initial user_zone_id:", user_zone_id);

        // Retrieve all invalid vendors
        await checkVendors().then(expiredStores => {
            inValidVendors = expiredStores;
        });

        // Load categories for filter
        loadCategoriesForFilter();

        // Fetch and render banners before initializing Slick
        getBanners();
        if (enableAdvertisement) {
            getHighlights();
            $('.highlights-section').removeClass('d-none');
        } else {
            $('.highlights-section').addClass('d-none');
        }

        myInterval = setInterval(callStore, 1000);
    });

    // Function to load categories into filter dropdown
    async function loadCategoriesForFilter() {
        try {
            const categoriesSnapshot = await database.collection('vendor_categories')
                .where('publish', '==', true)
                .get();

            const categorySelect = $('#restaurant-category');

            categoriesSnapshot.docs.forEach(doc => {
                const category = doc.data();
                categorySelect.append(`<option value="${doc.id}">${category.title}</option>`);
            });
        } catch (error) {
            console.error('Error loading categories:', error);
        }
    }

    function myStopTimer() {
        clearInterval(myInterval);
    }

    async function callStore() {
        console.log("callStore - address_lat:", address_lat, "address_lng:", address_lng, "user_zone_id:", user_zone_id);
        if (address_lat == '' || address_lng == '' || address_lng == NaN || address_lat == NaN || address_lat ==
            null || address_lng == null) {
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
            myStopTimer();
            getItemCategories();
            // getHomepageCategory();
            getMostPopularStores();
            getAllStore();

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
        if ($("#middle_banner").length > 0 && $("#middle_banner").html().trim() !== "") {
            $('#middle_banner').slick({
                slidesToShow: 3,
                dots: true,
                arrows: true,
                responsive: [{
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 3,
                    }
                },
                    {
                        breakpoint: 767,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 650,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });
        } else {
            console.log("Middle banner element not found or empty.");
        }
    }

    async function getAllStore() {
        if (VendorNearBy) {
            var nearestRestauantRefnew = geoFirestore.collection('vendors').near({
                center: new firebase.firestore.GeoPoint(address_lat, address_lng),
                radius: VendorNearBy
            }).where('zoneId', '==', user_zone_id);
        } else {
            var nearestRestauantRefnew = geoFirestore.collection('vendors').where('zoneId', '==', user_zone_id);
        }
        nearestRestauantRefnew.get().then(async function(snapshots) {
            if (snapshots.docs.length > 0) {
                window.vendorsData = snapshots; // Store the data globally for sorting

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

                // Calculate prices for all vendors in batch (much faster)
                const minPrices = await getAllVendorMinPrices(vendors);
                vendors.forEach(vendor => {
                    vendor.minPrice = minPrices.get(vendor.id) || 0;
                });

                // Initialize pagination with all vendors
                filteredVendorsData = vendors;
                totalRestaurants = vendors.length;
                totalPages = Math.ceil(totalRestaurants / pagesize);
                currentPage = 1;

                // Initialize pagination system
                initializePagination();

                // Display first page
                displayCurrentPage();

                start = snapshots.docs[snapshots.docs.length - 1];
                endarray.push(snapshots.docs[0]);
            } else {
                $(".all-stores-section").remove();
                $(".new-arrivals-section").remove();
                $(".section-content").remove();
                jQuery(".zone-error").show();
                jQuery(".zone-error").find('.title').text('{{ trans('lang.restaurant_error_title') }}');
                jQuery(".zone-error").find('.text').text('{{ trans('lang.restaurant_error_text') }}');
            }
        });
    }

    // Cache for vendor delivery status
    let vendorDeliveryCache = new Map();
    let initialDataLoaded = false;

    // Function to preload vendor data
    async function preloadVendorData() {
        if (initialDataLoaded || !window.vendorsData) return;

        try {
            const vendorIds = window.vendorsData.docs.map(doc => doc.id);
            const vendorsSnapshot = await database.collection('vendors')
                .where(firebase.firestore.FieldPath.documentId(), 'in', vendorIds)
                .get();

            vendorsSnapshot.docs.forEach(doc => {
                const data = doc.data();
                vendorDeliveryCache.set(doc.id, {
                    isSelfDelivery: data.isSelfDelivery || false,
                    hasFreeSelfDelivery: data.isSelfDelivery && isSelfDeliveryGlobally
                });
            });

            initialDataLoaded = true;
        } catch (error) {
            console.error('Error preloading vendor data:', error);
        }
    }

    // Optimized filter handler
    $('#restaurant-sort, #restaurant-status, #restaurant-price, #restaurant-rating, #restaurant-category, #restaurant-delivery').on('change', function() {
        if (!window.vendorsData) return;

        const sortOrder = $('#restaurant-sort').val();
        const statusFilter = $('#restaurant-status').val();
        const priceFilter = $('#restaurant-price').val();
        const ratingFilter = $('#restaurant-rating').val();
        const categoryFilter = $('#restaurant-category').val();
        const deliveryFilter = $('#restaurant-delivery').val();

        // Show loading indicator
        $('#all_stores').html('<div class="text-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>');

        // Use setTimeout to prevent UI blocking
        setTimeout(() => {
            let vendors = [];

            window.vendorsData.docs.forEach((listval) => {
                var datas = listval.data();
                datas.id = listval.id;

                if (!inValidVendors.has(listval.id)) {
                    let includeVendor = true;

                    // Get cached delivery status
                    const deliveryStatus = vendorDeliveryCache.get(datas.id);

                    // Status filter
                    if (statusFilter !== 'default') {
                        const currentStatus = getVendorStatus(datas);
                        if (statusFilter === 'open' && currentStatus !== 'Open') {
                            includeVendor = false;
                        } else if (statusFilter === 'closed' && currentStatus !== 'Closed') {
                            includeVendor = false;
                        }
                    }

                    // Rating filter
                    if (includeVendor && ratingFilter !== 'default') {
                        const avgRating = datas.reviewsCount ? (datas.reviewsSum / datas.reviewsCount) : 0;
                        if (avgRating < parseFloat(ratingFilter)) {
                            includeVendor = false;
                        }
                    }

                    // Category filter
                    if (includeVendor && categoryFilter !== 'default') {
                        if (!datas.categoryID || !datas.categoryID.includes(categoryFilter)) {
                            includeVendor = false;
                        }
                    }

                    // Delivery filter
                    if (includeVendor && deliveryFilter !== 'default' && deliveryStatus) {
                        if (deliveryFilter === 'free_delivery' && !deliveryStatus.hasFreeSelfDelivery) {
                            includeVendor = false;
                        } else if (deliveryFilter === 'paid_delivery' && deliveryStatus.hasFreeSelfDelivery) {
                            includeVendor = false;
                        }
                    }

                    if (includeVendor) {
                        vendors.push(datas);
                    }
                }
            });

            // Apply sorting
            if (sortOrder !== 'default' || priceFilter !== 'default') {
                vendors.sort((a, b) => {
                    if (sortOrder === 'asc') return (a.title || '').localeCompare(b.title || '');
                    if (sortOrder === 'desc') return (b.title || '').localeCompare(a.title || '');
                    if (priceFilter === '1') return (a.minPrice || 0) - (b.minPrice || 0);
                    if (priceFilter === '2') return (b.minPrice || 0) - (a.minPrice || 0);
                    return 0;
                });
            }

            // Update UI
            const html = buildAllStoresHTMLFromArray(vendors);
            $('#all_stores').html(html);

            // Update delivery badges
            vendors.forEach(vendor => {
                const deliveryStatus = vendorDeliveryCache.get(vendor.id);
                if (deliveryStatus?.hasFreeSelfDelivery) {
                    $('.free-delivery-' + vendor.id).html('<span><img src="{{ asset('img/free_delivery.png') }}" width="100px"> {{trans("lang.free_delivery")}}</span>');
                }
            });
        }, 0);
    });

    // Call preload when document is ready
    $(document).ready(async function() {
        // ... existing ready handler code ...

        // Preload vendor data after initial data is loaded
        const checkDataInterval = setInterval(() => {
            if (window.vendorsData) {
                clearInterval(checkDataInterval);
                preloadVendorData();
            }
        }, 100);
    });

    // Optimized function to get minimum prices for all vendors at once
    async function getAllVendorMinPrices(vendors) {
        const vendorIds = vendors.map(v => v.id);
        const minPrices = new Map();

        try {
            // Batch query all products for all vendors
            const productsSnapshot = await database.collection('vendor_products')
                .where('vendorID', 'in', vendorIds)
                .where('publish', '==', true)
                .get();

            // Group products by vendor
            const vendorProducts = new Map();
            productsSnapshot.docs.forEach((doc) => {
                const product = doc.data();
                if (!vendorProducts.has(product.vendorID)) {
                    vendorProducts.set(product.vendorID, []);
                }
                vendorProducts.get(product.vendorID).push(product);
            });

            // Calculate min price for each vendor
            vendors.forEach(vendor => {
                let minPrice = Infinity;
                const products = vendorProducts.get(vendor.id) || [];

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

                minPrices.set(vendor.id, minPrice === Infinity ? 0 : minPrice);
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

    // Function to get vendor's current status
    function getVendorStatus(vendorData) {
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

    // Update buildAllStoresHTMLFromArray to use the stored status
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

                var photo = placeholderImage;
                if (val.hasOwnProperty('photo') && val.photo != '' && val.photo != null) {
                    photo = val.photo;
                }

                var view_vendor_details = "{{ url('restaurant') }}/" + val.id;
                var currentStatus = val.currentStatus || getVendorStatus(val);
                var statusClass = currentStatus === 'Open' ? 'text-success' : 'text-danger';
                var statusText = currentStatus === 'Open' ? '{{ trans("lang.open") }}' : '{{ trans("lang.closed") }}';

                html = html + '<div class="col-md-3 product-list">';
                html = html + '<div class="list-card position-relative">';
                html = html + '<div class="list-card-image">';
                html = html + '<a href="' + view_vendor_details + '">';
                html = html + '<img onerror="this.onerror=null;this.src=\'' + placeholderImage + '\'" alt="#" src="' + photo + '" class="img-fluid item-img w-100" loading="lazy">';
                html = html + '</a>';
                html = html + '</div>';
                html = html + '<div class="py-2 position-relative">';
                html = html + '<div class="list-card-body position-relative">';
                html = html + '<h6 class="product-title mb-1">';
                html = html + '<a href="' + view_vendor_details + '" class="text-black">' + val.name + '</a>';
                html = html + '</h6>';
                html = html + '<h6 class="mb-1 popular_food_category_ pro-cat" id="popular_food_category_' + val.categoryID + '_' + val.id + '"></h6>';

                // Add minimum price display
                if (val.minPrice && val.minPrice > 0) {
                    html = html + '<div class="mb-1"><small class="text-muted">Starting from ' + currentCurrency + ' ' + val.minPrice.toFixed(decimal_degits) + '</small></div>';
                }

                html = html + '<div class="star position-relative mt-3">';
                html = html + '<span class="badge badge-success"><i class="feather-star"></i>' + rating + ' (' + reviewsCount + ')</span>';
                html = html + '<span class="badge badge-light ml-2 ' + statusClass + '">' + statusText + '</span>';
                html = html + '</div>';
                html = html + '</div>';
                html = html + '</div>';
                html = html + '</div>';
                html = html + '</div>';
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
            } else {
                $('.home-categories-section').remove();
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
                                        status = '{{ trans('lang.open') }}';
                                        statusclass = "open";
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
                        most_popular_item.innerHTML = trendingStorehtml;
                    });
                } else {
                    $(".most-popular-item-section").remove();
                }
            }
        }
    }

    async function getMostPopularStores() {
        var popularRestauantRefnew = geoFirestore.collection('vendors').near({
            center: new firebase.firestore.GeoPoint(address_lat, address_lng),
            radius: VendorNearBy
        }).limit(200).where('zoneId', '==', user_zone_id);

        await popularRestauantRefnew.get().then(async function(popularRestauantSnapshot) {
            if (popularRestauantSnapshot.docs.length > 0) {
                var most_popular_store = document.getElementById('most_popular_store');
                most_popular_store.innerHTML = '';
                var popularStorehtml = await buildHTMLPopularStore(popularRestauantSnapshot);
                most_popular_store.innerHTML = popularStorehtml;
            } else {
                $(".most-popular-store-section").remove();
                $(".most-popular-item-section").remove();
                $('.offers-coupons-section').remove();
            }
        });
        if (storyEnabled) {

            await getStories();
        }
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
                                    status = '{{ trans('lang.open') }}';
                                    statusclass = "open";
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
                '"><img onerror="this.onerror=null;this.src=\'' + placeholderImage + '\'"  alt="#" src="' +
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
                if (nearByVendorsForStory.includes(datas.id)) {} else {
                    nearByVendorsForStory.push(datas.id);
                }
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
                                        status = '{{ trans('lang.open') }}';
                                        statusclass = "open";
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
                    offers_coupons.innerHTML = '';
                    var couponlistHTML = buildHTMLCouponList(couponListSnapshot);
                    offers_coupons.innerHTML = couponlistHTML;
                } else {
                    $('.offers-coupons-section').remove();
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

    async function getStories() {

        var alldata = [];
        var storyDatas = [];
        var queryPromises = [];
        for (var i = 0; i < nearByVendorsForStory.length; i++) {
            const query = await database.collection('story').where('vendorID', '==', nearByVendorsForStory[i]).limit(2).get();
            queryPromises.push(query);

        }

        await Promise.all(queryPromises).then((querySnapshots) => {
            for (const querySnapshot of querySnapshots) {
                querySnapshot.forEach((doc) => {
                    alldata.push(doc.data());
                });
            }
        });
        for (data of alldata) {

            var vendorDataRes = await database.collection('vendors').doc(data.vendorID).get();
            var vendorData = vendorDataRes.data();

            if (vendorData != undefined) {

                var vendorRating = '';
                if (vendorData.hasOwnProperty('reviewsSum') && vendorData.reviewsSum != 0 && vendorData
                    .hasOwnProperty('reviewsCount') && vendorData.reviewsCount != 0) {
                    rating = (vendorData.reviewsSum / vendorData.reviewsCount);
                    rating = Math.round(rating * 10) / 10;
                    reviewsCount = vendorData.reviewsCount;
                    vendorRating = vendorRating +
                        '<div class="star position-relative ml-1 mt-3"><span class="badge badge-success "><i class="feather-star"></i>' +
                        rating + ' (' + reviewsCount + ')</span></div>';
                }

                var vendorLink = "/restaurant/" + vendorData.id + "/" + vendorData.restaurant_slug + "/" + vendorData.zone_slug;

                var itemsObject = [];

                data.videoUrl.forEach((video) => {
                    var itemObject = {
                        id: vendorData.id,
                        type: "video",
                        length: 5,
                        src: video,
                        link: vendorLink,
                        linkText: vendorData.title,
                        time: new Date(data.createdAt.toDate()).getTime() / 1000,
                        seen: false
                    };
                    itemsObject.push(itemObject);
                });

                var storyObject = {
                    id: vendorData.id,
                    photo: data.videoThumbnail,
                    name: vendorData.title,
                    link: vendorLink,
                    seen: false,
                    items: itemsObject
                }
                storyDatas.push(storyObject);
            }
        }

        if (storyDatas.length) {

            new Zuck('stories', {
                backNative: true,
                previousTap: true,
                skin: 'snapssenger',
                autoFullScreen: true,
                avatars: true,
                list: false,
                cubeEffect: true,
                localStorage: true,
                stories: storyDatas,
                language: {
                    unmute: '<i class="fa fa-volume-up"></i>',
                }
            });

            new Swiper('.swiper-stories', {
                slidesPerView: 5,
                breakpoints: {
                    991: {
                        slidesPerView: 4,
                    },
                    767: {
                        slidesPerView: 3,
                    },
                    650: {
                        slidesPerView: 2,
                    },
                },
            });
        }
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
    async function getHighlights() {
        var html = '';
        var advlength = 0;
        database.collection('advertisements')
            .where('status', '==', 'approved')
            .where('paymentStatus', '==', true)
            .get()
            .then(async function(snapshots) {
                if (snapshots.docs.length === 0) {
                    $('.highlights-section').addClass('d-none');
                    return;
                }


                let advertisements = [];

                snapshots.docs.forEach(doc => {
                    advertisements.push({
                        ...doc.data()
                    });
                });

                let filteredAds = [];

                for (const data of advertisements) {
                    const ExpiryDate = data.endDate;
                    const startDate = data.startDate;

                    const vendorDoc = await geoFirestore.collection('vendors').doc(data.vendorId).get();
                    if (!vendorDoc.exists) continue;

                    const vendorData = vendorDoc.data();
                    if (vendorData.zoneId !== user_zone_id) continue;
                    if (vendorData.isPaused) continue;
                    let rating = 0;
                    let reviewsCount = 0;

                    if (vendorData.reviewsSum && vendorData.reviewsCount) {
                        rating = Math.round((vendorData.reviewsSum / vendorData.reviewsCount) * 10) / 10;
                        reviewsCount = vendorData.reviewsCount;
                    }

                    const start = startDate && new Date(startDate.seconds * 1000);
                    const end = ExpiryDate && new Date(ExpiryDate.seconds * 1000);

                    if (start && start < new Date() && end && end > new Date()) {
                        filteredAds.push({
                            ...data,
                            rating,
                            reviewsCount
                        });
                    }
                }

                filteredAds.sort((a, b) => {
                    const aPriority = (a.priority === "N/A" || a.priority === null || a.priority === undefined) ? Infinity : parseInt(a.priority);
                    const bPriority = (b.priority === "N/A" || b.priority === null || b.priority === undefined) ? Infinity : parseInt(b.priority);
                    return aPriority - bPriority;
                });
                advlength = filteredAds.length;
                for (const data of filteredAds) {
                    const view_vendor_details = "/restaurant/" + data.vendorId + "/" + data.restaurant_slug + "/" + data.zone_slug;

                    if (data.type === 'restaurant_promotion') {
                        html += `<div id="profile-preview-box" class="cat-item profile-preview-box pt-4"><div class=" profile-preview-box-inner">
                        <div class="profile-preview-img">
                            <div class="profile-preview-img-inner">
                                <img src="${data.coverImage}">
                            </div>
                            <div class="review-rating-demo ${data.showRating || data.showReview ? '' : 'd-none'}" >
                                <div class="rating-text static-text ${data.showRating ? '' : 'd-none'}" style="display: block;" id="preview-rating">
                                    <div class="rating-number d-flex align-items-center ">
                                        <i class="fa fa-star"></i><span id="rating_data">${data.rating}</span>
                                    </div>
                                </div>
                                <span class="review--text static-text ${data.showReview ? '' : 'd-none'}" style="display: inline;" id="preview-review">(${data.reviewsCount === 0 ? '0' : '+' + data.reviewsCount})</span>
                            </div>
                        </div>
                        <div class="profile-preview-content">
                            <?php if (Auth::check()) : ?>
                        <div class="profile-preview-wishlist">
                            <a href="javascript:void(0)" id="${data.vendorId}" class="preview-wishlist-icon addToFavorite">
                                    <i class="fa fa-heart-o"></i>
                                </a>
                            </div>
                            <?php else : ?>
                        <div class="profile-preview-wishlist">
                            <a href="javascript:void(0)" class="preview-wishlist-icon loginAlert"><i class="fa fa-heart-o"></i></a>
                        </div>
<?php endif; ?>
                        <div class="d-flex align-items-center justify-content-between gap-2">
                            <div class="prev-profile-image">
                                <img src="${data.profileImage}">
                                </div>
                                <div class="prev-profile-detail">
                                    <a href="${view_vendor_details}"><h3>${data.title}</h3></a>
                                    <a href="${view_vendor_details}"><p>${data.description}</p></a>
                                </div>
                            </div>
                        </div>
                    </div></div>`;
                    } else {
                        html += `<div id="profile-preview-box" class="cat-item profile-preview-box pt-4"><div class="profile-preview-box-inner">
                        <div class="profile-preview-img">
                            <div class="profile-preview-img-inner">
                                <video width="400px" height="250px" controls autoplay muted playsinline>
                                    <source src="${data.video}" type="video/mp4">
                                </video>
                            </div>
                        </div>
                        <div class="profile-preview-content">
                            <div class="d-flex align-items-center justify-content-between gap-2">
                                <div class="prev-profile-detail">
                                    <a href="${view_vendor_details}"><h3>${data.title}</h3></a>
                                    <a href="${view_vendor_details}"><p>${data.description}</p></a>
                                </div>
                            </div>
                            <div class="prev-profile-btn">
                                <a href="${view_vendor_details}" class="btn btn-primary py-1 px-3 cursor-auto text-white" id="preview-arrow" tabindex="0">
                                    <span class="fa fa-arrow-right"></span>
                                </a>
                            </div>
                        </div>
                    </div></div>`;
                    }
                }
                if (html != '') {
                    $('#highlights').html(html);
                } else {
                    $('.highlights-section').addClass('d-none');
                }
                setTimeout(() => {
                    slickHightlightsCarousel(advlength);
                }, 1000);



                <?php if (Auth::check()) : ?>
                $('.addToFavorite').each(function() {
                    var vendorId = $(this).attr('id');
                    checkFavVendor(vendorId);
                });
                <?php endif; ?>
            });

    }



    async function slickHightlightsCarousel(advlength) {
        const highlightCount = advlength;
        console.log(highlightCount)
        if ($('.highlights-slider').hasClass('slick-initialized')) {
            $('.highlights-slider').slick('unslick');
        }
        if (highlightCount <= 1) return;
        $('.highlights-slider').slick({
            slidesToShow: (advlength<=3) ? advlength - 1 : 3,
            arrows: true,
            responsive: [{
                breakpoint: 1199,
                settings: {
                    arrows: true,
                    centerMode: true,
                    centerPadding: '40px',
                    slidesToShow: (advlength<=3) ? advlength - 1 : 3,
                }
            }, {
                breakpoint: 992,
                settings: {
                    arrows: true,
                    centerMode: true,
                    centerPadding: '40px',
                    slidesToShow: (advlength<=3) ? advlength - 1 : 3,
                }
            }, {
                breakpoint: 768,
                settings: {
                    arrows: true,
                    centerMode: true,
                    centerPadding: '40px',
                    slidesToShow: (advlength<=3) ? advlength - 1 : 3,
                }
            },
                {
                    breakpoint: 560,
                    settings: {
                        arrows: false,
                        centerMode: true,
                        centerPadding: '20px',
                        slidesToShow: 1,
                    }
                }
            ]
        });

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

    // Update buildAllStoresHTMLFromArray to keep using checkSelfDeliveryForVendor
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

                var status = val.currentStatus;
                var statusclass = status.toLowerCase() === 'open' ? 'open' : 'closed';

                var vendor_id_single = val.id;
                var view_vendor_details = "/restaurant/" + vendor_id_single + "/" + val.restaurant_slug + "/" + val.zone_slug;

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
                    '"><img onerror="this.onerror=null;this.src=\'' + placeholderImage + '\'" alt="#" src="' +
                    photo +
                    '" class="img-fluid item-img w-100"></a></div><div class="py-2 position-relative"><div class="list-card-body"><h6 class="mb-1 popul-title"><a href="' +
                    view_vendor_details + '" class="text-black">' + val.title +
                    '</a></h6><p class="text-gray mb-1 small address"><span class="fa fa-map-marker"></span>' +
                    val.location + '</p>';

                // Add distance information if available
                if (val.hasOwnProperty('distance')) {
                    const distanceText = radiusUnit === 'miles'
                        ? (val.distance / 1.60934).toFixed(1) + ' mi'
                        : val.distance.toFixed(1) + ' km';
                    html = html + '<p class="text-gray mb-1 small vendor-distance-' + val.id + '"><span class="fa fa-road"></span> ' + distanceText + '</p>';
                }

                // Add delivery information
                html = html + '<div class="delivery-info-' + val.id + ' text-gray mb-1 small"></div>';

                html = html + '<span class="pro-price vendor_dis_' + val.id + ' " ></span>';
                html = html +
                    '<div class="star position-relative mt-3"><span class="badge badge-success "><i class="feather-star"></i>' +
                    rating + ' (' + reviewsCount + ')</span></div>';
                html = html + '</div>';
                html = html + '</div></div></div>';

                // Keep the existing checkSelfDeliveryForVendor call
                checkSelfDeliveryForVendor(val.id);
            });
            html = html + '</div>';
        } else {
            html = '<div class="text-center mt-5"><p>No restaurants found matching the selected filters.</p></div>';
        }
        return html;
    }

    // Update delivery filter to work with existing functionality
    $('#restaurant-delivery').on('change', function() {
        const deliveryFilter = $(this).val();

        if (deliveryFilter === 'default') {
            // Show all vendors
            updateVendorsList();
        } else {
            // Filter vendors based on delivery option
            const vendors = window.vendorsData.docs.filter(doc => {
                const data = doc.data();
                if (inValidVendors.has(doc.id)) return false;

                if (deliveryFilter === 'free_delivery') {
                    return data.isSelfDelivery && isSelfDeliveryGlobally;
                } else if (deliveryFilter === 'paid_delivery') {
                    return data.isSelfDelivery && !isSelfDeliveryGlobally;
                }
                return true;
            });

            const html = buildAllStoresHTMLFromArray(vendors);
            $('#all_stores').html(html);
        }
    });

    // Add these filter functions without modifying existing code

    function filterByPrice(vendors, order = 'asc') {
        return vendors.sort((a, b) => {
            const priceA = getPriceRangeValue(a);
            const priceB = getPriceRangeValue(b);
            return order === 'asc' ? priceA - priceB : priceB - priceA;
        });
    }

    function filterByRating(vendors, minRating = 0) {
        return vendors.filter(vendor => {
            if(vendor.reviewsSum && vendor.reviewsCount) {
                const rating = vendor.reviewsSum / vendor.reviewsCount;
                return rating >= minRating;
            }
            return false;
        });
    }

    function filterByCategory(vendors, categoryId) {
        return vendors.filter(vendor =>
            vendor.categoryID && vendor.categoryID.includes(categoryId)
        );
    }

    function filterByDeliveryOption(vendors, freeDeliveryOnly = false) {
        return vendors.filter(vendor =>
            !freeDeliveryOnly || (vendor.isSelfDelivery && isSelfDeliveryGlobally)
        );
    }

    function filterByStatus(vendors, openOnly = true) {
        return vendors.filter(vendor => {
            const isOpen = checkIfOpen(vendor.workingHours);
            return openOnly ? isOpen : !isOpen;
        });
    }

    function filterByOffers(vendors, hasOffersOnly = true) {
        return vendors.filter(vendor => {
            const hasOffers = vendor.discount || vendor.coupons?.length;
            return hasOffersOnly ? hasOffers : !hasOffers;
        });
    }

    function filterByDistance(vendors, maxDistance) {
        return vendors.filter(vendor => {
            const distance = calculateDistance(
                address_lat,
                address_lng,
                vendor.latitude,
                vendor.longitude
            );
            return distance <= maxDistance;
        });
    }

    $(document).ready(function() {
        $('#restaurant-sort, #restaurant-status, #restaurant-price, #restaurant-rating').on('change', function() {
            if (!window.vendorsData) return;

            const sortOrder = $('#restaurant-sort').val();
            const statusFilter = $('#restaurant-status').val();
            const priceFilter = $('#restaurant-price').val();
            const ratingFilter = $('#restaurant-rating').val();

            // First get the vendors data
            let vendors = [];
            window.vendorsData.docs.forEach((listval) => {
                var datas = listval.data();
                datas.id = listval.id;
                if (!inValidVendors.has(listval.id)) {
                    // Add current status to vendor data
                    datas.currentStatus = getVendorStatus(datas);

                    // Calculate average rating
                    let avgRating = 0;
                    let reviewCount = 0;
                    if (datas.reviewsCount && datas.reviewsCount > 0) {
                        avgRating = datas.reviewsSum / datas.reviewsCount;
                        reviewCount = datas.reviewsCount;
                    }
                    datas.avgRating = avgRating;
                    datas.reviewCount = reviewCount;

                    // Apply filters
                    let includeVendor = true;

                    // Status filter
                    if (statusFilter !== 'default') {
                        if (statusFilter === 'open' && datas.currentStatus !== 'Open') {
                            includeVendor = false;
                        } else if (statusFilter === 'closed' && datas.currentStatus !== 'Closed') {
                            includeVendor = false;
                        }
                    }

                    // Rating filter
                    if (ratingFilter !== 'default') {
                        const minRating = parseFloat(ratingFilter);
                        if (avgRating < minRating) {
                            includeVendor = false;
                        }
                    }

                    if (includeVendor) {
                        vendors.push(datas);
                    }
                }
            });

            // Apply sorting
            if (sortOrder === 'asc') {
                vendors.sort((a, b) => (a.title || '').localeCompare(b.title || ''));
            } else if (sortOrder === 'desc') {
                vendors.sort((a, b) => (b.title || '').localeCompare(a.title || ''));
            } else if (priceFilter === '1') {
                vendors.sort((a, b) => (a.minPrice || 0) - (b.minPrice || 0));
            } else if (priceFilter === '2') {
                vendors.sort((a, b) => (b.minPrice || 0) - (a.minPrice || 0));
            }

            buildAllStoresHTMLFromArray(vendors);
        });
    });

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

    // Handle custom distance input visibility
    $('#restaurant-distance').on('change', function() {
        if ($(this).val() === 'custom') {
            $('#custom-distance-container').show();
        } else {
            $('#custom-distance-container').hide();
        }

        // Update distance unit based on global setting
        $('#distance-unit').text(radiusUnit || 'km');
    });

    // Update the filter handler to include distance
    $('#restaurant-sort, #restaurant-status, #restaurant-price, #restaurant-rating, #restaurant-category, #restaurant-delivery, #restaurant-offers, #restaurant-distance, #custom-distance').on('change', function() {
        if (!window.vendorsData) return;

        const sortOrder = $('#restaurant-sort').val();
        const statusFilter = $('#restaurant-status').val();
        const priceFilter = $('#restaurant-price').val();
        const ratingFilter = $('#restaurant-rating').val();
        const categoryFilter = $('#restaurant-category').val();
        const deliveryFilter = $('#restaurant-delivery').val();
        const offersFilter = $('#restaurant-offers').val();
        let distanceFilter = $('#restaurant-distance').val();

        // Handle custom distance
        if (distanceFilter === 'custom') {
            const customDistance = $('#custom-distance').val();
            if (customDistance && !isNaN(customDistance)) {
                distanceFilter = parseFloat(customDistance);
            }
        } else if (distanceFilter !== 'default') {
            distanceFilter = parseFloat(distanceFilter);
        }

        // Convert distance if using miles
        if (radiusUnit === 'miles' && distanceFilter !== 'default') {
            distanceFilter = distanceFilter * 1.60934; // Convert miles to km for calculation
        }

        // Show loading indicator
        $('#all_stores').html('<div class="text-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>');

        // Use setTimeout to prevent UI blocking
        setTimeout(() => {
            let vendors = [];

            window.vendorsData.docs.forEach((listval) => {
                var datas = listval.data();
                datas.id = listval.id;

                if (!inValidVendors.has(listval.id)) {
                    let includeVendor = true;

                    // Get cached delivery status
                    const deliveryStatus = vendorDeliveryCache.get(datas.id);

                    // Status filter
                    if (statusFilter !== 'default') {
                        const currentStatus = getVendorStatus(datas);
                        if (statusFilter === 'open' && currentStatus !== 'Open') {
                            includeVendor = false;
                        } else if (statusFilter === 'closed' && currentStatus !== 'Closed') {
                            includeVendor = false;
                        }
                    }

                    // Rating filter
                    if (includeVendor && ratingFilter !== 'default') {
                        const avgRating = datas.reviewsCount ? (datas.reviewsSum / datas.reviewsCount) : 0;
                        if (avgRating < parseFloat(ratingFilter)) {
                            includeVendor = false;
                        }
                    }

                    // Category filter
                    if (includeVendor && categoryFilter !== 'default') {
                        if (!datas.categoryID || !datas.categoryID.includes(categoryFilter)) {
                            includeVendor = false;
                        }
                    }

                    // Delivery filter
                    if (includeVendor && deliveryFilter !== 'default' && deliveryStatus) {
                        if (deliveryFilter === 'free_delivery' && !deliveryStatus.hasFreeSelfDelivery) {
                            includeVendor = false;
                        } else if (deliveryFilter === 'paid_delivery' && deliveryStatus.hasFreeSelfDelivery) {
                            includeVendor = false;
                        }
                    }

                    // Offers filter
                    if (includeVendor && offersFilter !== 'default') {
                        if (offersFilter === 'active_discounts' && (!datas.hasOwnProperty('discount') || !datas.discount)) {
                            includeVendor = false;
                        } else if (offersFilter === 'active_coupons' && (!datas.hasOwnProperty('coupons') || !datas.coupons.length)) {
                            includeVendor = false;
                        } else if (offersFilter === 'special_offers' && (!datas.hasOwnProperty('specialOffers') || !datas.specialOffers)) {
                            includeVendor = false;
                        }
                    }

                    // Distance filter
                    if (includeVendor && distanceFilter !== 'default') {
                        const distance = calculateDistance(
                            address_lat,
                            address_lng,
                            datas.latitude,
                            datas.longitude
                        );

                        if (distance > distanceFilter) {
                            includeVendor = false;
                        }

                        // Add distance to vendor data for display
                        datas.distance = distance;
                    }

                    if (includeVendor) {
                        vendors.push(datas);
                    }
                }
            });

            // Apply sorting
            if (sortOrder !== 'default' || priceFilter !== 'default') {
                vendors.sort((a, b) => {
                    if (sortOrder === 'asc') return (a.title || '').localeCompare(b.title || '');
                    if (sortOrder === 'desc') return (b.title || '').localeCompare(a.title || '');
                    if (priceFilter === '1') return (a.minPrice || 0) - (b.minPrice || 0);
                    if (priceFilter === '2') return (b.minPrice || 0) - (a.minPrice || 0);
                    return 0;
                });
            }

            // Update UI
            const html = buildAllStoresHTMLFromArray(vendors);
            $('#all_stores').html(html);

            // Update delivery badges and distance information
            vendors.forEach(vendor => {
                const deliveryStatus = vendorDeliveryCache.get(vendor.id);
                if (deliveryStatus?.hasFreeSelfDelivery) {
                    $('.free-delivery-' + vendor.id).html('<span><img src="{{ asset('img/free_delivery.png') }}" width="100px"> {{trans("lang.free_delivery")}}</span>');
                }

                // Add distance information if available
                if (vendor.hasOwnProperty('distance')) {
                    const distanceText = radiusUnit === 'miles'
                        ? (vendor.distance / 1.60934).toFixed(1) + ' mi'
                        : vendor.distance.toFixed(1) + ' km';
                    $('.vendor-distance-' + vendor.id).text(distanceText);
                }
            });
        }, 0);
    });

    // Show/hide custom distance input
    $(document).ready(function() {
        $('#restaurant-distance').on('change', function() {
            if ($(this).val() === 'custom') {
                $('#custom-distance-container').show();
            } else {
                $('#custom-distance-container').hide();
            }
        });

        // Handle custom distance input
        $('#custom-distance').on('change', function() {
            const customDistance = $(this).val();
            if (customDistance && !isNaN(customDistance)) {
                updateVendorsList();
            }
        });
    });

    $(document).ready(function() {
        // ... existing ready handler code ...

        // Add clear filter functionality
        $('#clear-filters').on('click', function() {
            $('#restaurant-sort').val('default');
            $('#restaurant-status').val('default');
            $('#restaurant-price').val('default');
            $('#restaurant-rating').val('default');
            $('#restaurant-category').val('default');
            $('#restaurant-delivery').val('default');
            $('#restaurant-offers').val('default');
            $('#restaurant-distance').val('default');
            $('#custom-distance').val('');
            $('#custom-distance-container').hide();
            updateVendorsList();
        });
    });

    window.randomizedRatings = {};

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

        const html = buildAllStoresHTMLFromArray(pageData);
        $('#all_stores').html(html);

        // Update delivery badges and distance information
        pageData.forEach(vendor => {
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
    }

    // Function to handle pagination with filtering
    function updateVendorsListWithPagination() {
        if (!window.vendorsData) return;

        const sortOrder = $('#restaurant-sort').val();
        const statusFilter = $('#restaurant-status').val();
        const priceFilter = $('#restaurant-price').val();
        const ratingFilter = $('#restaurant-rating').val();
        const categoryFilter = $('#restaurant-category').val();
        const deliveryFilter = $('#restaurant-delivery').val();
        const offersFilter = $('#restaurant-offers').val();
        let distanceFilter = $('#restaurant-distance').val();

        // Show loading indicator
        $('#all_stores').html('<div class="text-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>');

        setTimeout(async () => {
            let vendors = [];

            for (const listval of window.vendorsData.docs) {
                const datas = listval.data();
                datas.id = listval.id;

                if (!inValidVendors.has(listval.id)) {
                    let includeVendor = true;

                    // Get vendor delivery details
                    const deliveryDetails = await getVendorDeliveryDetails(datas.id);

                    // Apply filters
                    if (statusFilter !== 'default') {
                        const currentStatus = getVendorStatus(datas);
                        if (statusFilter === 'open' && currentStatus !== 'Open') {
                            includeVendor = false;
                        } else if (statusFilter === 'closed' && currentStatus !== 'Closed') {
                            includeVendor = false;
                        }
                    }

                    if (includeVendor && ratingFilter !== 'default') {
                        const avgRating = datas.reviewsCount ? (datas.reviewsSum / datas.reviewsCount) : 0;
                        if (avgRating < parseFloat(ratingFilter)) {
                            includeVendor = false;
                        }
                    }

                    if (includeVendor && categoryFilter !== 'default') {
                        if (!datas.categoryID || !datas.categoryID.includes(categoryFilter)) {
                            includeVendor = false;
                        }
                    }

                    if (includeVendor && deliveryFilter !== 'default') {
                        if (!vendorMatchesDeliveryFilter(deliveryDetails, deliveryFilter)) {
                            includeVendor = false;
                        }
                    }

                    if (includeVendor && offersFilter !== 'default') {
                        if (offersFilter === 'active_discounts' && (!datas.hasOwnProperty('discount') || !datas.discount)) {
                            includeVendor = false;
                        } else if (offersFilter === 'active_coupons' && (!datas.hasOwnProperty('coupons') || !datas.coupons.length)) {
                            includeVendor = false;
                        } else if (offersFilter === 'special_offers' && (!datas.hasOwnProperty('specialOffers') || !datas.specialOffers)) {
                            includeVendor = false;
                        }
                    }

                    if (includeVendor && distanceFilter !== 'default') {
                        const distance = calculateDistance(
                            address_lat,
                            address_lng,
                            datas.latitude,
                            datas.longitude
                        );

                        if (distance > distanceFilter) {
                            includeVendor = false;
                        }

                        // Add distance and delivery charge to vendor data
                        datas.distance = distance;
                        datas.deliveryCharge = calculateDeliveryCharge(deliveryDetails, distance);
                    }

                    if (includeVendor) {
                        vendors.push(datas);
                    }
                }
            }

            // Apply sorting
            if (sortOrder !== 'default' || priceFilter !== 'default') {
                vendors.sort((a, b) => {
                    if (sortOrder === 'asc') return (a.title || '').localeCompare(b.title || '');
                    if (sortOrder === 'desc') return (b.title || '').localeCompare(a.title || '');
                    if (priceFilter === '1') return (a.minPrice || 0) - (b.minPrice || 0);
                    if (priceFilter === '2') return (b.minPrice || 0) - (a.minPrice || 0);
                    return 0;
                });
            }

            // Update filtered data and pagination
            filteredVendorsData = vendors;
            totalRestaurants = vendors.length;
            totalPages = Math.ceil(totalRestaurants / pagesize);
            currentPage = 1;

            // Initialize pagination
            initializePagination();

            // Display first page
            displayCurrentPage();
        }, 0);
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

        // Update filter handlers to use pagination
        $('#restaurant-sort, #restaurant-status, #restaurant-price, #restaurant-rating, #restaurant-category, #restaurant-delivery, #restaurant-offers, #restaurant-distance').off('change').on('change', function() {
            updateVendorsListWithPagination();
        });

        // Clear filters with pagination
        $('#clear-filters').off('click').on('click', function() {
            $('#restaurant-sort').val('default');
            $('#restaurant-status').val('default');
            $('#restaurant-price').val('default');
            $('#restaurant-rating').val('default');
            $('#restaurant-category').val('default');
            $('#restaurant-delivery').val('default');
            $('#restaurant-offers').val('default');
            $('#restaurant-distance').val('default');
            $('#custom-distance').val('');
            $('#custom-distance-container').hide();
            updateVendorsListWithPagination();
        });
    });

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

    // Backward compatibility function for load more
    function loadMoreRestaurants() {
        if (paginationEnabled) {
            if (currentPage < totalPages) {
                goToPage(currentPage + 1);
            }
        } else {
            // Original load more logic would go here
            console.log('Load more functionality not implemented');
        }
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
