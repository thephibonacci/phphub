<?php

namespace System\request;

use System\config\Config;
use System\Request\Traits\HasFileValidationRules;
use System\Request\Traits\HasRunValidation;
use System\Request\Traits\HasValidationRules;

class Request
{
    use HasValidationRules, HasFileValidationRules, HasRunValidation;

    protected $errorExist = false;
    protected $request;
    protected $files = null;
    protected $errorVariablesName = [];

    public function __construct($rules = [])
    {
        if (isset($_POST)) {
            $this->postAttributes();
        }
        if (!empty($_FILES)) {
            $this->files = $_FILES;
        }
        empty($rules) ?: $this->run($rules);
        $this->errorRedirect();
    }

    protected function run($rules): void
    {
        foreach ($rules as $att => $values) {
            if (is_string($values)) {
                $ruleArray = explode('|', $values);
            } else {
                $ruleArray = $values;
            }
            if (in_array('file', $ruleArray)) {
                unset($ruleArray[array_search('file', $ruleArray)]);
                $this->fileValidation($att, $ruleArray);
            } elseif (in_array('number', $ruleArray)) {
                $this->numberValidation($att, $ruleArray);
            } else {
                $this->normalValidation($att, $ruleArray);
            }
        }
    }

    public function file($name)
    {
        return $this->files[$name] ?? false;
    }

    protected function postAttributes(): void
    {
        foreach ($_POST as $key => $value) {
            $this->request[$key] = htmlentities($value);
            $this->__set($key, htmlentities($value));
        }
    }

    public function __set($name, $value)
    {
        // Validate input
        if (!is_string($name) || !preg_match('/^[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*$/', $name)) {
            dd('Invalid property name: ' . $name);
        }
        $this->request[$name] = $value;
    }

    public function __get($name)
    {
        return $this->request[$name] ?? null;
    }

    public function input($name)
    {
        return $this->request[$name];
    }

    public function ip(): ?string
    {
        return getIP();
    }

    public function all()
    {
        return $this->request;
    }

    public function getMethod(): string
    {
        return methodField();
    }

    public function getUri()
    {
        return $_SERVER['REQUEST_URI'];
    }

}