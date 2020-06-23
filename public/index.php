<?php

/**
 * Miduner - A PHP Framework For Amateur
 *
 * @package  Miduner
 * @author   danganh97 <danganh.dev@gmail.com>
 */

define('MIDUNER_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels great to relax.
|
*/

require __DIR__ . '/../main/App.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/
require __DIR__ . '/../main/Helpers/common.php';

/*
|--------------------------------------------------------------------------
/ Include the config file
/ This file is needed to run the app
/ Please make sure you're run midun config:cache before
|--------------------------------------------------------------------------
*/
if (!file_exists(__DIR__ . '/../cache/app.php')) {
    die('Please configuration cache.');
}
$config = require __DIR__ . '/../cache/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/
$app = App::getInstance($config);

$app->run();
