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
        'size'   => 256,
        'fallback' => 'robohash',
    ),
    'medium' => array (
        'size'   => 128,
        'fallback' => 'robohash',
    ),
    'small' => array (
        'size'   => 64,
        'fallback' => 'robohash',
    ),
);
