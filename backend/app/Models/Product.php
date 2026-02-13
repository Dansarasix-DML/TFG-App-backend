<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'categories',
        'slug',
        'imgs',
        'price',
        'quantity',
        'blog_id',
        'purchase_price',
        'sale_price',
        'taxes_porcent',
    ];

    public function blog() {
        return $this->belongsTo(Blog::class, 'blog_id', 'id');
    }

    public function type() {
        return $this->belongsTo(Product_Type::class, 'type_id', 'id');
    }

    public function reviews() {
        return $this->hasMany(Review::class, 'product_id', 'id');
    }

    // Método recursivo para calcular el rating total
    public function calculateTotalRating() {
        return $this->calculateRatingRecursive($this->reviews);
    }

    private function calculateRatingRecursive($reviews) {
        if ($reviews->isEmpty()) {
            return 0;
        }

        $totalRating = 0;

        foreach ($reviews as $review) {
            $totalRating += $review->rating;
        }

        return $totalRating / count($reviews);
    }
}
