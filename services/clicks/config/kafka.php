<?php
return [
    'brokers' => env('KAFKA_BROKERS', 'kafka:9092'),
    'topic_clicks' => env('KAFKA_TOPIC_CLICKS', 'clicks_raw'),
    'group_id' => env('KAFKA_GROUP_ID', 'clicks-consumer'),
    'security_protocol' => env('KAFKA_SECURITY_PROTOCOL', 'PLAINTEXT'),
];
