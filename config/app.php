<?php

use App\middlewares\{CorsMiddleware, CsrfMiddleware, RateLimitMiddleware};
use App\providers\{AppServiceProvider, SessionProvider};

return [
    "SITE_STATUS" => "UP",
    "APP_TITLE" => "phpHub",
    "BASE_URL" => "http://localhost",
    "BASE_DIR" => dirname(__DIR__),
    "DISPLAY_ERRORS" => true,
    "TIMEZONE" => "Iran",
    "providers" => [
        SessionProvider::class,
        AppServiceProvider::class
    ],
    "middlewares" => [
        new CorsMiddleware(),
        new RateLimitMiddleware(),
        new CsrfMiddleware(),
    ]
];