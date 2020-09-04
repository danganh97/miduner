<?php

Route::get('/', [HomeController::class, 'home'])->name('home');

Route::get('about', [HomeController::class, 'about'])->name('about');

Route::get('post', [HomeController::class, 'post']);

Route::get('contact', [HomeController::class, 'contact'])->name('contact');

Route::post('test-file', [HomeController::class, 'testPostFile'])->name('test');

Route::get('action', function () {
    return action([\App\Http\Controllers\HomeController::class, 'home'], ['name' => 'anh', 'age' => 23]);
});