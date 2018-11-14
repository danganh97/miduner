<?php

Route::get('/', [App\Controllers\HomeController::class, 'home']);

Route::get('/about', [App\Controllers\HomeController::class, 'about']);

Route::get('/post', [App\Controllers\HomeController::class, 'post']);

Route::get('/contact', [App\Controllers\HomeController::class, 'contact']);

Route::get('/test-invoke/{id}', 'TestController');
Route::resource('/users', 'UserController');
Route::resource('/posts', 'PostController');
Route::resource('/orders', 'OrderController');

Route::get('/test-class-url', [App\Controllers\WrongController::class, 'index']);

Route::any('/abc/{id}/{slug}/{abc}', function ($id, $a, $b) {
});

Route::post('/login', 'UserController@login');
Route::post('/logout', [App\Controllers\UserController::class, 'logout']);


Route::get('/test-join', function(){
    $users = App\Main\QueryBuilder::table('users')
    // ->select('users.user_id as id', 'users.is_verified' , 'name', 'email', 'provider_user_id')
    ->join('social_accounts', 'users.user_id', '=' , 'social_accounts.user_id')
    ->where('is_verified', '=', 1)
    ->get();
    // return view('users/index', compact('users'));
    return response($users);
});

Route::get('/add-to-cart/{id}', 'CartController@addToCart');
Route::get('/get-cart', 'CartController@getCart');
Route::get('/remove-cart/{id}', 'CartController@removeCart');

Route::get('/test-nhe', function () {
    $a = \App\Main\QueryBuilder::table('users')->get();
    return response($a);
});