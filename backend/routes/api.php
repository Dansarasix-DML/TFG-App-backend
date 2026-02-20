<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\JWTMiddleware;
use Illuminate\Http\Request;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\GoogleLoginController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\WishListController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PayController;
use App\Http\Controllers\SubscriptionController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::prefix('auth')->group(function() {
//     Route::post('register', [AuthController::class, 'register']);
// });

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function () {

    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    // Route::post('logout', 'AuthController@logout');
    // Route::post('refresh', 'AuthController@refresh');
    Route::middleware(JWTMiddleware::class)->post('me', [AuthController::class, 'me']);
    Route::post('/google/callback', [GoogleLoginController::class, 'handleGoogleCallback']);
    Route::post('/activateToken', [AuthController::class, 'activateToken']);
});

Route::group([

    'middleware' => 'api',
    'prefix' => 'blog'

], function () {
    Route::get('/lastBlogsAJAX', [BlogController::class, 'lastBlogs']);
    Route::get('/getBlogs', [BlogController::class, 'getBlogs']);
    Route::get('/{blogSlug}/getInfo', [BlogController::class, 'getBlogInfo']);
    Route::get('/search', [BlogController::class, 'search']);
    Route::post('/search', [BlogController::class, 'search']);
    Route::get('/lastPostsIndexAJAX', [BlogController::class, 'lastPostsIndex']);
    Route::get('/{blogSlug}/lastPostsAJAX', [BlogController::class, 'lastPosts']);
    Route::get('/{blogSlug}/gamesAJAX', [BlogController::class, 'getGames']);
    Route::get('/{blogSlug}/merchAJAX', [BlogController::class, 'getMerch']);
    Route::get('/{blogId}/getSubscribers', [SubscriptionController::class, 'getSubscribers']);


    Route::post('/me', [BlogController::class, 'userBlogs']);


    Route::post('/add', [BlogController::class, 'addBlog']);
    Route::post('/{blogSlug}', [BlogController::class, 'getBlog']);
    Route::post('/{blogSlug}/edit', [BlogController::class, 'editBlog']);
    Route::delete('/{blogSlug}/delete', [BlogController::class, 'deleteBlog']);


    // Route::get('/{blogSlug}/lastPostsAJAXV2', [BlogController::class, 'lastPostsByBlogSlug']);
    Route::post('/{blogSlug}/add', [BlogController::class, 'addPost']);
    Route::get('/{blogSlug}/{postSlug}', [BlogController::class, 'searchPost']);
    Route::post('/{blogSlug}/{postSlug}', [BlogController::class, 'searchPost']);
    Route::post('/{blogSlug}/{postSlug}/edit', [BlogController::class, 'editPost']);
    Route::delete('/{blogSlug}/{postSlug}/delete', [BlogController::class, 'deletePost']);


    Route::post('/{blogSlug}/{postSlug}/addComment', [BlogController::class, 'addComment']);
    Route::post('/comment/reply/{commentId}', [BlogController::class, 'addCommentReply']);

});

Route::group([

    'middleware' => 'api',
    'prefix' => 'user'

], function () {
    Route::get('/{username}', [UserController::class, 'index']);
    Route::get('/{username}/products', [UserController::class, 'allProducts']);
    Route::post('/me', [UserController::class, 'me']);
    Route::post('/edit', [UserController::class, 'edit']);
    Route::post('/delete', [UserController::class, 'delete']);

    Route::post('/subscribe', [SubscriptionController::class, 'subscribe']);
    Route::post('/getSubs', [SubscriptionController::class, 'getSubs']);

    Route::post('/myOrders', [UserController::class, 'getOrders']);
    Route::post('/getOrderDetails', [UserController::class, 'getOrderDetails']);
});

Route::group([

    'middleware' => 'api',
    'prefix' => 'shop'

], function () {
    Route::get('/lastProductsAJAX/{type}', [ProductController::class, 'lastProducts']);
    Route::get('/productsTypesAJAX', [ProductController::class, 'productsTypes']);
    Route::post('/productsTypesAJAX', [ProductController::class, 'productsTypes']);
    Route::get('/allProductsAJAX', [ProductController::class, 'allProducts']);
    Route::get('/productImagesAJAX/{productSlug}', [ProductController::class, 'productImages']);
    Route::get('/productDataAJAX/{productSlug}', [ProductController::class, 'productData']);
    Route::get('/searchAJAX', [ProductController::class, 'search']);
    Route::post('/searchAJAX', [ProductController::class, 'search']);

    Route::get('/{productSlug}/reviews', [ProductController::class, 'getReviews']);
    Route::post('/{productSlug}/userReview', [ReviewController::class, 'getUserReview']);
    Route::post('/{productSlug}/addReview', [ReviewController::class, 'addReview']);
    Route::post('/{productSlug}/editReview', [ReviewController::class, 'editReview']);
    Route::post('/{productSlug}/deleteReview', [ReviewController::class, 'deleteReview']);

    Route::post('/getCart', [CartController::class, 'index']);
    Route::post('/getCartIds', [CartController::class, 'getCartProductsIds']);
    Route::post('/addCart', [CartController::class, 'addProduct']);
    Route::post('/removeCart', [CartController::class, 'removeProduct']);
    Route::post('/removeCartProduct', [CartController::class, 'removeCartProduct']);

    Route::post('/getWishList', [WishListController::class, 'index']);
    Route::post('/addWishList', [WishListController::class, 'addProduct']);
    Route::post('/removeWishList', [WishListController::class, 'removeProduct']);

    Route::post('/me', [ProductController::class, 'userProducts']);
    Route::post('/add', [ProductController::class, 'addProduct']);
    Route::delete('/{productSlug}/delete', [ProductController::class, 'deleteProduct']);
    // Route::get('/lastProductsBySubcategoryAJAX', [IndexController::class, 'lastProductsBySubcategory']);
    // Route::get('/lastProductsByBrandAJAX', [IndexController::class, 'lastProductsByBrand']);
    // Route::get('/lastProductsBySearchAJAX', [IndexController::class, 'lastProductsBySearch']);
});


Route::group([

    'middleware' => 'api',
    'prefix' => 'event'

], function () {
    Route::get('/lastEventsAJAX', [EventController::class, 'lastEvents']);
    Route::get('/allEventsAJAX', [EventController::class, 'allEvents']);
    Route::get('/searchAJAX', [EventController::class, 'search']);
    Route::post('/searchAJAX', [EventController::class, 'search']);
    Route::post('/me', [EventController::class, 'userEvents']);
    Route::post('/add', [EventController::class, 'addEvent']);
    Route::get('/{eventSlug}', [EventController::class, 'searchEvent']);
    Route::post('/{eventSlug}', [EventController::class, 'searchEvent']);
    Route::post('/{eventSlug}/edit', [EventController::class, 'editEvent']);
    Route::delete('/{eventSlug}/delete', [EventController::class, 'deleteEvent']);
});

Route::group([

    'middleware' => 'api',
    'prefix' => 'pay'

], function () {
    Route::post('/checkout', [PayController::class, 'checkout']);
    Route::post('/getCheckoutData', [PayController::class, 'getCheckoutData']);
});

// Aquí como agrupar las rutas protegidas --> https://youtu.be/2BsPz5i00KA?si=0slUWTcA1CEO6PqA&t=6322

// // BLOGS
// Route::get('/{blogSlug}', [BlogController::class, 'index'])->name('indexBlog');
// Route::get('/{blogSlug}/lastPosts', [BlogController::class, 'lastPosts'])->name('lastPosts');

// Route::get('/b/{blogSlug}/config', function ($blogSlug) {
//     return view('welcome'); //TODO
// });

// // Agregar un blog (VISTA)
// Route::get('/b/add', function () {
//     return view('welcome'); //TODO
// });

// // Agregar un blog (ACCIÓN)
// Route::post('/b/add', function () {
//     return view('welcome'); //TODO
// });

// // Editar un blog (VISTA)
// Route::get('/b/{blogSlug}/edit', function ($blogSlug) {
//     return view('welcome'); //TODO
// });

// // Editar un blog (ACCIÓN)
// Route::put('/b/{blogSlug}', function ($blogSlug) {
//     return view('welcome'); //TODO
// });

// // Eliminar un blog (ACCIÓN)
// Route::delete('/b/{blogSlug}', function ($blogSlug) {
//     return view('welcome'); //TODO
// });