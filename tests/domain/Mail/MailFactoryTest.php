<?php

namespace Websoftwares\Tests\domain\Mail;

use Websoftwares\Domain\Mail\MailFactory;

/**
 * Class MailFactoryTest.
 */
class MailFactoryTest extends \PHPUnit_Framework_TestCase
{
    public $mailFactory;

    public function setUp()
    {
        $this->data = [
                'subject' => 'Hello World',
                'to' => 'boris@Websoftwar.es',
                'from' => 'boris@Websoftwar.es',
                'body' => 'Welcome you, thanks!',
            ];
        $this->mailFactory = new MailFactory();
    }

    public function testInstantiateAsObjectSucceeds()
    {
        $this->assertInstanceOf(
            'Websoftwares\Domain\Mail\MailFactory',
            new MailFactory());
    }

    public function testNewInstanceSucceeds()
    {
        $actual = $this->mailFactory->newMailMessageEntity($this->data);
        $expected = 'Websoftwares\Domain\Mail\MailMessageEntity';
        $this->assertInstanceOf($expected, $actual);
    }
}
