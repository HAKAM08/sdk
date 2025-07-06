<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Notifications\OrderStatusUpdate;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index(Request $request)
    {
        $query = Order::with('user');
        
        // Search by order ID or customer name
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('id', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('user', function($userQuery) use ($searchTerm) {
                      $userQuery->where('name', 'like', '%' . $searchTerm . '%');
                  });
            });
        }
        
        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        // Filter by payment status
        if ($request->has('payment_status') && !empty($request->payment_status)) {
            $query->where('payment_status', $request->payment_status);
        }
        
        // Sort by column
        $sortColumn = $request->sort ?? 'created_at';
        $sortDirection = $request->direction ?? 'desc';
        
        // Validate sort column to prevent SQL injection
        $allowedColumns = ['id', 'created_at', 'total', 'status', 'payment_status'];
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'created_at';
        }
        
        $query->orderBy($sortColumn, $sortDirection === 'asc' ? 'asc' : 'desc');
        
        $orders = $query->paginate(10)->withQueryString();
        
        // Get available statuses for filter dropdown
        $statuses = [
            'pending' => 'Pending',
            'processing' => 'Processing',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled'
        ];
        
        $paymentStatuses = [
            'pending' => 'Pending',
            'paid' => 'Paid',
            'failed' => 'Failed',
            'refunded' => 'Refunded'
        ];
        
        return view('admin.orders.index', compact('orders', 'statuses', 'paymentStatuses'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit(Order $order)
    {
        $order->load(['user', 'items.product']);
        $statuses = [
            'pending' => 'Pending',
            'processing' => 'Processing',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled'
        ];
        
        $paymentStatuses = [
            'pending' => 'Pending',
            'paid' => 'Paid',
            'failed' => 'Failed',
            'refunded' => 'Refunded'
        ];
        
        return view('admin.orders.edit', compact('order', 'statuses', 'paymentStatuses'));
    }

    /**
     * Update the specified order in storage.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|string|in:pending,paid,failed,refunded',
            'tracking_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        // Store the previous status before updating
        $previousStatus = $order->status;
        
        $order->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status,
            'tracking_number' => $request->tracking_number,
            'notes' => $request->notes,
        ]);
        
        // Send notification if status has changed
        if ($previousStatus !== $request->status) {
            try {
                // Send notification immediately without queueing
                $order->user->notify(new OrderStatusUpdate($order, $previousStatus));
                \Illuminate\Support\Facades\Log::info('Order status update email sent successfully', ['order_id' => $order->id, 'user_email' => $order->user->email]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to send order status update email', [
                    'order_id' => $order->id,
                    'user_email' => $order->user->email,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                // Continue with order update process even if email fails
            }
        }

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order updated successfully.');
    }
}