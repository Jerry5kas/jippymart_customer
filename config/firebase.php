<?php

return [
    'credentials' => [
        'file' => env('FIREBASE_CREDENTIALS', storage_path('app/firebase/credentials.json')),
    ],
];
