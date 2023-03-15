<?php

namespace App\middlewares;

use System\request\Request;
use System\session\Session;

class CsrfMiddleware extends Middleware
{
    protected array $excludedUrls = [];

    public function __construct(array $excludedUrls = [])
    {
        $this->excludedUrls = $excludedUrls;
    }

    public function handle(string $request): ?string
    {
        if (methodField() !== 'get') {
            $req = new Request();
            $currentUrl = $req->getUri();
            foreach ($this->excludedUrls as $excludedUrl) {
                if (str_starts_with($currentUrl, $excludedUrl)) {
                    return parent::handle($request);
                }
            }
            $token = $req->input("CSRF_TOKEN") ?? '';
            if (!hash_equals(Session::get('CSRF_TOKEN'), $token)) {
                return 'Invalid CSRF token';
            } else {
                $newToken = randomToken();
                Session::set('CSRF_TOKEN', $newToken);
            }
        }
        return parent::handle($request);
    }
}