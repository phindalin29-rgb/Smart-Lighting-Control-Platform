<?php

return [
    'host' => env('MQTT_HOST', '127.0.0.1'),
    'port' => env('MQTT_PORT', 1883),
    'username' => env('MQTT_USERNAME', null),
    'password' => env('MQTT_PASSWORD', null),
    'client_id' => env('MQTT_CLIENT_ID', 'smart-lighting'),
    'connect_timeout' => env('MQTT_CONNECT_TIMEOUT', 3),
    'api_token' => env('MQTT_API_TOKEN'),
];
