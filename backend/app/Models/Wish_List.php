<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wish_List extends Model
{
    use HasFactory;

    protected $table = 'wish_lists';

    protected $fillable = [
        'user_id',
        'products',
        'events',
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
