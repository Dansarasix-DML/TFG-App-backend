<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\BlogController;


// HOME
// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('/', [IndexController::class, 'index'])->name('home');
// Route::get('/lastBlogsAJAX', [IndexController::class, 'lastBlogs'])->name('lastBlogs');
// Route::get('/lastPostsAJAX', [IndexController::class, 'lastPosts'])->name('lastPosts');



// // BLOGS
// Route::get('/{blogSlug}AJAX', [BlogController::class, 'index'])->name('indexBlog');
// Route::get('/{blogSlug}/lastPostsAJAX', [BlogController::class, 'lastPosts'])->name('lastPosts');

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



// // POSTS
// // Ver un post
// Route::get('/{blogSlug}/{postSlug}AJAX', [BlogController::class, 'searchPost'])->name('searchPost');

// // Agregar un post (VISTA)
// Route::get('/p/{blogSlug}/add', function ($blogSlug) {
//     return view('welcome'); //TODO
// });

// // Agregar un post (ACCIÓN)
// Route::post('/p/{blogSlug}/add', function ($blogSlug) {
//     return view('welcome'); //TODO
// });

// // Editar un post (VISTA)
// Route::get('/p/{blogSlug}/{postSlug}/edit', function ($blogSlug, $postSlug) {
//     return view('welcome'); //TODO
// });

// // Editar un post (ACCIÓN)
// Route::put('/p/{blogSlug}/{postSlug}', function ($blogSlug, $postSlug) {
//     return view('welcome'); //TODO
// });

// // Eliminar un post (ACCIÓN)
// Route::delete('/p/{blogSlug}/{postSlug}', function ($blogSlug, $postSlug) {
//     return view('welcome'); //TODO
// });



// // USERS
// Route::get('/user/{username}AJAX2', [UserController::class, 'index'])->name('userIndex');

// Route::get('/u/{username}/config', function ($username) {
//     return view('welcome'); //TODO
// });



// // SHOP
// // Ver tienda
// Route::get('/shop', function () {
//     return view('welcome'); //TODO
// });

// // Agregar un producto (VISTA)
// Route::get('/shop/add-product', function () {
//     return view('welcome'); //TODO
// });

// // Agregar un producto (ACCIÓN)
// Route::post('/shop/add-product', function () {
//     return view('welcome'); //TODO
// });

// // Ver un producto
// Route::get('/shop/{product}', function ($product) {
//     return view('welcome'); //TODO
// });

// // Editar un producto (VISTA)
// Route::get('/shop/{product}/edit', function ($product) {
//     return view('welcome'); //TODO
// });

// // Editar un producto (ACCIÓN)
// Route::put('/shop/{product}', function ($product) {
//     return view('welcome'); //TODO
// });

// // Eliminar un producto (ACCIÓN)
// Route::delete('/shop/{product}', function ($product) {
//     return view('welcome'); //TODO
// });



// // SESIONES
// Route::get('/login', [UserController::class, 'login_view']);

// Route::post('/login', [UserController::class, 'login']);

// Route::post('/loginAJAX', [UserController::class, 'loginAJAX']);

// Route::middleware('auth')->post('/userAJAX', function () {
//     $user = auth()->user(); // Obtener el usuario autenticado
//     $errors = session('errors', []); // Obtener errores de la sesión

//     return response()->json([
//         'csrf_token' => csrf_token(), // Token CSRF
//         'user' => $user, // Información del usuario
//         'errors' => $errors, // Errores de la sesión
//     ]);
// });

// Route::post('/csrf', function () {
//     return response()->json([
//         'csrf_token' => csrf_token(), // Token CSRF
//     ]);
// });

// // Route::get('/register', [UserController::class, 'register_view']);

// // Route::post('/register', [UserController::class, 'register']);

// Route::get('/logout', [UserController::class, 'logout']);



// // TESTING
// Route::get('/users', [UserController::class, 'index']);