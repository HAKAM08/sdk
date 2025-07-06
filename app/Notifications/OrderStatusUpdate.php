<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusUpdate extends Notification
{
    // Removed ShouldQueue interface and Queueable trait to ensure immediate sending

    protected $order;
    protected $previousStatus;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order, string $previousStatus)
    {
        $this->order = $order;
        $this->previousStatus = $previousStatus;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $order = $this->order->load('items');
        
        return (new MailMessage)
            ->subject('Order Status Update - #' . $order->id)
            ->markdown('emails.orders.status-update', [
                'order' => $order,
                'previousStatus' => $this->previousStatus,
                'isAuthenticated' => auth()->check()
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'previous_status' => $this->previousStatus,
            'current_status' => $this->order->status,
        ];
    }
}