@component('mail::message')
# Order Confirmation

Thank you for your order!

**Order Number:** {{ $order->id }}
**Order Date:** {{ $order->created_at->format('F j, Y') }}
**Order Total:** ${{ number_format($order->total, 2) }}

## Order Items

@component('mail::table')
| Product | Quantity | Price |
|:--------|:--------:|------:|
@foreach($order->items as $item)
| {{ $item->product_name }} | {{ $item->quantity }} | ${{ number_format($item->price, 2) }} |
@endforeach
@endcomponent

## Shipping Information
{{ $order->shipping_address }}

## Billing Information
{{ $order->billing_address }}

## Payment Method
{{ ucfirst($order->payment_method) }}

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