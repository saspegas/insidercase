<?php

namespace App\Services\Repositories;

use App\Models\Message;
use Illuminate\Support\Collection;
use App\Services\Interfaces\MessageInterface;

class MessageRepository implements MessageInterface
{
    public function index(): Collection
    {
        return Message::all();
    }

    public function find(int $id): ?Message
    {
        return Message::find($id);
    }

    public function store(array $data): Message
    {
        return Message::create($data);
    }

    public function update(int $id, array $data): bool
    {
        return Message::find($id)->update($data);
    }

    public function delete(int $id): bool
    {
        return Message::destroy($id);
    }

    public function markAsSent(int $id): Message
    {
        $message = Message::find($id);
        $message->status = 'sent';
        $message->attempted_at = now();
        $message->save();

        return $message;
    }

    public function markAsFailed(int $id): Message
    {
        $message = Message::find($id);
        $message->status = 'failed';
        $message->attempted_at = now();
        $message->save();

        return $message;
    }

    public function markAsAttempted(int $id): Message
    {
        $message = Message::find($id);
        $message->status = 'attempted';
        $message->attempted_at = now();
        $message->save();

        return $message;
    }
}