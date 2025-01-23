<?php

return [

    'paths' => ['api/*', 'users/*', 'posts/*'], // Adjust to your API paths

    'allowed_methods' => ['*'], // Allow all HTTP methods

    'allowed_origins' => ['http://localhost:3000'], // Replace * with specific origins if credentials are needed

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['Content-Type', 'X-Auth-Token', 'Origin', 'Authorization'], // Add any other headers if needed

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true, // Enable this if you're using cookies or authentication
];
