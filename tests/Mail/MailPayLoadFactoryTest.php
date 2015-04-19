<?php

namespace Websoftwares\Tests\Domain\Mail;

use Websoftwares\Domain\Mail\MailPayloadFactory;

/**
 * Class MailPayloadFactoryTest.
 */
class MailPayLoadFactoryTest extends \PHPUnit_Framework_TestCase
{
    public $mailPayloadFactory;

    public function setUp()
    {
        $this->mailPayloadFactory = new mailPayloadFactory();
    }

    public function testInstantiateAsObjectSucceeds()
    {
        $this->assertInstanceOf(
            'Websoftwares\Domain\Mail\MailPayloadFactory',
            new MailPayloadFactory());
    }

    public function testNewInstanceSucceeds()
    {
        $actual = $this->mailPayloadFactory->delivered(
            array('deliverd' => 1)
        );
        $expected = 'Websoftwares\Domain\Mail\Payload\Delivered';
        $this->assertInstanceOf($expected, $actual);

        $actual = $this->mailPayloadFactory->notDelivered(
            array('deliverd' => 1)
        );
        $expected = 'Websoftwares\Domain\Mail\Payload\NotDelivered';
        $this->assertInstanceOf($expected, $actual);
    }
}
