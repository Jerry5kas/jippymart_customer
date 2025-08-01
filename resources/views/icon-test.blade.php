@include('layouts.app')
@include('layouts.header')

<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info">
                <strong>Note:</strong> If you're still seeing emoji-like icons, please clear your browser cache (Ctrl+F5 or Cmd+Shift+R) to ensure the updated Font Awesome CSS is loaded.
            </div>
            
            <h2 class="mb-4">Icon Test Page</h2>
            
            <div class="card">
                <div class="card-header">
                    <h5>Fallback Rating System Test</h5>
                </div>
                <div class="card-body">
                    <p>This demonstrates how products with no ratings will show fallback ratings instead of "0.0 (0)":</p>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h6>Product with No Rating</h6>
                                    <div class="star position-relative mt-3">
                                        <span class="badge badge-success">
                                            <i class="feather-star"></i><span id="fallback-rating">Loading...</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Font Awesome Icons (Search & Filter)</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                                                 <div class="col-md-6">
                             <h6>Search Icons:</h6>
                             <p><i class="fas fa-search"></i> Search Icon (New)</p>
                             <p><i class="fas fa-search"></i> Search Icon (Same)</p>
                         </div>
                        <div class="col-md-6">
                            <h6>Filter Icons:</h6>
                            <p><i class="fas fa-clock"></i> Clock Icon</p>
                            <p><i class="fas fa-truck"></i> Truck Icon</p>
                            <p><i class="fas fa-percent"></i> Percent Icon</p>
                            <p><i class="fas fa-sliders-h"></i> Sliders Icon</p>
                            <p><i class="fas fa-times"></i> Times Icon</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Feather Icons</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Common Icons:</h6>
                            <p><i class="feather-home"></i> Home Icon</p>
                            <p><i class="feather-search"></i> Search Icon</p>
                            <p><i class="feather-star"></i> Star Icon</p>
                            <p><i class="feather-heart"></i> Heart Icon</p>
                            <p><i class="feather-map-pin"></i> Map Pin Icon</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Navigation Icons:</h6>
                            <p><i class="feather-chevron-left"></i> Chevron Left</p>
                            <p><i class="feather-chevron-right"></i> Chevron Right</p>
                            <p><i class="feather-clock"></i> Clock Icon</p>
                            <p><i class="feather-truck"></i> Truck Icon</p>
                            <p><i class="feather-percent"></i> Percent Icon</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Search & Filter Bar (Replica)</h5>
                </div>
                <div class="card-body">
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
                                            <input type="text" class="form-control border-left-0" placeholder="Search restaurants...">
                                        </div>
                                    </div>
                                    
                                    <!-- Sort -->
                                    <div class="col-lg-3 col-md-6 mb-2 mb-md-0">
                                        <select class="form-control">
                                            <option>Rating (High to Low)</option>
                                            <option>Rating (Low to High)</option>
                                            <option>Name (A-Z)</option>
                                        </select>
                                    </div>
                                    
                                    <!-- Quick Filters -->
                                    <div class="col-lg-5 col-md-12">
                                        <div class="d-flex flex-wrap align-items-center">
                                            <div class="form-check form-check-inline mr-3">
                                                <input class="form-check-input" type="checkbox" id="testOpenNow">
                                                <label class="form-check-label small" for="testOpenNow">
                                                    <i class="fas fa-clock mr-1"></i>Open Now
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline mr-3">
                                                <input class="form-check-input" type="checkbox" id="testFreeDelivery">
                                                <label class="form-check-label small" for="testFreeDelivery">
                                                    <i class="fas fa-truck mr-1"></i>Free Delivery
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline mr-3">
                                                <input class="form-check-input" type="checkbox" id="testHasDiscount">
                                                <label class="form-check-label small" for="testHasDiscount">
                                                    <i class="fas fa-percent mr-1"></i>Has Discount
                                                </label>
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
</div>

@include('layouts.footer')
@include('layouts.nav')

<script>
// Initialize randomized ratings for demonstration
window.randomizedRatings = {};

// Demo function to show fallback ratings
function demonstrateFallbackRating() {
    const testProductId = 'demo-product-123';
    
    // Simulate a product with no ratings
    const product = {
        id: testProductId,
        reviewsSum: 0,
        reviewsCount: 0
    };
    
    let rating = 0;
    let reviewsCount = 0;
    
    if (product.hasOwnProperty('reviewsSum') && product.reviewsSum != 0 && product.reviewsSum != null && 
        product.reviewsSum != '' && product.hasOwnProperty('reviewsCount') && product.reviewsCount != 0 && 
        product.reviewsCount != null && product.reviewsCount != '') {
        rating = (product.reviewsSum / product.reviewsCount);
        rating = Math.round(rating * 10) / 10;
        reviewsCount = product.reviewsCount;
    } else {
        // Fallback to randomized ratings for better UI
        if (window.randomizedRatings && window.randomizedRatings[product.id]) {
            rating = window.randomizedRatings[product.id].rating;
            reviewsCount = window.randomizedRatings[product.id].reviewsCount;
        } else {
            rating = (Math.random() * (5.0 - 4.1) + 4.1).toFixed(1);
            reviewsCount = Math.floor(Math.random() * (25 - 11 + 1)) + 11;
            if (!window.randomizedRatings) {
                window.randomizedRatings = {};
            }
            window.randomizedRatings[product.id] = { rating, reviewsCount };
        }
    }
    
    document.getElementById('fallback-rating').textContent = `${rating} (${reviewsCount})`;
}

// Run the demo when page loads
document.addEventListener('DOMContentLoaded', function() {
    demonstrateFallbackRating();
});
</script> 