<?php

require __DIR__.'/../app/main/App.php';

$config = require __DIR__.'/../config/app.php';

$app = new App($config);
$app->run();