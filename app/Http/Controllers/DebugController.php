<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderConfirmation;
use App\Notifications\OrderStatusUpdate;
use App\Notifications\WelcomeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\View\View;

class DebugController extends Controller
{
    /**
     * Show the email testing interface
     */
    public function index(): View
    {
        return view('debug.email-test');
    }
    
    /**
     * Test email functionality
     */
    public function testEmail(Request $request)
    {
        // Get current mail configuration
        $mailConfig = [
            'driver' => Config::get('mail.default'),
            'host' => Config::get('mail.mailers.smtp.host'),
            'port' => Config::get('mail.mailers.smtp.port'),
            'encryption' => Config::get('mail.mailers.smtp.encryption'),
            'username' => Config::get('mail.mailers.smtp.username'),
            'from_address' => Config::get('mail.from.address'),
            'from_name' => Config::get('mail.from.name'),
        ];
        
        // Get the authenticated user or first user in the system
        $user = auth()->user() ?? User::first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No users found in the system to test email.',
                'config' => $mailConfig
            ]);
        }
        
        $type = $request->input('type', 'raw');
        $result = [];
        
        try {
            switch ($type) {
                case 'welcome':
                    $user->notify(new WelcomeNotification());
                    $result['message'] = 'Welcome email notification sent';
                    break;
                    
                case 'order':
                    // Get the latest order or create a dummy one
                    $order = Order::where('user_id', $user->id)->latest()->first();
                    if (!$order) {
                        $result['warning'] = 'No order found for this user. Using a dummy order.';
                        $order = new Order([
                            'id' => 999,
                            'user_id' => $user->id,
                            'total' => 99.99,
                            'status' => 'pending',
                            'created_at' => now(),
                            'shipping_address' => '123 Test St, Test City',
                            'billing_address' => '123 Test St, Test City',
                            'payment_method' => 'credit_card'
                        ]);
                    }
                    $user->notify(new OrderConfirmation($order));
                    $result['message'] = 'Order confirmation email notification sent';
                    break;
                    
                case 'status':
                    // Get the latest order or create a dummy one
                    $order = Order::where('user_id', $user->id)->latest()->first();
                    if (!$order) {
                        $result['warning'] = 'No order found for this user. Using a dummy order.';
                        $order = new Order([
                            'id' => 999,
                            'user_id' => $user->id,
                            'total' => 99.99,
                            'status' => 'shipped',
                            'created_at' => now(),
                            'shipping_address' => '123 Test St, Test City',
                            'billing_address' => '123 Test St, Test City',
                            'payment_method' => 'credit_card'
                        ]);
                    }
                    $user->notify(new OrderStatusUpdate($order, 'pending'));
                    $result['message'] = 'Order status update email notification sent';
                    break;
                    
                case 'raw':
                default:
                    Mail::raw('This is a test email from Fishing Tackle Shop to verify email functionality.', function ($message) use ($user) {
                        $message->to($user->email)
                            ->subject('Fishing Tackle Shop - Email Test');
                    });
                    $result['message'] = 'Raw test email sent';
                    break;
            }
            
            Log::info('Test email sent successfully', ['type' => $type, 'user' => $user->email]);
            
            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'warning' => $result['warning'] ?? null,
                'recipient' => $user->email,
                'config' => $mailConfig
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to send test email', [
                'type' => $type,
                'user' => $user->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'config' => $mailConfig
            ], 500);
        }
    }
}