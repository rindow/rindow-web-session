<?php
namespace Rindow\Web\Session;

class FlashBag
{
    public static function factory($serviceLocator,$componentName,$args)
    {
        $session = $args['session'];
        if(!isset($args['session']))
            throw new Exception\DomainException('Invalid session name');
        if($serviceLocator==null)
            throw new Exception\DomainException('Invalid service locator');
        if($componentName==null)
            throw new Exception\DomainException('Invalid component name');
        $session = $serviceLocator->get($session);
        $container = $session->createContainer($componentName);
        return new self($container);
    }

    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function has($type)
    {
        return isset($this->container[$type]);
    }

    public function add($type,$message)
    {
        if(!$this->has($type))
            $this->container[$type] = array();
        $messages = $this->container[$type];
        $messages[] = $message;
        $this->container[$type] = $messages;
    }

    public function peek($type, array $default=null)
    {
        if(!$this->has($type)) {
            if($default)
                return $default;
            return array();
        }
        return $this->container[$type];
    }

    public function get($type, array $default=null)
    {
        $messages = $this->peek($type, $default);
        unset($this->container[$type]);
        return $messages;
    }

    public function set($type, array $messages)
    {
        $this->container[$type] = $messages;
    }

    public function peekAll()
    {
        return $this->container->toArray();
    }

    public function getAll()
    {
        $all = $this->container->toArray();
        $this->container->clear();
        return $all;
    }

    public function setAll(array $all)
    {
        $this->container->setAll($all);
    }

    public function typeList()
    {
        return $this->container->keys();
    }

    public function flush()
    {
        $this->container->flush();
    }
}