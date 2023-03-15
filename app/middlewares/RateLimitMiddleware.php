<?php

namespace App\middlewares;

class RateLimitMiddleware extends Middleware
{
    private int $maxRequestsPerSecond;
    private int $second;


    public function __construct(int $maxRequestsPerSecond = 80, int $second = 60)
    {
        $this->maxRequestsPerSecond = $maxRequestsPerSecond;
        $this->second = $second;
    }

    public function handle(string $request): ?string
    {
        $time = time();
        $count = $_SESSION['rateLimitCount'] ?? 0;
        if (isset($_SESSION['rateLimitTime']) && ($time - $this->second) > $_SESSION['rateLimitTime']) {
            unset($_SESSION['rateLimitTime']);
            unset($_SESSION['rateLimitCount']);
        }
        if ($count >= $this->maxRequestsPerSecond) {
            return 'Too many requests';
        }
        !isset($_SESSION['rateLimitCount']) ? $_SESSION['rateLimitCount'] = 1 : $_SESSION['rateLimitCount']++;
        isset($_SESSION['rateLimitTime']) ?: $_SESSION['rateLimitTime'] = $time;
        return parent::handle($request);
    }
}