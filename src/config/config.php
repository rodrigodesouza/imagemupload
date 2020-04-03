<?php

return [
    'name' => 'ImagemUpload',
    'upload-uri' => 'upload-imagem',
    'destino' => [
        // 'driver' => 'local',
        'root' => storage_path('app/public'),
        'url' => env('APP_URL').'/storage',
        'visibility' => 'public',
    ],
    'generate_gitignore' => true,
    'qualidade' => 100,
];
