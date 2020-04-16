<?php
namespace Rindow\Web\Session;

use Rindow\Stdlib\Dict;
use Iterator;
use Countable;
use ArrayAccess;

class Container implements Iterator,Countable,ArrayAccess
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
        return $container;
    }

    protected $name;
    protected $connected = false;
    protected $session;

    public function __construct($session,$name)
    {
        $this->session = $session;
        $this->name = $name;
    }

    protected function onAccess()
    {
        if($this->connected)
            return;
        $this->connected = true;
        $old = $this->session->get($this->name);
        if($old) {
            $this->dict = $old;
        } else {
            $this->dict = new Dict();
        }

        $this->session->set($this->name,$this->dict);
    }

    public function offsetExists($name)
    {
        $this->onAccess();
        return $this->dict->offsetExists($name);
    }

    public function offsetGet($name)
    {
        $this->onAccess();
        return $this->dict->offsetGet($name);
    }

    public function offsetSet($name, $value)
    {
        $this->onAccess();
        return $this->dict->offsetSet($name, $value);
    }

    public function offsetUnset($name)
    {
        $this->onAccess();
        return $this->dict->offsetUnset($name);
    }

    public function toArray()
    {
        $this->onAccess();
        return $this->dict->toArray();
    }

    public function isEmpty()
    {
        $this->onAccess();
        return $this->dict->isEmpty();
    }

    public function count()
    {
        $this->onAccess();
        return $this->dict->count();
    }

    public function current()
    {
        $this->onAccess();
        return $this->dict->current();
    }

    public function key()
    {
        $this->onAccess();
        return $this->dict->key();
    }

    public function next()
    {
        $this->onAccess();
        return $this->dict->next();
    }

    public function rewind()
    {
        $this->onAccess();
        return $this->dict->rewind();
    }

    public function valid()
    {
        $this->onAccess();
        return $this->dict->valid();
    }

    public function keys()
    {
        $this->onAccess();
        return $this->dict->keys();
    }

    public function set($name,$value)
    {
        $this->onAccess();
        $this->dict->set($name,$value);
        return $this;
    }

    public function get($name,$default=null)
    {
        $this->onAccess();
        return $this->dict->get($name,$default);
    }

    public function has($name)
    {
        $this->onAccess();
        return $this->dict->has($name);
    }

    public function delete($name)
    {
        $this->onAccess();
        $this->dict->has($name);
        return $this;
    }

    public function clear()
    {
        $this->onAccess();
        $this->dict->clear();
        return $this;
    }

    public function pop($name=null,$default=null)
    {
        $this->onAccess();
        return $this->dict->pop($name,$default);
    }

    public function setDefault($name,$default=null)
    {
        $this->onAccess();
        return $this->dict->setDefault($name,$default);
    }

    public function values()
    {
        $this->onAccess();
        return $this->dict->values();
    }

    public function setAll(array $elements)
    {
        $this->onAccess();
        $this->dict->values($elements);
        return $this;
    }

    public function getAll()
    {
        return $this->dict->getAll();
    }

    public function getName()
    {
        return $this->name;
    }

    public function drop()
    {
        if($this->name==null)
            return;
        $this->onAccess();
        $this->dict = null;
        $this->session->remove($this->name);
        $this->name = null;
    }
}
