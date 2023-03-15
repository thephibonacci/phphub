<?php

namespace System\arr;

class Arr
{
    private array $arr;

    public function __construct(array $arr)
    {
        $this->arr = $arr;
    }

    public static function create(array $arr): Arr
    {
        return new self($arr);
    }

    public function get($key, $default = null)
    {
        return $this->arr[$key] ?? $default;
    }

    public function set($key, $value): static
    {
        $this->arr[$key] = $value;
        return $this;
    }

    public function remove($key): static
    {
        unset($this->arr[$key]);
        return $this;
    }

    public function length(): int
    {
        return count($this->arr);
    }

    public function sort($desc = false): static
    {
        $desc ? arsort($this->arr) : asort($this->arr);
        return $this;
    }

    public function reverse(): static
    {
        $this->arr = array_reverse($this->arr);
        return $this;
    }

    public function join($delimiter = ','): string
    {
        return implode($delimiter, $this->arr);
    }

    public static function count($arr): int
    {
        return count($arr);
    }

    public static function sorted($arr, $desc = false)
    {
        $desc ? arsort($arr) : asort($arr);
        return $arr;
    }

    public static function reversed($arr): array
    {
        return array_reverse($arr);
    }

    public static function joined($arr, $delimiter = ','): string
    {
        return implode($delimiter, $arr);
    }
}
