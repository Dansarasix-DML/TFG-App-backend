<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'datetime',
        'user_id',
        'price',
        'tax',
        'total',
        'status',
        'session_id'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}