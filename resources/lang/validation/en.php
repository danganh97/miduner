<?php

return [
    'required' => ':attribute is required',
    'min' => [
        'string' => ':attribute k the ngan hon :min',
        'number' => ':attribute k the nho hon :min'
    ],
    'max' => [
        'string' => ':attribute k the dai hon :max',
        'number' => ':attribute k the lon hon :max'
    ],
    'number' => 'The :attribute must be a number',
    'file' => 'The :attribute must be a file',
    'image' => 'The :attribute must be an image',
    'video' => 'The :attribute must be a video',
    'audio' => 'The :attribute must be an audio'
];