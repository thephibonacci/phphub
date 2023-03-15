<?php

namespace App\providers;

class SessionProvider extends Provider
{
    public function boot()
    {
        $this->iniSetting();
        $this->startSession();
        $this->configureSessionFlash();
        $this->configureSessionCSRF();
    }

    private function iniSetting()
    {
        ini_set('session.gc_probability', 1);
        ini_set('session.gc_divisor', 1);
        ini_set('session.save_path', dirname(__DIR__, 2) . "/storage/system/session");
        ini_set('session.name', "PHPHUB_SESSID");
        session_name("PHPHUB_SESSID");
        ini_set('session.use_cookies', 'true');
        ini_set('session.use_only_cookies', 'true');
        ini_set('session.sid_bits_per_character', 5);
        ini_set('session.sid_length', 192);
        ini_set("session.cookie_lifetime", 0);
        ini_set('session.gc_maxlifetime', 0);
    }

    private function startSession()
    {
        session_start(['cookie_httponly' => true]);
        session_regenerate_id(true);
    }

    private function configureSessionFlash()
    {
        if (isset($_SESSION['temporary_flash'])) unset($_SESSION['temporary_flash']);
        if (isset($_SESSION['temporary_errorFlash'])) unset($_SESSION['temporary_errorFlash']);
        if (isset($_SESSION['old'])) unset($_SESSION['temporary_old']);

        if (isset($_SESSION['flash'])) {
            $_SESSION['temporary_flash'] = $_SESSION['flash'];
            unset($_SESSION['flash']);
        }

        if (isset($_SESSION['errorFlash'])) {
            $_SESSION['temporary_errorFlash'] = $_SESSION['errorFlash'];
            unset($_SESSION['errorFlash']);
        }

        if (isset($_SESSION['old'])) {
            $_SESSION['temporary_old'] = $_SESSION['old'];
            unset($_SESSION['old']);
        }

        $params = [];
        $params = !isset($_GET) ? $params : array_merge($params, $_GET);
        $params = !isset($_POST) ? $params : array_merge($params, $_POST);
        $_SESSION['old'] = $params;
        unset($params);
    }

    private function configureSessionCSRF()
    {
        if (!isset($_SESSION["CSRF_TOKEN"])) {
            $token = randomToken();
            $_SESSION['CSRF_TOKEN'] = $token;
        }
    }
}