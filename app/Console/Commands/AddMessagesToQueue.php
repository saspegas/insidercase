<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Interfaces\MessageInterface;
use App\Services\Repositories\MessageRepository;

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
     * Create a new command instance.
     *
     * @param MessageRepository $repository
     * @return void
     */
    public function __construct(MessageInterface $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $messagesToQueue = $this->repository->getMessagesToQueue();

        // add messages to the queue
    }
}
