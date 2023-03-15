<?php

namespace System\view\traits;

trait HasViewLoader
{
    private $viewNameArray = [];

    private function viewLoader($dir): string
    {
        $dir = trim($dir, " ./");
        $dir = str_replace(".", "/", $dir);
        if (file_exists(dirname(__DIR__, 3) . "/view/$dir.php")) {
            $this->registerView($dir);
            return htmlentities(file_get_contents(dirname(__DIR__, 3) . "/view/$dir.php"),ENT_COMPAT);
        } else {
            var_dump("error");
            die();
        }
    }

    private function registerView($view): void
    {
        $this->viewNameArray[] = $view;
    }

}