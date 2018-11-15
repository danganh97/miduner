<?php
use App\Main\QueryBuilder as DB;

Route::get('/', [App\Controllers\HomeController::class, 'home']);

Route::get('/about', [App\Controllers\HomeController::class, 'about']);

Route::get('/post', [App\Controllers\HomeController::class, 'post']);

Route::get('/contact', [App\Controllers\HomeController::class, 'contact']);

Route::resource('/users', 'UserController');
Route::resource('/posts', 'PostController');

Route::get('/test-class-url', [App\Controllers\WrongController::class, 'index']);

Route::any('/abc/{id}/{slug}/{abc}', function ($id, $a, $b) {
});

Route::post('/login', 'UserController@login');
Route::post('/logout', [App\Controllers\UserController::class, 'logout']);


Route::get('/test-join', function(){
    $users = App\Main\QueryBuilder::table('users')
    ->select('users.user_id as id', 'users.is_verified' , 'name', 'email', 'provider_user_id')
    ->join('social_accounts', 'users.user_id', '=' , 'social_accounts.user_id')
    ->where('is_verified', '=', 1)
    ->get();
    return response()->json($users);
});

Route::get('/add-to-cart/{id}', 'CartController@addToCart');
Route::get('/get-cart', 'CartController@getCart');
Route::get('/remove-cart/{id}', 'CartController@removeCart');

Route::get('/test-nhe', function () {
    $a = DB::table('users')->limit(10)->get();
    return response($a);
});

Route::get('/json', function () {
    $users = DB::table('users')->limit(10)->orderByDesc('user_id')->get();
    return response()->json($users);
});

Route::get('/test', function () {
    return action('HomeController@home');
});