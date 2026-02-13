<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;

class ReviewController extends Controller
{
    public function getUserReview($productSlug) {
        $user = auth()->user();

        $product = Product::where('slug', $productSlug)->first();

        $review = Review::where('user_id', $user->id)
        ->where('product_id', $product->id)->get();

        return $review;
    }

    public function addReview(Request $request) {
        $user = auth()->user();

        $product = Product::where('slug', $request->productSlug)->first();

        $review = new Review();
        $review->user_id = $user->id;
        $review->product_id = $product->id;
        $review->rating = $request->rating;
        $review->content = $request->content;
        $review->save();

        return $review;
        
    }

    public function editReview(Request $request) {

        $product = Product::where('slug', $request->productSlug)->first();

        $review = Review::where('id', $request->id)
                        ->where('product_id', $product->id)
                        ->first();
        $review->rating = $request->rating;
        $review->content = $request->content;
        $review->save();

        return $review;
    }

    public function deleteReview(Request $request) {
        $product = Product::where('slug', $request->productSlug)->first();

        $review = Review::where('id', $request->id)
                        ->where('product_id', $product->id)
                        ->first();
        $review->delete();

        return $review;
    }
}
