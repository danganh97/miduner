<?php

Route::get('/', [App\Http\Controllers\HomeController::class, 'home'])->name('home')->middleware(['web:auth', 'checkPermission']);

Route::get('/about', [App\Http\Controllers\HomeController::class, 'about'])->name('about');

Route::get('/post', [App\Http\Controllers\HomeController::class, 'post'])->middleware('web:auth');

Route::get('/contact', [App\Http\Controllers\HomeController::class, 'contact'])->name('contact');

Route::resources([
    'posts' => 'PostController',
    'partners' => 'PartnerController',
])->except(['create', 'edit'])->middleware('web:auth');

Route::resource('users', 'UserController')->middleware('web:auth')->except(['destroy']);
Route::get('/add-to-cart/{id}', 'CartController@addToCart');
Route::get('/get-cart', 'CartController@getCart');
Route::get('/remove-cart/{id}', 'CartController@removeCart');


Route::get('/abc', function () {
    route('contact');
});

Route::post('/login', 'AuthController@login')->name('login');
Route::post('/logout', 'AuthController@logout')->name('logout');
Route::get('/get-current-user', 'AuthController@getCurrentUser')->name('getCurrentUser');
