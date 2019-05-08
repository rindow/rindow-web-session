<?php
namespace Rindow\Web\Session;

abstract class AbstractSession
{
    protected $connected = false;
    protected $listener;

    abstract protected function doStart();
    abstract protected function doAbort();
    abstract protected function doDestroySesionCookie();
    abstract protected function doCheckConnected();
    abstract protected function doGetId();
    abstract protected function doSetId($id);
    abstract protected function doSetTimeout($timeout);
    abstract protected function doClear();
    abstract protected function doSet($name,$value);
    abstract protected function doGet($name);
    abstract protected function doCheck($name);
    abstract protected function doRemove($name);
    abstract protected function doGetAll();
    abstract protected function doSetPath($path);
    abstract protected function doGetPath();
    abstract protected function noticeCreatedContainer($name);

    final public function setConnectedEventListener($listener)
    {
        $this->listener = $listener;
    }

    final public function getConnectedEventListener()
    {
        return $this->listener;
    }

    final public function start()
    {
        if(!$this->doStart())
            throw new Exception\DomainException('the session can not start.');
    }

    final public function abort()
    {
        $this->doAbort();
    }

    final public function connect()
    {
        if($this->isConnected())
            return;
        $this->start();
        $this->onConnected();
    }

    private function onConnected()
    {
        $this->connected = true;
        if($this->listener)
            call_user_func($this->listener,$this);
    }

    final public function isConnected()
    {
        if($this->connected)
            return true;
        if($this->doCheckConnected()) {
            $this->onConnected();
            return true;
        }
        return false;
    }

    final public function destroy()
    {
        $this->connect();
        $this->doClear();
        $this->doDestroySesionCookie();
        $this->doAbort();
    }

    final public function getId()
    {
        $this->connect();
        return $this->doGetId();
    }

    final public function setId($id)
    {
        if($this->isConnected())
            throw new Exception\DomainException('session is already started.');
        $this->doSetId($id);
    }

    final public function setTimeout($timeout)
    {
        $this->doSetTimeout($timeout);
    }

    final public function setPath($path)
    {
        return $this->doSetPath($path);
    }

    final public function getPath()
    {
        return $this->doGetPath();
    }

    final public function clear()
    {
        $this->connect();
        $this->doClear();
    }

    final public function set($name,$value)
    {
        $this->connect();
        $this->doSet($name,$value);
        return $this;
    }

    final public function get($name,$default=null,$callback=null)
    {
        $this->connect();
        if($this->has($name))
            return $this->doGet($name);
        if($callback==null)
            return $default;
        $value = $default;
        $args = array($this, $offset, &$value);
        if(call_user_func_array($callback,$args)) {
            $this->set($name, $value);
        }
        return $value;
    }

    final public function has($name)
    {
        $this->connect();
        return $this->doCheck($name);
    }

    final public function remove($name)
    {
        $this->connect();
        $this->doRemove($name);
    }

    final public function getAll()
    {
        $this->connect();
        return $this->doGetAll();
    }

    final public function createContainer($name)
    {
        $container = new Container($this,$name);
        $this->noticeCreatedContainer($name);
        return $container;
    }

    final public function dropContainer($name)
    {
        if($name instanceof Container) {
            $name->drop();
            return;
        }
        $this->connect();
        $this->doDelete($name);
    }
}
