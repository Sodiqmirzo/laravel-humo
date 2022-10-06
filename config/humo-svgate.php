<?php

// config for Uzbek/Humo
return [
    'base_urls' => [
        '11210' => 'https://humo.uz/api/v1/', /*payment*/
        '13010' => 'https://humo.uz/api/v1/', /*access gateway*/
        '8443' => 'https://humo.uz/api/v1/', /*issuing*/
        '6677' => 'https://humo.uz/api/v1/', /*card*/
        'json_info' => 'https://humo.uz/api/v1/', /*json info*/
    ],
    'username' => env('HUMO_USERNAME', 'username'),
    'password' => env('HUMO_PASSWORD', 'password'),
    'token' => env('HUMO_TOKEN', 'token'),
    'max_amount_without_passport' => env('HUMO_MAX_AMOUNT_WITHOUT_PASSPORT', 0),
];
