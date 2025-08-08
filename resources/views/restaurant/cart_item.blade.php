<style>
    .product-name {
        display: flex;
        align-items: center;
    }

    .product-name .item-details {
        max-width: calc(100% - 50px);
        word-wrap: break-word;
    }

    .product-name img {
        border-radius: 5px;
    }
    #remove-coupon {
    cursor: pointer !important;
}
</style>
<?php
if (@$order_complete){ ?>
<div class="d-flex siddhi-cart-item-profile bg-white p-3">
    <p>{{ trans('lang.your_order_placed_successfully') }}</p>
</div>
<?php } ?>

<div class="sidebar-header p-3">
    <h3 class="font-weight-bold h6 w-100">{{ trans('lang.cart') }}</h3>
    <?php if (@$cart['item'] && !empty($cart['item'])) {
        $restaurant_id = array_key_first($cart['item']);
        $restaurant_name = @$cart['restaurant']['name'];
    ?>
        <div class="d-flex justify-content-between align-items-center">
            <small class="text-muted">
            <i class="fa fa-store"></i> Items from: <strong id="restaurant-name"></strong></small>
            <button type="button" class="btn btn-sm btn-outline-danger ml-2" onclick="clearCart()">
                <i class="fa fa-trash"></i> Clear Cart
            </button>
        </div>
    <?php } ?>
</div>
<?php

