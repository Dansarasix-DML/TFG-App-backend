<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Models\Blog;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Models\Subscription;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewPost;


class BlogController extends Controller
{
    public function lastBlogs() {
        $blogs = Blog::select('blogs.id',
            \DB::raw('substring(blogs.title,1,30) as title'),
            'blogs.slug',
            'blogs.description',
            'blogs.blogger',
            'blogs.profile_img',
            'blogs.banner_img',
            'users.name',
            'users.username'
        )
        ->orderBy('blogs.updated_at', 'desc')
        ->take(6)
        ->join('users', 'blogs.blogger', '=', 'users.id')
        ->get();

        return $blogs;
    }

    public function getBlogInfo($blogSlug) {
        $blog = Blog::select('blogs.id',
                \DB::raw('substring(blogs.title,1,30) as title'),
                'blogs.slug',
                'blogs.description',
                'blogs.blogger',
                'blogs.profile_img',
                'blogs.banner_img',
                'users.name as user_name',
                'users.username as user_username'
            )->join('users', 'blogs.blogger', '=', 'users.id')
            ->where('slug', $blogSlug)->first();

        return response()->json(compact('blog'), 200, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);

    }

    public function getBlogs() {

        $blogs = Blog::select('blogs.id',
            \DB::raw('substring(blogs.title,1,30) as title'),
            'blogs.slug',
            'blogs.description',
            'blogs.blogger',
            'blogs.profile_img',
            'blogs.banner_img',
            'users.name as blogger_name',
            'users.username as blogger_username'
        )
        ->join('users', 'blogs.blogger', '=', 'users.id')
        ->orderBy('blogs.updated_at', 'desc')
        ->get();

        foreach ($blogs as $blog) {
            $blog->total_posts = $blog->posts()->count();
            $blog->total_products = $blog->products()->count();
            $blog->total_events = $blog->events()->count();
        }

        return compact('blogs');
    }

