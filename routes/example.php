<?php

use DB;
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
| in config/app.php [MAIN_LAYOUT]
|---------------------------------------------------------------------------
 */
Route::get('/example', function () {
    return view('example');
});

/*
|---------------------------------------------------------------------------
| This Route for only the simple view non extends master layout
|---------------------------------------------------------------------------
 */
Route::get('/example', function () {
    return simpleView('example');
});

/*
|---------------------------------------------------------------------------
| This Route return data format json for the API Application
|---------------------------------------------------------------------------
 */
Route::get('/example', function () {
    $users = DB::table('users')->get();
    return response()->json($users);
});

/*
|---------------------------------------------------------------------------
| This Route passing data to the view choosing using compact function()
|---------------------------------------------------------------------------
 */
Route::get('/example', function () {
    $users = DB::table('users')->get();
    return view('users/index', compact('users'));
});

/*
|---------------------------------------------------------------------------
| Now, we're supported callable action with array parameters or compact()
|---------------------------------------------------------------------------
 */
Route::get('/example', function () {
    $users = DB::table('users')->get();
    return action([UserController::class, 'index'], ['users' => $users]);
});

Route::get('/example', function () {
    $users = DB::table('users')->get();
    return action('UserController@index', compact('users'));
});

/*
|---------------------------------------------------------------------------
| This Route for url having one or many variables
|---------------------------------------------------------------------------
 */
Route::get('/example/{variable}', function ($variable) {
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

/*
|---------------------------------------------------------------------------
| Or you can using only controller instead of using controller and action
| Remember in your controller must be having magic method __invoke()
|---------------------------------------------------------------------------
 */

Route::get('/example', 'ExampleController');

/*
|---------------------------------------------------------------------------
| Here all method we're support
|---------------------------------------------------------------------------
 */

Route::get('/example', 'ExampleController@get');
Route::post('/example', 'ExampleController@post');
Route::patch('/example', 'ExampleController@patch');
Route::put('/example', 'ExampleController@put');
Route::delete('/example', 'ExampleController@delete');
Route::any('/example', 'ExampleController@any');

/*
|---------------------------------------------------------------------------
| Route resource will be declare 7 Route like:
|
| Route::get('/example', 'ExampleController@index);
| Route::get('/example/create', 'ExampleController@create);
| Route::post('/example', 'ExampleController@store);
| Route::get('/example/{example}/show', 'ExampleController@show);
| Route::get('/example/{example}/edit', 'ExampleController@edit);
| Route::put('/example/{example}/update', 'ExampleController@update);
| Route::get('/example/{example}/delete', 'ExampleController@destroy);
|---------------------------------------------------------------------------
 */

Route::resource('/example', 'ExampleController');
