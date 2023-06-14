<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected  $table = 'messages';

    protected $fillable = [
        'chat_id',
        'receiver_id',
        'message',
        'images',
        'files',
        'is_seen'
    ];

    protected $casts = [
        'images'=>'array',
        'files'=>'array',
        'is_seen'=>'boolean'
    ];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
