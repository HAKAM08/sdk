<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Middleware is applied in routes
    }
    /**
     * Display the cart.
     */
    public function index()
    {
        $cart = Session::get('cart', []);
        $cartItems = [];
        $total = 0;
        
        foreach ($cart as $id => $details) {
            $product = Product::find($id);
            if ($product) {
                $cartItems[] = [
                    'id' => $id,
                    'product' => $product,
                    'quantity' => $details['quantity'],
                    'price' => $product->sale_price ?? $product->price,
                    'subtotal' => ($product->sale_price ?? $product->price) * $details['quantity']
                ];
                $total += ($product->sale_price ?? $product->price) * $details['quantity'];
            }
        }
        
        return view('cart.index', compact('cartItems', 'total'));
    }

    /**
     * Add a product to the cart.
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);
        
        $productId = $request->product_id;
        $quantity = $request->quantity;
        
        $product = Product::findOrFail($productId);
        
        // Check if product has enough stock
        if ($product->stock < $quantity) {
            return redirect()->back()->with('error', 'Sorry, "' . $product->name . '" only has ' . $product->stock . ' items in stock.');
        }
        $cart = Session::get('cart', []);
        
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'quantity' => $quantity
            ];
        }
        
        Session::put('cart', $cart);
        
        return redirect()->back()->with('success', '"' . $product->name . '" has been added to your cart.');
    }

    /**
     * Update the cart.
     */
    public function update(Request $request)
    {
        $request->validate([
            'quantities' => 'required|array',
            'quantities.*' => 'required|integer|min:1'
        ]);
        
        $cart = Session::get('cart', []);
        $outOfStockItems = [];
        
        foreach ($request->quantities as $id => $quantity) {
            if (isset($cart[$id])) {
                // Check if product has enough stock
                $product = Product::find($id);
                if ($product && $product->stock < $quantity) {
                    $outOfStockItems[] = $product->name . ' (Available: ' . $product->stock . ')';
                    // Keep the original quantity if not enough stock
                    continue;
                }
                
                $cart[$id]['quantity'] = $quantity;
            }
        }
        
        Session::put('cart', $cart);
        
        if (!empty($outOfStockItems)) {
            return redirect()->route('cart.index')
                ->with('error', 'The following items do not have enough stock: ' . implode(', ', $outOfStockItems));
        }
        
        return redirect()->route('cart.index')->with('success', 'Cart updated successfully.');
    }

    /**
     * Remove an item from the cart.
     */
    public function remove($id)
    {
        $cart = Session::get('cart', []);
        
        if (isset($cart[$id])) {
            unset($cart[$id]);
            Session::put('cart', $cart);
        }
        
        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }

    /**
     * Clear the cart.
     */
    public function clear()
    {
        Session::forget('cart');
        
        return redirect()->route('cart.index')->with('success', 'Cart cleared successfully.');
    }
}