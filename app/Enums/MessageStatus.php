<?php

namespace App\Enums;
 
enum MessageStatus: string
{
    case New = 'new';
    case Queued = 'queued';
    case Sent = 'sent';
    case Failed = 'failed';
}