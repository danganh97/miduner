<?php
use App\Main\QueryBuilder as DB;

Route::get('/', [App\Controllers\HomeController::class, 'home']);

Route::get('/about', [App\Controllers\HomeController::class, 'about']);

Route::get('/post', [App\Controllers\HomeController::class, 'post']);

Route::get('/contact', [App\Controllers\HomeController::class, 'contact']);

Route::post('/contact', [App\Controllers\HomeController::class, 'feedback']);

Route::resource('/users', 'UserController');
Route::resource('/posts', 'PostController');

Route::get('/add-to-cart/{id}', 'CartController@addToCart');
Route::get('/get-cart', 'CartController@getCart');
Route::get('/remove-cart/{id}', 'CartController@removeCart');