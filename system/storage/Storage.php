<?php

namespace System\storage;

class Storage
{
    private static mixed $disk = 'local';
    private static mixed $options = [];

    private function __construct($disk = 'local', $options = [])
    {
        self::$options = $options;
        self::$disk = $disk;
    }

    public static function disk($disk = 'local', $options = []): self
    {
        return new self($disk, $options);
    }

    public static function options($options): self
    {
        self::$options = $options;
        return new static();
    }

    public static function put($path, $contents, $options = []): bool
    {
        return self::getDriver()->put($path, $contents, self::mergeOptions($options));
    }

    public static function get($path, $options = []): bool|string
    {
        return self::getDriver()->get($path, self::mergeOptions($options));
    }

    public static function delete($path, $options = []): bool
    {
        return self::getDriver()->delete($path, self::mergeOptions($options));
    }

    public static function listContents($directory = ''): array
    {
        return self::getDriver()->listContents($directory);
    }

    public static function copy($sourcePath, $destinationPath, $options = []): bool
    {
        return self::getDriver()->copy($sourcePath, $destinationPath, self::mergeOptions($options));
    }

    public static function move($sourcePath, $destinationPath, $options = []): bool
    {
        return self::getDriver()->move($sourcePath, $destinationPath, self::mergeOptions($options));
    }

    public static function getMetadata($path): bool|array
    {
        return self::getDriver()->getMetadata($path);
    }

    public static function url($path, $options = []): bool|string
    {
        return self::getDriver()->url($path, self::mergeOptions($options));
    }

    public static function save($path, $file, $options = []): bool
    {
        return self::getDriver()->save($path, $file, self::mergeOptions($options));
    }

    protected static function getDriver(): LocalDriver
    {
        switch (self::$disk) {
            case 'local':
                return new LocalDriver(self::$options);
            default:
                dd("Unsupported disk: " . self::$disk);
        }
    }

    protected static function mergeOptions($options): array
    {
        return array_merge(self::$options, $options);
    }
}