<?php

return [
    'supportsCredentials' => false,
    'allowedOrigins' => [
        'http://www.bydre.co',
        'http://dev.bydre.co',
        'http://127.0.0.1:4000',
    ],
    'allowedHeaders' => ['Content-Type', 'Accept'],
    'allowedMethods' => ['GET', 'POST', 'PUT',  'DELETE'],
    'exposedHeaders' => [],
    'maxAge' => 0,
    'hosts' => [],
];
