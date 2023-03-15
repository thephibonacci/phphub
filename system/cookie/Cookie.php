<?php

namespace System\cookie;

use System\crypto\Crypto;

class Cookie
{

    protected string $name;
    protected mixed $value;
    protected int|float $expiration = 3600;
    protected string $path = '/';
    protected $domain = null;
    protected bool $secure = false;
    protected bool $httpOnly = true;

    public function __construct(string $name, mixed $value = null, int $expiration = null)
    {
        $this->name = $name;
        $this->value = $value;
        $this->expiration = ($expiration * 60);
    }

    public function value(mixed $value): static
    {
        $this->value = $value;
        return $this;
    }

    public function expiration(int $expiration): static
    {
        $this->expiration = ($expiration * 60);
        return $this;
    }

    public function path(string $path): static
    {
        $this->path = $path;
        return $this;
    }

    public function domain($domain): static
    {
        $this->domain = $domain;
        return $this;
    }

    public function secure(bool $secure): static
    {
        $this->secure = $secure;
        return $this;
    }

    public function httpOnly(bool $httpOnly): static
    {
        $this->httpOnly = $httpOnly;
        return $this;
    }

    public function set(): string
    {
        $this->value = Crypto::encryptAES192($this->value, null, false);
        setcookie(
            $this->name,
            $this->value,
            $this->expiration,
            $this->path,
            $this->domain,
            $this->secure,
            $this->httpOnly
        );
        return $this->getRaw();
    }

    public static function delete($name, $path = '/', $domain = null): void
    {
        setcookie($name, null, time() - 3600, $path, $domain);
    }

    public static function create(string $name, mixed $value = null, int $expiration = null): Cookie
    {
        return new self($name, $value, $expiration);
    }

    public static function exists(string $name): bool
    {
        return isset($_COOKIE[$name]);
    }

    public static function get(string $name): ?string
    {
        if (isset($_COOKIE[$name])) {
            return Crypto::decryptAES192($_COOKIE[$name], null, false);
        }
        return null;
    }

    public static function all(): array
    {
        $result = [];
        foreach ($_COOKIE as $cookie => $content) {
            $result[$cookie] = empty(Crypto::decryptAES192($content, null, false)) ? $content : Crypto::decryptAES192($content, null, false);
        }
        return $result;
    }

    public function getRaw(): string
    {
        $raw = $this->name . '=' . urlencode($this->value);
        if ($this->expiration !== null) {
            $raw .= '; expires=' . gmdate('D, d M Y H:i:s T', $this->expiration);
        }
        if ($this->path !== null) {
            $raw .= '; path=' . $this->path;
        }
        if ($this->domain !== null) {
            $raw .= '; domain=' . $this->domain;
        }
        if ($this->secure) {
            $raw .= '; secure';
        }
        if ($this->httpOnly) {
            $raw .= '; httponly';
        }
        return $raw;
    }
}
