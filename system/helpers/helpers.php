<?php

use System\{config\Config, database\dbBuilder\DBbuilder, database\lightSQL, request\Request, view\ViewBuilder};

/**
 * It accepts any number of parameters and takes var_dump one by one and dies at the end
 * @param ...$vars
 * @return void
 */
function dd(...$vars): void
{
    echo "<pre>";
    foreach ($vars as $v) {
        var_dump($v);
    }
    die();
}

/**
 * It accepts any number of parameters and takes var_dump one by one
 * @param ...$vars
 * @return void
 */
function dump(...$vars): void
{
    echo "<pre>";
    foreach ($vars as $v) {
        var_dump($v);
    }
}

/**
 * It accepts any number of parameters and takes print_r one by one and dies at the end
 * @param ...$vars
 * @return void
 */
function debug(...$vars): void
{
    echo "<pre>";
    foreach ($vars as $v) {
        print_r($v);
    }
    die();
}

/**
 * It for display a view
 * @param string $dir
 * @param array $data
 * @param int $httpStatus
 * @param array $httpHeaders
 * @return void
 */
function view(string $dir, array $data = [], int $httpStatus = 200, array $httpHeaders = []): void
{
    http_response_code($httpStatus);
    foreach ($httpHeaders as $name => $values) {
        header($name . ': ' . $values);
    }
    $viewBuilder = new ViewBuilder();
    $viewBuilder->run($dir);
    $viewVars = $viewBuilder->vars;
    $content = $viewBuilder->content;
    empty($viewVars) ?: extract($viewVars);
    empty($data) ?: extract($data);
    eval(" ?> " . html_entity_decode($content));
}

/**
 * It for display a view but with less process
 * @param string $dir
 * @param array $data
 * @param int $httpStatus
 * @param array $httpHeaders
 * @return void
 */
function lightView(string $dir, array $data = [], int $httpStatus = 200, array $httpHeaders = []): void
{
    $file = Config::get("app.BASE_DIR") . "/view/" . str_replace('.', DIRECTORY_SEPARATOR, $dir) . '.php';
    http_response_code($httpStatus);
    foreach ($httpHeaders as $name => $values) {
        header($name . ': ' . $values);
    }
    extract($data);
    require_once trim($file, " /.");
}

/**
 * This function is summarized in html_entity_decode
 * @param $text
 * @return string
 */
function html($text): string
{
    return html_entity_decode($text);
}

/**
 * It is used to display the value of previous inputs
 * @param $name
 * @return mixed
 */
function old($name): mixed
{
    return $_SESSION["temporary_old"][$name] ?? null;
}


/**
 * It is used to display or set a message flash
 * @param $name
 * @param $message
 * @return false|mixed|void
 */
function flash($name, $message = null)
{
    if (empty($message)) {
        if (isset($_SESSION["temporary_flash"][$name])) {
            $temporary = $_SESSION["temporary_flash"][$name];
            unset($_SESSION["temporary_flash"][$name]);
            return $temporary;
        } else {
            return false;
        }
    } else {
        $_SESSION["flash"][$name] = $message;
    }
}

/**
 * To check the presence of copper flash
 * @param $name
 * @return bool
 */
function flashExists($name): bool
{
    return isset($_SESSION["temporary_flash"][$name]) === true;
}

/**
 * return all flash messages
 * @return mixed
 */
function allFlashes(): mixed
{
    if (isset($_SESSION["temporary_flash"])) {
        $temporary = $_SESSION["temporary_flash"];
        unset($_SESSION["temporary_flash"]);
        return $temporary;
    } else {
        return false;
    }
}


/**
 * It is used to display or set error message flash
 * @param $name
 * @param $message
 * @return false|mixed|void
 */
function error($name, $message = null)
{
    if (empty($message)) {
        if (isset($_SESSION["temporary_errorFlash"][$name])) {
            $temporary = $_SESSION["temporary_errorFlash"][$name];
            unset($_SESSION["temporary_errorFlash"][$name]);
            return $temporary;
        } else {
            return false;
        }
    } else {
        $_SESSION["errorFlash"][$name] = $message;
    }
}

/**
 * To check the presence of copper error flash
 * @param $name
 * @return bool|int
 */
function errorExists($name = null): bool|int
{
    if ($name === null) {
        return isset($_SESSION['temporary_errorFlash']) === true ? count($_SESSION['temporary_errorFlash']) : false;
    } else {
        return isset($_SESSION['temporary_errorFlash'][$name]) === true;
    }
}

/**
 * return all flash messages
 * @return mixed
 */
function allErrors(): mixed
{
    if (isset($_SESSION["temporary_errorFlash"])) {
        $temporary = $_SESSION["temporary_errorFlash"];
        unset($_SESSION["temporary_errorFlash"]);
        return $temporary;
    } else {
        return false;
    }
}

