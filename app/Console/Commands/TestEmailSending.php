<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Mail\Message;

class TestEmailSending extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {recipient}'; 

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email sending functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $recipient = $this->argument('recipient');
        
        $this->info("Attempting to send test email to {$recipient}...");
        
        try {
            Mail::raw('This is a test email from Fishing Tackle Shop to verify email functionality.', function (Message $message) use ($recipient) {
                $message->to($recipient)
                    ->subject('Fishing Tackle Shop - Email Test');
            });
            
            $this->info("Test email sent successfully to {$recipient}!");
            Log::info("Test email sent successfully", ['recipient' => $recipient]);
        } catch (\Exception $e) {
            $this->error("Failed to send test email: {$e->getMessage()}");
            Log::error("Failed to send test email", [
                'recipient' => $recipient,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}