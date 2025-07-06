@component('mail::message')
# Welcome to {{ config('app.name') }}!

Thank you for registering with us. We're excited to have you as a member of our community!

## Your Account Benefits

* Browse our extensive catalog of fishing tackle and equipment
* Place orders easily and securely
* Track your order status
* Access exclusive deals and promotions
* Manage your account preferences

@component('mail::button', ['url' => url('/shop')])
Start Shopping
@endcomponent

@component('mail::panel')
If you have any questions or need assistance, please don't hesitate to contact our customer service team at {{ config('mail.from.address') }}.
@endcomponent

Happy fishing!

Regards,<br>
The {{ config('app.name') }} Team
@endcomponent