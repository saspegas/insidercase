<?php

namespace App\Jobs;

use App\Services\Interfaces\MessageInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Redis;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateSentSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private int $model_id
    ) {}

    /**
     * Execute the job.
     */
    public function handle(MessageInterface $messageRepository): void
    {
        $message_id = Redis::get("sms:{$this->model_id}:message_id");
        $sent_at = Redis::get("sms:{$this->model_id}:sent_at");

        $messageRepository->update($this->model_id, [
            'status' => 'sent',
            'message_id' => $message_id,
            'sent_at' => $sent_at,
        ]);

        Redis::del("sms:{$this->model_id}:message_id");
        Redis::del("sms:{$this->model_id}:sent_at");
    }
}
