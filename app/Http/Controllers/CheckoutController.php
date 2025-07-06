<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Notifications\OrderConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Middleware is applied in routes
    }

    /**
     * Show the checkout form.
     */
    public function index()
    {
        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
        
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
        
        $user = Auth::user();
        
        return view('checkout.index', compact('cartItems', 'total', 'user'));
    }

    /**
     * Process the checkout.
     */
    public function process(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|max:255',
            'billing_address' => 'required|string|max:255',
            'payment_method' => 'required|in:credit_card,paypal',
            'notes' => 'nullable|string|max:500',
        ]);
        
        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
        
        $total = 0;
        $orderItems = [];
        $outOfStockItems = [];
        
        foreach ($cart as $id => $details) {
            $product = Product::find($id);
            if ($product) {
                // Check if product has enough stock
                if ($product->stock < $details['quantity']) {
                    $outOfStockItems[] = $product->name . ' (Available: ' . $product->stock . ')';
                    continue;
                }
                
                $price = $product->sale_price ?? $product->price;
                $subtotal = $price * $details['quantity'];
                $total += $subtotal;
                
                $orderItems[] = [
                    'product_id' => $id,
                    'product_name' => $product->name,
                    'quantity' => $details['quantity'],
                    'price' => $price,
                ];
            }
        }
        
        // If any products are out of stock, redirect back with error
        if (!empty($outOfStockItems)) {
            return redirect()->route('cart.index')
                ->with('error', 'The following items do not have enough stock: ' . implode(', ', $outOfStockItems));
        }
        
        // If no valid items in cart after stock check
        if (empty($orderItems)) {
            return redirect()->route('cart.index')->with('error', 'Your cart contains invalid or out of stock items.');
        }
        
        // Create order
        $order = Order::create([
            'user_id' => Auth::id(),
            'status' => 'pending',
            'total' => $total,
            'shipping_address' => $request->shipping_address,
            'billing_address' => $request->billing_address,
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
            'notes' => $request->notes,
        ]);
        
        // Create order items and update product stock
        foreach ($orderItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'product_name' => $item['product_name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
            
            // Update product stock
            $product = Product::find($item['product_id']);
            if ($product) {
                $product->stock = max(0, $product->stock - $item['quantity']);
                $product->save();
            }
        }
        
        // Clear cart
        Session::forget('cart');
        
        // Send order confirmation email
        $user = Auth::user();
        try {
            // Send notification immediately without queueing
            $user->notify(new OrderConfirmation($order));
            \Illuminate\Support\Facades\Log::info('Order confirmation email sent successfully', ['order_id' => $order->id, 'user_email' => $user->email]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send order confirmation email', [
                'order_id' => $order->id,
                'user_email' => $user->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            // Continue with checkout process even if email fails
        }
        
        return redirect()->route('checkout.success', $order->id);
    }

    /**
     * Show the checkout success page.
     */
    public function success($orderId)
    {
        $order = Order::with('items')->findOrFail($orderId);
        
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('checkout.success', compact('order'));
    }
}