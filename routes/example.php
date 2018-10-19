<?php


/*
|--------------------------------------------------------------------------
| Routing of the Application
|--------------------------------------------------------------------------
|
| Declare all the route of your application.
| This class compare between your url and this by your request.
| Remember ! Declare your route file from this folder when you make a new
| file to config\app.php [AUTO_LOAD]
|---------------------------------------------------------------------------
*/

/*
|---------------------------------------------------------------------------
| This Route for the view extends master layout you declared 
| in config/app.php
|---------------------------------------------------------------------------
*/
Route::get('/example', function(){
    return view('example');
});

/*
|---------------------------------------------------------------------------
| This Route for only the simple view non extends master layout
|---------------------------------------------------------------------------
*/
Route::get('/example', function(){
    return simpleView('example');
});

/*
|---------------------------------------------------------------------------
| This Route return data format json for the API Application
|---------------------------------------------------------------------------
*/
Route::get('/example', function(){
    $users = App\Main\QueryBuilder::table('users')->get();
    return response($users);
});

/*
|---------------------------------------------------------------------------
| This Route passing data to the view choosing using compact function()
|---------------------------------------------------------------------------
*/
Route::get('/example', function(){
    $users = App\Main\QueryBuilder::table('users')->get();
    return view('users/index', compact('users'));
});

/*
|---------------------------------------------------------------------------
| This Route for url having one or many variables
|---------------------------------------------------------------------------
*/
Route::get('/example/{variable}', function($variable){
    echo $variable;
});

/*
|---------------------------------------------------------------------------
| This Route the controller from app\controllers and you must declare 
| controller before using this here
|---------------------------------------------------------------------------
*/
Route::get('/example', 'ExampleController@ExampleFunction');
Route::get('/example', [App\Controllers\ExampleController::class, 'ExampleFunction']);