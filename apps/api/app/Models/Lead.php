<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'status',
        'source',
        'utm_json',
        'ip_address',
        'user_agent',
        'attachments',
        'processed_at',
        'note',
    ];

    protected $casts = [
        'utm_json' => 'array',
        'attachments' => 'array',
        'processed_at' => 'datetime',
    ];

    protected $attributes = [
        'attachments' => '[]', 
    ];
}
