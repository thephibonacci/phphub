<?php


namespace System\storage;

interface LocalDriverInterface
{
    public static function put($path, $contents, $options = []);

    public static function get($path, $options = []);

    public static function delete($path, $options = []);

    public static function listContents($directory = '');

    public static function copy($sourcePath, $destinationPath, $options = []);

    public static function move($sourcePath, $destinationPath, $options = []);

    public static function getMetadata($path);

    public static function url($path, $options = []);

    public static function save($path, $file, $options = []);
}
