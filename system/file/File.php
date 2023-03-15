<?php

namespace System\file;

class File
{
    public static function PUT(string $filename, mixed $data): bool|int
    {
        return file_put_contents($filename, $data);
    }

    public static function GET(string $filename, bool $use_include_path = false): bool|string
    {
        return file_get_contents($filename, $use_include_path);
    }

    public static function EXIST(string $filename): bool
    {
        return file_exists($filename);
    }
}