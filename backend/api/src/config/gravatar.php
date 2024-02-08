<?php

return array(
    'default' => array(
        'size'   => 256,
        'fallback' => 'mm',
        'secure' => false,
        'maximumRating' => 'g',
        'forceDefault' => false,
        'forceExtension' => 'webp',
    ),
    'large' => array (
        'size'   => env('AVATAR_SIZE_LARGE', 256),
        'fallback' => 'robohash',
    ),
    'medium' => array (
        'size'   => env('AVATAR_SIZE_MEDIUM', 128),
        'fallback' => 'robohash',
    ),
    'small' => array (
        'size'   => env('AVATAR_SIZE_SMALL', 64),
        'fallback' => 'robohash',
    ),
);
