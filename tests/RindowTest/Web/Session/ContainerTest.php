<?php
namespace RindowTest\Web\Session\ContainerTest;

use PHPUnit\Framework\TestCase;
use Rindow\Web\Session\Container;
use Rindow\Web\Session\TestModeSession;

class Test extends TestCase
{
	public function testBasicAccess()
	{
        $this->assertTrue(true);
        $this->markTestIncomplete('may be success');
	}

	public function testSharedContainer()
	{
		$session = new TestModeSession();
		$container1 = $session->createContainer('testContainer');
		$this->assertInstanceOf('Rindow\Web\Session\Container',$container1);
		$this->assertFalse($session->isConnected());
		$container1['foo'] = 'bar';
		$this->assertTrue($session->isConnected());

		//$this->assertEquals(array(),$session->getAll());
		//unset($container);
		//$this->assertEquals(array('testContainer'=>array('foo'=>'bar')),$session->getAll());

		$container2 = $session->createContainer('testContainer');
		$this->assertEquals('bar',$container2['foo']);
		$container2['foo'] = 'boo';

		$this->assertEquals('boo',$container1['foo']);
	}
}