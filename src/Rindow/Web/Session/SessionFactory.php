<?php
namespace Rindow\Web\Session;

class SessionFactory
{
    public static function factory($serviceLocator,$component,$args)
    {
        if(isset($args['testmode']) && $args['testmode']) {
            return new TestModeSession();
        }
        return new Session();
    }
}