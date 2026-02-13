<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\Blog;

class SubscriptionController extends Controller
{
    public function subscribe(Request $request) {
        $user = auth()->user();
        $blog = Blog::find($request->input('blogId'));
        
        $subscription = Subscription::where('user_id', $user->id)
        ->where('blog_id', $blog->id)->first();

        $sub_state = null;

        if (!$subscription) {
            $subscription = new Subscription();
            $subscription->user_id = $user->id;
            $subscription->blog_id = $blog->id;
            $subscription->save();
            $sub_state = true;
        } else {
            $subscription->delete();
            $sub_state = false;
        }

        return response()->json(compact('sub_state'), 200, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);;

    }

    public function getSubs(Request $request) {
        $user = auth()->user();
        $blog = Blog::find($request->input('blogId'));
        $subscription = Subscription::where('user_id', $user->id)
        ->where('blog_id', $blog->id)->first();

        $sub = (!$subscription) ? false : true;

        return response()->json(compact('sub'), 200, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
    }

    public function getSubscribers($blogId) {
        $blog = Blog::find($blogId);
        
        $subs = Subscription::where('blog_id', $blog->id)->get();

        $subscribers = count($subs);

        return response()->json(compact('subscribers'), 200, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
        // return $subscribers;
        
    }
}
