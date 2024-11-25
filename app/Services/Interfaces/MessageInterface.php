<?php

namespace App\Services\Interfaces;

use App\Models\Message;
use Illuminate\Support\Collection;

interface MessageInterface
{
    public function index(): Collection;

    public function find(int $id): ?Message;

    public function store(array $data): Message;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function markAsSent(int $id): Message;

    public function getMessagesToQueue(): Collection;

    public function setMessagesAsQueued(): int;
}