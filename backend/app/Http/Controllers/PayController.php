<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\User;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Order;
use App\Models\Order_Details;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewOrder;


class PayController extends Controller {
    public function checkout(Request $request) {
        $email = base64_decode($request->input('user_email'));

        $user = User::where('email', $email)->first();
        
        if (!$user) {
            return redirect(env('FRONTEND_URL')."login", 301);
        }

        // Obtener Productos y crear Stripe Prices

        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        $cart = Cart::where('user_id', $user->id)->first();

        $products = array_merge(explode(",", $cart->products), explode(",", $cart->events));

        $products_count = array_count_values($products);

        $big_array = [];

        foreach ($products_count as $key => $count) {
            if ($key != "") {
                array_push($big_array, [
                    "id" => $key,
                    "count" => $count,
                    "product" => Product::where('id', $key)->first(), 
                    "stripe_price" => NULL
                ]);
            }
        }

        foreach ($big_array as &$product) {
            $prodPrice = 0;

            if ($product["product"]["sale_price"] != 0) {
                $prodPrice = $product["product"]["sale_price"];
            } else {
                $prodPrice = $product["product"]["price"];
            }

            $product["stripe_price"] = $stripe->prices->create([
                'currency' => 'eur',
                'unit_amount' => $prodPrice * 100,
                'product_data' => ['name' => $product["product"]["name"]],
              ]);
        }

        $prices = [];

        foreach ($big_array as $pr2) {
            $new = [
                "price" => $pr2["stripe_price"]["id"],
                "quantity" => $pr2["count"],
                'tax_rates' => ['txr_1PPk4zIQ3pO5zYqfpBZyEu7u'],
            ];
            array_push($prices, $new);
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Crea una sesión de Checkout en Stripe
        try {
            $checkout_session = Session::create([
                'line_items' => [$prices],
                'customer_email' => $user->email,
                'billing_address_collection' => 'required',
                'shipping_address_collection' => [
                  'allowed_countries' => ['ES'],
                ],
                'mode' => 'payment',
                'success_url' => env('FRONTEND_URL') . '/shop/checkout/finish?success=true&session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => env('FRONTEND_URL') . '/shop/checkout/finish?canceled=true&session_id={CHECKOUT_SESSION_ID}',
            ]);

            // Redirige al usuario a la URL de la sesión de Checkout
            return redirect($checkout_session->url, 303);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function getCheckoutData(Request $request) {
        $user = auth()->user();
        $checkout = $request->input('checkout');

        $orderExistent = Order::where('session_id', $checkout)->first();

        if (!$checkout) {
            return response()->json(['error' => "No hay ningún checkout id"], 406);
        } 
        
        if (!$user) {
            return response()->json(['error' => "No hay ningún usuario id"], 401);
        } 
        
        if ($orderExistent) {
            return response()->json(['error' => "Ese pago ya ha sido agregado", 'order' => $orderExistent], 406);
        }

        $cart = Cart::where('user_id', $user->id)->first();

        if (!$checkout) {
            return response()->json(['error' => "No hay ningún carrito"], 406);
        }

        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        $sessionChekoutObj = $stripe->checkout->sessions->retrieve(
            $checkout,
            []
          );

        if ($sessionChekoutObj['status'] != 'complete' || $sessionChekoutObj['payment_status'] != 'paid') {
            return response()->json(['error' => "No se ha realizado el pago correctamente"], 402);
        }

        $address = $sessionChekoutObj->customer_details->address;

        $order = new Order();
        $order->datetime = date('Y-m-d H:i:s');
        $order->user_id = $user->id;
        $order->price = $sessionChekoutObj->amount_subtotal / 100;
        $order->tax = $sessionChekoutObj->total_details->amount_tax / 100;
        $order->total = $sessionChekoutObj->amount_total / 100;
        $order->status = "Pagado";
        $order->session_id = $sessionChekoutObj->id;
        $order->ship_name = $sessionChekoutObj->customer_details->name;
        $order->ship_address = implode(";", [$address->city, $address->country, $address->line1, $address->line2, $address->postal_code, $address->state]);
        $order->save();

        $products = array_merge(explode(",", $cart->products), explode(",", $cart->events));

        $products_count = array_count_values($products);

        foreach ($products_count as $key => $count) {
            if ($key != "") {
                $prod = Product::where('id', $key)->first();

                $prodPrice = 0;

                if ($prod->sale_price != 0) {
                    $prodPrice = $prod->sale_price;
                } else {
                    $prodPrice = $prod->price;
                }

                $order_details = new Order_Details();
                $order_details->order_id = $order->id;
                $order_details->product_id = $key;
                $order_details->quantity = $count;
                $order_details->unitary_price = $prodPrice;
                $order_details->discount_price = 0;
                $order_details->save();
            }
        }

        $cart->delete();

        Mail::to($user)
            ->send(new NewOrder($user, $order));

        return response()->json($order);
    }
}
