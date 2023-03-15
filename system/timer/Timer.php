<?php

namespace System\timer;

class Timer
{
    private static float|null $startTime = null;
    private static float|null $stopTime = null;

    public static function start(): void
    {
        self::$startTime = microtime(true);
    }

    public static function result(): string
    {
        if (self::$startTime) {
            self::$stopTime = microtime(true);
            return round((self::$stopTime - self::$startTime) * 1000, 2) . " ms";
        } else {
            return "start time not found";
        }
    }


}