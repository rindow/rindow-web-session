<?php
namespace Rindow\Web\Session;

class Module
{
    public function getConfig()
    {
        return array(
            'container' => array(
                'components' => array(
                    'Rindow\\Web\\Session\\DefaultFlashBag' => array(
                        'class' => 'Rindow\\Web\\Session\\FlashBag',
                        'factory' => 'Rindow\\Web\\Session\\FlashBag::factory',
                        'factory_args' => array(
                            'session' => 'Rindow\\Web\\Session\\DefaultSession',
                        ),
                    ),
                    'Rindow\\Web\\Session\\DefaultSession' => array(
                        'class' => 'Rindow\\Web\\Session\\Session',
                        'factory' => 'Rindow\\Web\\Session\\SessionFactory::factory',
                        'factory_args' => array(
                            'testmode' => getenv('UNITTEST'),
                        ),
                    ),
                ),
            ),
        );
    }
}
