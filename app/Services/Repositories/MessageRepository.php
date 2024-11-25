<?php

namespace App\Services\Repositories;

use App\Enums\MessageStatus;
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

    public function getMessagesToQueue(): Collection
    {
        return Message::newMessages()->get();
    }

    public function setMessagesAsQueued(): int
    {
        return Message::newMessages()->update(['status' => MessageStatus::Queued]);
    }
}