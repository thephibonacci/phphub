<?php

namespace System\number;

class Number
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function add($number): static
    {
        $this->value += $number;
        return $this;
    }

    public function subtract($number): static
    {
        $this->value -= $number;
        return $this;
    }

    public function multiply($number): static
    {
        $this->value *= $number;
        return $this;
    }

    public function divide($number): static
    {
        $this->value /= $number;
        return $this;
    }

    public function power($exponent): static
    {
        $this->value = pow($this->value, $exponent);
        return $this;
    }

    public function round($precision = 0): static
    {
        $this->value = round($this->value, $precision);
        return $this;
    }

    public function get()
    {
        return $this->value;
    }

    public static function create($value): Number
    {
        return new self($value);
    }
}