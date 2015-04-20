<?php

namespace Websoftwares\Tests\domain\Throttle;

use Websoftwares\Domain\Throttle\ThrottleFactory;

/**
 * Class ThrottleFactoryTest.
 */
class ThrottleFactoryTest extends \PHPUnit_Framework_TestCase
{
    public $throttleFactory;
    public $logger;

    public function setUp()
    {
        $this->logger = $this->getMock('Psr\Log\LoggerInterface');
        $this->throttleFactory = new ThrottleFactory();
    }

    public function testInstantiateAsObjectSucceeds()
    {
        $this->assertInstanceOf(
            'Websoftwares\Domain\Throttle\ThrottleFactory',
            $this->throttleFactory);
    }

    public function testNewInstancesSucceeds()
    {
        $expected = 'Websoftwares\Throttle';
        $this->assertInstanceOf($expected, $this->throttleFactory->bRange($this->logger));
        $this->assertInstanceOf($expected, $this->throttleFactory->cRange($this->logger));
        $this->assertInstanceOf($expected, $this->throttleFactory->ipAddress($this->logger));
        $this->assertInstanceOf($expected, $this->throttleFactory->userEmail($this->logger));
    }
}
