<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use GuzzleHttp\Client;
use App\Models\Product;
use App\Models\Product_Type;
use App\Models\Blog;

class ProductController extends Controller
{
    public function lastProducts($type)
    {
        $products = Product::leftJoin('blogs', 'products.blog_id', '=', 'blogs.id')
                    ->leftJoin('products_types', 'products.type_id', '=', 'products_types.id')
                    ->select('products.*', 'blogs.title as blogTitle',
                    'blogs.profile_img as blogProfile', 'products_types.name as typeName')
                    ->orderBy('created_at', 'desc')->take($type === '1' ? 10 : 12)->get();
        
        return response()->json(compact('products'), 200, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
    }

    public function productsTypes() {
        $types = Product_Type::all();
        return response()->json(compact('types'), 200, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
    }

    public function allProducts() {
        $products = Product::query()
                    ->leftJoin('blogs', 'products.blog_id', '=', 'blogs.id')
                    ->leftJoin('products_types', 'products.type_id', '=', 'products_types.id')
                    ->select('products.*', 'blogs.title as blogTitle', 'products_types.name as typeName')
                    ->orderBy('created_at', 'desc')
                    ->get();

        return response()->json(compact('products'), 200, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
    }

    public function search(Request $request) {
        $searchTerm = $request->search;
        $search = Product::query()
                ->leftJoin('blogs', 'products.blog_id', '=', 'blogs.id')
                ->leftJoin('products_types', 'products.type_id', '=', 'products_types.id')
                ->select('products.*', 'blogs.title as blogTitle', 'products_types.name as typeName')
                ->where(function($query) use ($searchTerm) {
                    $query->where('products.name', 'like', '%'.$searchTerm.'%')
                        ->orWhere('products.sale_price', 'like', '%'.$searchTerm.'%')
                        ->orWhere('blogs.title', 'like', '%'.$searchTerm.'%')
                        ->orWhere('products_types.name', 'like', '%'.$searchTerm.'%');
                })
                ->orderBy('products.name', 'asc')
                ->get();

        return response()->json(compact('search'), 200, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
    }

    public function productData($productSlug) {
        $product = Product::leftJoin('blogs', 'products.blog_id', '=', 'blogs.id')
                    ->leftJoin('products_types', 'products.type_id', '=', 'products_types.id')
                    ->select('products.*', 'blogs.title as blogTitle', 'products_types.name as typeName')
                    ->where('products.slug', $productSlug)->first();

        $rating = $product->calculateTotalRating();
        
        return response()->json(compact('product', 'rating'), 200, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);

    }

    public function productImages($productSlug){
        $product = Product::where('products.slug', $productSlug)->first();
        $images = $product->imgs;
        return response()->json(compact('images'), 200, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
    }

    public function getReviews($productSlug) {
        $product = Product::where('slug', $productSlug)->first();
        $reviews = $product->reviews()->leftJoin('users', 'reviews.user_id', '=', 'users.id')
                    ->select('reviews.*', 'users.name as name', 'users.username as UserName', 'users.avatar as profileImg')
                    ->orderBy('created_at', 'desc')->get();
        return response()->json(compact('reviews'), 200, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
        
    }

    public function userProducts() {
        $user = auth()->user();

        foreach ($user->bloggers as $blog) {
            $products = $blog->products()->leftJoin('blogs', 'products.blog_id', '=', 'blogs.id')
            ->select('products.*', 'blogs.slug as blogSlug')->orderBy('products.updated_at', 'desc')->get();
        }

        return response()->json(compact('products'), 200, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
    }

    public function addProduct(Request $request) {
        $blog = Blog::where('slug', $request->blog)->first();
    
        if (!$blog) {
            return response()->json(['error' => 'Blog not found'], 404);
        }
    
        $product = new Product();
        $product->name = $request->name;
        $product->slug = $request->slug;
        $product->description = $request->desc;
        $product->summary = $request->summary;
        $product->categories = $request->categories;
        $product->purchase_price = $request->purchase_price;
        $product->sale_price = $request->sale_price;
        $product->price = $request->sale_price;
        $product->quantity = $request->quantity;
        $product->blog_id = $blog->id;
        $product->type_id = $request->type;
        $product->taxes_porcent = $request->taxes;
    
        $fileNames = [];
        foreach ($request->files as $key => $file) {
            if (strpos($key, 'file-') === 0) {
                $fileName = $file->getClientOriginalName();
                $reactPublicPath = env('IMG_LOCATION').'blogs/' . $blog->slug;
    
                // Crear la carpeta si no existe
                if (!File::exists($reactPublicPath)) {
                    File::makeDirectory($reactPublicPath, 0755, true, true);
                }
    
                // Mover el archivo a la carpeta de destino en React
                $file->move($reactPublicPath, $fileName);
    
                // Guardar el nombre de archivo en la cadena
                $fileNames[] = $blog->slug . '/' . $fileName;
            }
        }
    
        $fileNamesString = implode(', ', $fileNames);
    
        $product->imgs = $fileNamesString;
        $product->save();
    
        return response()->json(compact('product'), 201, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
    }

    public function deleteProduct(Request $request) {
        Product::where('slug', $request->productSlug)->first()->delete();
        return response()->json(['message' => 'Product deleted successfully'], 200);
        
    }
}