if (@$cart['item']){ ?>

    <?php

    $item_count = 0;
    $total_price = 0;
    $total_item_price = 0;

    if (@$cart['tip_amount']) {
        $tip_amount = $cart['tip_amount'];
    } else {
        $tip_amount = '';
    }
    if (@$cart['coupon_code']) {
        $coupon_code = $cart['coupon_code'];
    } else {
        $coupon_code = '';
    }
    ?>

    <?php
    if (!isset($digit_decimal)) {
        $digit_decimal = 0;
    }
    ?>

    <?php foreach ($cart['item'] as $key => $value_vendor) {

    if (isset($key) && !empty($key) && count($value_vendor) > 0) {
        $item_count++;
    }
    ?>

<div class="bg-white p-3 sidebar-item-list">

        <?php if (count($value_vendor) > 0){ ?>

    <h6 class="pb-3">{{ trans('lang.item') }}</h6>

    <input type="hidden" name="main_vendor_id" value="<?php echo @$key; ?>" id="main_vendor_id">

        <?php foreach ($value_vendor as $key1 => $value_item) { ?>

    <div class="product-item gold-members row align-items-center py-2 border mb-2 rounded-lg m-0"
         id="item_<?php echo @$key1; ?>" data-id="<?php echo @$key1; ?>">
        <input type="hidden" id="price_<?php echo @$key1; ?>"
               value="<?php echo floatval($value_item['price']) + floatval($value_item['extra_price']); ?>">
        <input type="hidden" id="dis_price_<?php echo @$key1; ?>"
               value="<?php echo floatval($value_item['dis_price']); ?>">
        <input type="hidden" id="item_price_<?php echo @$key1; ?>"
               value="<?php echo floatval($value_item['item_price']); ?>">
        <input type="hidden" id="photo_<?php echo @$key1; ?>" value="<?php echo $value_item['image']; ?>">
        <input type="hidden" id="name_<?php echo @$key1; ?>" value="<?php echo @$value_item['name']; ?>">
        <input type="hidden" id="quantity_<?php echo @$key1; ?>" value="<?php echo $value_item['quantity']; ?>">
        <input type="hidden" id="variant_info_<?php echo @$key1; ?>"
               value="<?php echo @$value_item['variant_info'] ? base64_encode(json_encode($value_item['variant_info'])) : ''; ?>">
        <input type="hidden" id="category_id_<?php echo @$key1; ?>" value="<?php echo $value_item['category_id']; ?>">
        <div class="media align-items-center col-md-6">
                <?php
            if (isset($_COOKIE['dine_in_active']) && $_COOKIE['dine_in_active'] == 'true'){
            if (isset($value_item['veg']) && $value_item['veg'] === "true") { ?>
            <div class="mr-2 text-success veg">
                &middot;
            </div>
            <?php }else{ ?>
            <div class="mr-2 text-danger non_veg">
                &middot;
            </div>
            <?php }
            } ?>
            <div class="media-body">
                <div class="m-0 product-name d-flex align-items-center">
                        <?php
                        if (isset($value_item['variant_info']) && !empty($value_item['variant_info'])) {
                            if (!empty($value_item['variant_info']['variant_image'])) {
                                echo '<img src="' . $value_item['variant_info']['variant_image'] . '" class="img-responsive img-rounded" style="max-height: 40px; max-width: 25px;">';
                            }
                        } else {
                            echo '<img src="' . $value_item['image'] . '" class="img-responsive img-rounded" style="max-height: 40px; max-width: 25px;">';
                        }
                        ?>
                    <div class="item-details">
                        <p style="margin: 0; font-size: 12px; color: #555;">
                                <?php echo $value_item['name']; ?>
                        </p>
                    </div>
                </div>
                    <?php
                    if (isset($value_item['variant_info']) && !empty($value_item['variant_info'])) {
                        echo '<div class="variant-info">';
                        echo '<ul>';
                        foreach ($value_item['variant_info']['variant_options'] as $label => $value) {
                            echo '<li class="variant"><span class="label">' . $label . '</span>&nbsp;<span class="value">' . $value . '</span></li>';
                        }
                        echo '</ul>';
                        echo '</div>';
                    }
                    ?>
                    <?php if (@$value_item['extra']){ ?>
                <div class="extras">
                    <span class="label">{{ trans('lang.extra') }}</span>&nbsp;
                        <?php if (@is_array($value_item['extra'])){
                    foreach ($value_item['extra'] as $key3 => $extra) { ?>
                    <input type="hidden" class="extras_<?php echo @$key1; ?>" value="<?php echo $extra; ?>">
                    <span class="value"><?php echo $extra; ?>&nbsp;&nbsp;</span>
                    <?php }
                    } ?>
                </div>
                <?php } ?>
                <input type="hidden" id="extras_price_<?php echo @$key1; ?>"
                       value="<?php echo @$value_item['extra_price']; ?>">
                    <?php if (@$value_item['size']){ ?>
                <div class="size">
                    <span>{{ trans('lang.size') }}</span>
                    <p><?php echo $value_item['size']; ?></p>
                </div>
                <?php } ?>
                <input type="hidden" id="size_<?php echo @$key1; ?>" value="<?php echo @$value_item['size']; ?>">
                <input type="hidden" id="vegs_<?php echo @$key1; ?>" value="<?php echo @$value_item['veg']; ?>">
            </div>
        </div>
        <div class="d-flex align-items-center count-number-box col-md-5">
            <span class="count-number float-right">
                <button type="button" data-vendor="<?php echo $key; ?>" data-id="<?php echo $key1; ?>"
                        data-vendor-id="<?php echo @$cart['restaurant']['id']; ?>"
                        <?php if (isset($value_item['variant_info']) && !empty($value_item['variant_info'])) {
                            $varient_qty = $value_item['variant_info']['variant_qty'];
                            ?> data-vqty="<?php echo $varient_qty; ?>"
                        data-vqtymsg="{{ trans('lang.invalid_stock_qty') }}"
                        <?php }else{ ?> data-vqty="<?php echo $value_item['stock_quantity']; ?>"
                        data-vqtymsg="{{ trans('lang.invalid_stock_qty') }}"
                        <?php } ?> class="count-number-input-cart btn-sm left dec btn btn-outline-secondary">
                    <i class="feather-minus"></i>
                </button>
                <input class="count-number-input count_number_<?php echo $key1; ?>" type="text" readonly
                       value="<?php echo $value_item['quantity']; ?>">
                <button type="button" data-vendor="<?php echo $key; ?>" data-id="<?php echo $key1; ?>"
                        <?php if (isset($value_item['variant_info']) && !empty($value_item['variant_info'])) {
                            $varient_qty = $value_item['variant_info']['variant_qty'];
                            ?> data-vqty="<?php echo $varient_qty; ?>"
                        data-vqtymsg="{{ trans('lang.invalid_stock_qty') }}"
                        <?php }else{ ?> data-vqty="<?php echo $value_item['stock_quantity']; ?>"
                        data-vqtymsg="{{ trans('lang.invalid_stock_qty') }}"
                        <?php } ?> class="count-number-input-cart btn-sm right inc btn btn-outline-secondary count_number_right"
                        data-vendor-id="<?php echo @$cart['restaurant']['id']; ?>">
                    <i class="feather-plus"></i>
                </button></span>
            <p class="text-gray mb-0 float-right ml-3 text-muted small">
                <span class="currency-symbol-left"></span>
                <span class="cart_iteam_total_<?php echo $key1; ?>">
                    <?php 
                    // Calculate individual item total correctly
                    $basePrice = @floatval($value_item['item_price']); // Use item_price as base price
                    $extraPrice = @floatval($value_item['extra_price']);
                    $quantity = @floatval($value_item['quantity']);
                    $totalItemPrice = ($basePrice + $extraPrice) * $quantity;
                    
                    $digit_decimal = 0;
                    if (@$cart['decimal_degits']) {
                        $digit_decimal = $cart['decimal_degits'];
                    }
                    echo number_format($totalItemPrice, $digit_decimal);
                    
                    // Debug information (remove in production)
                    if (isset($_GET['debug'])) {
                        echo "<!-- Debug: basePrice=$basePrice, extraPrice=$extraPrice, quantity=$quantity, total=$totalItemPrice -->";
                    }
                    ?>
                </span>
                <span class="currency-symbol-right"></span>
            </p>
        </div>
        <div class="close remove_item col-md-1" data-restaurant="<?php echo $key; ?>"
             data-vendor-id="<?php echo @$cart['restaurant']['id']; ?>" data-id="<?php echo $key1; ?>"><i
                class="fa fa-times"></i></div>
    </div>

        <?php 
        // Calculate total price correctly for each item
        $basePrice = @floatval($value_item['item_price']); // Use item_price as base price
        $extraPrice = @floatval($value_item['extra_price']);
        $quantity = @floatval($value_item['quantity']);
        $itemTotal = ($basePrice + $extraPrice) * $quantity;
        $total_price = $total_price + $itemTotal;
    } ?>

    <?php } ?>

    <?php } ?>

        <?php $total_item_price = $total_price; ?>

        <?php
        $discount_amount = 0; $special_discount = 0; $coupon_id = ''; $coupon_code = ''; $discount = ''; $discountType = '';
        $coupon_message = '';
        if (@$cart['coupon'] && $cart['coupon']['discountType']) {
            $discountType = $cart['coupon']['discountType'];
            $coupon_code = $cart['coupon']['coupon_code'];
            $coupon_id = @$cart['coupon']['coupon_id'];
            $discount = $cart['coupon']['discount'];
            // Only apply coupon if total item value is greater than the discount
            if ($total_price > $discount) {
                if ($discountType == 'Fix Price') {
                    $discount_amount = $cart['coupon']['discount'];
                    $total = $total_price - $discount_amount;
                    if ($total < 0) {
                        $total = 0;
                    }
                    if ($discount_amount > $total) {
                        $discount_amount = $total;
                    }
                } else {
                    $discount_amount = $cart['coupon']['discount'];
                    $discount_amount = ($total_item_price * $discount_amount) / 100;
                    $total = $total_price - $discount_amount;
                    if ($total < 0) {
                        $total = 0;
                    }
                    if ($discount_amount > $total) {
                        $discount_amount = $total;
                    }
                }
            } else {
                // Coupon not applied, order value too low
                $discount_amount = 0;
                $coupon_message = '<div class="alert alert-danger mt-2">Order value is too low for this coupon.</div>';
                $total = $total_price;
            }
        } else {
            $total = $total_price;
        }
        // Special Discount Calculation
        if (@$cart['specialOfferDiscount'] && $total_item_price > 0){
            $specialOfferType = $cart['specialOfferType'];
            $specialOfferDiscountVal = $cart['specialOfferDiscountVal'];
            if ($specialOfferType == 'amount') {
                $special_discount = $specialOfferDiscountVal;
            } else {
                $special_discount = ($total_item_price * $specialOfferDiscountVal) / 100;
            }
            $special_discount = round($special_discount, 2);
            $total = $total - $special_discount;
            if ($special_discount > $total) {
                $special_discount = $total;
            }
            if ($total < 0) {
                $total = 0;
            }
        }
        ?>

    <div id="cart_list">
        <?php if (!empty($coupon_message)) { echo $coupon_message; } ?>
        <div class="bg-white px-3 clearfix">
            <div class="border-bottom py-3">
                <h3 class="font-weight-bold  mb-3">Billing Details</h3>
                <div class="d-flex justify-content-between mb-2">
                    <span>Item total</span>
                    <span>₹ <?php echo number_format(floatval($total_price), $digit_decimal); ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Delivery Fee</span>
                    <span>₹ <?php echo number_format(floatval(@$cart['delivery_charge']), $digit_decimal); ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Platform Fee</span>
                    <!-- <span>₹ <?php echo number_format(floatval(@$cart['platform_fee']), $digit_decimal); ?></span> -->
                    <span><s>₹15</s></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Coupon Discount</span>
                    <span class="text-success">₹<?php echo number_format(floatval($discount_amount), $digit_decimal); ?></span>
                    <?php if($coupon_code > 0){ ?>
                    <span id="remove-coupon" class="pointer text-danger">x</span>
                    <?php } ?>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Special Discount</span>
                    <span class="text-success">- (₹<?php echo number_format(floatval($special_discount), $digit_decimal); ?>)</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Delivery Tips</span>
                    <span>₹ <?php echo number_format(floatval(@$cart['tip_amount']), $digit_decimal); ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Taxes & Charges</span>
                    <span>₹ <?php echo number_format(floatval(@$cart['tax']), $digit_decimal); ?></span>
                </div>
                <div class="d-flex justify-content-between mt-3 pt-2 border-top">
                    <span class="font-weight-bold">To Pay</span>
                    <span class="font-weight-bold">
                    ₹ <?php
                          // Add tip amount to total for display
                          $to_pay = floatval($total) + floatval(@$cart['tip_amount']);
                          echo number_format($to_pay, $digit_decimal);
                          ?>
            </span>
                </div>
            </div>
            <div class="bg-white px-3 clearfix delevery-partner"
                 style="<?php if(@$cart['delivery_option'] == "takeaway" ||(@$cart['isSelfDelivery']===true || @$cart['isSelfDelivery']==="true")){ ?> display:none; <?php } ?>">
                <div class="border-bottom py-3">
                    <h3>{{ trans('lang.tip_your_delivery_partner') }}</h3>
                    <span class="float-center">100% of the {{ trans('lang.tip_go_to_your_delivery_partner') }}</span>
                    <div class="tip-box d-flex justify-content-between">
                        <button type="button"
                                class="btn btn-outline-primary btn-sm mr-2<?php if(@$tip_amount==5) echo ' active'; ?>"
                                onclick="selectTip(5)">₹ 5.00
                        </button>
                        <button type="button"
                                class="btn btn-outline-primary btn-sm mr-2<?php if(@$tip_amount==10) echo ' active'; ?>"
                                onclick="selectTip(10)">₹ 10.00
                        </button>
                        <button type="button"
                                class="btn btn-outline-primary btn-sm mr-2<?php if(@$tip_amount==15) echo ' active'; ?>"
                                onclick="selectTip(15)">₹ 15.00
                        </button>
                        <button type="button"
                                class="btn btn-outline-secondary btn-sm<?php if($tip_amount && (@$tip_amount!=5 && @$tip_amount!=10 && @$tip_amount!=15)) echo ' active'; ?>"
                                onclick="selectTip('other')">Other
                        </button>
                    </div>
                    <div id="otherTipInput" class="mt-2"
                         style="display:<?php echo ($tip_amount!=10 && $tip_amount!=20 && $tip_amount!=30 && $tip_amount>0)?'block':'none'; ?>;">
                        <input type="number" min="1" class="form-control" placeholder="Enter tip amount"
                               value="<?php echo ($tip_amount!=5 && $tip_amount!=10 && $tip_amount!=15)?$tip_amount:''; ?>"
                               onchange="selectTip(this.value)"/>
                    </div>
                </div>
            </div>
            <script>
                function selectTip(amount) {
                    if (amount === 'other') {
                        document.getElementById('otherTipInput').style.display = 'block';
                        return;
                    } else {
                        document.getElementById('otherTipInput').style.display = 'none';
                    }
                    $.ajax({
                        type: 'POST',
                        url: "<?php echo route('order-tip-add'); ?>",
                        data: {
                            _token: '<?php echo csrf_token(); ?>',
                            tip: amount,
                            is_checkout: 1
                        },
                        success: function (data) {
                            data = JSON.parse(data);
                            $('#cart_list').html(data.html);
                            // Update the Pay button amount if present in the response
                            if (data.total) {
                                $('#pay-total').text(data.total);
                            }
                        }
                    });
                }
            </script>
            <!-- <div class="bg-white px-3 clearfix">
                <div class="border-bottom pb-3">
                    <div class="input-group-sm mb-2 input-group">
                        <input placeholder="{{ trans('lang.promo_help') }}" data-restaurant="<?php echo @$key1; ?>"
                               data-vendor-id="<?php echo @$cart['restaurant']['id']; ?>"
                               value="<?php echo @$cart['coupon']['coupon_code']; ?>" id="coupon_code" type="text"
                               class="form-control">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-primary" id="apply-coupon-code"
                                    data-vendor-id="<?php echo @$cart['restaurant']['id']; ?>">
                                <i class="feather-percent"></i> {{ trans('lang.apply') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div> -->
{{-- Coupon messages --}}
@if (session('error'))
    <div class="alert alert-danger mt-2">{{ session('error') }}</div>
@endif

@if (session('success'))
    <div class="alert alert-success mt-2">{{ session('success') }}</div>
@endif
<input type="text" name="coupon_code" id="coupon_code" class="form-control mb-2"
    value="<?php echo @$cart['coupon']['coupon_code']; ?>"
    data-vendor-id="<?php echo @$cart['restaurant']['id']; ?>"
    placeholder="Enter coupon code" required>
    <div id="coupon-message"></div>
    <button type="button" id="apply-coupon-code" class="btn btn-primary btn-sm" data-vendor-id="<?php echo @$cart['restaurant']['id']; ?>">Apply Coupon</button>
    <!-- <button type="button" id="remove-coupon" class="btn btn-primary btn-sm ml-2">Remove Coupon</button> -->
@php
    $cartTotal = session('$to_pay') ?? 0;
@endphp

<!-- {{-- Coupon suggestions --}}
@if ($cartTotal < 299)
    <p class="text-muted mt-2">Shop ₹{{ 299 }} more to unlock ₹100 off with code <strong>FLAT100</strong>.</p>
@elseif ($cartTotal < 599)
    <p class="text-info mt-2">You can use <strong>SAVE30</strong> for ₹30 off.</p>
    <p class="text-muted">Shop ₹{{ 599 - $to_pay }} more to unlock ₹50 off with code <strong>SAVE50</strong>.</p>
@else
    <p class="text-success mt-2">You can use <strong>SAVE30</strong> or <strong>SAVE50</strong>.</p>
@endif -->
</div>
        <!-- <button class="btn btn-block mt-3" style="background-color: #ff9800; color: #fff; border: none;" type="button"
                onclick="finalCheckout()">
            <input type="hidden" id="total_pay"
                   value="<?php echo round(floatval($total) + floatval(@$cart['tip_amount']), 2); ?>">
            Pay ₹<span
                id="pay-total"><?php echo number_format(floatval($total) + floatval(@$cart['tip_amount']), $digit_decimal)  + floatval(@$cart['five_percent_charge'])?></span>
            &rarr;
        </button> -->

        <a href="{{route('qrcode')}}">
        <button class="btn btn-block mt-3" style="background-color: #ff9800; color: #fff; border: none;" type="button"
                onclick="">
            <input type="hidden" id="total_pay"
                   value="<?php echo round(floatval($total) + floatval(@$cart['tip_amount']), 2); ?>">
            Pay ₹<span
                id="pay-total"><?php echo number_format(floatval($total) + floatval(@$cart['tip_amount']), $digit_decimal)  + floatval(@$cart['five_percent_charge'])?></span>
            &rarr;
        </button>
        </a>
            <?php
            $total_with_tip = floatval($total) + floatval(@$cart['tip_amount']);
        if ($total_with_tip > 599) { ?>
        <div class="alert alert-warning mt-2" role="alert">
            <small><i class="fa fa-exclamation-triangle"></i> Cash on Delivery is not available for orders over ₹600.
                Please choose another payment method.</small>
        </div>
        <?php } ?>


    </div>

    <!-- Thanks with a tip! Section -->
    <!-- <div class="bg-white p-3 clearfix delivery-box" style="<?php if (!@$cart['item']){ ?> display:none; <?php } ?>">
    <h3>{{ trans('lang.delivery_option') }}</h3>
    <div class="delevery-option">
        <?php $delivery_option = '';
                                                                                                                                                                              if (@$cart['delivery_option']) {
                                                                                                                                                                                  $delivery_option = $cart['delivery_option'];
                                                                                                                                                                              } else {
                                                                                                                                                                                  $delivery_option = @$cart['delivery_option'];
                                                                                                                                                                                  Session::get('takeawayOption');
                                                                                                                                                                                  if (Session::get('takeawayOption') == 'true') {
                                                                                                                                                                                      $delivery_option = 'takeaway';
                                                                                                                                                                                  } else {
                                                                                                                                                                                      $delivery_option = 'delivery';
                                                                                                                                                                                  }
                                                                                                                                                                              }
                                                                                                                                                                              ?>
        <input type="hidden" name="delivery_option" value="<?php echo $delivery_option; ?>">
        <?php if ($delivery_option == "takeaway"){ ?>
        <label class="custom-control-labels" for="takeaway">{{ trans('lang.take_away') }}({{ trans('lang.free') }})</label>
        <?php }else{ ?>
        <label class="custom-control-labels" for="takeaway">Delivery
            <?php if (@$cart['deliverychargemain'] && (@$cart['isSelfDelivery'] === false || @$cart['isSelfDelivery'] === "false")){ ?> (<span class="currency-symbol-left"></span>
            <?php
                                                                                                                                                                $digit_decimal = 0;
                                                                                                                                                                if (@$cart['decimal_degits']) {
                                                                                                                                                                    $digit_decimal = $cart['decimal_degits'];
                                                                                                                                                                }
                                                                                                                                                                echo number_format(floatval(@$cart['deliverychargemain']), $digit_decimal);
                                                                                                                                                                ?>
        <span class="currency-symbol-right"></span> )
<?php } ?>
        </label>
        <?php } ?>
        </div>
    </div> -->

    <!-- <div class="bg-white p-3 clearfix btm-total">
    <p class="mb-2">
        {{ trans('lang.sub_total') }}
    <span class="float-right text-dark">
        <span class="currency-symbol-left"></span>
<?php
        $digit_decimal = 0;
        if (@$cart['decimal_degits']) {
            $digit_decimal = $cart['decimal_degits'];
        }
        echo number_format(floatval($total_price), $digit_decimal);
        ?>
        <span class="currency-symbol-right"></span>
    </span>
</p> -->

        <?php

        $discount_amount = 0; $coupon_id = ''; $coupon_code = ''; $discount = ''; $discountType = '';

    if (@$cart['coupon'] && $cart['coupon']['discountType']){ ?>
    <hr>
    <p class="mb-1 text-success">
            <?php $discountType = $cart['coupon']['discountType'];
            $coupon_code = $cart['coupon']['coupon_code'];
            $coupon_id = @$cart['coupon']['coupon_id'];
            $discount = $cart['coupon']['discount'];
            if ($discountType == 'Fix Price') {
                $discount_amount = $cart['coupon']['discount'];
                $total = $total_price - $discount_amount;
                if ($total < 0) {
                    $total = 0;
                }
                if ($discount_amount > $total) {
                    $discount_amount = $total;
                }
            } else {
                $discount_amount = $cart['coupon']['discount'];
                $discount_amount = ($total_item_price * $discount_amount) / 100;
                $total = $total_price - $discount_amount;
                if ($total < 0) {
                    $total = 0;
                }
                if ($discount_amount > $total) {
                    $discount_amount = $total;
                }
            }
            ?>
        {{ trans('lang.total') }} {{ trans('lang.discount') }} <span class="float-right text-success"><span
                class="currency-symbol-left"></span>
            <?php
                $digit_decimal = 0;
                if (@$cart['decimal_degits']) {
                    $digit_decimal = $cart['decimal_degits'];
                }
                echo number_format(floatval($discount_amount), $digit_decimal);
                ?><span class="currency-symbol-right"></span></span>
    </p>
    <?php } else { ?>

        <?php $total = $total_price; ?>

    <?php } ?>
    <input type="hidden" id="discount_amount" value="<?php echo $discount_amount; ?>">
    <input type="hidden" id="coupon_id" value="<?php echo $coupon_id; ?>">
    <input type="hidden" id="coupon_code_main" value="<?php echo $coupon_code; ?>">
    <input type="hidden" id="discount" value="<?php echo $discount; ?>">
    <input type="hidden" id="discountType" value="<?php echo $discountType; ?>">
        <?php $specialOfferDiscount = 0; $specialOfferType = ''; $specialOfferDiscountVal = 0;
    if (@$cart['specialOfferDiscount'] && $total_item_price > 0){ ?>
    <p class="mb-1 text-success">
            <?php
            $specialOfferDiscount = $cart['specialOfferDiscount'];
            $specialOfferType = $cart['specialOfferType'];
            $specialOfferDiscountVal = $cart['specialOfferDiscountVal'];
            if ($specialOfferType == 'amount') {
                $specialOfferDiscount = $specialOfferDiscountVal;
            } else {
                $specialOfferDiscount = ($total_item_price * $specialOfferDiscountVal) / 100;
            }
            $specialOfferDiscount = round($specialOfferDiscount, 2);
            $total = $total - $specialOfferDiscount;
            if ($specialOfferDiscount > $total) {
                $specialOfferDiscount = $total;
            }
            if ($total < 0) {
                $total = 0;
            }
            $special_html = '';
            if ($specialOfferType == 'percentage') {
                $special_html = '(' . $specialOfferDiscountVal . '%)';
            }
            ?>
        {{ trans('lang.special') }} {{ trans('lang.offer') }} {{ trans('lang.discount') }} <?php echo $special_html; ?>
        <span class="float-right text-success"><span class="currency-symbol-left"></span><?php
                                                                                             $digit_decimal = 0;
                                                                                             if (@$cart['decimal_degits']) {
                                                                                                 $digit_decimal = $cart['decimal_degits'];
                                                                                             }
                                                                                             echo number_format(floatval($specialOfferDiscount), $digit_decimal);
                                                                                             ?><span
                class="currency-symbol-right"></span></span>
    </p>
    <?php } ?>
    <input type="hidden" id="specialOfferDiscountAmount" value="<?php echo $specialOfferDiscount; ?>">
    <input type="hidden" id="specialOfferType" value="<?php echo $specialOfferType; ?>">
    <input type="hidden" id="specialOfferDiscountVal" value="<?php echo $specialOfferDiscountVal; ?>">
        <?php
        $total_item_price = $total_item_price - $discount_amount - $specialOfferDiscount;
    if ($item_count && $total_price && $cart['tax'] && @$cart['taxValue']){ ?>
    <hr>
        <?php
    foreach ($cart['taxValue'] as $val){
        ?>
    <p class="mb-2"><?php echo $val['title']; ?>
                        <?php if ($val['type'] == 'fix'){ ?>
        ( <span class="currency-symbol-left"></span>
            <?php
            $digit_decimal = 0;
            if (@$cart['decimal_degits']) {
                $digit_decimal = $cart['decimal_degits'];
            }
            echo number_format(floatval($val['tax']), $digit_decimal);
            $tax = $val['tax'];
            ?>
        <span class="currency-symbol-right"></span> )
        <?php }else{
            $tax = ($val['tax'] * $total_item_price) / 100; ?>
        (<?php echo $val['tax']; ?>%)
        <?php } ?>
            <?php ?>
        <span class="float-right text-dark">
            <span class="currency-symbol-left"></span>
            <?php
                $digit_decimal = 0;
                if (@$cart['decimal_degits']) {
                    $digit_decimal = $cart['decimal_degits'];
                }
                echo number_format(floatval($tax), $digit_decimal);
                ?>
            <span class="currency-symbol-right"></span>
        </span>
    </p> <?php
             $total = $total + $tax;
         }
         } ?>
    <input type="hidden" id="tax_label" value="<?php echo @$cart['tax_label']; ?>">
    <input type="hidden" id="tax" value="<?php echo @$cart['tax']; ?>">
    <hr>
    <!-- <p class="mb-2">
        {{ trans('lang.deliveryCharge') }} <span class="float-right text-dark"><?php if (@$cart['isSelfDelivery'] === false || @$cart['isSelfDelivery'] === 'false'){ ?><span class="currency-symbol-left"></span><?php } ?>
                                                                                                                                                                                                                              <?php
                                                                                                                                                                                                                              $digit_decimal = 0;
                                                                                                                                                                                                                              if (@$cart['decimal_degits']) {
                                                                                                                                                                                                                                  $digit_decimal = $cart['decimal_degits'];
                                                                                                                                                                                                                              }
                                                                                                                                                                                                                          if ($item_count && $total_price && @$cart['deliverycharge']) {
        $total = $total + $cart['deliverycharge'];
        echo number_format(floatval(@$cart['deliverycharge']), $digit_decimal);
    } else {
                                                                                                                                                                                                                          if (@$cart['isSelfDelivery'] === true || @$cart['isSelfDelivery'] === 'true') { ?>
        <span class="text-success">Free Delivery</span>
<?php } else {
        echo number_format(0, $digit_decimal);
    }
        }
            ?>
            <?php if ($item_count && $total_price && @$cart['deliverycharge']){ ?>
            <?php if (@$cart['isSelfDelivery'] === false || @$cart['isSelfDelivery'] === 'false'){ ?><span class="currency-symbol-right"></span><?php } ?><?php if (@$cart['deliverykm']){ ?> (<?php echo number_format($cart['deliverykm'], 2);
                                                                                                                                                                                                   echo $cart['distanceType'] ?? ''; ?>) <?php } ?> </span>
        <?php } ?>
        </p>
<?php if ($item_count && $tip_amount){
            $total = $total + $tip_amount; ?>
        <hr>
        <p class="mb-2">
{{ trans('lang.tip_amount') }} <span class="float-right text-dark"><span class="currency-symbol-left"></span><?php
                                                                                                                         $digit_decimal = 0;
                                                                                                                         if (@$cart['decimal_degits']) {
                                                                                                                             $digit_decimal = $cart['decimal_degits'];
                                                                                                                         }
                                                                                                                         echo number_format(floatval($tip_amount), $digit_decimal);
                                                                                                                         ?><span class="currency-symbol-right"></span></span>
    </p>
    <?php } ?>
        <input type="hidden" value="<?php echo @$cart['deliverycharge']; ?>" id="deliveryCharge">
    <input type="hidden" value="" id="deliveryChargeMain">
    <input type="hidden" value="<?php echo $cart['distanceType'] ?? ''; ?>" id="distanceType">
    <input type="hidden" id="adminCommission" value="0">
    <input type="hidden" id="adminCommissionType" value="Fix Price">
    <input type="hidden" id="total_pay" value="<?php echo round($total, 2); ?>">
    <hr>
    <h6 class="font-weight-bold mb-0">{{ trans('lang.total') }}
    <p class="float-right">
        <span class="currency-symbol-left"></span>
        <span>
<?php
        $digit_decimal = 0;
        if (@$cart['decimal_degits']) {
            $digit_decimal = $cart['decimal_degits'];
        }
        echo number_format(floatval($total), $digit_decimal);
        ?>
        </span>
        <span class="currency-symbol-right"></span>
    </p>
</h6>
<a class="btn btn-primary btn-block btn-lg" href="javascript:void(0)" onclick="finalCheckout()">{{ trans('lang.pay') }}
    <span class="currency-symbol-left"></span>
<?php echo number_format(floatval($total), @$cart['decimal_degits']); ?>
        <span class="currency-symbol-right"></span>
        <i class="feather-arrow-right"></i>
    </a>
</div> -->

    <?php }else{ ?>

    <div class="bg-white border-bottom py-2">
        <div class="gold-members d-flex align-items-center justify-content-between px-3 py-2 border-bottom">
            <span>{{ trans('lang.your_cart_is_empty') }}</span>
        </div>
    <?php } ?>

    <script>
        function noNegative(e) {
            // Prevent typing the minus key
            if (e.key === '-' || e.key === 'e') {
                e.preventDefault();
            }
        }

        // Function to clear cart
        function clearCart() {
            Swal.fire({
                title: 'Clear Cart?',
                text: 'Are you sure you want to remove all items from your cart?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, clear it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: "<?php echo route('clear-cart'); ?>",
                        data: {
                            _token: '<?php echo csrf_token(); ?>'
                        },
                        success: function (data) {
                            data = JSON.parse(data);
                            $('#cart_list').html(data.html);
                            loadcurrencynew();

                            // Also clear localStorage cart to sync both systems
                            if (typeof localStorage !== 'undefined') {
                                localStorage.removeItem('cart');
                                console.log('Cleared localStorage cart');
                            }

                            Swal.fire(
                                'Cleared!',
                                'Your cart has been cleared.',
                                'success'
                            );
                        }
                    });
                }
            });
        }
    </script>
