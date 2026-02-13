<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;


class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content',
        'post_id',
        'parent_id'
    ];

    protected function content(): Attribute {
        return new Attribute(
            set: fn($value) => ucfirst($value)
        );
    }

    public function post() {
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }

    public function parent() {
        return $this->belongsTo(Comment::class, 'parent_id', 'id');
    }

    public function replies() {
        return $this->hasMany(Comment::class, 'parent_id', 'id');
    }

}
