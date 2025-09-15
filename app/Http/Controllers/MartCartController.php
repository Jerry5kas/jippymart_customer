<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class MartCartController extends Controller
{
    /**
     * Add item to cart
     */
    public function addToCart(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|string',
                'name' => 'required|string',
                'price' => 'required|numeric|min:0',
                'disPrice' => 'required|numeric|min:0',
                'image' => 'required|string',
                'subcategoryTitle' => 'nullable|string',
                'description' => 'nullable|string',
                'grams' => 'nullable|string',
                'rating' => 'nullable|numeric',
                'reviews' => 'nullable|integer',
            ]);

            $cart = Session::get('mart_cart', []);
            $itemId = $request->id;
            
            // If item already exists, increment quantity
            if (isset($cart[$itemId])) {
                $cart[$itemId]['quantity'] += 1;
            } else {
                // Add new item to cart
                $cart[$itemId] = [
                    'id' => $request->id,
                    'name' => $request->name,
                    'price' => $request->disPrice, // Use discounted price
                    'originalPrice' => $request->price,
                    'image' => $request->image,
                    'subcategoryTitle' => $request->subcategoryTitle,
                    'description' => $request->description,
                    'grams' => $request->grams,
                    'rating' => $request->rating,
                    'reviews' => $request->reviews,
                    'quantity' => 1,
                    'added_at' => now()->toISOString(),
                ];
            }

            Session::put('mart_cart', $cart);
            Session::save();

            $totalItems = array_sum(array_column($cart, 'quantity'));

            return response()->json([
                'success' => true,
                'message' => 'Item added to cart successfully',
                'cart' => $cart,
                'totalItems' => $totalItems,
                'item' => $cart[$itemId]
            ]);

        } catch (\Exception $e) {
            Log::error('Error adding item to cart: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add item to cart'
            ], 500);
        }
    }

    /**
     * Update item quantity in cart
     */
    public function updateQuantity(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|string',
                'quantity' => 'required|integer|min:0',
            ]);

            $cart = Session::get('mart_cart', []);
            $itemId = $request->id;
            $quantity = $request->quantity;

            if (isset($cart[$itemId])) {
                if ($quantity <= 0) {
                    unset($cart[$itemId]);
                } else {
                    $cart[$itemId]['quantity'] = $quantity;
                }

                Session::put('mart_cart', $cart);
                Session::save();

                $totalItems = array_sum(array_column($cart, 'quantity'));

                return response()->json([
                    'success' => true,
                    'message' => 'Cart updated successfully',
                    'cart' => $cart,
                    'totalItems' => $totalItems
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Item not found in cart'
            ], 404);

        } catch (\Exception $e) {
            Log::error('Error updating cart quantity: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update cart'
            ], 500);
        }
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|string',
            ]);

            $cart = Session::get('mart_cart', []);
            $itemId = $request->id;

            if (isset($cart[$itemId])) {
                unset($cart[$itemId]);
                Session::put('mart_cart', $cart);
                Session::save();

                $totalItems = array_sum(array_column($cart, 'quantity'));

                return response()->json([
                    'success' => true,
                    'message' => 'Item removed from cart successfully',
                    'cart' => $cart,
                    'totalItems' => $totalItems
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Item not found in cart'
            ], 404);

        } catch (\Exception $e) {
            Log::error('Error removing item from cart: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove item from cart'
            ], 500);
        }
    }

    /**
     * Get cart contents
     */
    public function getCart()
    {
        try {
            $cart = Session::get('mart_cart', []);
            $totalItems = array_sum(array_column($cart, 'quantity'));
            $totalPrice = 0;

            foreach ($cart as $item) {
                $totalPrice += $item['price'] * $item['quantity'];
            }

            return response()->json([
                'success' => true,
                'cart' => $cart,
                'totalItems' => $totalItems,
                'totalPrice' => $totalPrice
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting cart: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to get cart'
            ], 500);
        }
    }

    /**
     * Clear entire cart
     */
    public function clearCart()
    {
        try {
            Session::forget('mart_cart');
            Session::save();

            return response()->json([
                'success' => true,
                'message' => 'Cart cleared successfully',
                'cart' => [],
                'totalItems' => 0
            ]);

        } catch (\Exception $e) {
            Log::error('Error clearing cart: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cart'
            ], 500);
        }
    }
}

