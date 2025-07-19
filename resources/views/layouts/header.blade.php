<meta name="csrf-token" content="{{ csrf_token() }}"/>
<header class="section-header">
    <?php
    if (Session::get('takeawayOption') == 'true' || Session::get('takeawayOption') == true) {
        $takeaway_options = true;
    } else {
        $takeaway_options = false;
    }
    ?>
    <script>
        <?php if($takeaway_options){ ?>
        var takeaway_options = true;
        <?php }else{ ?>
        var takeaway_options = false;
        <?php } ?>
        function takeAwayOnOff(takeAway) {
            var check_val;
            if (takeaway_options == true) {
                if (takeAway.checked == false) {
                    let isExecuted = confirm("If you select take away option then it will empty cart. are you sure want to do ?");
                    if (isExecuted) {
                    } else {
                        return false;
                    }
                } else {
                    let isExecuted = confirm("If you select take away option then it will empty cart. are you sure want to do ?");
                    if (isExecuted) {
                    } else {
                        return false;
                    }
                }
            }
            if (takeAway.checked == true) {
                check_val = true;
                takeaway_options = true;
            } else {
                check_val = false;
                takeaway_options = false;
            }
            $.ajax({
                type: 'POST',
                url: 'takeaway',
                data: {
                    takeawayOption: check_val,
                    "_token": "{{ csrf_token() }}",
                },
                success: function (result) {
                    result = $.parseJSON(result);
                    location.reload();
                }
            });
        }

        // Add CSS for consistent white dropdown backgrounds
        document.addEventListener('DOMContentLoaded', function() {
            var style = document.createElement('style');
            style.textContent = `
                .dropdown-menu {
                    background-color: white !important;
                    border: 1px solid #ddd !important;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.1) !important;
                }
                .dropdown-item {
                    color: #333 !important;
                    background-color: white !important;
                }
                .dropdown-item:hover {
                    background-color: #f8f9fa !important;
                    color: #333 !important;
                }
                .dropdown-header {
                    color: #6c757d !important;
                    background-color: white !important;
                }
                .dropdown-divider {
                    border-top: 1px solid #e9ecef !important;
                }
                .pac-target-input:focus {
                    outline: 2px solid orange !important;
                    box-shadow: 0 0 0 2px orange !important;
                    border: 1px solid orange !important;
                    border-radius: 25px !important; /* or use 8px for less rounding */
                }
            `;
            document.head.appendChild(style);
        });

        // Function to show full location on hover
        function showFullLocation(element) {
            var locationText = element.value || element.placeholder;
            if (locationText && locationText.trim() !== '') {
                // Create or update tooltip
                var tooltip = document.getElementById('location-tooltip');
                if (!tooltip) {
                    tooltip = document.createElement('div');
                    tooltip.id = 'location-tooltip';
                    tooltip.style.cssText = `
                        position: absolute;
                        background: #333;
                        color: white;
                        padding: 8px 12px;
                        border-radius: 4px;
                        font-size: 14px;
                        max-width: 300px;
                        word-wrap: break-word;
                        z-index: 9999;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
                        pointer-events: none;
                    `;
                    document.body.appendChild(tooltip);
                }

                tooltip.textContent = locationText;

                // Position tooltip near the input
                var rect = element.getBoundingClientRect();
                tooltip.style.left = rect.left + 'px';
                tooltip.style.top = (rect.bottom + 5) + 'px';
                tooltip.style.display = 'block';
            }
        }

        // Function to hide full location tooltip
        function hideFullLocation() {
            var tooltip = document.getElementById('location-tooltip');
            if (tooltip) {
                tooltip.style.display = 'none';
            }
        }
    </script>
    <section class="header-main shadow-sm bg-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-2">
                    <a href="{{url('/')}}" class="brand-wrap mb-0">
                        <img alt="#" class="img-fluid" src="{{asset('img/logo_web.png')}}" id="logo_web">
                    </a>
                </div>
                <div class="col-3 d-flex align-items-center m-none head-search">
                    <!-- <div class="dropdown ml-4"> -->
                    <!-- <a class="text-dark dropdown-toggle d-flex align-items-center p-0" href="#" id="navbarDropdown"
                       role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> -->
                    <div class="head-loc d-flex align-items-center" id="location-container">
                        <i class="feather-map-pin mr-2 bg-light rounded-pill p-2 icofont-size"></i>
                        <input id="user_locationnew" type="text" size="80" class="pac-target-input" placeholder="Enter your location"
                               title="" style="padding: 0px 16px;">
                    </div>
                    <!-- </a> -->
                    <div class="dropdown-menu dropdown-menu-left" aria-labelledby="navbarDropdown" style="min-width: 250px;">
                        <div class="p-2">
                            <a class="dropdown-item" href="#" onclick="getCurrentLocation('reload'); return false;">
                                <i class="feather-navigation mr-2"></i>
                                Use My Current Location
                            </a>
                        </div>
                    </div>
                    <!-- </div> -->
                </div>
                <div class="col-7 header-right">
                    <div class="d-flex align-items-center justify-content-end pr-5">
                        <a href="{{url('search')}}" class="widget-header mr-4 text-dark">
                            <div class="icon d-flex align-items-center">
                                <i class="feather-search h6 mr-2 mb-0"></i> <span>{{trans('lang.search')}}</span>
                            </div>
                        </a>
                        <a href="{{url('offers')}}" class="widget-header mr-4 text-dark offer-link">
                            <div class="icon d-flex align-items-center">
                                <img alt="#" class="img-fluid mr-2"
                                     src="{{asset('img/discount.png')}}">
                                <span>{{trans('lang.offers')}}</span>
                            </div>
                        </a>
                        @auth
                        @else
                            <a href="{{url('login')}}" class="widget-header mr-4 text-dark m-none">
                                <div class="icon d-flex align-items-center">
                                    <i class="feather-user h6 mr-2 mb-0"></i> <span>{{trans('lang.signin')}}</span>
                                </div>
                            </a>
                        @endauth
                        <div class="dropdown mr-4 m-none d-inline-flex align-items-center" style="gap: 0.5rem;">
                            <a href="#" class="dropdown-toggle text-dark py-2 px-2 d-inline-flex align-items-center" id="dropdownMenuButton"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 1rem; min-height: 36px;">
                               
    </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                @auth
                                    <a class="dropdown-item" href="{{url('profile')}}">{{trans('lang.my_account')}}</a>
                                    
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
									document.getElementById('logout-form').submit();">{{trans('lang.logout')}}</a>
                                @else
                                    <a class="dropdown-item"
                                       href="{{url('restaurants')}}">{{trans('lang.all_restaurants')}}</a>
                                    <a class="dropdown-item dine_in_menu" style="display: none;"
                                       href="{{url('restaurants')}}?dinein=1">{{trans('lang.dine_in_restaurants')}}</a>
                                    <a class="dropdown-item"
                                       href="{{ route('faq') }}">{{trans('lang.delivery_support')}}</a>
                                    <a class="dropdown-item" href="{{url('contact-us')}}">{{trans('lang.contact_us')}}</a>
                                    <a class="dropdown-item" href="{{ route('terms') }}">{{trans('lang.terms_use')}}</a>
                                    <a class="dropdown-item"
                                       href="{{ route('privacy') }}">{{trans('lang.privacy_policy')}}</a>
                                @endauth
                            </div>
                        </div>
                        <a href="{{url('/checkout')}}" class="widget-header mr-4 text-dark">
                            <div class="icon d-flex align-items-center">
                                <i class="feather-shopping-cart h6 mr-2 mb-0"></i> <span>{{trans('lang.cart')}}</span>
                            </div>
                        </a>
                        <!--              <?php if (Session::get('takeawayOption') == "true") { ?>-->
                        <!--                  <div class="icon d-flex align-items-center text-dark takeaway-div">-->
                        <!--	<span class="takeaway-btn">-->
                        <!--		<i class="fa fa-car h6 mr-1 mb-0"></i> <span> {{trans('lang.take_away')}} </span>-->
                        <!--		<input type="checkbox" onclick="takeAwayOnOff(this)"-->
                        <!--                                             <?php if (Session::get('takeawayOption') == "true") { ?> checked <?php } ?>> <span-->
                        <!--                                              class="slider round"></span>-->
                        <!--		</span>-->
                        <!--                  </div>-->
                        <!--              <?php } else { ?>-->
                        <!--                  <div class="icon d-flex align-items-center text-dark takeaway-div">-->
                        <!--<span class="takeaway-btn">-->
                        <!--	<i class="fa fa-car h6 mr-1 mb-0"></i> <span> {{trans('lang.delivery')}} </span>-->
                        <!--	<input type="checkbox" onclick="takeAwayOnOff(this)"> <span-->
                        <!--                                          class="slider round"></span>-->
                        <!--	</span>-->
                        <!--                  </div>-->
                        <!--              <?php } ?>-->
{{--                        <div style="visibility: hidden;"--}}
{{--                             class="language-list icon d-flex align-items-center text-dark ml-2"--}}
{{--                             id="language_dropdown_box">--}}
{{--                            <div class="language-select">--}}
{{--                                <i class="feather-globe"></i>--}}
{{--                            </div>--}}
{{--                            <div class="language-options">--}}
{{--                                <select class="form-control changeLang text-dark" id="language_dropdown">--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <a class="toggle" href="#" style="width:22px; height:22px; display:inline-flex; align-items:center; justify-content:center;">
                            <span style="display:block; width:100%; height:2px; background:#222; border-radius:2px;"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</header>
<div class="d-none">
    <div class="bg-primary p-3 d-flex align-items-center">
        <a class="toggle togglew toggle-2" href="#"><span></span></a>
        <a href="{{url('/')}}" class="mobile-logo brand-wrap mb-0">
            <img alt="#" class="img-fluid" src="{{asset('img/logo_web.png')}}">
        </a>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.dropdown').on('show.bs.dropdown', function () {
            showFullLocation(document.getElementById('user_locationnew'));
        });
        $('.dropdown').on('hide.bs.dropdown', function () {
            hideFullLocation();
        });
    });
</script>
