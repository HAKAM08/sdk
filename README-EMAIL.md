# Email Notification System

## Overview

This document provides information about the email notification system implemented in the Fishing Tackle Shop application. The system sends automated emails for the following events:

1. **Order Confirmation** - Sent when a customer completes a purchase
2. **Order Status Updates** - Sent when an admin changes the status of an order
3. **Welcome Emails** - Sent when a new user registers an account

## Configuration

### Environment Variables

To configure the email system, add the following variables to your `.env` file:

```
# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=hakammedtaha@gmail.com
MAIL_PORT=587
MAIL_USERNAME=hakammedtaha@gmail.com
MAIL_PASSWORD=zabeladitih
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@fishingtackleshop.com
MAIL_FROM_NAME="SDK-AquaPro"

# Queue Configuration (optional, for better performance)
QUEUE_CONNECTION=database
```

### Mail Driver Options

The application supports multiple mail drivers:

- **SMTP** - Standard email sending via SMTP server
- **Mailgun** - Email service by Mailgun
- **Postmark** - Email service by Postmark
- **Amazon SES** - Email service by Amazon
- **Log** - Logs emails to storage/logs (useful for development)

For production, we recommend using a dedicated email service like Mailgun, Postmark, or Amazon SES.

## Email Templates

Email templates are located in the following directories:

- `resources/views/emails/orders/confirmation.blade.php` - Order confirmation email
- `resources/views/emails/orders/status-update.blade.php` - Order status update email
- `resources/views/emails/welcome.blade.php` - Welcome email for new users

These templates use Laravel's Markdown mail component system, which allows for easy styling and formatting.

## Queue Configuration (Optional)

For better performance, emails are configured to use Laravel's queue system. To enable this:

1. Set `QUEUE_CONNECTION=database` in your `.env` file
2. Run the queue migration: `php artisan queue:table`
3. Run the migration: `php artisan migrate`
4. Start the queue worker: `php artisan queue:work`

In production, you should configure a process monitor like Supervisor to keep the queue worker running.

## Testing Emails

To test emails during development:

1. Set `MAIL_MAILER=log` in your `.env` file
2. Check the logs in `storage/logs/laravel.log` after triggering an email event

Alternatively, you can use a service like Mailtrap for testing emails in a development environment.