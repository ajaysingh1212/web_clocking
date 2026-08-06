<?php

return [
    'eemot_api' => [
        'base_url' => env('API_BASE_URL', 'https://new.eemotclocking.in/api/v1/'),
        'timeout' => (int) env('API_TIMEOUT', 20),
        'password' => env('API_PASSWORD', 'ADMIN@EEMOT#2026'),
    ],
];
