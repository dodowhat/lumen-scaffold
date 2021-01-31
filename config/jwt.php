<?php

return [
    'header' => 'Authorization',
    'prefix' => 'Bearer ',
    'algorithm' => 'HS256',
    'secret_length' => 32,
    'audience' => [
        'admin' => 'admin',
        'app' => 'app'
    ]
];