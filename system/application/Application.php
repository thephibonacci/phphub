<?php

namespace System\application;

use System\config\Config;
use System\middlewares\MiddlewarePipeline;
use System\Router\Routing;
use System\router\web\Route;

class Application
{
    public function __construct()
    {
        $this->checkPHPVersion();
        $this->loadHelpers();
        $this->display_error();
        $this->loadProviders();
        $this->registerRoutes();
        $this->runMiddlewares();
        $this->routing();
        $this->checkStatus();
    }

    private function runMiddlewares(): void
    {
        $middlewarePipeline = new MiddlewarePipeline();
        $response = $middlewarePipeline->handle("request");
        if (is_string($response)) {
            function err429()
            {
                require_once dirname(__DIR__, 2) . "/view/err/429.php";
                http_response_code(429);
                die();
            }

            match ($response) {
                "Too many requests" => err429(),
                "Invalid CSRF token" => redirect('419'),
            };
        }
    }

    private function loadProviders(): void
    {
        $appConfigs = require dirname(__DIR__, 2) . '/config/app.php';
        $providers = $appConfigs['providers'];
        foreach ($providers as $provider) {
            $providerObject = new $provider();
            $providerObject->boot();
        }
    }

    private function loadHelpers(): void
    {
        require_once(dirname(__DIR__) . '/helpers/helpers.php');
        foreach (glob(Config::get("app.BASE_DIR") . "/app/helpers/*.php") as $pathName) {
            require_once $pathName;
        }
    }

    private function registerRoutes(): void
    {
        global $Routes;
        $Routes = ['get' => [], 'post' => [], 'put' => [], 'delete' => []];
        Route::get("dl/{id}", function ($ID) {
            $ID = strtolower($ID);
            foreach (glob(Config::get("app.BASE_DIR") . "/res/css/*.css") as $cssFile) {
                if (str_replace(Config::get("app.BASE_DIR") . "/res/css/", "", $cssFile) == $ID . ".css") {
                    getFile($cssFile, "text/css", false);
                    break;
                }
            }
            foreach (glob(Config::get("app.BASE_DIR") . "/res/js/*.js") as $jsFile) {
                if (str_replace(Config::get("app.BASE_DIR") . "/res/js/", "", $jsFile) == $ID . ".js") {
                    getFile($jsFile, "text/javascript", false);
                    break;
                }
            }
            redirect("404");
        });
        foreach (glob(dirname(__DIR__, 2) . '/routes/*.php') as $path) {
            require_once($path);
        }
    }

    private function routing(): void
    {
        $routing = new Routing();
        $routing->run();
    }

    private function checkStatus(): void
    {
        if (Config::get("app.SITE_STATUS") && strtolower(Config::get("app.SITE_STATUS")) == "down") {
            redirect("503");
        }
    }

    private function checkPHPVersion(): void
    {
        if (version_compare(phpversion(), '8', '<')) {
            die("you cant use phpHub because your php version is under 8.0");
        }
    }

    function display_error(): void
    {
        displayError(false);
        if (Config::get("app.DISPLAY_ERRORS")) {
            displayError(Config::get("app.DISPLAY_ERRORS"));
        }
    }
}