/**
 * return current Domain
 * @return string
 */
function currentDomain(): string
{
    return trim(httpProtocol() . $_SERVER['HTTP_HOST'], " /");
}

/**
 * redirect internal website
 * @param string $url
 * @param string|int $response_code
 * @param bool $replace
 * @return void
 */
function redirect(string $url, string|int $response_code = 0, bool $replace = true): void
{
    $url = trim($url, '/ ');
    $url = str_starts_with($url, currentDomain()) ? $url : currentDomain() . '/' . $url;
    header("Location: " . $url, $replace, (int)$response_code);
    die();
}

/**
 * redirect back
 * @param string $default
 * @return void
 */
function back(string $default = "/"): void
{
    $http_referer = $_SERVER['HTTP_REFERER'] ?? null;
    ($http_referer == null) ? redirect($default) : redirect($http_referer);
}

/**
 * redirect external website
 * @param $url
 * @return void
 */
function externalRedirect($url): void
{
    header("Location: " . trim($url, " /"));
    die();
}


/**
 * create url with CurrentDomain prefix
 * @param $url
 * @return string
 */
function url($url): string
{
    return currentDomain() . ("/" . trim($url, "/ "));
}

/**
 * helper function for route()
 * @param string $name
 * @return mixed
 */
function findRouteByName(string $name): mixed
{
    global $Routes;
    $allRoutes = array_merge($Routes['get'], $Routes['post'], $Routes['put'], $Routes['delete']);
    $route = null;
    foreach ($allRoutes as $r) {
        if ($r['name'] == $name && $r['name'] !== null) {
            $route = $r['url'];
            break;
        }
    }
    return $route;
}

/**
 * find and return route url with routeName
 * @param string $name
 * @param array $params
 * @return string
 */
function route(string $name, array $params = []): string
{
    $route = findRouteByName($name);
    if ($route === null) {
        var_dump('route not found');
        die;
    }
    $params = array_reverse($params);
    $routeParamsMatch = [];
    preg_match_all("/{[^}.]*}/", $route, $routeParamsMatch);
    if (count($routeParamsMatch[0]) > count($params)) {
        var_dump('route params not enough!!');
        die;
    }
    foreach ($routeParamsMatch[0] as $routeMatch) {
        $route = str_replace($routeMatch, array_pop($params), $route);
    }
    return currentDomain() . "/" . trim($route, " /");
}


/**
 * return current http method
 * @return string
 */
function methodField(): string
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


/**
 * convert array to array dot
 * @param $array
 * @param array $return_array
 * @param string $return_key
 * @return array|mixed
 */
function array_dot($array, array $return_array = [], string $return_key = ''): mixed
{
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $return_array = array_merge($return_array, array_dot($value, $return_array, $return_key . $key . '.'));
        } else {
            $return_array[$return_key . $key] = $value;
        }
    }
    return $return_array;
}


/**
 * return full url
 * @return string
 */
function currentUrl(): string
{
    return currentDomain() . $_SERVER['REQUEST_URI'];
}

/**
 * return current http protocol
 * @return string
 */
function httpProtocol(): string
{
    return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === "on") ? "https://" : "http://";
}

/**
 * helper function for dl
 * @param $path
 * @param $type
 * @param bool $age
 * @return void
 */
function getFile($path, $type, bool $age = true): void
{
    if ($type == null || $type == '') {
        header('Content-Type: application/octet-stream');
    } else {
        header('Content-Type: ' . $type);
    }
    header('Content-Description: File Transfer');
    header('X-Content-Type-Options: nosniff');
    header('Content-Disposition: attachment; filename="' . basename($path) . '"');
    if ($age) {
        header('Cache-Control: max-age=604800, must-revalidate');
        header('Expires: 604800');
    } else {
        ob_clean();
        header('Cache-Control: must-revalidate');
        header('Expires: 0');
    }
    header('Pragma: public');
    header('Content-Length: ' . filesize($path));
    readfile($path);
    die();
}

/**
 * helper function for dl
 * @param $name
 * @return string
 */
function getDL($name): string
{
    return "/dl/" . $name;
}

/**
 * check isset http referer
 * @param $url
 * @return bool
 */
function checkReferer($url = null): bool
{
    if ($_SERVER['HTTP_REFERER'] == null) {
        return false;
    } elseif ($url != null) {
        $url = trim($url, '/ ');
        $url = str_starts_with($url, currentDomain()) ? $url : currentDomain() . '/' . $url;
        return $url == $_SERVER['HTTP_REFERER'];
    } else {
        return true;
    }
}

