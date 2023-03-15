<?php

namespace App\middlewares;


class CorsMiddleware extends Middleware
{
    private array $allowedOrigins;
    private array $allowedMethods;
    private array $allowedHeaders;
    private int $maxAge;

    public function __construct(array $allowedOrigins = ['*'], array $allowedMethods = ['GET', 'POST', 'PUT', 'DELETE'], array $allowedHeaders = ['Content-Type'], int $maxAge = 86400)
    {
        $this->allowedOrigins = $allowedOrigins;
        $this->allowedMethods = $allowedMethods;
        $this->allowedHeaders = $allowedHeaders;
        $this->maxAge = $maxAge;
    }

    public function handle(string $request): ?string
    {
        $this->setCorsHeaders();
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header('Access-Control-Max-Age: ' . $this->maxAge);
            header('Content-Length: 0');
            exit;
        } else {
            return parent::handle($request);
        }
    }

    private function setCorsHeaders(): void
    {
        $requestOrigin = $_SERVER['HTTP_ORIGIN'] ?? '';
        if (in_array('*', $this->allowedOrigins) || in_array($requestOrigin, $this->allowedOrigins)) {
            header('Access-Control-Allow-Origin: ' . $requestOrigin);
        }
        header('Access-Control-Allow-Methods: ' . implode(', ', $this->allowedMethods));
        header('Access-Control-Allow-Headers: ' . implode(', ', $this->allowedHeaders));
        header('Access-Control-Allow-Credentials: true');
    }
}