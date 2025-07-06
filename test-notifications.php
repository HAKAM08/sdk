<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Get the first user
$user = \App\Models\User::first();
if (!$user) {
    die("No users found in the database.\n");
}

echo "Testing notifications with user: {$user->name} ({$user->email})\n";

// Get the first order or create a test order
$order = \App\Models\Order::with('items')->first();
if (!$order) {
    echo "No orders found. Creating a test order...\n";
    
    // Create a test order
    $order = new \App\Models\Order([
        'user_id' => $user->id,
        'status' => 'pending',
        'total' => 99.99,
        'shipping_address' => '123 Test St, Test City, TS 12345',
        'billing_address' => '123 Test St, Test City, TS 12345',
        'payment_method' => 'credit_card',
        'payment_status' => 'pending',
    ]);
    $order->save();
    
    // Create a test order item
    $product = \App\Models\Product::first();
    if ($product) {
        $orderItem = new \App\Models\OrderItem([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity' => 1,
            'price' => $product->price,
        ]);
        $orderItem->save();
    }
    
    $order->load('items');
}

echo "Using order #{$order->id} for testing\n";

// Test sending OrderConfirmation notification directly (not through queue)
try {
    echo "\nSending OrderConfirmation notification directly...\n";
    $user->notify(new \App\Notifications\OrderConfirmation($order));
    echo "OrderConfirmation notification sent successfully!\n";
} catch (\Exception $e) {
    echo "Error sending OrderConfirmation notification: {$e->getMessage()}\n";
    echo "Stack trace: {$e->getTraceAsString()}\n";
}

// Test sending OrderStatusUpdate notification directly (not through queue)
try {
    echo "\nSending OrderStatusUpdate notification directly...\n";
    $previousStatus = $order->status;
    $newStatus = ($previousStatus === 'pending') ? 'processing' : 'pending';
    
    // Temporarily update the order status for the notification
    $order->status = $newStatus;
    $user->notify(new \App\Notifications\OrderStatusUpdate($order, $previousStatus));
    echo "OrderStatusUpdate notification sent successfully!\n";
    
    // Restore the original status
    $order->status = $previousStatus;
    $order->save();
} catch (\Exception $e) {
    echo "Error sending OrderStatusUpdate notification: {$e->getMessage()}\n";
    echo "Stack trace: {$e->getTraceAsString()}\n";
}

echo "\nDone testing notifications.\n";