/**
 * helper function for show or hide errors
 * @param $status
 * @return void
 */
function displayError($status): void
{
    if ($status) {
        ini_set("display_errors", 1);
        ini_set("display_startup_errors", 1);
        error_reporting(E_ALL & ~E_DEPRECATED);
    } else {
        ini_set("display_errors", 0);
        ini_set("display_startup_errors", 0);
        error_reporting(0);
    }
}

/**
 * helper function for generate a random token
 * @param int $len
 * @return string
 */
function randomToken(int $len = 64): string
{
    $key = '';
    $keys = array_merge(range(0, 9), range('a', 'f'), range('A', 'F'), ['h', 'H', 'k', 'K', 'm', 'M', 'o', 'O', 's', 'S', 't', 'T', 'v', 'V', 'w', 'W', 'x', 'X', 'y', 'Y', 'z', 'Z', 'p', 'P']);
    for ($i = 0; $i < $len; $i++) {
        $key .= $keys[array_rand($keys)];
    }
    return $key;
}

/**
 * helper function for generate random number with custom digits
 * @param int $digits
 * @return int
 * @throws Exception
 */
function randomNumber(int $digits = 6): int
{
    $min = pow(10, $digits - 1);
    $max = pow(10, $digits) - 1;
    return random_int($min, $max);
}

/**
 * helper function for hash sha256
 * @param $data
 * @return string
 */
function sha256($data): string
{
    return hash("sha256", $data);
}

/**
 * helper function for hash sha512
 * @param $data
 * @return string
 */
function sha512($data): string
{
    return hash("sha512", $data);
}

/**
 * helper function for hash bcrypt
 * @param $value
 * @param int $cost
 * @return string
 */
function bcrypt($value, int $cost = 12): string
{
    return password_hash($value, PASSWORD_BCRYPT, ['cost' => $cost]);
}

/**
 * helper function for compare passwords
 * @param $password
 * @param $hash
 * @return bool
 */
function verifyPassword($password, $hash): bool
{
    return password_verify($password, $hash);
}

/**
 * get current ip user
 * @return string|null
 */
function getIP(): ?string
{
    $IP = match (true) {
        isset($_SERVER['HTTP_CLIENT_IP']) => $_SERVER['HTTP_CLIENT_IP'],
        isset($_SERVER['HTTP_X_FORWARDED_FOR']) => $_SERVER['HTTP_X_FORWARDED_FOR'],
        isset($_SERVER['HTTP_X_FORWARDED']) => $_SERVER['HTTP_X_FORWARDED'],
        isset($_SERVER['HTTP_FORWARDED_FOR']) => $_SERVER['HTTP_FORWARDED_FOR'],
        isset($_SERVER['HTTP_FORWARDED']) => $_SERVER['HTTP_FORWARDED'],
        isset($_SERVER['REMOTE_ADDR']) => $_SERVER['REMOTE_ADDR'],
        default => null,
    };
    $IP = $IP ?? null;
    if ($IP && filter_var($IP, FILTER_VALIDATE_IP)) {
        if ($IP == '::1') {
            $IP = '127.0.0.1';
        }
        return $IP;
    }
    return null;
}

/**
 * helper function for remove a file
 * @param $path
 * @return void
 */
function removeFile($path): void
{
    $path = trim(System\config\Config::get("BASE_DIR"), " /") . trim($path, "/ ");
    if (file_exists($path)) {
        unlink($path);
    }
}

/**
 * helper function for abort app
 * @param int $code
 * @param string $message
 * @return void
 */
function abort(int $code, string $message = ""): void
{
    http_response_code($code);
    if (!empty($message)) {
        echo "<h1>Error $code</h1><p>$message</p>";
    }
    die();
}


/**
 * create slug with text
 * @param string $str
 * @return string
 */
function slug(string $str): string
{
    $str = strtolower($str);
    $str = preg_replace('/[^a-z0-9]+/', '-', $str);
    return trim($str, '-');
}

/**
 * check user access
 * @param $user_id
 * @param array $access
 * @param string $redirectIfNotAccess
 * @return void
 */
function roleAccess($user_id, array $access, string $redirectIfNotAccess = "/"): void
{
    $query = lightSQL::select("SELECT * FROM `users` WHERE `users`.`id` = " . $user_id . " AND  `role` IN (" . trim(implode(", ", $access), " ,") . ")")->rowCount();
    if ($query == 0) {
        redirect($redirectIfNotAccess);
        die();
    }
}

/**
 * helper function for run migrations
 * @return void
 */
function runMigrations(): void
{
    DBbuilder::run();
}

/**
 * helper function for add js
 * @param string|array $dlID
 * @return string
 */
