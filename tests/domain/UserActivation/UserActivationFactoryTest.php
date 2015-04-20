<?php

namespace Websoftwares\Tests\domain\UserActivation;

use Websoftwares\Domain\UserActivation\UserActivationFactory;

/**
 * Class UserActivationFactoryTest.
 */
class UserActivationFactoryTest extends \PHPUnit_Framework_TestCase
{
    public $data;
    public $UserActivationFactory;

    public function setUp()
    {
        $this->data = [
                'id' => 1,
                'userId' => 1,
                'token' => 'webwebweb',
                'created' => '12-30-2014 10:10:10',
            ];
        $this->UserActivationFactory = new UserActivationFactory();
    }

    public function testInstantiateAsObjectSucceeds()
    {
        $this->assertInstanceOf(
            'Websoftwares\Domain\UserActivation\UserActivationFactory',
            new UserActivationFactory());
    }

    public function testNewInstanceSucceeds()
    {
        $actual = $this->UserActivationFactory->newEntity($this->data);
        $expected = 'Websoftwares\Domain\UserActivation\UserActivationEntity';
        $this->assertInstanceOf($expected, $actual);
    }
}
