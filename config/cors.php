<?php

return [

    'paths' => ['api/*'], // Autorise toutes les routes API

    'allowed_methods' => ['*'], // Autorise toutes les méthodes (GET, POST, etc.)

    'allowed_origins' => ['http://localhost:4200'], // Autorise l'origine de votre application Angular

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'], // Autorise tous les en-têtes

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true, // Nécessaire pour les cookies d'authentification
];
