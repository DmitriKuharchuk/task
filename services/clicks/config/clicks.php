<?php
return [
    'queue_ingest' => env('CLICK_INGEST_QUEUE', false),
    'publish_to_kafka' => env('CLICK_PUBLISH_TO_KAFKA', true),
    'sources' => [
        // локальные секреты (будут переопределяться DI‑сервисом при наличии)
        // 'asd_network_1' => ['secret' => env('CLICK_SOURCE_ASD_NETWORK_1_SECRET')],
    ],
];
