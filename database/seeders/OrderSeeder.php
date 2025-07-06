<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users except admin
        $users = User::where('is_admin', false)->get();
        
        // Get all products
        $products = Product::all();
        
        // Create 1-3 orders for each user
        foreach ($users as $user) {
            $orderCount = rand(1, 3);
            
            for ($i = 0; $i < $orderCount; $i++) {
                // Create order
                $order = Order::create([
                    'user_id' => $user->id,
                    'status' => $this->getRandomStatus(),
                    'total' => 0, // Will be calculated based on items
                    'shipping_address' => $user->address ?? $this->getFakeAddress(),
                    'billing_address' => $user->address ?? $this->getFakeAddress(),
                    'payment_method' => $this->getRandomPaymentMethod(),
                    'payment_status' => $this->getRandomPaymentStatus(),
                    'tracking_number' => $this->getRandomTrackingNumber(),
                    'notes' => rand(0, 1) ? $this->getFakeNotes() : null,
                ]);
                
                // Add 1-5 random products to the order
                $orderTotal = 0;
                $orderItemCount = rand(1, 5);
                $orderProducts = $products->random($orderItemCount);
                
                foreach ($orderProducts as $product) {
                    $quantity = rand(1, 3);
                    $price = $product->sale_price ?? $product->price;
                    $subtotal = $price * $quantity;
                    $orderTotal += $subtotal;
                    
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'quantity' => $quantity,
                        'price' => $price,
                    ]);
                }
                
                // Update order total
                $order->update(['total' => $orderTotal]);
            }
        }
    }
    
    /**
     * Get a random order status.
     *
     * @return string
     */
    private function getRandomStatus(): string
    {
        $statuses = ['pending', 'processing', 'completed', 'cancelled'];
        return $statuses[array_rand($statuses)];
    }
    
    /**
     * Get a random payment method.
     *
     * @return string
     */
    private function getRandomPaymentMethod(): string
    {
        $methods = ['credit_card', 'paypal'];
        return $methods[array_rand($methods)];
    }
    
    /**
     * Get a random payment status.
     *
     * @return string
     */
    private function getRandomPaymentStatus(): string
    {
        $statuses = ['pending', 'paid', 'failed'];
        return $statuses[array_rand($statuses)];
    }
    
    /**
     * Get a random tracking number.
     *
     * @return string|null
     */
    private function getRandomTrackingNumber(): ?string
    {
        if (rand(0, 1)) {
            return 'TRK' . strtoupper(substr(md5(mt_rand()), 0, 10));
        }
        
        return null;
    }
    
    /**
     * Get a fake address.
     *
     * @return string
     */
    private function getFakeAddress(): string
    {
        $streets = ['123 Main St', '456 Oak Ave', '789 Pine Rd', '101 Maple Ln', '202 Cedar Blvd'];
        $cities = ['New York', 'Los Angeles', 'Chicago', 'Houston', 'Phoenix'];
        $states = ['NY', 'CA', 'IL', 'TX', 'AZ'];
        $zipCodes = ['10001', '90001', '60601', '77001', '85001'];
        
        $street = $streets[array_rand($streets)];
        $city = $cities[array_rand($cities)];
        $state = $states[array_rand($states)];
        $zipCode = $zipCodes[array_rand($zipCodes)];
        
        return "$street, $city, $state $zipCode";
    }
    
    /**
     * Get fake order notes.
     *
     * @return string
     */
    private function getFakeNotes(): string
    {
        $notes = [
            'Please leave the package at the front door.',
            'Call before delivery.',
            'This is a gift, please don\'t include the receipt.',
            'Please deliver after 5 PM.',
            'The doorbell is broken, please knock.',
        ];
        
        return $notes[array_rand($notes)];
    }
}