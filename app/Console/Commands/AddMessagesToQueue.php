<?php

namespace App\Console\Commands;

use App\Jobs\SendSms;
use Illuminate\Console\Command;
use App\Services\Interfaces\MessageInterface;
use App\Services\Repositories\MessageRepository;
use App\Services\Interfaces\SendSmsServiceInterface;

class AddMessagesToQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-messages-to-queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adding new messages to the queue';

    /**
     * @var MessageInterface
     */
    protected $repository;

    /**
     * @var SendSmsServiceInterface
     */
    protected $sendSmsService;

    /**
     * Create a new command instance.
     *
     * @param MessageRepository $repository
     * @return void
     */
    public function __construct(MessageInterface $repository, SendSmsServiceInterface $sendSmsService)
    {
        parent::__construct();
        $this->repository = $repository;
        $this->sendSmsService = $sendSmsService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $messagesToQueue = $this->repository->getMessagesToQueue();

        $messagesToQueue->each(function ($message) {
            SendSms::dispatch($this->sendSmsService, $message->id, $message->recipient, $message->text)->onQueue('sms');
        });

        $count = $this->repository->setMessagesAsQueued();

        $this->info("Added {$count} messages to the queue");
    }
}
