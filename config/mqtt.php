<?php

return [
    'host'             => env('MQTT_HOST', 'broker.emqx.io'),
    'port'             => env('MQTT_PORT', 1883),
    'topic'            => env('MQTT_TOPIC', 'panic/button'),
    'client_id_prefix' => env('MQTT_CLIENT_ID_PREFIX', 'panicbutton_laravel_'),
];
