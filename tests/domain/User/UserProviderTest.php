<?php

namespace Websoftwares\Tests\domain\User;

use League\Container\Container;

/**
 * Class UserProviderTest.
 */
class UserProviderTest extends \PHPUnit_Framework_TestCase
{
    public $container;

    public function setUp()
    {
        $this->container = new Container();
        $this->container->addServiceProvider('Websoftwares\Domain\User\UserProvider');
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
        $expected = 'Websoftwares\Domain\User\UserGateway';
        $actual = $this->container->get('Websoftwares\Domain\User\UserGateway');

        $this->assertInstanceOf($expected, $actual);

        $expected = 'Websoftwares\Domain\User\UserFactory';
        $actual = $this->container->get('Websoftwares\Domain\User\UserFactory');

        $this->assertInstanceOf($expected, $actual);

        $expected = 'Websoftwares\Domain\User\UserService';
        $actual = $this->container->get('Websoftwares\Domain\User\UserService');

        $this->assertInstanceOf($expected, $actual);

        $expected = 'Websoftwares\Domain\User\UserFilter';
        $actual = $this->container->get('Websoftwares\Domain\User\UserFilter');

        $this->assertInstanceOf($expected, $actual);
    }
}
