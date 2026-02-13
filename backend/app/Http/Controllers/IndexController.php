<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Post;

class IndexController extends Controller
{
    function index() {

        return view('welcome');
    }

    function lastPosts() {
        $posts = Post::leftJoin('blogs', 'posts.blog_id', '=', 'blogs.id')
                ->leftJoin('users', 'blogs.blogger', '=', 'users.id')
                ->select('posts.*', 'blogs.slug as blogslug', "users.username as blogger", "users.avatar")
                ->orderBy('created_at', 'desc')->take(10)->get();

        return compact('posts');
    }

    function lastBlogs() {
        $blogs = Blog::leftJoin('users', 'blogs.blogger', '=', 'users.id')
                ->select('blogs.*', "users.username", "users.avatar")
                ->orderBy('updated_at', 'desc')->take(10)->get();

        // $posts = Post::orderBy('created_at', 'desc')->take(10)->get();

        $users = $blogs->map(function($blog) {
            return $blog->bloggers;
        });

        return compact('blogs', 'users');
    }
}
