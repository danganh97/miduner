<?php

Route::resources([
    'posts' => 'PostController',
])->except(['create', 'edit']);

Route::resource('users', 'UserController');

Route::post('login', 'AuthController@login')->name('login');
Route::post('logout', 'AuthController@logout')->name('logout');
Route::get('get-current-user', 'AuthController@getCurrentUser')->name('getCurrentUser');