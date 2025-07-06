@component('mail::message')
# Order Status Update

Your order status has been updated.

**Order Number:** {{ $order->id }}
**Previous Status:** {{ ucfirst($previousStatus) }}
**New Status:** {{ ucfirst($order->status) }}

@if($order->status == 'shipped')
**Tracking Number:** {{ $order->tracking_number ?? 'Not available yet' }}
@endif

## Order Items

@component('mail::table')
| Product | Quantity | Price |
|:--------|:--------:|------:|
@foreach($order->items as $item)
| {{ $item->product_name }} | {{ $item->quantity }} | ${{ number_format($item->price, 2) }} |
@endforeach
@endcomponent

@if(isset($isAuthenticated) && $isAuthenticated)
@component('mail::button', ['url' => url('/user/orders/' . $order->id)])
View Order Details
@endcomponent
@else
@component('mail::button', ['url' => url('/checkout/success/' . $order->id)])
View Order Details
@endcomponent
@endif

Thank you for shopping with us!

Regards,<br>
{{ config('app.name') }}
@endcomponent