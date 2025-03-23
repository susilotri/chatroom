<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'], // Izinkan API dan CSRF
    'allowed_methods' => ['*'], // Semua metode diperbolehkan (GET, POST, dll.)
    'allowed_origins' => ['http://localhost:5713'], // Sesuaikan dengan domain Vue.js kamu
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'], // Semua header diizinkan
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true, // Izinkan credentials (cookie/token)
];
