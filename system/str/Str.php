<?php

namespace System\str;

class Str
{
    private mixed $string;

    public function __construct($string = '')
    {
        $this->string = $string;
    }

    public function camel(): static
    {
        $this->string = lcfirst(str_replace(' ', '', ucwords(str_replace('-', ' ', $this->string))));
        return $this;
    }

    public function kebab(): static
    {
        $this->string = str_replace(' ', '-', strtolower($this->string));
        return $this;
    }

    public function length(): int
    {
        return strlen($this->string);
    }

    public function limit($limit = 70, $end = '...'): static
    {
        if (strlen($this->string) > $limit) {
            $this->string = substr($this->string, 0, $limit) . $end;
        }
        return $this;
    }

    public function words($words = 100, $end = '...'): static
    {
        $wordsArray = explode(' ', $this->string);
        if (count($wordsArray) > $words) {
            $this->string = implode(' ', array_slice($wordsArray, 0, $words)) . $end;
        }
        return $this;
    }

    public function upper(): static
    {
        $this->string = strtoupper($this->string);
        return $this;
    }

    public function lower(): static
    {
        $this->string = strtolower($this->string);
        return $this;
    }

    public function rand($length = 16): static
    {
        $this->string = bin2hex(random_bytes($length / 2));
        return $this;
    }

    public function random($length = 16): static
    {
        $this->string = substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', $length)), 0, $length);
        return $this;
    }

    public function reverse(): static
    {
        $this->string = strrev($this->string);
        return $this;
    }

    public function slug(): static
    {
        $this->string = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->string), '-'));
        return $this;
    }

    public function snake(): static
    {
        $this->string = str_replace(' ', '_', strtolower($this->string));
        return $this;
    }

    public function __toString()
    {
        return $this->string;
    }

    public static function create($value): Str
    {
        return new self($value);
    }
}