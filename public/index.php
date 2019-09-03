<?php
require __DIR__.'/../app/Main/App.php';
require __DIR__.'/../helpers/common.php';
$config = require __DIR__.'/../config/app.php';
$app = new App($config);
$app->run();