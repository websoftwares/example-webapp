<?php

namespace Websoftwares\Tests\Throttle\Mail;

use League\Container\Container;

/**
 * Class UserActivationProviderTest.
 */
class ThrottleProviderTest extends \PHPUnit_Framework_TestCase
{
    public $container;

    public function setUp()
    {
        $this->container = new Container();
        $this->container->addServiceProvider('Websoftwares\Domain\Throttle\ThrottleProvider');
    }

    public function testInstantiateAsObjectSucceeds()
    {
        $this->assertInstanceOf(
            'League\Container\Container',
            $this->container
            );
    }

    public function testResolveDependenciesSucceeed()
    {
        $expected = 'Websoftwares\Domain\Throttle\ThrottleService';
        $actual = $this->container->get('Websoftwares\Domain\Throttle\ThrottleService');

        $this->assertInstanceOf($expected, $actual);

        $expected =  'Websoftwares\Domain\Throttle\ThrottleFactory';
        $actual = $this->container->get('Websoftwares\Domain\Throttle\ThrottleFactory');

        $this->assertInstanceOf($expected, $actual);
    }
}
