<?php

namespace System\middlewares;

interface MiddlewareInterface
{
    public function setNext(MiddlewareInterface $middleware): MiddlewareInterface;

    public function handle(string $request): ?string;
}