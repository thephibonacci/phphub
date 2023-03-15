<?php

namespace System\userAgent;

class UserAgent
{
    private bool $is_mobile = false;
    private bool $is_tablet = false;
    private bool $is_desktop = false;
    private string|array|null $browserName;
    private string $browserVersion;
    private string $osName;
    private string $osVersion;
    private mixed $userAgent;

    public function __construct()
    {
        $this->userAgent = $_SERVER['HTTP_USER_AGENT'];
        ini_set('user_agent', '');

        // Extract browser name and version
        if (preg_match('/(Firefox|OPR|Edge|Chrome|Safari)[\/\s]?([\d.]+)/i', $this->userAgent, $matches)) {
            $this->browserName = $matches[1];
            $this->browserVersion = $matches[2];
        } else {
            $this->browserName = 'Unknown';
            $this->browserVersion = '';
        }

        // Extract operating system name
        if (preg_match('/(Windows NT|Windows|Linux|Mac OS X|Android|iOS)[\/\s]?([\d._]+)?/i', $this->userAgent, $matches)) {
            $this->osName = $matches[1];
            $this->osVersion = $matches[2] ?? '';
        } else {
            $this->osName = 'Unknown';
            $this->osVersion = '';
        }

        // Remove version numbers from the browser name
        $this->browserName = preg_replace('/\/[\d.]+/', '', $this->browserName);

        // Check for mobile devices
        if (preg_match('/(iPhone|iPod|Android|Windows Phone)/i', $this->userAgent)) {
            $this->is_mobile = true;
        }

        // Check for tablets
        if (preg_match('/(iPad|Android)/i', $this->userAgent)) {
            $this->is_tablet = true;
        }

        // Check screen width and height to determine device type
        $screen_width = isset($_SERVER['HTTP_SCREEN_WIDTH']) ? intval($_SERVER['HTTP_SCREEN_WIDTH']) : 0;
        $screen_height = isset($_SERVER['HTTP_SCREEN_HEIGHT']) ? intval($_SERVER['HTTP_SCREEN_HEIGHT']) : 0;

        if ($this->is_mobile || $this->is_tablet) {
            if ($screen_width >= 768 && $screen_height >= 1024) {
                $this->is_tablet = true;
                $this->is_mobile = false;
            } else {
                $this->is_mobile = true;
            }
        } else {
            $this->is_desktop = true;
        }
    }

    public function getOSVersion(): string
    {
        return $this->osVersion;
    }

    public function getOSName(): string
    {
        return $this->osName;
    }

    public function getBrowserName(): array|string|null
    {
        return $this->browserName;
    }

    public function getBrowserVersion(): string
    {
        return $this->browserVersion;
    }

    public function isMobile(): bool
    {
        return $this->is_mobile;
    }

    public function isTablet(): bool
    {
        return $this->is_tablet;
    }

    public function isDesktop(): bool
    {
        return $this->is_desktop;
    }

    public function getUserAgent()
    {
        return $this->userAgent;
    }

}