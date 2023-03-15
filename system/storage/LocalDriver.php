<?php

namespace System\storage;

use Exception;

class LocalDriver implements LocalDriverInterface
{
    protected mixed $options;

    public function __construct($options = [])
    {
        $this->options = $options;
    }

    public static function put($path, $contents, $options = []): bool
    {
        self::createDirectory(dirname($path));

        if (!file_put_contents($path, $contents)) {
            throw new Exception("Failed to write to file: $path");
        } else {
            return true;
        }
    }

    public static function get($path, $options = []): bool|string
    {
        if (!self::exists($path)) {
            return false;
        }

        return file_get_contents($path);
    }

    public static function delete($path, $options = []): bool
    {
        if (!self::exists($path)) {
            return false;
        }

        return unlink($path);
    }

    public static function listContents($directory = ''): array
    {
        $directory = rtrim($directory, '/') . '/';
        $contents = [];

        foreach (scandir($directory) as $filename) {
            if ($filename === '.' || $filename === '..') {
                continue;
            }

            $path = $directory . $filename;

            $contents[] = [
                'type' => is_dir($path) ? 'dir' : 'file',
                'path' => $path,
                'timestamp' => filemtime($path),
                'size' => is_dir($path) ? null : filesize($path)
            ];
        }

        return $contents;
    }

    public static function copy($sourcePath, $destinationPath, $options = []): bool
    {
        if (!self::exists($sourcePath)) {
            return false;
        }

        if (self::exists($destinationPath)) {
            return false;
        }

        self::createDirectory(dirname($destinationPath));

        return copy($sourcePath, $destinationPath);
    }

    public static function move($sourcePath, $destinationPath, $options = []): bool
    {
        if (!self::exists($sourcePath)) {
            return false;
        }

        if (self::exists($destinationPath)) {
            return false;
        }

        self::createDirectory(dirname($destinationPath));

        return rename($sourcePath, $destinationPath);
    }

    public static function getMetadata($path): bool|array
    {
        if (!self::exists($path)) {
            return false;
        }

        return [
            'type' => is_dir($path) ? 'dir' : 'file',
            'path' => $path,
            'timestamp' => filemtime($path),
            'size' => is_dir($path) ? null : filesize($path)
        ];
    }

    public static function url($path, $options = []): bool|string
    {
        if (!self::exists($path)) {
            return false;
        }

        return realpath($path);
    }

    public static function save($path, $file, $options = []): bool
    {
        self::createDirectory(dirname($path));
        if (!move_uploaded_file($file['tmp_name'], $path)) {
            throw new Exception("Failed to upload file: {$file['name']}");
        } else {
            return  true;
        }
    }

    protected static function exists($path): bool
    {
        return file_exists($path);
    }

    protected static function createDirectory($directory)
    {
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
    }
}
