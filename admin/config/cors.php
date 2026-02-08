<?php

return [

    'paths' => [
        'api/*',
        'sanctum/csrf-cookie',
        'login',
        'logout',
        'register',
    ],

    // Permitir todos os métodos HTTP (GET, POST, PUT, DELETE, etc.)
    'allowed_methods' => ['*'],

    // Em desenvolvimento: liberar o Vite (frontend) e o Laravel (backend)
    'allowed_origins' => [
        'http://localhost:5173',   // Vite dev server
        'http://127.0.0.1:8000',   // Laravel dev server
    ],

    // Se precisar liberar padrões dinâmicos (ex: subdomínios)
    'allowed_origins_patterns' => [],

    // Permitir todos os headers
    'allowed_headers' => ['*'],

    // Se precisar expor headers específicos para o frontend
    'exposed_headers' => ['Authorization', 'Content-Type'],

    // Tempo de cache da resposta CORS (em segundos)
    'max_age' => 3600,

    // Se sua aplicação usa cookies/autenticação cross-origin
    'supports_credentials' => true,
];