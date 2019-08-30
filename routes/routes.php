<?php
use App\Main\Route;

Route::get('/', [App\Controllers\HomeController::class, 'home'])->middleware('auth')->name('home');

Route::get('/about', [App\Controllers\HomeController::class, 'about'])->name('about');

Route::get('/post', [App\Controllers\HomeController::class, 'post']);

Route::get('/contact', [App\Controllers\HomeController::class, 'contact']);

Route::resources([
    'users' => 'UserController',
    'posts' => 'PostController',
    'partners' => 'PartnerController'
]);
Route::get('/add-to-cart/{id}', 'CartController@addToCart');
Route::get('/get-cart', 'CartController@getCart');
Route::get('/remove-cart/{id}', 'CartController@removeCart');