<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'picture',
        'description',
        'location',
        'start_time',
        'end_time',
        'organizer_id'
    ];

    protected $hidden = [
        'organizer_id',
        "created_at",
        "updated_at",
    ];

    public function organizer(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
