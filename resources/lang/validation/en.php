<?php

return [
    'required' => ':attribute is required',
    'min' => [
        'string' => ':attribute k the ngan hon :min',
        'number' => ':attribute k the nho hon :min',
        'file' => ':attribute k the nho hon :min MB'
    ],
    'max' => [
        'string' => ':attribute k the dai hon :max',
        'number' => ':attribute k the lon hon :max',
        'file' => ':attribute k the lon hon :min MB'
    ],
    'number' => 'The :attribute must be a number',
    'file' => 'The :attribute must be a file',
    'image' => 'The :attribute must be an image',
    'video' => 'The :attribute must be a video',
    'audio' => 'The :attribute must be an audio',
    'email' => 'The :attribute must be a valid email',
    'unique' => 'The :attribute has already been taken',
    'custom' => [
        'message' => 'This is custom message for custom rule'
    ]
];