@extends('layouts.app')

@section('content')
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

                <!-- Restaurant Filter Bar -->
                <div class="filter-bar-container mb-4">
                    <div class="filter-bar">
                        <!-- Active Filters with remove -->
                        <div class="filter-chip" id="active-filters" style="display: none;">
                            <!-- Active filters will be dynamically added here -->
                        </div>

                        <!-- Filter Dropdowns -->
                        <div class="dropdown">
                            <div class="filter-chip dropdown-toggle" data-bs-toggle="dropdown" id="sort-filter">
                                <span class="filter-text">Sort By: Popular</span>
                                <span class="dropdown-arrow">▼</span>
                            </div>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item filter-option" href="#" data-filter="popular" data-value="popular">
                                    <span class="option-text">Popular</span>
                                    <span class="checkmark" style="display: none;">✓</span>
                                </a></li>
                                <li><a class="dropdown-item filter-option" href="#" data-filter="rating" data-value="rating">
                                    <span class="option-text">Rating</span>
                                    <span class="checkmark" style="display: none;">✓</span>
                                </a></li>
                                <li><a class="dropdown-item filter-option" href="#" data-filter="newest" data-value="newest">
                                    <span class="option-text">Newest</span>
                                    <span class="checkmark" style="display: none;">✓</span>
                                </a></li>
                                <li><a class="dropdown-item filter-option" href="#" data-filter="distance" data-value="distance">
                                    <span class="option-text">Distance</span>
                                    <span class="checkmark" style="display: none;">✓</span>
                                </a></li>
                            </ul>
                        </div>

                        <div class="dropdown">
                            <div class="filter-chip dropdown-toggle" data-bs-toggle="dropdown" id="status-filter">
                                <span class="filter-text">Status: All</span>
                                <span class="dropdown-arrow">▼</span>
                            </div>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item filter-option" href="#" data-filter="status" data-value="all">
                                    <span class="option-text">All</span>
                                    <span class="checkmark" style="display: none;">✓</span>
                                </a></li>
                                <li><a class="dropdown-item filter-option" href="#" data-filter="status" data-value="open">
                                    <span class="option-text">Open Now</span>
                                    <span class="checkmark" style="display: none;">✓</span>
                                </a></li>
                                <li><a class="dropdown-item filter-option" href="#" data-filter="status" data-value="closed">
                                    <span class="option-text">Closed</span>
                                    <span class="checkmark" style="display: none;">✓</span>
                                </a></li>
                            </ul>
                        </div>

                        <div class="dropdown">
                            <div class="filter-chip dropdown-toggle" data-bs-toggle="dropdown" id="delivery-filter">
                                <span class="filter-text">Delivery: All</span>
                                <span class="dropdown-arrow">▼</span>
                            </div>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item filter-option" href="#" data-filter="delivery" data-value="all">
                                    <span class="option-text">All</span>
                                    <span class="checkmark" style="display: none;">✓</span>
                                </a></li>
                                <li><a class="dropdown-item filter-option" href="#" data-filter="delivery" data-value="self">
                                    <span class="option-text">Self Delivery</span>
                                    <span class="checkmark" style="display: none;">✓</span>
                                </a></li>
                                <li><a class="dropdown-item filter-option" href="#" data-filter="delivery" data-value="partner">
                                    <span class="option-text">Partner Delivery</span>
                                    <span class="checkmark" style="display: none;">✓</span>
                                </a></li>
                            </ul>
                        </div>

                        <div class="dropdown">
                            <div class="filter-chip dropdown-toggle" data-bs-toggle="dropdown" id="distance-filter">
                                <span class="filter-text">Distance: All</span>
                                <span class="dropdown-arrow">▼</span>
                            </div>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item filter-option" href="#" data-filter="distance_range" data-value="all">
                                    <span class="option-text">All</span>
                                    <span class="checkmark" style="display: none;">✓</span>
                                </a></li>
                                <li><a class="dropdown-item filter-option" href="#" data-filter="distance_range" data-value="5">
                                    <span class="option-text">Within 5km</span>
                                    <span class="checkmark" style="display: none;">✓</span>
                                </a></li>
                                <li><a class="dropdown-item filter-option" href="#" data-filter="distance_range" data-value="10">
                                    <span class="option-text">Within 10km</span>
                                    <span class="checkmark" style="display: none;">✓</span>
                                </a></li>
                                <li><a class="dropdown-item filter-option" href="#" data-filter="distance_range" data-value="15">
                                    <span class="option-text">Within 15km</span>
                                    <span class="checkmark" style="display: none;">✓</span>
                                </a></li>
                            </ul>
                        </div>

                        <!-- Clear All -->
                        <div class="filter-chip clear-all" id="clear-all-filters" style="display: none;">
                            <span class="filter-text">Clear All</span>
                            <span class="remove-btn">&times;</span>
                        </div>
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
    /* Filter Bar Styles */
    .filter-bar-container {
        margin: 1rem 0;
    }
    .filter-bar {
        background: #fff;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        align-items: center;
        border: 1px solid #e9ecef;
    }
    .filter-chip {
        display: inline-flex;
        align-items: center;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 25px;
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        color: #495057;
        position: relative;
        min-width: 120px;
        justify-content: space-between;
    }
    .filter-chip:hover {
        background: #e9ecef;
        border-color: #007bff;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,123,255,0.15);
        text-decoration: none;
        color: #495057;
    }
    .filter-chip.active {
        background: #007bff;
        color: white;
        border-color: #007bff;
    }
    .filter-chip.active .dropdown-arrow {
        color: white;
    }
    .filter-text {
        flex: 1;
    }
    .dropdown-arrow {
        margin-left: 0.5rem;
        font-size: 0.7rem;
        transition: transform 0.3s ease;
        color: #6c757d;
    }
    .filter-chip:hover .dropdown-arrow {
        transform: rotate(180deg);
    }
    .filter-chip .remove-btn {
        margin-left: 6px;
        font-size: 1rem;
        line-height: 1;
        cursor: pointer;
        color: #6c757d;
        padding: 2px 4px;
        border-radius: 50%;
        transition: all 0.2s ease;
    }
    .filter-chip .remove-btn:hover {
        background: rgba(220,53,69,0.1);
        color: #dc3545;
    }
    .dropdown-toggle::after {
        display: none;
    }
    .dropdown-menu {
        border-radius: 12px;
        padding: 0.5rem;
        border: none;
        min-width: 200px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        animation: dropdownFade 0.3s ease-in-out;
        margin-top: 0.5rem;
    }
    @keyframes dropdownFade {
        from { opacity: 0; transform: translateY(-10px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    .dropdown-item {
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: space-between;
        text-decoration: none;
        color: #495057;
        margin-bottom: 0.25rem;
    }
    .dropdown-item:hover {
        background: #f8f9ff;
        color: #007bff;
        transform: translateX(5px);
        text-decoration: none;
    }
    .dropdown-item.active {
        background: #e3f2fd;
        font-weight: 600;
        color: #007bff;
    }
    .dropdown-item.active .checkmark {
        color: #007bff;
        font-weight: bold;
    }
    .option-text {
        flex: 1;
    }
    .checkmark {
        font-size: 1rem;
        color: #28a745;
    }
    /* Clear All Button */
    .clear-all {
        background: linear-gradient(135deg, #ff6b6b, #ee5a24);
        color: white;
        border: none;
        box-shadow: 0 2px 8px rgba(255,107,107,0.3);
    }
    .clear-all:hover {
        background: linear-gradient(135deg, #ff5252, #d63031);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(255,107,107,0.4);
        text-decoration: none;
    }
    .clear-all .remove-btn {
        color: white;
    }
    .clear-all .remove-btn:hover {
        background: rgba(255,255,255,0.2);
        color: white;
    }

    /* Restaurant Card Styles - Matching Existing Design */
    #all_stores {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-top: 1rem;
    }

    /* Popular Restaurants Grid Layout */
    .restaurant-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-top: 1rem;
    }

    .restaurant-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        height: 100%;
        border: 1px solid #f0f0f0;
        cursor: pointer;
        position: relative;
    }
    .restaurant-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        border-color: #007bff;
    }
    .restaurant-card:active {
        transform: translateY(-2px);
    }
    .restaurant-card.card-clicked {
        transform: scale(0.98);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
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
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 0.6rem;
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
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .restaurant-info {
        padding: 1.25rem;
    }

    .restaurant-name {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        color: #2c3e50;
        line-height: 1.3;
    }
    .restaurant-name:hover {
        color: #007bff;
        cursor: pointer;
    }
  
    .restaurant-location {
        display: flex;
        align-items: center;
        margin-bottom: 0.75rem;
        color: #7f8c8d;
        font-size: 0.9rem;
    }
    .restaurant-location .location-icon {
        margin-right: 0.5rem;
        width: 16px;
        height: 16px;
        flex-shrink: 0;
    }
    .restaurant-location .location-icon svg {
        width: 100%;
        height: 100%;
    }
    .restaurant-location .location-text {
        flex: 1;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .restaurant-location:hover {
        color: #007bff;
        cursor: pointer;
    }
    .restaurant-location:hover .location-icon svg path {
        fill: #007bff;
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
        font-weight: 400;
    }
    .rating-badge:active {
        transform: translateY(0);
    }
    .rating-badge::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }
    .rating-badge:hover::before {
        left: 100%;
    }
    .rating-badge .badge-icon {
        font-size: 0.7rem;
        transition: transform 0.2s ease;
    }
    .rating-badge:hover .badge-icon {
        transform: scale(1.1);
    }
    .rating-badge.badge-clicked {
        transform: scale(0.95);
        background: linear-gradient(135deg, #1e7e34, #20c997);
    }

    .restaurant-price {
        color: #27ae60;
        font-weight: 700;
        font-size: 1rem;
    }

    /* Grid responsive adjustments */
    @media (max-width: 768px) {
        #all_stores, .restaurant-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        .restaurant-card {
            margin: 0 0.5rem;
        }
    }

    @media (min-width: 769px) and (max-width: 1024px) {
        #all_stores, .restaurant-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (min-width: 1025px) {
        #all_stores, .restaurant-grid {
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        }
    }

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
        // Check if user_zone_id is available
        if (!user_zone_id) {
            console.log("User zone ID not available, skipping banner fetch");
            return;
        }

        var available_stores = [];
        geoFirestore.collection('vendors').where('zoneId', '==', user_zone_id).get().then(async function(snapshots) {
            snapshots.docs.forEach((doc) => {
                if (!inValidVendors.has(doc.id)) {
                    available_stores.push(doc.id);
                }
            });
        });
        
        var position1_banners = [];
        
        // Filter banners by user's zone ID - using simpler query to avoid index issues
        // Create a new reference without orderBy to avoid composite index requirement
        var zoneBannerRef = database.collection('menu_items').where('zoneId', '==', user_zone_id).where('is_publish', '==', true);
        zoneBannerRef.get().then(async function(banners) {
            console.log("Fetching banners for zone:", user_zone_id);
            console.log("Found banners:", banners.docs.length);
            
            banners.docs.forEach((banner) => {
                var bannerData = banner.data();
                var redirect_type = '';
                var redirect_id = '';
                
                console.log("Processing banner:", bannerData.title, "Position:", bannerData.position, "ZoneId:", bannerData.zoneId);
                
                // Only process banners with position 'top' and matching zone
                if (bannerData.position == 'top' && bannerData.zoneId == user_zone_id) {
                    if (bannerData.hasOwnProperty('redirect_type')) {
                        redirect_type = bannerData.redirect_type;
                        redirect_id = bannerData.redirect_id;
                    }
                    var object = {
                        'photo': bannerData.photo,
                        'redirect_type': redirect_type,
                        'redirect_id': redirect_id,
                        'restaurant_slug': bannerData.restaurant_slug || '',
                        'zone_slug': bannerData.zone_slug || '',
                        'set_order': bannerData.set_order || 0
                    }
                    position1_banners.push(object);
                    console.log("✅ Added banner for zone:", user_zone_id, "Title:", bannerData.title);
                } else {
                    console.log("❌ Banner filtered out - Position:", bannerData.position, "Expected: 'top', ZoneId:", bannerData.zoneId, "Expected:", user_zone_id);
                }
            });
            
            // Sort banners by set_order on client side
            position1_banners.sort((a, b) => (a.set_order || 0) - (b.set_order || 0));
            
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
                            } else {
                                redirect_id = "/restaurant/" + banner.redirect_id + "/" + banner.restaurant_slug + "/" + banner.zone_slug;
                            }
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
                console.log("Banners displayed for zone:", user_zone_id);
            } else {
                console.log("No banners found for zone:", user_zone_id);
                $('.ecommerce-banner').remove();
            }
            setTimeout(function() {
                slickcatCarousel();
            }, 200)
        }).catch(function(error) {
            console.error("Error fetching banners for zone:", user_zone_id, error);
            // Fallback: try without zone filter if the query fails
            console.log("Trying fallback banner query without zone filter...");
            bannerref.get().then(async function(fallbackBanners) {
                var fallback_banners = [];
                fallbackBanners.docs.forEach((banner) => {
                    var bannerData = banner.data();
                    if (bannerData.position == 'top') {
                        var object = {
                            'photo': bannerData.photo,
                            'redirect_type': bannerData.redirect_type || '',
                            'redirect_id': bannerData.redirect_id || '',
                            'restaurant_slug': bannerData.restaurant_slug || '',
                            'zone_slug': bannerData.zone_slug || ''
                        }
                        fallback_banners.push(object);
                    }
                });
                
                if (fallback_banners.length > 0) {
                    var html = '';
                    for (banner of fallback_banners) {
                        html += '<div class="banner-item">';
                        html += '<div class="banner-img">';
                        var redirect_id = '#';
                        if (banner.redirect_type != '') {
                            if (banner.redirect_type == "store") {
                                if (jQuery.inArray(banner.redirect_id, available_stores) === -1) {
                                    redirect_id = '#';
                                } else {
                                    redirect_id = "/restaurant/" + banner.redirect_id + "/" + banner.restaurant_slug + "/" + banner.zone_slug;
                                }
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
                    console.log("Fallback banners displayed");
                } else {
                    $('.ecommerce-banner').remove();
                }
                setTimeout(function() {
                    slickcatCarousel();
                }, 200)
            });
        });
    }


    var myInterval = '';

    // Filter functionality variables
    var activeFilters = {};
    var filteredRestaurants = [];
    var originalRestaurants = [];

    // Initialize randomized ratings object if it doesn't exist
    if (typeof window.randomizedRatings === 'undefined') {
        window.randomizedRatings = {};
    }

    // Initialize filter functionality
    function initializeFilters() {
        console.log("Initializing restaurant filters...");

        // Set default filters
        activeFilters = {
            sort: 'popular',
            status: 'all',
            delivery: 'all',
            distance_range: 'all'
        };

        // Bind filter events
        bindFilterEvents();
    }

    // Bind all filter events
    function bindFilterEvents() {
        // Filter option clicks
        $('.filter-option').on('click', function(e) {
            e.preventDefault();
            var filterType = $(this).data('filter');
            var filterValue = $(this).data('value');
            var filterText = $(this).find('.option-text').text().trim();

            // Remove checkmark from all options in this dropdown
            $(this).closest('.dropdown-menu').find('.checkmark').hide();
            $(this).closest('.dropdown-menu').find('.dropdown-item').removeClass('active');

            // Add checkmark to selected option
            $(this).find('.checkmark').show();
            $(this).addClass('active');

            // Update filter
            applyFilter(filterType, filterValue, filterText);

            // Close dropdown after selection
            $(this).closest('.dropdown').removeClass('show');
        });

        // Clear all filters
        $('#clear-all-filters').on('click', function(e) {
            e.preventDefault();
            clearAllFilters();
        });

        // Add hover effects for dropdown arrows
        $('.filter-chip').hover(
            function() {
                if (!$(this).hasClass('active')) {
                    $(this).find('.dropdown-arrow').css('transform', 'rotate(180deg)');
                }
            },
            function() {
                if (!$(this).hasClass('active')) {
                    $(this).find('.dropdown-arrow').css('transform', 'rotate(0deg)');
                }
            }
        );

        // Close dropdown when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.dropdown').length) {
                $('.dropdown').removeClass('show');
                $('.dropdown-menu').removeClass('show');
            }
        });
    }

    // Apply a filter
    function applyFilter(filterType, filterValue, filterText) {
        console.log("Applying filter:", filterType, "=", filterValue);

        // Update active filters
        activeFilters[filterType] = filterValue;

        // Update filter chip text
        updateFilterChip(filterType, filterText);

        // Apply filters to restaurants
        filterRestaurants();

        // Show/hide clear all button
        updateClearAllButton();
    }

    // Update filter chip text
    function updateFilterChip(filterType, filterText) {
        var chipMap = {
            'popular': '#sort-filter',
            'rating': '#sort-filter',
            'newest': '#sort-filter',
            'distance': '#sort-filter',
            'status': '#status-filter',
            'delivery': '#delivery-filter',
            'distance_range': '#distance-filter'
        };

        var chipSelector = chipMap[filterType];
        if (chipSelector) {
            var prefix = chipSelector.replace('#', '').replace('-filter', '');
            prefix = prefix.charAt(0).toUpperCase() + prefix.slice(1);
            if (prefix === 'Sort') prefix = 'Sort By';

            // Update the filter text span
            $(chipSelector).find('.filter-text').text(prefix + ': ' + filterText.replace(' ✓', ''));

            // Add active class for visual feedback
            $(chipSelector).addClass('active');
        }
    }

    // Filter restaurants based on active filters
    function filterRestaurants() {
        console.log("Filtering restaurants with:", activeFilters);

        var filtered = [...originalRestaurants]; // Copy original array

        // Apply status filter
        if (activeFilters.status !== 'all') {
            filtered = filtered.filter(restaurant => {
                var isOpen = isRestaurantOpen(restaurant);
                return activeFilters.status === 'open' ? isOpen : !isOpen;
            });
        }

        // Apply delivery filter
        if (activeFilters.delivery !== 'all') {
            filtered = filtered.filter(restaurant => {
                return activeFilters.delivery === 'self' ?
                    restaurant.isSelfDelivery :
                    !restaurant.isSelfDelivery;
            });
        }

        // Apply distance filter
        if (activeFilters.distance_range !== 'all' && address_lat && address_lng) {
            var maxDistance = parseInt(activeFilters.distance_range);
            filtered = filtered.filter(restaurant => {
                var distance = calculateDistance(
                    address_lat, address_lng,
                    restaurant.latitude, restaurant.longitude
                );
                return distance <= maxDistance;
            });
        }

        // Apply sorting
        filtered = sortRestaurants(filtered, activeFilters.sort);

        filteredRestaurants = filtered;
        console.log("Filtered restaurants:", filtered.length);

        // Re-render restaurant list
        renderFilteredRestaurants();
    }

    // Sort restaurants based on sort type
    function sortRestaurants(restaurants, sortType) {
        switch(sortType) {
            case 'rating':
                return restaurants.sort((a, b) => {
                    var ratingA = a.reviewsCount > 0 ? (a.reviewsSum / a.reviewsCount) : 0;
                    var ratingB = b.reviewsCount > 0 ? (b.reviewsSum / b.reviewsCount) : 0;
                    return ratingB - ratingA;
                });
            case 'newest':
                return restaurants.sort((a, b) => {
                    return new Date(b.createdAt) - new Date(a.createdAt);
                });
            case 'distance':
                if (address_lat && address_lng) {
                    return restaurants.sort((a, b) => {
                        var distanceA = calculateDistance(address_lat, address_lng, a.latitude, a.longitude);
                        var distanceB = calculateDistance(address_lat, address_lng, b.latitude, b.longitude);
                        return distanceA - distanceB;
                    });
                }
                return restaurants;
            case 'popular':
            default:
                // Default to original order (popularity based on reviews count)
                return restaurants.sort((a, b) => b.reviewsCount - a.reviewsCount);
        }
    }

    // Check if restaurant is open
    function isRestaurantOpen(restaurant) {
        if (window.restaurantStatusManager) {
            const workingHours = restaurant.workingHours || [];
            const isOpen = restaurant.isOpen !== undefined ? restaurant.isOpen : null;
            return window.restaurantStatusManager.isRestaurantOpenNow(workingHours, isOpen);
        }
        return false; // Fallback
    }

    // Calculate distance between two coordinates
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

    // Render filtered restaurants
    function renderFilteredRestaurants() {
        console.log("Rendering", filteredRestaurants.length, "filtered restaurants");

        // Clear existing restaurants
        $('#all_stores').empty();

        if (filteredRestaurants.length === 0) {
            $('#all_stores').html(`
                <div class="text-center py-5">
                    <h5 class="text-muted">No restaurants found matching your filters</h5>
                    <p class="text-muted">Try adjusting your filter criteria</p>
                </div>
            `);
            return;
        }

        // Render restaurants
        filteredRestaurants.forEach(restaurant => {
            var restaurantHtml = buildRestaurantHTML(restaurant);
            $('#all_stores').append(restaurantHtml);
        });

        // Add interactive functionality to restaurant cards
        addRestaurantCardInteractivity();
    }

    // Clear all filters
    function clearAllFilters() {
        console.log("Clearing all filters");

        // Reset active filters
        activeFilters = {
            sort: 'popular',
            status: 'all',
            delivery: 'all',
            distance_range: 'all'
        };

        // Reset filter chips
        $('#sort-filter .filter-text').text('Sort By: Popular');
        $('#status-filter .filter-text').text('Status: All');
        $('#delivery-filter .filter-text').text('Delivery: All');
        $('#distance-filter .filter-text').text('Distance: All');

        // Remove active classes
        $('.filter-chip').removeClass('active');

        // Reset dropdown selections
        $('.dropdown-item').removeClass('active');
        $('.checkmark').hide();

        // Show default selections
        $('.dropdown-menu').each(function() {
            $(this).find('.dropdown-item').first().addClass('active');
            $(this).find('.dropdown-item').first().find('.checkmark').show();
        });

        // Hide clear all button
        $('#clear-all-filters').hide();

        // Reset restaurants
        filteredRestaurants = [...originalRestaurants];
        renderFilteredRestaurants();
    }

    // Update clear all button visibility
    function updateClearAllButton() {
        var hasActiveFilters = Object.values(activeFilters).some(value =>
            value !== 'all' && value !== 'popular'
        );
        $('#clear-all-filters').toggle(hasActiveFilters);
    }

    // Store original restaurants when loaded
    function storeOriginalRestaurants(restaurants) {
        originalRestaurants = restaurants;
        filteredRestaurants = [...restaurants];
        console.log("Stored", restaurants.length, "original restaurants");
    }

    // Add interactive functionality to restaurant cards
    function addRestaurantCardInteractivity() {
        // Restaurant card click handlers - now handled by onclick attribute
        $('.restaurant-card').off('click').on('click', function(e) {
            // Don't trigger if clicking on badges
            if (!$(e.target).closest('.rating-badge').length) {
                var restaurantName = $(this).find('.restaurant-name').text();
                console.log('Restaurant clicked:', restaurantName);

                // Add click animation
                $(this).addClass('card-clicked');
                setTimeout(() => {
                    $(this).removeClass('card-clicked');
                }, 200);

                // Navigation is now handled by onclick attribute in the HTML
            }
        });

        // Rating badge click handlers
        $('.rating-badge').off('click').on('click', function(e) {
            e.stopPropagation(); // Prevent card click

            var badgeText = $(this).text().trim();
            var badgeType = $(this).attr('title');

            console.log('Rating badge clicked:', badgeType, badgeText);

            // Add badge click animation
            $(this).addClass('badge-clicked');
            setTimeout(() => {
                $(this).removeClass('badge-clicked');
            }, 300);

            // Here you can add functionality like showing detailed reviews
            // showRestaurantReviews(restaurantId);
        });

        // Location click handler
        $('.restaurant-location').off('click').on('click', function(e) {
            e.stopPropagation(); // Prevent card click

            var locationText = $(this).find('.location-text').text();
            console.log('Location clicked:', locationText);

            // Here you can add functionality like opening maps
            // openInMaps(locationText);
        });
    }

    // Build restaurant HTML for filter display - Matching existing design
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
        var status = 'Closed';
        var statusclass = "closed";
        if (window.restaurantStatusManager) {
            const workingHours = restaurant.workingHours || [];
            const isOpen = restaurant.isOpen !== undefined ? restaurant.isOpen : null;
            const isOpenNow = window.restaurantStatusManager.isRestaurantOpenNow(workingHours, isOpen);
            if (isOpenNow) {
                status = 'Open';
                statusclass = "open";
            }
        }

        var photo = restaurant.photo || placeholderImageSrc;
        var distance = '';
        if (address_lat && address_lng && restaurant.latitude && restaurant.longitude) {
            var dist = calculateDistance(address_lat, address_lng, restaurant.latitude, restaurant.longitude);
            distance = `<span class="distance">${dist.toFixed(1)} km</span>`;
        }

        // Format location text (truncate if too long)
        var locationText = restaurant.location || '';
        if (locationText.length > 60) {
            locationText = locationText.substring(0, 60) + '...';
        }

        // Create SVG location icon
        var locationIcon = `
            <svg class="location-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" fill="#ff6b35"/>
            </svg>
        `;

        // Rating badges are now included in the main HTML structure

        // Create restaurant detail page URL
        var restaurantUrl = "/restaurant/" + restaurant.id + "/" + (restaurant.restaurant_slug || 'restaurant') + "/" + (restaurant.zone_slug || 'zone');

        return `
            <div class="restaurant-card" onclick="window.location.href='${restaurantUrl}'">
                <div class="restaurant-image">
                    <img src="${photo}" alt="${restaurant.title}" onerror="this.src='${placeholderImageSrc}'">
                    <div class="restaurant-status ${statusclass}">${status}</div>
                    ${distance}
                </div>
                <div class="restaurant-info">
                    <h5 class="restaurant-name">${restaurant.title}</h5>
                    <div class="restaurant-location">
                        <div class="location-icon">${locationIcon}</div>
                        <span class="location-text">${locationText}</span>
                    </div>
                    <div class="restaurant-rating">
                        <div class="rating-stars">
<!--                            <span class="star-icon"></span>-->
                            <span style="font-size: 0.6rem" class="rating-value">★ ${rating}</span>
                        </div>
                        <div class="rating-badges">
                            <div class="rating-badge" data-badge="reviewsCount">
<!--                                <svg class="badge-icon" style="color: white" viewBox="0 0 24 24" width="14" height="14">-->
<!--                                    <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zm4 18v-6h2.5l-2.54-7.63A1.5 1.5 0 0 0 18.54 8H16c-.8 0-1.54.37-2.01 1.01L12 11l-1.99-1.99C9.54 8.37 8.8 8 8 8H5.46c-.8 0-1.54.37-2.01 1.01L.95 16.63A1.5 1.5 0 0 0 2.5 18H5v4h2v-6h2v6h2v-6h2v6h2v-6h2v6h2z"/>-->
<!--                                </svg>-->
                                <span style="font-size: 0.6rem"  class="badge-text">${reviewsCount}</span>
                            </div>
                        </div>
                    </div>
<!--
                     <div class="restaurant-price">
                         ${restaurant.minPrice ? `From ₹${restaurant.minPrice}` : 'Price not available'}
                     </div>
-->
                </div>
            </div>
        `;
    }

    // Location initialization function
    async function initializeLocation() {
        return new Promise((resolve, reject) => {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    async function(position) {
                        try {
                            address_lat = position.coords.latitude;
                            address_lng = position.coords.longitude;
                            console.log("Location obtained:", address_lat, address_lng);

                            // Set cookies for future use
                            setCookie('address_lat', address_lat, 365);
                            setCookie('address_lng', address_lng, 365);

                            // Get address from coordinates
                            await getAddressFromCoordinates(address_lat, address_lng);

                            // Get zone ID for this location
                            await getUserZoneId();

                            resolve();
                        } catch (error) {
                            console.error("Error processing location:", error);
                            reject(error);
                        }
                    },
                    function(error) {
                        console.error("Geolocation error:", error);
                        let errorMessage = "Unable to get your location";
                        
                        switch(error.code) {
                            case error.PERMISSION_DENIED:
                                errorMessage = "Location access denied. Please allow location access or set location manually.";
                                break;
                            case error.POSITION_UNAVAILABLE:
                                errorMessage = "Location information unavailable.";
                                break;
                            case error.TIMEOUT:
                                errorMessage = "Location request timed out.";
                                break;
                        }
                        
                        console.log(errorMessage);
                        
                        // Show user-friendly message
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Location Required',
                                text: errorMessage,
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Set Location Manually',
                                cancelButtonText: 'Try Again',
                                allowOutsideClick: false
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = '{{ url('set-location') }}';
                                } else if (result.dismiss === Swal.DismissReason.cancel) {
                                    // Try again
                                    initializeLocation();
                                }
                            });
                        } else {
                            alert(errorMessage);
                        }
                        
                        // Try to use a default location as fallback
                        console.log("Trying fallback location...");
                        tryFallbackLocation().then(resolve).catch(() => {
                            showLocationError();
                            reject(error);
                        });
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 15000,
                        maximumAge: 300000 // 5 minutes
                    }
                );
            } else {
                console.error("Geolocation not supported");
                showLocationError();
                reject(new Error("Geolocation not supported"));
            }
        });
    }

    // Function to get user zone ID (moved from footer.blade.php)
    async function getUserZoneId() {
        if (!address_lat || !address_lng) {
            console.log("No location available for zone detection");
            return;
        }

        try {
            var zone_list = [];
            console.log("Fetching zones from database...");
            var snapshots = await database.collection('zone').where("publish", "==", true).get();
            console.log("Found", snapshots.docs.length, "published zones");

            if (snapshots.docs.length > 0) {
                snapshots.docs.forEach((snapshot) => {
                    var zone_data = snapshot.data();
                    zone_data.id = snapshot.id;
                    zone_list.push(zone_data);
                    console.log("Zone:", zone_data.id, "-", zone_data.title || "No title");
                });
            }

            if (zone_list.length > 0) {
                console.log("Checking location", address_lat, address_lng, "against", zone_list.length, "zones...");

                for (i = 0; i < zone_list.length; i++) {
                    var zone = zone_list[i];
                    var vertices_x = [];
                    var vertices_y = [];

                    if (zone.area && zone.area.length > 0) {
                        console.log("Checking zone", zone.id, "with", zone.area.length, "boundary points");

                        for (j = 0; j < zone.area.length; j++) {
                            var geopoint = zone.area[j];
                            vertices_x.push(geopoint.longitude);
                            vertices_y.push(geopoint.latitude);
                        }

                        var points_polygon = (vertices_x.length) - 1;
                        var isInZone = is_in_polygon(points_polygon, vertices_x, vertices_y, address_lng, address_lat);
                        console.log("Zone", zone.id, "polygon test result:", isInZone);

                        if (isInZone) {
                            user_zone_id = zone.id;
                            console.log("✅ Zone detected:", user_zone_id, "-", zone.title || "No title");
                            
                            // Save zone ID to cookies for mart page
                            setCookie('user_zone_id', user_zone_id, 365);
                            console.log("Zone ID saved to cookies:", user_zone_id);
                            
                            return; // Exit function once zone is found
                        }
                    } else {
                        console.log("⚠️ Zone", zone.id, "has no area boundaries defined");
                    }
                }
            } else {
                console.log("❌ No published zones found in database");
            }

            // If no zone found, try fallback approaches
            if (!user_zone_id) {
                console.log("No zone found for current location. Trying fallback approaches...");
                var fallbackSuccess = await tryFallbackZoneAssignment();
                if (fallbackSuccess) {
                    console.log("✅ Fallback zone assignment successful:", user_zone_id);
                    // Trigger data loading now that we have a zone
                    setTimeout(() => {
                        callStore();
                    }, 1000);
                } else {
                    console.log("❌ All zone assignment attempts failed");
                    showLocationError();
                }
            } else {
                console.log("✅ Zone assignment successful:", user_zone_id);
                // Trigger data loading now that we have a zone
                setTimeout(() => {
                    callStore();
                }, 1000);
            }

        } catch (error) {
            console.error("Error getting zone ID:", error);
            // Try fallback on error too
            await tryFallbackZoneAssignment();
        }
    }

    // Polygon point-in-polygon test function (moved from footer.blade.php)
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

    // Function to try fallback zone assignment
    async function tryFallbackZoneAssignment() {
        try {
            console.log("Attempting fallback zone assignment...");

            // First, try to get any published zone (as a fallback)
            var snapshots = await database.collection('zone').where("publish", "==", true).get();

            if (snapshots.docs.length > 0) {
                // Use the first available zone as fallback
                var firstZone = snapshots.docs[0];
                user_zone_id = firstZone.id;
                console.log("🔄 Using fallback zone:", user_zone_id, "-", firstZone.data().title || "No title");
                
                // Save fallback zone ID to cookies for mart page
                setCookie('user_zone_id', user_zone_id, 365);
                console.log("Fallback zone ID saved to cookies:", user_zone_id);
                
                return true;
            } else {
                // If no zones exist at all, try to create a default zone or use a system default
                console.log("❌ No zones exist in database. This is a configuration issue.");
                console.log("Please create at least one zone in your admin panel.");
                return false;
            }
        } catch (error) {
            console.error("Error in fallback zone assignment:", error);
            return false;
        }
    }

    // Function to try fallback location (default city center)
    async function tryFallbackLocation() {
        return new Promise((resolve, reject) => {
            // Use a default location - Chennai, India coordinates (based on your detected location)
            address_lat = 12.9716; // Chennai latitude
            address_lng = 80.2206; // Chennai longitude

            console.log("Using fallback location:", address_lat, address_lng);

            // Set cookies for the fallback location
            setCookie('address_lat', address_lat, 365);
            setCookie('address_lng', address_lng, 365);

            // Get address from coordinates
            getAddressFromCoordinates(address_lat, address_lng);

            // Try to get zone ID for fallback location
            getUserZoneId().then(() => {
                resolve();
            }).catch(() => {
                reject();
            });
        });
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
            // Try using browser's geolocation API first (if available)
            if (navigator.geolocation && 'geolocation' in navigator) {
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

    // Function to show location error
    function showLocationError() {
        console.log("Showing location error message");
        jQuery(".section-content").remove();
        jQuery(".zone-error").show();

        // Add more helpful error message
        var errorHtml = `
            <div class="zone-error m-5 p-5 text-center">
                <div class="zone-image text-center">
                    <img src="{{ asset('img/zone_logo.png') }}" width="100" onerror="this.src='${placeholderImage}'">
                </div>
                <div class="zone-content text-center">
                    <h3 class="title text-danger">{{ trans('lang.zone_error_title') }}</h3>
                    <h6 class="text text-muted">{{ trans('lang.zone_error_text') }}</h6>
                    <div class="mt-3">
                        <p class="small text-info">
                            <strong>Location detected:</strong> ${address_lat}, ${address_lng}<br>
                            <strong>Issue:</strong> No delivery zones configured for this area.
                        </p>
                        <button class="btn btn-primary btn-sm" onclick="window.location.reload()">
                            Try Again
                        </button>
                    </div>
                </div>
            </div>
        `;
        jQuery(".zone-error").html(errorHtml);
    }

    $(document).ready(async function() {
        console.log("Initial user_zone_id:", typeof user_zone_id, user_zone_id);
        console.log("Initial address_lat:", typeof address_lat, address_lat);
        console.log("Initial address_lng:", typeof address_lng, address_lng);

        // Initialize location if not available
        if (typeof address_lat === 'undefined' || address_lat === null || address_lat === '' ||
            typeof address_lng === 'undefined' || address_lng === null || address_lng === '') {
            console.log("Location not found in cookies, attempting to get current location...");
            await initializeLocation();
        } else {
            console.log("Location found in cookies, proceeding with zone detection...");
            // Trigger zone detection with existing location
            await getUserZoneId();
        }

        // Initialize filters
        initializeFilters();

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

        // Retry mechanism for location detection - improved
        let locationRetryCount = 0;
        const maxLocationRetries = 15; // Increased retries
        const locationRetryInterval = setInterval(() => {
            if (typeof address_lat !== 'undefined' && typeof address_lng !== 'undefined' &&
                address_lat !== null && address_lng !== null &&
                typeof user_zone_id !== 'undefined' && user_zone_id !== null) {
                clearInterval(locationRetryInterval);
                console.log("Location and zone detected, initializing data...");
                callStore();
            } else if (locationRetryCount >= maxLocationRetries) {
                clearInterval(locationRetryInterval);
                console.log("Location detection timeout, showing error...");
                jQuery(".section-content").remove();
                jQuery(".zone-error").show();
            } else {
                console.log(`Location retry ${locationRetryCount + 1}/${maxLocationRetries} - address_lat: ${address_lat}, address_lng: ${address_lng}, user_zone_id: ${user_zone_id}`);
            }
            locationRetryCount++;
        }, 3000); // Increased interval to 3 seconds

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
            address_lat == '' || address_lng == '' || address_lng == NaN || address_lat == NaN || address_lat == null || address_lng == null ||
            user_zone_id == null || user_zone_id == '') {
            console.log("Location or zone not detected yet, waiting...");
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

                // Store restaurants for filter functionality
                storeOriginalRestaurants(vendors);

                // Display initial restaurants using filter system
                renderFilteredRestaurants();

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

    // Update buildAllStoresHTMLFromArray to use unified restaurant card UI
    function buildAllStoresHTMLFromArray(alldata) {
        var html = '';
        if (alldata.length > 0) {
            alldata.forEach((val) => {
                // Use the unified buildRestaurantHTML function
                html += buildRestaurantHTML(val);

                // Keep the existing checkSelfDeliveryForVendor call
                checkSelfDeliveryForVendor(val.id);
            });
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
            alldata.forEach((val) => {
                // Use the unified buildRestaurantHTML function
                html += buildRestaurantHTML(val);

                // Keep the existing checkSelfDeliveryForVendor call
                checkSelfDeliveryForVendor(val.id);

                // Keep the existing getMinDiscount call
                getMinDiscount(val.id);
            });
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
            var popularItemCount = 0;

            // Add grid container for popular restaurants
            html += '<div class="restaurant-grid">';

            alldata.forEach((val) => {
                if (popularItemCount < 10) {
                    popularItemCount++;
                    popularStoresList.push(val.id);
                }

                // Use the unified buildRestaurantHTML function
                html += buildRestaurantHTML(val);

                // Keep the existing getMinDiscount call
                getMinDiscount(val.id);
            });

            // Close grid container
            html += '</div>';
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

    // Function to check location and navigate to Mart
    function checkLocationAndNavigate(event) {
        // Check if basic location is available
        if (typeof address_lat !== 'undefined' && typeof address_lng !== 'undefined' &&
            address_lat && address_lng) {
            console.log('Location available, allowing access to Mart');
            return true;
        }
        
        // If no location, show alert but still allow navigation
        console.log('No location detected, but allowing access to Mart');
        return true;
        
        // Original location check code (commented out for now)
        /*
        // Check if location variables are defined and valid
        if (typeof address_lat === 'undefined' || typeof address_lng === 'undefined' || typeof user_zone_id === 'undefined' ||
            address_lat == '' || address_lng == '' || address_lat == null || address_lng == null ||
            user_zone_id == null || user_zone_id == '') {
            
            event.preventDefault();
            
            // Show alert to user
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Location Required',
                    text: 'Please set your location first to access the Mart section.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Set Location',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ url('set-location') }}';
                    }
                });
            } else {
                // Fallback if SweetAlert is not available
                if (confirm('Please set your location first to access the Mart section. Would you like to set your location now?')) {
                    window.location.href = '{{ url('set-location') }}';
                }
            }
            return false;
        }
        
        // Location is set, allow navigation
        return true;
        */
    }

    // Update floating button appearance based on location status
    function updateFloatingButtonStatus() {
        const floatingBtn = document.getElementById('mart-floating-btn');
        if (floatingBtn) {
            // Check if location is available
            if (typeof address_lat !== 'undefined' && typeof address_lng !== 'undefined' &&
                address_lat && address_lng) {
            floatingBtn.style.opacity = '1';
            floatingBtn.style.cursor = 'pointer';
            floatingBtn.title = 'Go to Mart';
            } else {
                floatingBtn.style.opacity = '0.8';
                floatingBtn.style.cursor = 'pointer';
                floatingBtn.title = 'Go to Mart (Location will be set if needed)';
            }
            
            // Original location check code (commented out for now)
            /*
            if (typeof address_lat === 'undefined' || typeof address_lng === 'undefined' || typeof user_zone_id === 'undefined' ||
                address_lat == '' || address_lng == '' || address_lat == null || address_lng == null ||
                user_zone_id == null || user_zone_id == '') {
                
                floatingBtn.style.opacity = '0.6';
                floatingBtn.style.cursor = 'not-allowed';
                floatingBtn.title = 'Please set your location first';
            } else {
                floatingBtn.style.opacity = '1';
                floatingBtn.style.cursor = 'pointer';
                floatingBtn.title = 'Go to Mart';
            }
            */
        }
    }

    // Update button status when location is detected
    $(document).ready(function() {
        // Update button status initially
        updateFloatingButtonStatus();
        
        // Update button status periodically until location is set
        const locationCheckInterval = setInterval(() => {
            updateFloatingButtonStatus();
            
            // Stop checking once location is set
            if (typeof address_lat !== 'undefined' && typeof address_lng !== 'undefined' && typeof user_zone_id !== 'undefined' &&
                address_lat && address_lng && user_zone_id) {
                clearInterval(locationCheckInterval);
            }
        }, 2000);
        
        // Clear interval after 30 seconds to avoid infinite checking
        setTimeout(() => {
            clearInterval(locationCheckInterval);
        }, 30000);
    });

</script>

<!-- Floating Button Styles -->
<style>
    .floating-btn {
      position: fixed;
      bottom: 20px;
      right: 20px;
      width: 60px;
      height: 60px;
      border-radius: 50%;
      background-color: #007F73;
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
<a href="{{ url('mart') }}" 
   class="floating-btn" 
   id="mart-floating-btn"
   title="Go to Mart"
   onclick="checkLocationAndNavigate(event)">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" 
        d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 
        3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 
        2.1-4.684 2.924-7.138a60.114 60.114 
        0 0 0-16.536-1.84M7.5 14.25 
        5.106 5.272M6 20.25a.75.75 0 
        1 1-1.5 0 .75.75 0 0 1 
        1.5 0Zm12.75 0a.75.75 0 
        1 1-1.5 0 .75.75 0 0 1 
        1.5 0Z" />
    </svg>
</a>

@include('layouts.nav')
@endsection
