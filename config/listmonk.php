<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Listmonk Base URL
    |--------------------------------------------------------------------------
    |
    | This is the base URL of your Listmonk installation.
    |
    */
    'base_url' => env('LISTMONK_URL', 'http://localhost:9000'),

    /*
    |--------------------------------------------------------------------------
    | Listmonk Authentication
    |--------------------------------------------------------------------------
    |
    | These credentials will be used to authenticate with the Listmonk API.
    |
    */
    'username' => env('LISTMONK_USERNAME', 'admin'),
    'password' => env('LISTMONK_PASSWORD', 'admin'),

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | The timeout in seconds for the HTTP client.
    |
    */
    'timeout' => 30,
];
