<?php

namespace System\middlewares;

class MiddlewarePipeline
{
    public function handle($request)
    {
        $appConfigs = require dirname(__DIR__, 2) . '/config/app.php';
        $middlewares = $appConfigs['middlewares'];
        if ($middlewares) {
            foreach ($middlewares as $middleware) {
                $response = $middleware->handle($request);
                if (is_string($response)) {
                    return $response;
                }
            }
            return null;
        }
    }
}