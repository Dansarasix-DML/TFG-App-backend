<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'blogger',
        'profile_image',
        'banner_image',
    ];

    protected function title(): Attribute {
        return new Attribute(
            set: fn($value) => ucwords($value)
        );
    }

    protected function slug(): Attribute {
        return new Attribute(
            set: fn($value) => strtolower($value)
        );
    }

    protected function description(): Attribute {
        return new Attribute(
            set: fn($value) => ucfirst($value)
        );
    }

    // protected function blogger(): Attribute {
    //     return new Attribute(
    //         set: fn($value) => ucwords($value)
    //     );
    // }

    protected function profile_image(): Attribute {
        return new Attribute(
            set: fn($value) => strtolower($value)
        );
    }

    protected function banner_image(): Attribute {
        return new Attribute(
            set: fn($value) => strtolower($value)
        );
    }

    public function bloggers() {
        return $this->belongsTo(User::class, 'blogger', 'id');
    }

    public function posts() {
        return $this->hasMany(Post::class, 'blog_id', 'id');
    }

    public function products() {
        return $this->hasMany(Product::class, 'blog_id', 'id');
    }

    public function events() {
        return $this->hasMany(Event::class, 'blog_id', 'id');
    }

    public function subscriptions() {
        return $this->hasMany(Subscription::class, 'blog_id', 'id');
    }
}
