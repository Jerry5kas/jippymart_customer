# Razorpay Mobile API Documentation

## Overview
This document describes the Razorpay API endpoints designed for mobile applications. These endpoints provide a complete payment flow for orders, wallet recharges, and gift card purchases.

## Base URL
```
https://your-domain.com/api/razorpay
```

## Authentication
Most endpoints require authentication using Laravel Sanctum. Include the Bearer token in the Authorization header:
```
Authorization: Bearer {your-token}
```

## API Endpoints

### 1. Get Razorpay Settings
**GET** `/api/razorpay/settings`

Get Razorpay configuration settings for the mobile app.

**No authentication required**

#### Response
```json
{
    "success": true,
    "data": {
        "is_enabled": true,
        "is_sandbox": false,
        "key": "rzp_test_xxxxxxxxxxxxx",
        "currency": "INR"
    }
}
```

### 2. Create Order
**POST** `/api/razorpay/create-order`

Create a new Razorpay order for payment processing.

**Authentication required**

#### Request Body
```json
{
    "amount": 500.00,
    "currency": "INR",
    "order_type": "order",
    "restaurant_id": "rest_123",
    "items": [
        {
            "id": "item_1",
            "name": "Pizza Margherita",
            "quantity": 2,
            "price": 250.00
        }
    ],
    "delivery_address": {
        "street": "123 Main St",
        "city": "Mumbai",
        "state": "Maharashtra",
        "pincode": "400001",
        "latitude": 19.0760,
        "longitude": 72.8777
    },
    "user_notes": "Extra cheese please"
}
```

#### Parameters
- `amount` (required): Order amount in decimal
- `currency` (optional): Currency code (default: INR)
- `order_type` (required): Type of order (order, wallet, giftcard)
- `restaurant_id` (required if order_type is "order"): Restaurant ID
- `items` (required if order_type is "order"): Array of order items
- `delivery_address` (required if order_type is "order"): Delivery address
- `user_notes` (optional): Additional notes from user

#### Response
```json
{
    "success": true,
    "message": "Order created successfully",
    "data": {
        "order_id": "order_xxxxxxxxxxxxx",
        "firebase_order_id": "firebase_order_id_123",
        "amount": 500.00,
        "currency": "INR",
        "receipt": "order_1234567890"
    }
}
```

### 3. Verify Payment
**POST** `/api/razorpay/verify-payment`

Verify and capture payment after successful payment processing.

**Authentication required**

#### Request Body
```json
{
    "razorpay_payment_id": "pay_xxxxxxxxxxxxx",
    "razorpay_order_id": "order_xxxxxxxxxxxxx",
    "razorpay_signature": "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
    "firebase_order_id": "firebase_order_id_123"
}
```

#### Parameters
- `razorpay_payment_id` (required): Payment ID from Razorpay
- `razorpay_order_id` (required): Order ID from Razorpay
- `razorpay_signature` (required): Payment signature from Razorpay
- `firebase_order_id` (required): Firebase order ID

#### Response
```json
{
    "success": true,
    "message": "Payment verified and captured successfully",
    "data": {
        "payment_id": "pay_xxxxxxxxxxxxx",
        "order_id": "order_xxxxxxxxxxxxx",
        "status": "captured",
        "amount": 500.00,
        "currency": "INR"
    }
}
```

### 4. Get Payment Details
**POST** `/api/razorpay/payment-details`

Get detailed information about a payment.

**Authentication required**

#### Request Body
```json
{
    "payment_id": "pay_xxxxxxxxxxxxx"
}
```

#### Response
```json
{
    "success": true,
    "data": {
        "payment_id": "pay_xxxxxxxxxxxxx",
        "status": "captured",
        "amount": 500.00,
        "currency": "INR",
        "method": "card",
        "created_at": 1640995200,
        "email": "user@example.com",
        "contact": "+919876543210"
    }
}
```

### 5. Refund Payment
**POST** `/api/razorpay/refund-payment`

Process a refund for a payment.

**Authentication required**

#### Request Body
```json
{
    "payment_id": "pay_xxxxxxxxxxxxx",
    "amount": 250.00,
    "reason": "Customer requested partial refund"
}
```

