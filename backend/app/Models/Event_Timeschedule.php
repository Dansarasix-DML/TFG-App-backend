<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'date',
        'start_time',
        'end_time',
    ];

    public function event() {
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }
}