    public function search(Request $request) {
        $searchTerm = $request->search;
        $search = Blog::query()
                ->leftJoin('users', 'blogs.blogger', '=', 'users.id')
                ->leftJoin(DB::raw('(SELECT blog_id, COUNT(*) as total_posts FROM posts GROUP BY blog_id) as post_counts'), 'blogs.id', '=', 'post_counts.blog_id')
                ->leftJoin(DB::raw('(SELECT blog_id, COUNT(*) as total_products FROM products GROUP BY blog_id) as product_counts'), 'blogs.id', '=', 'product_counts.blog_id')
                ->leftJoin(DB::raw('(SELECT blog_id, COUNT(*) as total_events FROM events GROUP BY blog_id) as event_counts'), 'blogs.id', '=', 'event_counts.blog_id')
                ->select(
                    'blogs.*',
                    'users.name as blogger_name',
                    DB::raw('IFNULL(post_counts.total_posts, 0) as total_posts'),
                    DB::raw('IFNULL(product_counts.total_products, 0) as total_products'),
                    DB::raw('IFNULL(event_counts.total_events, 0) as total_events')
                )
                ->where(function($query) use ($searchTerm) {
                    $query->where('blogs.title', 'like', '%'.$searchTerm.'%')
                        ->orWhere('blogs.description', 'like', '%'.$searchTerm.'%')
                        ->orWhere('users.name', 'like', '%'.$searchTerm.'%')
                        ->orWhere(DB::raw('IFNULL(post_counts.total_posts, 0)'), 'like', '%'.$searchTerm.'%')
                        ->orWhere(DB::raw('IFNULL(product_counts.total_products, 0)'), 'like', '%'.$searchTerm.'%')
                        ->orWhere(DB::raw('IFNULL(event_counts.total_events, 0)'), 'like', '%'.$searchTerm.'%');
                })
                ->orderBy('blogs.title', 'asc')
                ->orderBy('blogs.updated_at', 'desc')
                ->get();

        return response()->json(compact('search'), 200, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
    }

    public function lastPostsIndex() {
        $posts = Post::select(
            'posts.id',
            \DB::raw('substring(posts.title,1,50) as title'),
            \DB::raw('substring(posts.subtitle,1,50) as subtitle'),
            'posts.slug',
            'posts.banner_img',
            'posts.content',
            'posts.summary',
            'posts.blog_id',
            \DB::raw('substring(b.title,1,20) as blog_title'),
            \DB::raw('substring(b.slug,1,200) as blog_slug'),
            'b.profile_img as blog_img'
        )
        ->orderBy('posts.updated_at', 'desc')
        ->take(6)
        ->join('blogs as b', 'posts.blog_id', '=', 'b.id')
        ->get();

        return $posts;
    }

    public function lastPosts($blogSlug) {

        $blog = Blog::where('slug', $blogSlug)->first();

        if (!$blog) {
            // Manejar el caso donde el blog no existe
            // Puedes lanzar una excepción, devolver un array vacío o manejarlo de otra manera
            return response()->json(['error' => 'Blog not found'], 404);
        }

        $posts = $blog->posts()->orderBy('created_at', 'desc')->take(10)->get();

        return $posts;
    }

    public function lastPostsByBlogSlug($blogSlug) {
        // Primero, obtenemos el blog basado en el slug
        $blog = Blog::where('slug', $blogSlug)->first();

        // Si no se encuentra el blog, devolvemos null o manejamos el error según sea necesario
        if (!$blog) {
            return null;
        }

        // Ahora, seleccionamos los posts de ese blog con los campos específicos y limitamos la cantidad de resultados
        $posts = Post::select(
                'posts.id',
                \DB::raw('substring(posts.title,1,50) as title'),
                \DB::raw('substring(posts.subtitle,1,50) as subtitle'),
                'posts.slug',
                'posts.banner_img',
                'posts.content',
                'posts.blog_id',
                \DB::raw('substring(b.title,1,20) as blog_title'),
                'b.profile_img as blog_img'
            )
            ->where('posts.blog_id', $blog->id)
            ->join('blogs as b', 'posts.blog_id', '=', 'b.id')
            ->orderBy('posts.created_at', 'desc')
            ->get();

        return $posts;
    }

    public function getGames($blogSlug) {
        $blog = Blog::where('slug', $blogSlug)->first();

        $games = $blog->products()->leftJoin('products_types', 'products.type_id', '=', 'products_types.id')
        ->select('products.*', 'products_types.name as typeName')->where('products_types.name', '!=', 'merchandising')
        ->orderBy('products.created_at', 'desc')->get();

        return response()->json(compact('games'), 200, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);;
    }

    public function getMerch($blogSlug) {
        $blog = Blog::where('slug', $blogSlug)->first();

        $merch = $blog->products()->leftJoin('products_types', 'products.type_id', '=', 'products_types.id')
        ->select('products.*', 'products_types.name as typeName')->where('products_types.name', '=', 'merchandising')
        ->orderBy('products.created_at', 'desc')->get();

        return response()->json(compact('merch'), 200, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);;
    }

    public function searchPost($blogslug, $postslug) {
        $blog = Blog::where('slug', $blogslug)->first();

        $post = $blog->posts()->where('slug', $postslug)->first();

        $blogger = $blog->bloggers()->first();

        $comments = $post->comments()->leftJoin('users', 'comments.user_id', '=', 'users.id')
            ->select('comments.*', 'users.name as user_name', 'users.username as user_username',
            'users.avatar as user_avatar')->orderBy('comments.created_at', 'desc')->get();

        return compact('blogger', 'post', 'comments');

    }

    public function userBlogs() {

        $user = auth()->user();

        $blogs = Blog::select(
                'blogs.id',
                \DB::raw('substring(blogs.title, 1, 50) as title'),
                'blogs.slug',
                'blogs.profile_img',
                'blogs.banner_img',
            )
            ->where('blogs.blogger', $user->id)
            ->get();

        return $blogs;
    }

    public function addBlog(Request $request) {
        dd(auth()->user());
        $user = auth()->user();

        $request['blogger'] = $user->id;

        $blog = new Blog;
        $blog->title = $request->input('title');
        $blog->slug = $request->input('slug');
        $blog->description = $request->input('description');
        $blog->blogger = $request->input('blogger');

        // Verifica si 'profile_img' está presente en el request y no es nulo
        if ($request->hasFile('banner_img')) {
            // Verifica si 'profile_img' es un array y obtiene el primer archivo
            $banner_img_files = $request->file('banner_img');
            $banner_img_file = is_array($banner_img_files) ? $banner_img_files[0] : $banner_img_files;


            // Asegúrate de que 'profile_img' no esté vacío
            if ($banner_img_file) {
                $banner_img_name = $banner_img_file->getClientOriginalName();
                $reactPublicPath = env('IMG_LOCATION').'blogs/' . $request->input('slug');

                // Crear la carpeta si no existe
                if (!File::exists($reactPublicPath)) {
                    File::makeDirectory($reactPublicPath, 0755, true, true);
                }

                // Mover el archivo a la carpeta de destino en React
                $banner_img_file->move($reactPublicPath, $banner_img_name);

                // Guardar el nombre de archivo en la cadena
                $blog->banner_img = $request->input('slug') . '/' . $banner_img_name;
            }
        } else {
            $blog->banner_img = $request->input('banner_img');
        }

        // Verifica si 'profile_img' está presente en el request y no es nulo
        if ($request->hasFile('profile_img')) {
            // Verifica si 'profile_img' es un array y obtiene el primer archivo
            $profile_img_files = $request->file('profile_img');
            $profile_img_file = is_array($profile_img_files) ? $profile_img_files[0] : $profile_img_files;


            // Asegúrate de que 'profile_img' no esté vacío
            if ($profile_img_file) {
                $profile_icon_name = $profile_img_file->getClientOriginalName();
                $reactPublicPath = env('IMG_LOCATION').'blogs/' . $request->input('slug');

                // Crear la carpeta si no existe
                if (!File::exists($reactPublicPath)) {
                    File::makeDirectory($reactPublicPath, 0755, true, true);
                }

                // Mover el archivo a la carpeta de destino en React
                $profile_img_file->move($reactPublicPath, $profile_icon_name);

                // Guardar el nombre de archivo en la cadena
                $blog->profile_img = $request->input('slug') . '/' . $profile_icon_name;
            }
        } else {
            $blog->profile_img = $request->input('profile_img');
        }

        $blog->save();

        return $blog;
    }

    public function getBlog(Request $request) {

        $blog = $request->blogSlug;

        $blogs = Blog::where('slug', $blog)->first();

        return $blogs;
    }

    public function editBlog(Request $request) {

        $blog = Blog::where('id', $request->id)->first();

        $blog->title = $request->input('title');
        $blog->slug = $request->input('slug');
        $blog->description = $request->input('description');
        $blog->profile_img = $request->input('profile_img');
        $blog->banner_img = $request->input('banner_img');
        $blog->save();

        return $blog;
    }

    public function deleteBlog(Request $request) {

        Blog::where('slug', $request->blogSlug)->first()->delete();

        return Request("OK");
    }

    // -------------------------------------------------------------------

    public function addPost(Request $request, $blogSlug) {

        $blog = new Post;
        $blog->title = $request->input('title');
        $blog->subtitle = $request->input('subtitle');
        $blog->slug = $request->input('slug');
        $blog->content = $request->input('content');

        $blog->summary = substr($request->input('content'), 0, 250);
        $blog->blog_id = Blog::where('slug', $blogSlug)->first()->id;

        if ($request->hasFile('banner_img')) {
            // Verifica si 'profile_img' es un array y obtiene el primer archivo
            $blogSlug = Blog::where('slug', $blogSlug)->first()->slug;
            $banner_img_files = $request->file('banner_img');
            $banner_img_file = is_array($banner_img_files) ? $banner_img_files[0] : $banner_img_files;


            // Asegúrate de que 'profile_img' no esté vacío
            if ($banner_img_file) {
                $banner_img_name = $banner_img_file->getClientOriginalName();
                $reactPublicPath = env('IMG_LOCATION').'blogs/' . $blogSlug;

                // Crear la carpeta si no existe
                if (!File::exists($reactPublicPath)) {
                    File::makeDirectory($reactPublicPath, 0755, true, true);
                }

                // Mover el archivo a la carpeta de destino en React
                $banner_img_file->move($reactPublicPath, $banner_img_name);

                // Guardar el nombre de archivo en la cadena
                $blog->banner_img = $blogSlug . '/' . $banner_img_name;
            }
        } else {
            $blog->banner_img = $request->input('banner_img');
        }

        $blog->save();

        $suscribers = Subscription::where("blog_id", $blog->blog_id)->with('user')->get();
        foreach ($suscribers as $suscriptor) {
            Mail::to($suscriptor->user->email)
                ->send(new NewPost($suscriptor->user, $blog));
        }

        return $blog;
    }

    public function editPost(Request $request) {

        $post = Post::where('id', $request->id)->first();

        $post->title = $request->input('title');
        $post->subtitle = $request->input('subtitle');
        $post->slug = $request->input('slug');
        $post->content = $request->input('content');
        $post->banner_img = $request->input('banner_img');
        $post->save();

        return $post;
    }

    public function deletePost(Request $request) {

        Post::where('slug', $request->blogSlug)->first()->delete();

        return Request("OK");
    }

    // -------------------------------------------------------------------

    public function addComment(Request $request, $blogSlug, $postSlug, $comment_id="") {
        $userId = auth()->user();

        if (!is_null($userId)) {
            $userId = $userId->id;
        } else {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $comment = new Comment;
        $comment->user_id = $userId;
        $comment->content = $request->input('content');
        $post = Post::where('slug', $postSlug)->first();
        $comment->post_id = $post->id;
        if ($request->input('type') == "reply") {
            $comment->parent_id = $comment_id;
        }
        $comment->save();

        return $comment;
    }

    public function addCommentReply(Request $request, $comment_id) {
        $post_id = Comment::where('id', $comment_id)->first()->post_id;
        $post_slug = Post::where('id', $post_id)->first()->slug;
        return BlogController::addComment($request, "", $post_slug, $comment_id);
    }
}
