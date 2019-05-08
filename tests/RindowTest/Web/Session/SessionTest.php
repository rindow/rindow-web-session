<?php
namespace RindowTest\Web\Session\SessionTest;

use PHPUnit\Framework\TestCase;
use Rindow\Web\Session\TestModeSession;
use Rindow\Container\ModuleManager;

class TestListener
{
	public $connected = 0;
	public function onConnect($session)
	{
		$this->connected += 1;
	}
}

class Test extends TestCase
{
    public function setUp()
    {
    }

	public function testSession()
	{
		$listener = new TestListener();
		$session = new TestModeSession();
		$session->setConnectedEventListener(array($listener,'onConnect'));
		$this->assertFalse($session->isConnected());
		$this->assertEquals(0,$listener->connected);
		$session->set('test','fooValue');
		$this->assertEquals(1,$listener->connected);
		$this->assertEquals('fooValue',$session->get('test'));
		$this->assertTrue($session->has('test'));
		$session->remove('test');
		$this->assertEquals(null,$session->get('test'));
		$this->assertFalse($session->has('test'));
		$this->assertEquals(1,$listener->connected);
		$this->assertEquals('testSessionId',$session->getId());
	}

	public function testTestModeOnModule()
	{
		$config = array(
			'module_manager' => array(
				'modules' => array(
					'Rindow\Web\Session\Module' => true,
				),
				'enableCache'=>false,
			),
		);
		$mm = new ModuleManager($config);
		$session = $mm->getServiceLocator()->get('Rindow\Web\Session\DefaultSession');
		$this->assertInstanceOf('Rindow\Web\Session\TestModeSession',$session);
	}

	public function testSessionOnModule()
	{
		$config = array(
			'module_manager' => array(
				'modules' => array(
					'Rindow\Web\Session\Module' => true,
				),
				'enableCache'=>false,
			),
            'container' => array(
                'components' => array(
                    'Rindow\Web\Session\DefaultSession' => array(
                        'factory_args' => array(
                            'testmode' => false,
                        ),
                    ),
				),
			),
		);
		$mm = new ModuleManager($config);
		$session = $mm->getServiceLocator()->get('Rindow\Web\Session\DefaultSession');
		$this->assertInstanceOf('Rindow\Web\Session\Session',$session);
	}
}