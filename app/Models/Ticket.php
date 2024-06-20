<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'ticket_code',
        'status',
        'qr_img',
    ];

    protected $hidden = [
        "ticket_code",
        "created_at",
        "updated_at",
    ];

    public function event(): BelongsTo {
        return $this->belongsTo(Event::class, 'event_id');
    }
    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }
}
