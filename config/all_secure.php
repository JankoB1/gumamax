<?php

return [

    'default' => env('ALLSECURE_MODE', 'test'),

    'test' => [
        'accessToken' => 'OGE4Mjk0MTc1MzllZGI0MDAxNTNhZTgyMjA3MTIzMzF8QmdiRDREU3lwZA==',
        'entityId' => '8a829417539edb400153ae89e4242367',
        'baseURL' => 'https://test.oppwa.com'
    ],

    'live' => [
        'accessToken' => env('ALLSECURE_ACCESS_TOKEN', ''),
        'entityId' => env('ALLSECURE_ENTITY_ID', ''),
        'baseURL' => env('ALLSECURE_BASE_URL', '')
    ]
];
