<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'user_id',
        'products',
        'total_products',
        'events',
        'total_events',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getProducts() {
        return array_reverse(explode(',', $this->products));
    }

    public function getEvents() {
        return array_reverse(explode(',', $this->events));
    }
}