#### Parameters
- `payment_id` (required): Payment ID to refund
- `amount` (optional): Amount to refund (full amount if not specified)
- `reason` (optional): Reason for refund

#### Response
```json
{
    "success": true,
    "message": "Refund processed successfully",
    "data": {
        "refund_id": "rfnd_xxxxxxxxxxxxx",
        "payment_id": "pay_xxxxxxxxxxxxx",
        "amount": 250.00,
        "status": "processed"
    }
}
```

### 6. Get Order Status
**POST** `/api/razorpay/order-status`

Get the current status of an order.

**Authentication required**

#### Request Body
```json
{
    "firebase_order_id": "firebase_order_id_123"
}
```

#### Response
```json
{
    "success": true,
    "data": {
        "order_id": "order_xxxxxxxxxxxxx",
        "firebase_order_id": "firebase_order_id_123",
        "status": "confirmed",
        "payment_status": "completed",
        "amount": 500.00,
        "currency": "INR",
        "order_type": "order",
        "created_at": "2024-01-01T12:00:00Z",
        "updated_at": "2024-01-01T12:05:00Z"
    }
}
```

## Error Responses

All endpoints return consistent error responses:

### Validation Error (422)
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "amount": ["The amount field is required."]
    }
}
```

### Authentication Error (401)
```json
{
    "success": false,
    "message": "User not authenticated"
}
```

### Server Error (500)
```json
{
    "success": false,
    "message": "Failed to create order: Internal server error"
}
```

## Mobile App Integration Flow

### 1. Initialize Payment
1. Call `/api/razorpay/settings` to get Razorpay configuration
2. Initialize Razorpay SDK with the received key
3. Call `/api/razorpay/create-order` to create an order
4. Use the returned `order_id` to initialize Razorpay payment

### 2. Process Payment
1. Use Razorpay SDK to process payment on the client side
2. After successful payment, call `/api/razorpay/verify-payment` with payment details
3. Handle the response to confirm payment completion

### 3. Order Management
1. Use `/api/razorpay/order-status` to check order status
2. Use `/api/razorpay/payment-details` to get payment information
3. Use `/api/razorpay/refund-payment` if refund is needed

## Example Mobile App Integration (React Native)

```javascript
// Initialize Razorpay
const initializeRazorpay = async () => {
    const response = await fetch('/api/razorpay/settings');
    const { data } = await response.json();
    
    const options = {
        key: data.key,
        currency: data.currency,
        name: 'Your App Name',
        description: 'Order Payment',
        order_id: '', // Will be set when creating order
        handler: function (response) {
            verifyPayment(response);
        },
        prefill: {
            email: user.email,
            contact: user.phone
        },
        theme: {
            color: '#3399cc'
        }
    };
    
    return options;
};

// Create order
const createOrder = async (orderData) => {
    const response = await fetch('/api/razorpay/create-order', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify(orderData)
    });
    
    return await response.json();
};

// Verify payment
const verifyPayment = async (paymentResponse) => {
    const response = await fetch('/api/razorpay/verify-payment', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify({
            razorpay_payment_id: paymentResponse.razorpay_payment_id,
            razorpay_order_id: paymentResponse.razorpay_order_id,
            razorpay_signature: paymentResponse.razorpay_signature,
            firebase_order_id: currentOrder.firebase_order_id
        })
    });
    
    return await response.json();
};
```

## Security Considerations

1. **Signature Verification**: Always verify payment signatures on the server side
2. **Authentication**: Use proper authentication for all sensitive endpoints
3. **Input Validation**: All inputs are validated on the server side
4. **Error Handling**: Implement proper error handling in mobile apps
5. **HTTPS**: Always use HTTPS in production

## Testing

### Sandbox Mode
- Use Razorpay test credentials for development
- Test with Razorpay test cards
- Verify all payment flows in sandbox before going live

### Test Cards
- Success: 4111 1111 1111 1111
- Failure: 4000 0000 0000 0002
- Expired: 4000 0000 0000 0069

## Support

For technical support or questions about the API:
- Email: support@yourdomain.com
- Documentation: https://yourdomain.com/api/docs
- Status Page: https://status.yourdomain.com

