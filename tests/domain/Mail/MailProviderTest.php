<?php

namespace Websoftwares\Tests\Domain\Mail;

use League\Container\Container;

/**
 * Class UserActivationProviderTest.
 */
class MailProviderTest extends \PHPUnit_Framework_TestCase
{
    public $container;

    public function setUp()
    {
        $this->container = new Container();
        $this->container->addServiceProvider('Websoftwares\Domain\Mail\MailProvider');
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
        $expected = 'Websoftwares\Domain\Mail\MailService';
        $actual = $this->container->get('Websoftwares\Domain\Mail\MailService');

        $this->assertInstanceOf($expected, $actual);

        $expected =  'Websoftwares\Domain\Mail\MailFactory';
        $actual = $this->container->get('Websoftwares\Domain\Mail\MailFactory');

        $this->assertInstanceOf($expected, $actual);

        $expected = 'Websoftwares\Domain\Mail\MailStrategyFactory';
        $actual = $this->container->get('Websoftwares\Domain\Mail\MailStrategyFactory');

        $this->assertInstanceOf($expected, $actual);

        $expected = 'Websoftwares\Domain\Mail\MailPayloadFactory';
        $actual = $this->container->get('Websoftwares\Domain\Mail\MailPayloadFactory');

        $this->assertInstanceOf($expected, $actual);
    }
}
