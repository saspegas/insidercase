<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Redis;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Interfaces\SendSmsServiceInterface;

class SendSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private SendSmsServiceInterface $sendSmsService,
        private int $model_id,
        private string $phone,
        private string $message
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void {
        $message_id = $this->sendSmsService->send($this->phone, $this->message);
        $queue = 'attempted_sms';

        if ($message_id) {
            Redis::set("sms:{$this->model_id}:message_id", $message_id);
            Redis::set("sms:{$this->model_id}:sent_at", now()->toDateTimeString());
            
            UpdateSentSms::dispatch($this->model_id)->onQueue($queue);
        } else {
            Redis::set("sms:{$this->model_id}:error", 'Failed to send SMS');

            UpdateFailedSms::dispatch($this->model_id)->onQueue($queue);
        }
    }
}
