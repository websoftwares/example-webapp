<?php

namespace Websoftwares\Tests\Domain\User;

use Websoftwares\Domain\User\UserFactory;

/**
 * Class UserFactoryTest.
 */
class UserFactoryTest extends \PHPUnit_Framework_TestCase
{
    public $data;
    public $userFactory;

    public function setUp()
    {
        $this->data = [
                'id' => 1,
                'name' => 'Boris Verhaaff',
                'email' => 'boris@websoftwar.es',
                'password' => '123456',
                'active' => 0,
            ];
        $this->userFactory = new UserFactory();
    }

    public function testInstantiateAsObjectSucceeds()
    {
        $this->assertInstanceOf(
            'Websoftwares\Domain\User\UserFactory',
            new UserFactory());
    }

    public function testNewInstanceSucceeds()
    {
        $actual = $this->userFactory->newEntity($this->data);
        $expected = 'Websoftwares\Domain\User\UserEntity';
        $this->assertInstanceOf($expected, $actual);
    }
}
