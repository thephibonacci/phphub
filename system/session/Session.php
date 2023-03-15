<?php

namespace System\session;

class Session
{
    /**
     * Starts a session.
     */
    public static function start(): void
    {
        session_start();
    }

    /**
     * Destroys a session.
     */
    public static function destroy(): void
    {
        session_destroy();
    }

    /**
     * Sets a session value.
     *
     * @param string $key
     * @param mixed $value
     * @return Session
     */
    public static function set(string $key, mixed $value): Session
    {
        $_SESSION[$key] = $value;
        return new self();
    }

    /**
     * Gets a session value.
     *
     * @param string $key
     * @return mixed|null
     */
    public static function get(string $key): mixed
    {
        return $_SESSION[$key] ?? false;
    }
    /**
     * Gets all session.
     *
     * @return array
     */
    public static function all(): array
    {
        return $_SESSION;
    }

    /**
     * Checks if a session value exists.
     *
     * @param string $key
     * @return bool
     */
    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Removes a session value.
     *
     * @param string $key
     * @return Session
     */
    public static function remove(string $key): Session
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
        return new self();
    }

    /**
     * Regenerates the session ID.
     *
     * @return Session
     */
    public static function regenerate(): Session
    {
        session_regenerate_id(true);
        return new self();
    }

    /**
     * Clears all session data.
     *
     * @return Session
     */
    public static function clear(): Session
    {
        session_unset();
        return new self();
    }

    /**
     * Returns the session ID.
     *
     * @return string
     */
    public static function id(): string
    {
        return session_id();
    }
}