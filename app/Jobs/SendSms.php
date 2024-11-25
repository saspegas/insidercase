<?php

namespace App\Jobs;

use App\Services\Interfaces\SendSmsServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private SendSmsServiceInterface $sendSmsService,
        private string $phone,
        private string $message
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void {
        $message_id = $this->sendSmsService->send($this->phone, $this->message);
        
        // Log the message_id
    }
}
