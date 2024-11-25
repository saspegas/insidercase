<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Redis;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Interfaces\MessageInterface;

class UpdateFailedSms implements ShouldQueue
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
        $messageRepository->update($this->model_id, [
            'status' => 'failed',
        ]);

        Redis::del("sms:{$this->model_id}:error");
    }
}
