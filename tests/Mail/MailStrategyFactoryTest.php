<?php

namespace Websoftwares\Tests\Domain\Mail;

use Websoftwares\Domain\Mail\MailStrategyFactory;
use Websoftwares\Domain\Mail\MailMessageEntity;

/**
 * Class MailStrategyFactoryTest.
 */
class MailStrategyFactoryTest extends \PHPUnit_Framework_TestCase
{
    public $mailStrategyFactory;

    public function setUp()
    {
        $this->data = [
                'subject' => 'Hello World',
                'to' => 'boris@Websoftwar.es',
                'from' => 'boris@Websoftwar.es',
                'body' => 'Welcome you, thanks!',
            ];
        $this->mailStrategyFactory = new MailStrategyFactory();
    }

    public function testInstantiateAsObjectSucceeds()
    {
        $this->assertInstanceOf(
            'Websoftwares\Domain\Mail\MailStrategyFactory',
            new MailStrategyFactory());
    }

    public function testNewInstanceSucceeds()
    {
        $actual = $this->mailStrategyFactory->newInstance(
            'mail',
            new MailMessageEntity($this->data)
        );
        $expected = 'Websoftwares\Domain\Mail\Strategy\MailSwiftAdapter';
        $this->assertInstanceOf($expected, $actual);
    }
}
