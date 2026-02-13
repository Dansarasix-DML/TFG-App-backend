<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject {
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'bio',
        'avatar',
        'banner',
        'telephone',
        'email',
        'password',
        'active',
        'token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function name(): Attribute {
        return new Attribute(
            set: fn($value) => ucwords($value)
        );
    }

    protected function username(): Attribute {
        return new Attribute(
            set: fn($value) => strtolower($value)
        );
    }

    protected function avatar(): Attribute {
        return new Attribute(
            set: fn($value) => strtolower($value)
        );
    }

    protected function banner(): Attribute {
        return new Attribute(
            set: fn($value) => strtolower($value)
        );
    }

    protected function email(): Attribute {
        return new Attribute(
            set: fn($value) => strtolower($value)
        );
    }

    protected function password(): Attribute {
        return new Attribute(
            set: fn($value) => Hash::make($value)
        );
    }

    public function blogger() {
        return $this->hasOne(User::class, 'user_id', 'id');
    }

    public function bloggers() {
        return $this->hasMany(Blog::class, 'blogger', 'id');
    }

    public function comments() {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }

    public function reviews() {
        return $this->hasMany(Review::class, 'user_id', 'id');
    }

    public function posts() {
        return $this->hasMany(Post::class, 'user_id', 'id');
    }

    public function wishList() {
        return $this->hasOne(Wish_List::class, 'user_id', 'id');
    }

    public function cart() {
        return $this->hasOne(Cart::class, 'user_id', 'id');
    }

    public function subscriptions() {
        return $this->hasMany(Subscription::class, 'user_id', 'id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

}
