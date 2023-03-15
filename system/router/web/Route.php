<?php

namespace System\router\web;
class Route
{
    protected static array $prefix = [];

    public static function group(array $option, callable $callable): void
    {
        self::$prefix[] = isset($option['prefix']) ? trim($option['prefix'], " /") : "";
        $callable();
        array_pop(self::$prefix);
    }

    public static function redirect(string $from, string $to, string|int $response_code = 0, ?string $name = null, bool $replace = true): void
    {
        self::configRoute($from, function () use ($to, $response_code, $replace) {
            redirect($to, $response_code, $replace);
        }, $name, "get");
    }

    public static function view(string $url, string $dir, array $data = [], ?string $name = null, int $httpStatus = 200, array $httpHeaders = []): void
    {
        self::configRoute($url, function () use ($dir, $data, $httpStatus, $httpHeaders) {
            view($dir, $data, $httpStatus, $httpHeaders);
        }, $name, "get");
    }

    public static function lightView(string $url, string $dir, array $data = [], ?string $name = null, int $httpStatus = 200, array $httpHeaders = []): void
    {
        self::configRoute($url, function () use ($dir, $data, $httpStatus, $httpHeaders) {
            lightView($dir, $data, $httpStatus, $httpHeaders);
        }, $name, "get");
    }

    public static function match(array $methods, string $url, callable|string $action, ?string $name = null): void
    {
        foreach ($methods as $method) {
            self::configRoute($url, $action, $name, strtolower($method));
        }
    }

    public static function any(string $url, callable|string $action, ?string $name = null): void
    {
        foreach (['post', 'put', 'delete', 'get'] as $method) {
            self::configRoute($url, $action, $name, $method);
        }
    }

    /**
     * Register a new GET route with the router.
     * @param string $url
     * @param callable|string $action
     * @param string|null $name
     */
    public static function get(string $url, callable|string $action, ?string $name = null): void
    {
        self::configRoute($url, $action, $name, "get");
    }

    /**
     * Register a new POST route with the router.
     * @param string $url
     * @param callable|string $action
     * @param string|null $name
     */
    public static function post(string $url, callable|string $action, ?string $name = null): void
    {
        self::configRoute($url, $action, $name, "post");
    }

    /**
     * Register a new PUT route with the router.
     * @param string $url
     * @param callable|string $action
     * @param string|null $name
     */
    public static function put(string $url, callable|string $action, ?string $name = null): void
    {
        self::configRoute($url, $action, $name, "put");
    }

    /**
     * Register a new DELETE route with the router.
     * @param string $url
     * @param callable|string $action
     * @param string|null $name
     */
    public static function delete(string $url, callable|string $action, ?string $name = null): void
    {
        self::configRoute($url, $action, $name, "delete");
    }

    /**
     * config route for prepare to register routing system.
     * @param string $url
     * @param callable|string $action
     * @param string|null $name
     * @param string $methodRoute
     */
    private static function configRoute(string $url, callable|string $action, ?string $name, string $methodRoute): void
    {
        if (is_callable($action)) {
            $class = null;
            $method = $action;
        } else {
            $action = explode("@", $action);
            $class = $action[0];
            $method = $action[1];
        }
        self::addRoute($methodRoute, trim(implode("/", self::$prefix), " /") . "/" . trim($url, " /"), $class, $method, $name);
    }

    /**
     * register new route to routing system.
     * @param string $methodVerb
     * @param string|null $url
     * @param string|null $class
     * @param callable|string $method
     * @param string|null $name
     */
    private static function addRoute(string $methodVerb, ?string $url, ?string $class, callable|string $method, ?string $name): void
    {
        global $Routes;
        $Routes[$methodVerb][trim($url, '/ ')] = ['url' => trim($url, '/ '), 'class' => $class, 'method' => $method, 'name' => $name];
    }

    public function __call(string $name, array $arguments)
    {
        $instance = new self();
        call_user_func_array([$instance, $name], $arguments);
    }
}
