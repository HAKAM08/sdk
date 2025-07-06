<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Cart extends Model
{
    use HasFactory;

    /**
     * Get the current cart from the session or create a new one
     *
     * @return array
     */
    public static function getCart()
    {
        return Session::get('cart', [
            'items' => [],
            'total_quantity' => 0,
            'total_price' => 0,
        ]);
    }

    /**
     * Add an item to the cart
     *
     * @param Product $product
     * @param int $quantity
     * @return array
     */
    public static function addItem(Product $product, int $quantity = 1)
    {
        $cart = self::getCart();
        $productId = $product->id;

        // Check if the product is already in the cart
        if (isset($cart['items'][$productId])) {
            // Update quantity
            $cart['items'][$productId]['quantity'] += $quantity;
        } else {
            // Add new item
            $cart['items'][$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity,
                'image' => $product->image,
                'slug' => $product->slug,
            ];
        }

        // Update cart totals
        self::updateCartTotals($cart);

        // Save cart to session
        Session::put('cart', $cart);

        return $cart;
    }

    /**
     * Update an item's quantity in the cart
     *
     * @param int $productId
     * @param int $quantity
     * @return array|null
     */
    public static function updateItem(int $productId, int $quantity)
    {
        $cart = self::getCart();

        // Check if the product is in the cart
        if (!isset($cart['items'][$productId])) {
            return null;
        }

        // Update quantity or remove if quantity is 0
        if ($quantity <= 0) {
            return self::removeItem($productId);
        }

        $cart['items'][$productId]['quantity'] = $quantity;

        // Update cart totals
        self::updateCartTotals($cart);

        // Save cart to session
        Session::put('cart', $cart);

        return $cart;
    }

    /**
     * Remove an item from the cart
     *
     * @param int $productId
     * @return array
     */
    public static function removeItem(int $productId)
    {
        $cart = self::getCart();

        // Remove the item if it exists
        if (isset($cart['items'][$productId])) {
            unset($cart['items'][$productId]);
        }

        // Update cart totals
        self::updateCartTotals($cart);

        // Save cart to session
        Session::put('cart', $cart);

        return $cart;
    }

    /**
     * Clear the cart
     *
     * @return array
     */
    public static function clearCart()
    {
        $emptyCart = [
            'items' => [],
            'total_quantity' => 0,
            'total_price' => 0,
        ];

        Session::put('cart', $emptyCart);

        return $emptyCart;
    }

    /**
     * Update the cart totals
     *
     * @param array $cart
     * @return void
     */
    private static function updateCartTotals(array &$cart)
    {
        $totalQuantity = 0;
        $totalPrice = 0;

        foreach ($cart['items'] as $item) {
            $totalQuantity += $item['quantity'];
            $totalPrice += $item['price'] * $item['quantity'];
        }

        $cart['total_quantity'] = $totalQuantity;
        $cart['total_price'] = $totalPrice;
    }

    /**
     * Get the number of items in the cart
     *
     * @return int
     */
    public static function getCount()
    {
        $cart = self::getCart();
        return $cart['total_quantity'];
    }

    /**
     * Get the total price of the cart
     *
     * @return float
     */
    public static function getTotal()
    {
        $cart = self::getCart();
        return $cart['total_price'];
    }

    /**
     * Check if the cart is empty
     *
     * @return bool
     */
    public static function isEmpty()
    {
        $cart = self::getCart();
        return count($cart['items']) === 0;
    }
}