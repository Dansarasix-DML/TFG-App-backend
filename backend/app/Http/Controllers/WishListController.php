<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wish_List;
use App\Models\Product;

class WishListController extends Controller
{
    public function index() {
        $user = auth()->user();
        
        $wish_list = Wish_List::where('user_id', $user->id)->first();

        if (!$wish_list) {
            $wish_list = new Wish_List();
            $wish_list->user_id = $user->id;
            $wish_list->products = '';
            $wish_list->save();
        }

        $products_ids = $wish_list->getProducts();

        $products = [];

        foreach ($products_ids as $product_id) {
            $product = Product::find($product_id);
            if ($product) {
                $products[] = $product;
            }
        }

        return $products;
    }

    public function addProduct(Request $request) {
        $user = auth()->user();
        $product = Product::where('slug', $request->input('productSlug'))->first();

        $wish_list = Wish_List::where('user_id', $user->id)->first();

        if (!$wish_list) {
            $wish_list = new Wish_List();
            $wish_list->user_id = $user->id;
            $wish_list->products = '';
            $wish_list->save();
        }

        $products = $wish_list->products;

        if ($products == '') {
            $products = $product->id;
        } else {
            
            if (in_array($product->id, explode(',', $products))) {
                return $wish_list->getProducts();
            }

            $products = $products . ',' . $product->id;
        }

        $wish_list->products = $products;
        $wish_list->save();

        return $wish_list->getProducts();
    }

    public function removeProduct(Request $request) {
        $user = auth()->user();
        $product = Product::where('slug', $request->input('productSlug'))->first();

        $wish_list = Wish_List::where('user_id', $user->id)->first();

        $products = $wish_list->products;

        $products = explode(',', $products);

        $new_products = [];

        foreach ($products as $product_id) {
            if ($product_id != $product->id) {
                $new_products[] = $product_id;
            }
        }

        $new_products = implode(',', $new_products);

        $wish_list->products = $new_products;
        $wish_list->save();

        return $wish_list->getProducts();
    }
}
