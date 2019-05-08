<?php
namespace Rindow\Web\Session;

use SessionHandlerInterface;

class Session extends AbstractSession
{
    protected function doStart()
    {
        return session_start();
    }

    protected function doAbort()
    {
        return session_destroy();
    }

    protected function doDestroySesionCookie()
    {
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
    }

    protected function doCheckConnected()
    {
        if ( PHP_SAPI /*php_sapi_name()*/ !== 'cli' ) {
            if ( version_compare(phpversion(), '5.4.0', '>=') ) {
                return session_status() === PHP_SESSION_ACTIVE ? true : false;
            } else {
                return session_id() === '' ? false : true;
            }
        }
        return false;
    }

    protected function doGetId()
    {
        return session_id();
    }

    protected function doSetId($id)
    {
        session_id($id);
    }

    protected function doSetPath($path)
    {
        return session_save_path($path);
    }

    protected function doGetPath()
    {
        return session_save_path();
    }

    protected function doSetTimeout($timeout)
    {
        session_cache_expire($timeout);
        session_set_cookie_params($timeout*60);
    }

    protected function doClear()
    {
        $_SESSION = array();
    }

    protected function doSet($name,$value)
    {
        $_SESSION[$name] = $value;
    }

    protected function doGet($name)
    {
        return $_SESSION[$name];
    }

    protected function doCheck($name)
    {
        return array_key_exists($name,$_SESSION);
    }

    protected function doRemove($name)
    {
        unset($_SESSION[$name]);
    }

    protected function doGetAll()
    {
        return $_SESSION;
    }

    protected function noticeCreatedContainer($name)
    {}
}
