<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'slug',
        'banner_image',
        'content',
        'blog_id',
    ];

    protected function title(): Attribute {
        return new Attribute(
            set: fn($value) => ucwords($value)
        );
    }

    protected function subtitle(): Attribute {
        return new Attribute(
            set: fn($value) => ucwords($value)
        );
    }

    protected function slug(): Attribute {
        return new Attribute(
            set: fn($value) => strtolower($value)
        );
    }

    protected function banner_image(): Attribute {
        return new Attribute(
            set: fn($value) => strtolower($value)
        );
    }

    protected function content(): Attribute {
        return new Attribute(
            set: fn($value) => ucfirst($value)
        );
    }

    public function blog() {
        return $this->belongsTo(Blog::class, 'blog_id', 'id');
    }

    public function comments() {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }

    
}
