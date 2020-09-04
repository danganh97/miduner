<?php

return [
    'secret' => env('JWT_SECRET', ''),
    'hash' => 'HS256',
    'exp' => 60 // minutes
];