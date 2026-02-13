<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Order_Details;

class UserController extends Controller
{
    public function index($username) {

        $user = User::where('username', $username)->first();

        $blogs = $user->bloggers;

        return compact('user', 'blogs');
    }

    public function allProducts($username) {
        $user = User::where('username', $username)->first();

        foreach ($user->bloggers as $blog) {
            $games = $blog->products()->leftJoin('products_types', 'products.type_id', '=', 'products_types.id')
            ->select('products.*', 'products_types.name as typeName')->where('products_types.name', '!=', 'merchandising')
            ->orderBy('products.created_at', 'desc')->get();

            $merch = $blog->products()->leftJoin('products_types', 'products.type_id', '=', 'products_types.id')
            ->select('products.*', 'products_types.name as typeName')->where('products_types.name', '=', 'merchandising')
            ->orderBy('products.created_at', 'desc')->get();
        }

        return response()->json(compact('games', 'merch'), 200, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
    }

    public function getOrders() {
        $user = auth()->user();

        $orders = Order::where('user_id', $user->id)->get();

        $big_array = [];
        
        foreach($orders as $order) {
            $details = Order_Details::with('product')
                ->where('order_id', $order->id)
                ->get();

            array_push($big_array, [
                'order' => $order,
                'order_details' => $details
            ]);
        }

        return $big_array;
    }

    public function getOrderDetails(Request $request) {
        $user = auth()->user();

        $order_id = $request->input('order_id');

        if (!$order_id) {
            return response()->json(['error' => "No hay ningún order id"], 404);
        }

        $order = Order::where('id', $order_id)->first();

        if (!$order) {
            return response()->json(['error' => "No hay ningún order con ese id"], 404);
        }

        if ($order->user_id != $user->id) {
            return response()->json(['error' => "Este pedido no corresponde a este usuario"], 401);
        }

        $order_details = Order_Details::with('product')->where('order_id', $order->id)->get();
        
        return response()->json([
            'order' => $order,
            'order_details' => $order_details,
        ]);
    }
}
