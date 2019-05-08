<?php
namespace Rindow\Web\Session;

class TestModeSession extends AbstractSession
{
    private $sessionId = null;
    private $data = array();
    private $path;
    private $createdContainerNames = array();

    protected function doStart()
    {
        if($this->sessionId)
            return false;
        $this->sessionId = 'testSessionId';
        return true;
    }

    protected function doAbort()
    {
        $this->sessionId = null;
    }

    protected function doDestroySesionCookie()
    {
    }

    protected function doCheckConnected()
    {
        return isset($this->sessionId);
    }

    protected function doGetId()
    {
        return $this->sessionId;
    }

    protected function doSetId($id)
    {
        if($this->sessionId)
            throw new Exception\DomainException('Session is already started.');
        $this->sessionId = $id;
    }

    protected function doSetPath($path)
    {
        if($this->sessionId)
            throw new Exception\DomainException('Session is already started.');
        return $this->path = $path;
    }

    protected function doGetPath()
    {
        return $this->path;
    }

    protected function doSetTimeout($timeout)
    {
    }

    protected function doClear()
    {
        $this->data = array();
    }

    protected function doSet($name,$value)
    {
        $this->data[$name] = $value;
    }

    protected function doGet($name)
    {
        return $this->data[$name];
    }

    protected function doCheck($name)
    {
        return array_key_exists($name,$this->data);
    }

    protected function doRemove($name)
    {
        unset($this->data[$name]);
    }

    protected function doGetAll()
    {
        return $this->data;
    }

    protected function noticeCreatedContainer($name)
    {
        $this->createdContainerNames[] = $name;
    }

    public function getCreatedContainerNames()
    {
        return $this->createdContainerNames;
    }

    public function export()
    {
        return $this->data;
    }

    public function import($data)
    {
        $this->data = $data;
    }
}
