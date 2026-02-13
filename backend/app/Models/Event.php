<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_id',
        'title',
        'subtitle',
        'slug',
        'banner_img',
        'content',
        'start_dtime',
        'end_dtime',
        'ubication',
        'section',
        'capacity',
    ];

    public function blog() {
        return $this->belongsTo(Blog::class, 'blog_id', 'id');
    }

    protected function slug(): Attribute {
        return new Attribute(
            set: fn($value) => strtolower($value)
        );
    }

    protected function banner_img(): Attribute {
        return new Attribute(
            set: fn($value) => strtolower($value)
        );
    }    
}