function groupJs(string|array $dlID): string
{
    if (is_array($dlID)) {
        $res = "";
        foreach ($dlID as $id) {
            $res .= "<script src = '/dl/$id'></script>";
        }
        return $res;
    } else {
        return "<script src='/dl/$dlID'></script>";
    }
}

/**
 * helper function for add css
 * @param string|array $dlID
 * @return string
 */
function groupCss(string|array $dlID): string
{
    if (is_array($dlID)) {
        $res = "";
        foreach ($dlID as $id) {
            $res .= "<link rel='stylesheet' href='/dl/$id'>";
        }
        return $res;
    } else {
        return "<link rel='stylesheet' href='/dl/$dlID'>";
    }
}

/**
 * if call this function redirect to 403 and die
 * @return void
 */
function denyPermission(): void
{
    redirect("403");
}


function app($className, array $parameters = [])
{
    $reflectionClass = new ReflectionClass($className);
    $constructor = $reflectionClass->getConstructor();
    if ($constructor === null) {
        return new $className();
    }
    $constructorParameters = $constructor->getParameters();
    $resolvedParameters = [];
    foreach ($constructorParameters as $parameter) {
        $parameterName = $parameter->getName();
        if (isset($parameters[$parameterName])) {
            $resolvedParameters[] = $parameters[$parameterName];
        } else if ($parameter->isDefaultValueAvailable()) {
            $resolvedParameters[] = $parameter->getDefaultValue();
        } else {
            throw new Exception("Unable to resolve parameter '{$parameterName}' for class '{$className}'");
        }
    }
    return $reflectionClass->newInstanceArgs($resolvedParameters);
}

function get_csrf()
{
    return $_SESSION['CSRF_TOKEN'];
}

function get_csrf_input(): string
{
    return '<input type="hidden" hidden value="' . get_csrf() . '" name="CSRF_TOKEN">';
}

function email($email, $minLen = null, $maxLen = null): bool|string
{
    $regexEmail = "/[^\s@]+@[^\s@]+\.[^\s@]+/";
    if ($maxLen != null) {
        if (strlen($email) > $maxLen) {
            return false;
        }
    }
    if ($minLen != null) {
        if (strlen($email) < $minLen) {
            return false;
        }
    }
    if (preg_match($regexEmail, $email)) {
        return $email;
    } else {
        return false;
    }
}

function password($password, $minLen = 8, $maxLen = 64)
{
    $regexPassword = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{" . $minLen . "," . $maxLen . "}$/";
    if (preg_match($regexPassword, $password)) {
        return $password;
    } else {
        return false;
    }
}

function username($username, $minLen = 3, $maxLen = 30)
{
    $regexUsername = "/^(?=.{" . $minLen . "," . $maxLen . "}$)(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+(?<![_.])$/";
    if (preg_match($regexUsername, $username)) {
        return $username;
    } else {
        return false;
    }
}

function fullName($fullName, $minLen = null, $maxLen = null)
{
    if ($maxLen != null) {
        if (strlen($fullName) > $maxLen) {
            return false;
        }
    }
    if ($minLen != null) {
        if (strlen($fullName) < $minLen) {
            return false;
        }
    }
    $regexFullName = "/^\p{L}([-']?\p{L}+)*( \p{L}([-']?\p{L}+)*)+$/";
    if (preg_match($regexFullName, $fullName)) {
        return $fullName;
    } else {
        return false;
    }
}

function validation($value, $minLen = null, $maxLen = null): bool|string
{
    if ($maxLen != null) {
        if (strlen($value) > $maxLen) {
            return false;
        }
    }
    if ($minLen != null) {
        if (strlen($value) < $minLen) {
            return false;
        }
    }
    $value = trim($value);
    $value = stripslashes($value);
    $value = str_replace(";", "", $value);
    return htmlentities($value);
}

function session(string|array $value)
{
    if (is_array($value)) {
        if (array_keys($value) !== range(0, count($value) - 1)) {
            foreach ($value as $k => $v) {
                $_SESSION[trim($k)] = trim($v);
            }
            return true;
        } else {
            $value = array_filter($value);
            $result = [];
            foreach ($value as $val) {
                $result[] = $_SESSION[$val];
            }
            return $result;
        }
    } elseif (is_string($value)) {
        return $_SESSION[$value];
    }
}

function forgetSession($key): void
{
    unset($_SESSION[$key]);
}

function flushSession(): void
{
    session_destroy();
}

function hasSession($key): bool
{
    return array_key_exists($key, $_SESSION);
}

function allSession(): array
{
    return $_SESSION;
}

function config($config)
{
    return Config::get($config);
}


function request($rules = []): Request
{
    try {
        return app(Request::class, ['rules' => $rules]);
    } catch (Exception $e) {
        dd($e);
    }
}