<?php

namespace App\Observers;

use App\Models\Message;
use Instasent\SMSCounter\SMSCounter;

class MessageObserver
{
    public function saving(Message $message): void
    {
        $smsCounter = new SMSCounter();

        $message->parts_count = $smsCounter->count($message->text)->messages;
    }
}
