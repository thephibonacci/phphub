<?php

namespace System\router;

use ReflectionMethod;
use System\config\Config;

class Routing
{
    private array $current_route;
    private string $method_field;
    private array $routes;
    private array $values = [];

    public function __construct()
    {
        $this->current_route = explode("/", Config::get("app.CURRENT_ROUTE"));
        $this->method_field = $this->methodField();
        global $Routes;
        $this->routes = $Routes;
    }

    public function run(): void
    {

        $match = $this->match();
        if (empty($match)) {
            $this->error404();
        }
        if ($match['class'] === null) {
            call_user_func_array($match['method'], $this->values);
        } else {
            $classPath = str_replace("\\", "/", $match['class']);
            $path = Config::get("app.BASE_DIR") . "/app/controllers/" . $classPath . ".php";
            if (!file_exists($path)) {
                die("controller not found");
            }
            $class = "\App\controllers\\" . $match['class'];
            $object = new $class();
            if (method_exists($object, $match['method'])) {
                $reflection = new ReflectionMethod($object, $match['method']);
                $parameterCount = $reflection->getNumberOfParameters();
                if ($parameterCount <= count($this->values)) {
                    call_user_func_array([$object, $match['method']], $this->values);
                } else {
                    $this->error404();
                }
            } else {
                die("method not found");
            }
        }
    }

    public function match(): array
    {
        $reservedRoutes = $this->routes[$this->method_field];
        foreach ($reservedRoutes as $reservedRoute) {
            if ($this->compare($reservedRoute['url'])) {
                return ["class" => $reservedRoute['class'], "method" => $reservedRoute['method']];
            } else {
                $this->values = [];
            }
        }
        return [];
    }

    private function compare($reservedRouteUrl): bool
    {
        if (trim($reservedRouteUrl, " /") === '') {
            return trim($this->current_route[0], " /") === '';
        }
        $reservedRouteUrlArray = explode("/", $reservedRouteUrl);
        if (sizeof($this->current_route) != sizeof($reservedRouteUrlArray)) {
            return false;
        }
        foreach ($this->current_route as $key => $currentRouteElement) {
            $reservedRouteUrlElement = $reservedRouteUrlArray[$key];
            if (str_starts_with($reservedRouteUrlElement, "{") && str_ends_with($reservedRouteUrlElement, "}")) {
                $this->values[] = $currentRouteElement;
            } elseif ($reservedRouteUrlElement != $currentRouteElement) {
                return false;
            }
        }
        return true;
    }

    public function error404(): void
    {
       redirect('404');
    }

    public function methodField(): string
    {
        $method_field = strtolower($_SERVER['REQUEST_METHOD']);
        if ($method_field == 'post') {
            if (isset($_POST['_method'])) {
                if (strtolower($_POST['_method']) == "put") {
                    $method_field = "put";
                } elseif (strtolower($_POST['_method']) == "delete") {
                    $method_field = "delete";
                }
            }
        }
        return $method_field;
    }

}