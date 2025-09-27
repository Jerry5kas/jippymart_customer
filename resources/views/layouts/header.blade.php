<meta name="csrf-token" content="{{ csrf_token() }}"/>
<header class="section-header">
    <?php
    if (Session::get('takeawayOption') == 'true' || Session::get('takeawayOption') == true) {
        $takeaway_options = true;
    } else {
        $takeaway_options = false;
    }
    ?>
    <!-- Include Shared Location Service -->
    <script src="{{ asset('js/shared-location-service.js') }}"></script>

    <!-- Google Maps API - Loaded dynamically in footer to avoid duplicates -->
    <script>
        const GOOGLE_MAP_KEY = 'AIzaSyCwGZ2HyUDONfY-qEUt4gzEXVZVIVYbO_E'; // Replace with your actual Google API key
    </script>
    
    <!-- Cloudflare Rocket Loader compatibility -->
    <script data-cfasync="false">
        // Prevent Rocket Loader from interfering with critical scripts
        if (typeof window.RocketLoader !== 'undefined') {
            window.RocketLoader = {
                config: {
                    enabled: false
                }
            };
        }
        
        // Add data-cfasync="false" to prevent Cloudflare from optimizing critical scripts
        document.addEventListener('DOMContentLoaded', function() {
            const criticalScripts = document.querySelectorAll('script[src*="maps.googleapis.com"], script[src*="firebase"], script[src*="slick"]');
            criticalScripts.forEach(script => {
                script.setAttribute('data-cfasync', 'false');
            });
        });
    </script>

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

                 /* Location Suggestions Styles */
                 .location-suggestions {
                     position: absolute;
                     top: 100%;
                     left: 0;
                     right: 0;
                     background: white;
                     border: 1px solid #ddd;
                     border-radius: 8px;
                     box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                     z-index: 1000;
                     max-height: 300px;
                     overflow-y: auto;
                 }

                 .suggestion-item {
                     padding: 12px 16px;
                     cursor: pointer;
                     display: flex;
                     align-items: center;
                     border-bottom: 1px solid #f0f0f0;
                     transition: background-color 0.2s;
                 }

                 .suggestion-item:hover {
                     background-color: #f8f9fa;
                 }

                 .suggestion-item:last-child {
                     border-bottom: none;
                 }

                 .suggestion-item i {
                     color: #007F73;
                     width: 16px;
                     text-align: center;
                 }

                 /* Hide Google Places autocomplete */
                 .pac-container {
                     display: none !important;
                 }

                 /* Ensure our custom suggestions are on top */
                 .location-suggestions {
                     z-index: 9999 !important;
                 }

                 /* Location Input Styles */
                 .location-input {
                     border: 1px solid #e9ecef !important;
                     border-radius: 8px !important;
                     padding: 8px 12px !important;
                     font-size: 14px !important;
                     width: 100% !important;
                     max-width: max-content !important;
                     min-width: 200px !important;
                     background-color: #f8f9fa !important;
                     color: #495057 !important;
                     transition: all 0.3s ease !important;
                     outline: none !important;
                     box-sizing: border-box !important;
                 }

                 .location-input:focus {
                     border-color: #007F73 !important;
                     background-color: white !important;
                     box-shadow: 0 0 0 2px rgba(0, 127, 115, 0.1) !important;
                 }

                 .location-input::placeholder {
                     color: #6c757d !important;
                     font-size: 14px !important;
                 }

                 /* Location Container Styles */
                 .head-loc {
                     width: 100% !important;
                     max-width: max-content !important;
                     min-width: 250px !important;
                 }

                 /* Responsive Location Input */
                 @media (max-width: 768px) {
                     .location-input {
                         min-width: 180px !important;
                         font-size: 13px !important;
                         padding: 6px 10px !important;
                     }
                     
                     .head-loc {
                         min-width: 200px !important;
                     }
                 }

                 @media (max-width: 576px) {
                     .location-input {
                         min-width: 150px !important;
                         font-size: 12px !important;
                         padding: 5px 8px !important;
                     }
                     
                     .head-loc {
                         min-width: 170px !important;
                     }
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

          // Location suggestions functionality
          let locationSuggestions = [];
          let isShowingSuggestions = false;
          let isRequestingLocation = false;

         // Initialize location suggestions
         function initializeLocationSuggestions() {
             const locationInput = document.getElementById('user_locationnew');
             const suggestionsContainer = document.getElementById('location-suggestions');

             if (!locationInput || !suggestionsContainer) return;

             // Show suggestions on focus
             locationInput.addEventListener('focus', function() {
                 if (this.value.trim() === '') {
                     showDefaultSuggestions();
                 }
             });

             // Handle input changes
             locationInput.addEventListener('input', function() {
                 const query = this.value.trim();
                 if (query.length > 0) {
                     searchLocationSuggestions(query);
                 } else {
                     showDefaultSuggestions();
                 }
             });

              // Hide suggestions when clicking outside
              document.addEventListener('click', function(e) {
                  if (!locationInput.contains(e.target) && !suggestionsContainer.contains(e.target)) {
                      hideLocationSuggestions();
                  }
              });

              // Handle blur event to save location
              locationInput.addEventListener('blur', function() {
                  const query = this.value.trim();
                  if (query.length > 0) {
                      // Update shared location service
                      if (window.sharedLocationService) {
                          window.sharedLocationService.setLocation({
                              address_name: query,
                              user_address: query,
                              timestamp: Date.now()
                          });
                      }
                  }
              });

              // Handle keyboard navigation
              locationInput.addEventListener('keydown', function(e) {
                  if (e.key === 'Escape') {
                      hideLocationSuggestions();
                  } else if (e.key === 'Enter') {
                      // Handle Enter key - update location and reload
                      const query = this.value.trim();
                      if (query.length > 0) {
                          // Update shared location service
                          if (window.sharedLocationService) {
                              window.sharedLocationService.setLocation({
                                  address_name: query,
                                  user_address: query,
                                  timestamp: Date.now()
                              });
                          }

                          // Reload page to update location
                          console.log('About to reload page in 500ms (current location)...');
                          setTimeout(() => {
                              console.log('Executing page reload (current location)...');
                              try {
                                  location.reload();
                              } catch (error) {
                                  console.error('Reload failed, trying alternative method:', error);
                                  window.location.href = window.location.href;
                              }
                          }, 500);
                      }
                      hideLocationSuggestions();
                  }
              });
         }

         // Show location suggestions
         function showLocationSuggestions() {
             const suggestionsContainer = document.getElementById('location-suggestions');
             if (suggestionsContainer) {
                 suggestionsContainer.style.display = 'block';
                 isShowingSuggestions = true;
             }
         }

         // Hide location suggestions
         function hideLocationSuggestions() {
             const suggestionsContainer = document.getElementById('location-suggestions');
             if (suggestionsContainer) {
                 suggestionsContainer.style.display = 'none';
                 isShowingSuggestions = false;
             }
         }

         // Search for location suggestions using Google Places API
         async function searchLocationSuggestions(query) {
             if (query.length < 2) {
                 hideLocationSuggestions();
                 return;
             }

             try {
                 // Use Google Places API for location search
                 const service = new google.maps.places.AutocompleteService();
                 const request = {
                     input: query,
                     componentRestrictions: { country: 'in' },
                     types: ['geocode']
                 };

                 service.getPlacePredictions(request, (predictions, status) => {
                     if (status === google.maps.places.PlacesServiceStatus.OK && predictions) {
                         updateLocationSuggestionsWithGoogle(predictions);
                         showLocationSuggestions();
                     } else {
                         // If no results, show default suggestions
                         showDefaultSuggestions();
                     }
                 });
             } catch (error) {
                 console.error('Error searching locations:', error);
                 // Show default suggestions on error
                 showDefaultSuggestions();
             }
         }

         // Show default suggestions when no search results
         function showDefaultSuggestions() {
             const suggestionsContainer = document.getElementById('location-suggestions');
             if (!suggestionsContainer) return;

             // Clear existing suggestions
             suggestionsContainer.innerHTML = '';

              // Add default suggestions
              const defaultSuggestions = [
                  { icon: 'feather-navigation', text: 'Use My Current Location', action: 'useCurrentLocation' },
                  { icon: 'feather-map-pin', text: 'Other', action: 'selectLocation("Other")' }
              ];

             defaultSuggestions.forEach(suggestion => {
                 const suggestionItem = document.createElement('div');
                 suggestionItem.className = 'suggestion-item';
                 suggestionItem.innerHTML = `<i class="${suggestion.icon} mr-2"></i><span>${suggestion.text}</span>`;

                 // Properly bind the onclick event
                 if (suggestion.action === 'useCurrentLocation') {
                     suggestionItem.onclick = useCurrentLocation;
                 } else if (suggestion.action === 'selectLocation("Other")') {
                     suggestionItem.onclick = () => selectLocation('Other');
                 }

                 suggestionsContainer.appendChild(suggestionItem);
             });

             showLocationSuggestions();
         }

         // Update location suggestions with Google Places results
         function updateLocationSuggestionsWithGoogle(predictions) {
             const suggestionsContainer = document.getElementById('location-suggestions');
             if (!suggestionsContainer) return;

             // Clear existing suggestions
             suggestionsContainer.innerHTML = '';

             // Add current location option
             const currentLocationOption = document.createElement('div');
             currentLocationOption.className = 'suggestion-item';
             currentLocationOption.innerHTML = '<i class="feather-navigation mr-2"></i><span>Use My Current Location</span>';
             currentLocationOption.onclick = useCurrentLocation;
             suggestionsContainer.appendChild(currentLocationOption);

             // Add search results
             predictions.forEach(prediction => {
                 const suggestionItem = document.createElement('div');
                 suggestionItem.className = 'suggestion-item';
                 suggestionItem.innerHTML = `<i class="feather-map-pin mr-2"></i><span>${prediction.description}</span>`;
                 suggestionItem.onclick = () => selectLocationFromGoogleSearch(prediction);
                 suggestionsContainer.appendChild(suggestionItem);
             });
         }

         // Update location suggestions with search results (legacy function for compatibility)
         function updateLocationSuggestions(results) {
             const suggestionsContainer = document.getElementById('location-suggestions');
             if (!suggestionsContainer) return;

             // Clear existing suggestions
             suggestionsContainer.innerHTML = '';

             // Add current location option
             const currentLocationOption = document.createElement('div');
             currentLocationOption.className = 'suggestion-item';
             currentLocationOption.innerHTML = '<i class="feather-navigation mr-2"></i><span>Use My Current Location</span>';
             currentLocationOption.onclick = useCurrentLocation;
             suggestionsContainer.appendChild(currentLocationOption);

             // Add search results
             results.forEach(result => {
                 const suggestionItem = document.createElement('div');
                 suggestionItem.className = 'suggestion-item';
                 suggestionItem.innerHTML = `<i class="feather-map-pin mr-2"></i><span>${result.display_name}</span>`;
                 suggestionItem.onclick = () => selectLocationFromSearch(result);
                 suggestionsContainer.appendChild(suggestionItem);
             });
         }

          // Check geolocation permission status
          async function checkLocationPermission() {
              if ('permissions' in navigator) {
                  try {
                      const permission = await navigator.permissions.query({ name: 'geolocation' });
                      return permission.state;
                  } catch (error) {
                      console.log('Permission API not supported');
                      return 'unknown';
                  }
              }
              return 'unknown';
          }

          // Force clear location cache and refresh
          function clearLocationCache() {
              try {
                  // Clear localStorage location data
                  localStorage.removeItem('sharedLocation');
                  localStorage.removeItem('userLocation');
                  localStorage.removeItem('geolocation');

                  // Clear any existing geolocation watch
                  if (navigator.geolocation && navigator.geolocation.clearWatch) {
                      navigator.geolocation.clearWatch(1);
                  }

                  console.log('Location cache cleared');
                  return true;
              } catch (error) {
                  console.error('Error clearing location cache:', error);
                  return false;
              }
          }

          // Use current location
          function useCurrentLocation() {
              console.log('useCurrentLocation function called');

              // Prevent multiple simultaneous requests
              if (isRequestingLocation) {
                  console.log('Location request already in progress');
                  return;
              }

              // Check if geolocation is supported
              if (!navigator.geolocation) {
                  alert('Geolocation is not supported by this browser. Please enter your location manually.');
                  return;
              }

              isRequestingLocation = true;

              // Show loading message
              const locationInput = document.getElementById('user_locationnew');
              if (!locationInput) {
                  console.error('Location input element not found');
                  isRequestingLocation = false;
                  return;
              }

              locationInput.value = 'Getting your location...';
              hideLocationSuggestions();

              // Clear any existing location cache
              clearLocationCache();

              // Geolocation options
              const options = {
                  enableHighAccuracy: true,
                  timeout: 15000,
                  maximumAge: 0
              };

              console.log('Requesting location with options:', options);

              navigator.geolocation.getCurrentPosition(
                  async function(position) {
                      console.log('Location obtained:', position.coords);

                      const lat = position.coords.latitude;
                      const lng = position.coords.longitude;

                      // Show processing message
                      locationInput.value = 'Processing location...';

                      try {
                          // Use Google Geocoding to get address
                          const geocoder = new google.maps.Geocoder();
                          const latlng = new google.maps.LatLng(lat, lng);

                          geocoder.geocode({ location: latlng }, (results, status) => {
                              isRequestingLocation = false;

                              if (status === 'OK' && results[0]) {
                                  const address = results[0].formatted_address;
                                  locationInput.value = address;

                                  console.log('Google Geocoding result:', results[0]);

                                  // Update shared location service
                                  if (window.sharedLocationService) {
                                      window.sharedLocationService.setLocation({
                                          address_name: address,
                                          user_address: address,
                                          address_lat: lat,
                                          address_lng: lng,
                                          timestamp: Date.now()
                                      });
                                  }

                                  // Reload page to update location
                                  setTimeout(() => {
                                      location.reload();
                                  }, 500);
                              } else {
                                  console.error('Google Geocoding failed:', status);
                                  locationInput.value = '';
                                  alert('Unable to get address for your location. Please enter it manually.');
                              }
                          });
                      } catch (geocodeError) {
                          isRequestingLocation = false;
                          console.error('Geocoding error:', geocodeError);
                          locationInput.value = '';
                          alert('Unable to get address for your location. Please enter it manually.');
                      }
                  },
                  function(error) {
                      isRequestingLocation = false;
                      locationInput.value = '';
                      console.error('Geolocation error:', error);

                      let errorMessage = 'Unable to get your current location. ';
                      switch(error.code) {
                          case error.PERMISSION_DENIED:
                              errorMessage += 'Location access was denied. Please allow location access in your browser settings and try again.';
                              break;
                          case error.POSITION_UNAVAILABLE:
                              errorMessage += 'Location information is unavailable.';
                              break;
                          case error.TIMEOUT:
                              errorMessage += 'Location request timed out. Please try again.';
                              break;
                          default:
                              errorMessage += 'Please allow location access or enter it manually.';
                              break;
                      }
                      alert(errorMessage);
                  },
                  options
              );
          }

          // Select location from predefined options
          function selectLocation(locationType) {
              const locationInput = document.getElementById('user_locationnew');

              // You can customize these based on user's saved locations
              const locations = {
                  'Home': '123 Main Street, City, State',
                  'Work': '456 Business Ave, City, State',
                  'Other': 'Enter your location'
              };

              if (locationType === 'Other') {
                  locationInput.focus();
                  hideLocationSuggestions();
              } else {
                  locationInput.value = locations[locationType];
                  hideLocationSuggestions();

                  // Update shared location service
                  if (window.sharedLocationService) {
                      window.sharedLocationService.setLocation({
                          address_name: locations[locationType],
                          user_address: locations[locationType],
                          timestamp: Date.now()
                      });
                  }

                  // Reload page to update location
                  console.log('About to reload page in 500ms (predefined location)...');
                  setTimeout(() => {
                      console.log('Executing page reload (predefined location)...');
                      try {
                          location.reload();
                      } catch (error) {
                          console.error('Reload failed, trying alternative method:', error);
                          window.location.href = window.location.href;
                      }
                  }, 500);
              }
          }

          // Select location from Google Places search results
          function selectLocationFromGoogleSearch(prediction) {
              const locationInput = document.getElementById('user_locationnew');
              locationInput.value = prediction.description;
              hideLocationSuggestions();

              // Use Google Geocoding to get coordinates
              const geocoder = new google.maps.Geocoder();
              geocoder.geocode({ placeId: prediction.place_id }, (results, status) => {
                  if (status === 'OK' && results[0]) {
                      const location = results[0].geometry.location;
                      const lat = location.lat();
                      const lng = location.lng();

                      // Update shared location service
                      if (window.sharedLocationService) {
                          window.sharedLocationService.setLocation({
                              address_name: prediction.description,
                              user_address: prediction.description,
                              address_lat: lat,
                              address_lng: lng,
                              place_id: prediction.place_id,
                              timestamp: Date.now()
                          });
                      }

                      // Reload page to update location
                      console.log('About to reload page in 500ms...');
                      setTimeout(() => {
                          console.log('Executing page reload...');
                          try {
                              location.reload();
                          } catch (error) {
                              console.error('Reload failed, trying alternative method:', error);
                              window.location.href = window.location.href;
                          }
                      }, 500);
                  } else {
                      console.error('Geocoding failed:', status);
                      // Still update with basic info
                      if (window.sharedLocationService) {
                          window.sharedLocationService.setLocation({
                              address_name: prediction.description,
                              user_address: prediction.description,
                              timestamp: Date.now()
                          });
                      }

                      console.log('About to reload page in 500ms (fallback)...');
                      setTimeout(() => {
                          console.log('Executing page reload (fallback)...');
                          try {
                              location.reload();
                          } catch (error) {
                              console.error('Reload failed, trying alternative method:', error);
                              window.location.href = window.location.href;
                          }
                      }, 500);
                  }
              });
          }

          // Select location from search results (legacy function for compatibility)
          function selectLocationFromSearch(result) {
              const locationInput = document.getElementById('user_locationnew');
              locationInput.value = result.display_name;
              hideLocationSuggestions();

              // Update shared location service
              if (window.sharedLocationService) {
                  window.sharedLocationService.setLocation({
                      address_name: result.display_name,
                      user_address: result.display_name,
                      address_lat: result.lat,
                      address_lng: result.lon,
                      timestamp: Date.now()
                  });
              }

              // Reload page to update location
              console.log('About to reload page in 500ms (legacy search)...');
              setTimeout(() => {
                  console.log('Executing page reload (legacy search)...');
                  try {
                      location.reload();
                  } catch (error) {
                      console.error('Reload failed, trying alternative method:', error);
                      window.location.href = window.location.href;
                  }
              }, 500);
          }

         // Wait for Google Maps API to load
         function waitForGoogleMaps() {
             return new Promise((resolve) => {
                 if (typeof google !== 'undefined' && google.maps && google.maps.places) {
                     resolve();
                 } else {
                     const checkGoogleMaps = setInterval(() => {
                         if (typeof google !== 'undefined' && google.maps && google.maps.places) {
                             clearInterval(checkGoogleMaps);
                             resolve();
                         }
                     }, 100);
                 }
             });
         }

         // Initialize shared location service for Restaurant section
         document.addEventListener('DOMContentLoaded', async function() {
             // Wait for Google Maps API to load
             await waitForGoogleMaps();

             // Initialize shared location service
             if (typeof SharedLocationService !== 'undefined') {
                 window.sharedLocationService = new SharedLocationService();

                 // Load existing location into the input field
                 const location = window.sharedLocationService.getLocation();
                 if (location && location.address_name) {
                     document.getElementById('user_locationnew').value = location.address_name;
                 }

                 // Listen for location updates from other sections
                 window.sharedLocationService.addListener(function(locationData) {
                     if (locationData && locationData.address_name) {
                         document.getElementById('user_locationnew').value = locationData.address_name;
                     }
                 });
             }

             // Initialize location suggestions
             initializeLocationSuggestions();
         });
    </script>
    <section class="header-main shadow-sm bg-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-2">
                    <a href="{{url('/')}}" class="brand-wrap mb-0">
                        <img alt="#" class="img-fluid" src="{{asset('img/logo_web.png')}}" id="logo_web">
                    </a>
                </div>
                <div class="col-4 col-lg-3 d-flex align-items-center m-none head-search">
                    <!-- <div class="dropdown ml-4"> -->
                    <!-- <a class="text-dark dropdown-toggle d-flex align-items-center p-0" href="#" id="navbarDropdown"
                       role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> -->
                    <div class="head-loc d-flex align-items-center position-relative" id="location-container">
                        <i class="feather-map-pin mr-2 bg-light rounded-pill p-2 icofont-size"></i>
                        <input id="user_locationnew" type="text" class="location-input" placeholder="Enter your location"
                               title="" autocomplete="off">

                        <!-- Location Suggestions Dropdown -->
                        <div id="location-suggestions" class="location-suggestions" style="display: none;">
                            <!-- Suggestions will be populated dynamically -->
                        </div>

                        <!-- Debug button (remove in production)
                        <button onclick="useCurrentLocation()" style="background: #007F73; color: white; padding: 4px 8px; border: none; border-radius: 4px; font-size: 12px; margin-left: 5px;">
                            Test Location
                        </button> -->
                    </div>
                    <!-- </div> -->
                </div>
                <div class="col-6 col-lg-7 header-right">
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
                        <a class="toggle" href="#" style="width:22px; height:22px; display:flex; align-items:center; justify-content:center; position:relative; top:0;">
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
