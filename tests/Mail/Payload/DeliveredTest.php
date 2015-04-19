<?php

namespace Websoftwares\Tests\Domain\Mail\Payload;

use Websoftwares\Domain\Mail\Payload\Delivered;

/**
 * Class DeliveredTest.
 */
class DeliveredTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->delivered = new Delivered(['delivered' => 1]);
    }

    public function testInstantiateAsObjectSucceeds()
    {
        $this->assertInstanceOf('Websoftwares\Domain\Mail\Payload\Delivered', $this->delivered);
        $this->assertInstanceOf('FOA\DomainPayload\AbstractPayload', $this->delivered);
    }

    public function testGetSucceeds()
    {
        $this->assertEquals(1, $this->delivered->get('delivered'));
        $this->assertNull($this->delivered->get('none'));
        $this->assertEquals($this->delivered->get(), ['delivered' => 1]);
    }
}
