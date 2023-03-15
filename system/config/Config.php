<?php

namespace System\config;


class Config
{

    private static $instance;
    private array $config_nested_array = [];
    private array $config_dot_array = [];

    private function __construct()
    {
        $this->initialConfigArrays();
    }

    private function initialConfigArrays(): void
    {
        $configPath = dirname(__DIR__, 2) . '/config/';
        foreach (glob($configPath . '*.php') as $fileName) {
            $config = require $fileName;
            $key = $fileName;
            $key = str_replace($configPath, '', $key);
            $key = str_replace('.php', '', $key);
            $this->config_nested_array[$key] = $config;
        }

        $this->initialDefaultValues();
        $this->config_dot_array = $this->array_dot($this->config_nested_array);
    }

    private function initialDefaultValues(): void
    {
        $temporary = str_replace($this->config_nested_array['app']['BASE_URL'], '', explode('?', $_SERVER['REQUEST_URI'])[0]);
        $temporary === "/" ? $temporary = "" : $temporary = substr($temporary, 1);
        $this->config_nested_array['app']['CURRENT_ROUTE'] = trim($temporary, " /");
    }


    private function array_dot($array, $return_array = [], $return_key = '')
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $return_array = array_merge($return_array, $this->array_dot($value, $return_array, $return_key . $key . '.'));
            } else {
                $return_array[$return_key . $key] = $value;
            }
        }
        return $return_array;
    }

    private static function getInstance(): Config
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function get($key)
    {
        $instance = self::getInstance();
        return $instance->config_dot_array[$key] ?? false;
    }
}