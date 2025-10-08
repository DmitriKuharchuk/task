<?php
return [
    'endpoint' => env('FINANCE_ENDPOINT', 'http://finance:8000'),
    'forward_batch' => env('FINANCE_FORWARD_BATCH', 5000),
    'timeout' => env('FINANCE_HTTP_TIMEOUT', 10),
];
