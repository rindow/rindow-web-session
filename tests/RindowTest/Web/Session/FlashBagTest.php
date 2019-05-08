<?php
namespace RindowTest\Web\Session\FlashBagTest;

use PHPUnit\Framework\TestCase;
use Rindow\Web\Session\Container;
use Rindow\Web\Session\TestModeSession;
use Rindow\Container\ModuleManager;

class Test extends TestCase
{
    public function setUp()
    {
    }

    public function getConfig()
    {
		$config = array(
			'module_manager' => array(
				'modules' => array(
					'Rindow\Web\Session\Module' => true,
				),
				'enableCache'=>false,
			),
		);
		return $config;
    }
    public function testNormal()
    {
		$mm = new ModuleManager($this->getConfig());
		$bag = $mm->getServiceLocator()->get('Rindow\\Web\\Session\\DefaultFlashBag');

		$bag->add('errors','Foo Error');
		$bag->add('errors','Boo Error');
		$bag->add('info','Bar Information');
		$bag->add('info','Woo Information');
		$bag->add('debug','Dev Debug');
		$bag->add('notice','Success!');

		$this->assertEquals(array('errors','info','debug','notice'),$bag->typeList());
		$this->assertEquals(array('Dev Debug'),$bag->get('debug'));
		$this->assertEquals(array('Success!'),$bag->get('notice'));
		$this->assertEquals(array('errors','info'),$bag->typeList());

		$logs = array();
		foreach ($bag->getAll() as $type => $texts) {
			foreach ($texts as $text) {
				$logs[] = "${type}: ${text}";
			}
		}
		$this->assertEquals(array(
				"errors: Foo Error",
				"errors: Boo Error",
				"info: Bar Information",
				"info: Woo Information",
			),
			$logs
		);
		$this->assertEquals(array(),$bag->getAll());
    }
}
