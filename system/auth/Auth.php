<?php

namespace System\auth;

use App\models\User;
use System\session\Session;

class Auth
{
    private string $redirectTo = "/login";

    private function userMethod()
    {
        if (!Session::get('user')) {
            redirect($this->redirectTo);
        }
        $user = User::find(Session::get('user'));
        if (empty($user)) {
            Session::remove('user');
            redirect($this->redirectTo);
        } else {
            return $user;
        }
    }

    private function checkMethod()
    {
        if (!Session::get('user')) {
            redirect($this->redirectTo);
        }
        $user = User::find(Session::get('user'));
        if (empty($user)) {
            Session::remove('user');
            redirect($this->redirectTo);
        } else
            return true;
    }

    private function checkLoginMethod(): bool
    {
        if (!Session::get('user')) {
            return false;
        }
        $user = User::find(Session::get('user'));
        if (empty($user)) {
            return false;
        } else
            return true;
    }

    private function logoutMethod(): void
    {
        Session::remove('user');
    }

    public function __call($name, $arguments)
    {
        return $this->methodCaller($name, $arguments);
    }

    public static function __callStatic($name, $arguments)
    {
        $instance = new self();
        return $instance->methodCaller($name, $arguments);
    }

    private function methodCaller($method, $arguments)
    {
        $suffix = 'Method';
        $methodName = $method . $suffix;
        return call_user_func_array([$this, $methodName], $arguments);
    }
}