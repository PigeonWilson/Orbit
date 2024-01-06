<?php
if (!defined('PREVENT_DIRECT_FILE_ACCESS_CONST')) die();
class Session
{
    public function create(string $key, $content) : bool
    {
        if (!isset($_SESSION)) return false;
        if (isset($_SESSION[strtolower($key)])) return false;
        if (!isset($_SESSION[strtolower($key)]))
        {
            $_SESSION[strtolower($key)] = serialize($content);

            if (isset($_SESSION[strtolower($key)])) return true;
            if (!isset($_SESSION[strtolower($key)])) return false;
        }

        return false;
    }

    public function delete(string $key) : bool
    {
        if (!isset($_SESSION)) return false;
        if (!isset($_SESSION[strtolower($key)])) return false;
        unset($_SESSION[strtolower($key)]);

        if (isset($_SESSION[strtolower($key)])) return false;
        if (!isset($_SESSION[strtolower($key)])) return true;

        return false;
    }

    public function update(string $key, $content) : bool
    {
        if (!isset($_SESSION)) return false;
        if (!isset($_SESSION[strtolower($key)])) return false;
        if (isset($_SESSION[strtolower($key)]))
        {
            $_SESSION[strtolower($key)] = serialize($content);
            return true;
        }

        return false;
    }

    public function read(string $key)
    {
        if (!isset($_SESSION)) return null;
        if (isset($_SESSION[strtolower($key)])) return unserialize($_SESSION[strtolower($key)]);
        return null;
    }

    public function exist(string $key) : bool
    {
        $flag = true;
        if (!isset($_SESSION[strtolower($key)])) $flag = false;
        if (isset($_SESSION[strtolower($key)]) && $_SESSION[strtolower($key)]  == '') $flag = false;
        return $flag;
    }

    public function flush()
    {
        session_destroy();
    }
}