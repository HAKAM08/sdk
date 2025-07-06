<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderConfirmation;
use App\Notifications\OrderStatusUpdate;
use App\Notifications\WelcomeNotification;
use Illuminate\Console\Command;

class TestEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {type=all : The type of email to test (welcome, order, status, all)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->argument('type');
        $user = User::first();
        
        if (!$user) {
            $this->error('No users found in the database. Please create a user first.');
            return 1;
        }
        
        $this->info('Testing email notifications with user: ' . $user->email);
        
        if ($type === 'welcome' || $type === 'all') {
            $this->testWelcomeEmail($user);
        }
        
        if ($type === 'order' || $type === 'all') {
            $this->testOrderConfirmationEmail($user);
        }
        
        if ($type === 'status' || $type === 'all') {
            $this->testOrderStatusUpdateEmail($user);
        }
        
        $this->info('Email tests completed. Check your configured mail driver for results.');
        return 0;
    }
    
    /**
     * Test welcome email notification.
     */
    private function testWelcomeEmail(User $user)
    {
        $this->info('Sending welcome email to ' . $user->email);
        $user->notify(new WelcomeNotification());
        $this->info('Welcome email sent.');
    }
    
    /**
     * Test order confirmation email notification.
     */
    private function testOrderConfirmationEmail(User $user)
    {
        $order = Order::where('user_id', $user->id)->first();
        
        if (!$order) {
            $this->warn('No orders found for this user. Skipping order confirmation email test.');
            return;
        }
        
        $this->info('Sending order confirmation email for order #' . $order->id);
        $user->notify(new OrderConfirmation($order));
        $this->info('Order confirmation email sent.');
    }
    
    /**
     * Test order status update email notification.
     */
    private function testOrderStatusUpdateEmail(User $user)
    {
        $order = Order::where('user_id', $user->id)->first();
        
        if (!$order) {
            $this->warn('No orders found for this user. Skipping order status update email test.');
            return;
        }
        
        $currentStatus = $order->status;
        $newStatus = $currentStatus === 'pending' ? 'processing' : 'pending';
        
        $this->info('Sending order status update email for order #' . $order->id);
        $this->info('Status change: ' . $currentStatus . ' -> ' . $newStatus);
        
        $user->notify(new OrderStatusUpdate($order, $currentStatus));
        $this->info('Order status update email sent.');
    }
}