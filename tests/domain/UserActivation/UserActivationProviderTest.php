<?php

namespace Websoftwares\Tests\Domain\UserActivation;

use League\Container\Container;

/**
 * Class UserActivationProviderTest.
 */
class UserActivationProviderTest extends \PHPUnit_Framework_TestCase
{
    public $container;

    public function setUp()
    {
        $this->container = new Container();
        $this->container->addServiceProvider('Websoftwares\Domain\UserActivation\UserActivationProvider');
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
        $expected = 'Websoftwares\Domain\UserActivation\UserActivationGateway';
        $actual = $this->container->get('Websoftwares\Domain\UserActivation\UserActivationGateway');

        $this->assertInstanceOf($expected, $actual);

        $expected = 'Websoftwares\Domain\UserActivation\UserActivationFactory';
        $actual = $this->container->get('Websoftwares\Domain\UserActivation\UserActivationFactory');

        $this->assertInstanceOf($expected, $actual);

        $expected = 'Websoftwares\Domain\UserActivation\UserActivationService';
        $actual = $this->container->get('Websoftwares\Domain\UserActivation\UserActivationService');

        $this->assertInstanceOf($expected, $actual);

        $expected = 'Websoftwares\Domain\UserActivation\UserActivationFilter';
        $actual = $this->container->get('Websoftwares\Domain\UserActivation\UserActivationFilter');

        $this->assertInstanceOf($expected, $actual);
    }
}
