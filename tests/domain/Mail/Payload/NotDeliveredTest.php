<?php

namespace Websoftwares\Tests\Domain\Mail\Payload;

use Websoftwares\Domain\Mail\Payload\NotDelivered;

/**
 * Class NotDeliveredTest.
 */
class NotDeliveredTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->notDelivered = new NotDelivered(['delivered' => 0]);
    }

    public function testInstantiateAsObjectSucceeds()
    {
        $this->assertInstanceOf('Websoftwares\Domain\Mail\Payload\NotDelivered', $this->notDelivered);
        $this->assertInstanceOf('FOA\DomainPayload\AbstractPayload', $this->notDelivered);
    }

    public function testGetSucceeds()
    {
        $this->assertEquals(0, $this->notDelivered->get('delivered'));
        $this->assertNull($this->notDelivered->get('none'));
        $this->assertEquals($this->notDelivered->get(), ['delivered' => 0]);
    }
}
