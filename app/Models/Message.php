<?php

namespace App\Models;

use App\Enums\MessageStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'recipient',
        'text',
        'status',
        'message_id',
        'sent_at',
    ];

    protected $casts = [
        'status' => MessageStatus::class,
    ];

    public function scopeNewMessages($query)
    {
        return $query->where('status', MessageStatus::New);
    }
}
