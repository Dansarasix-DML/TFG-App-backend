<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;

class CartController extends Controller
{
    public function index() {
        $user = auth()->user();
        
        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart) {
            $cart = new Cart();
            $cart->user_id = $user->id;
            $cart->products = '';
            $cart->total_products = 0;
            $cart->events = '';
            $cart->total_events = 0;
            $cart->save();
        }

        $products_ids = $cart->getProducts();

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

        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart) {
            $cart = new Cart();
            $cart->user_id = $user->id;
            $cart->products = '';
            $cart->total_products = 0;
            $cart->events = '';
            $cart->total_events = 0;
            $cart->save();
        }

        $products = $cart->products;
        $total = $cart->total_products;

        if ($products == '') {
            $products = $product->id;
            $total = $product->sale_price;
        } else {
            $products = $products . ',' . $product->id;
            $total = $total + $product->sale_price;
        }

        $cart->products = $products;
        $cart->total_products = $total;
        $cart->save();

        return $cart->getProducts();
    }

    public function removeProduct(Request $request) {
        $user = auth()->user();
        $product = Product::where('slug', $request->input('productSlug'))->first();

        $cart = Cart::where('user_id', $user->id)->first();

        $products = $cart->products;

        $products = explode(',', $products);

        $new_products = [];
        $new_total = 0;

        foreach ($products as $product_id) {
            if ($product_id != $product->id) {
                $new_products[] = $product_id;
                $new_total = $new_total + Product::find($product_id)->sale_price;
            }
        }

        $cart->products = implode(',', $new_products);
        $cart->total_products = $new_total;
        $cart->save();

        return $cart->getProducts();
    }

    public function removeCartProduct(Request $request) {
        $user = auth()->user();
        $product = Product::where('slug', $request->input('productSlug'))->first();

        $cart = Cart::where('user_id', $user->id)->first();

        $products = $cart->products;

        $products = explode(',', $products);

        $new_products = [];
        $removed = false;
        $new_total = 0;

        foreach ($products as $product_id) {
            if ($product_id == $product->id && !$removed) { // Solo elimina la primera coincidencia
                $removed = true;  // Evita eliminar duplicados posteriores
            } else {
                $new_products[] = $product_id;
                $new_total += Product::find($product_id)->sale_price;
            }
        }

        $cart->products = implode(',', $new_products);
        $cart->total_products = $new_total;
        $cart->save();

        return $cart->getProducts();
    }

    public function getCartProductsIds() {
        $user = auth()->user();
        
        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart) {
            $cart = new Cart();
            $cart->user_id = $user->id;
            $cart->products = '';
            $cart->total_products = 0;
            $cart->events = '';
            $cart->total_events = 0;
            $cart->save();
        }

        $products_ids = $cart->getProducts();

        return $products_ids;
    }
    
}
