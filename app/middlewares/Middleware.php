<?php

namespace App\middlewares;

use System\middlewares\MiddlewareInterface;

abstract class Middleware implements MiddlewareInterface
{
    private $nextMiddleware;

    public function setNext(MiddlewareInterface $middleware): MiddlewareInterface
    {
        $this->nextMiddleware = $middleware;
        return $middleware;
    }

    public function handle(string $request): ?string
    {
        if ($this->nextMiddleware) {
            return $this->nextMiddleware->handle($request);
        }
        return null;
    }
}