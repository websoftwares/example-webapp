<?php

namespace Websoftwares\Tests\Domain\User;

use Websoftwares\Domain\User\UserEntity;

/**
 * Class UserEntityTest.
 */
class UserEntityTest extends \PHPUnit_Framework_TestCase
{
    public $userEntity;

    public function setUp()
    {
        $this->userEntity = new UserEntity([
                'id' => 1,
                'name' => 'Boris Verhaaff',
                'email' => 'boris@websoftwar.es',
                'password' => '123456',
                'active' => 0,
            ]
        );
    }

    public function testInstantiateAsObjectSucceeds()
    {
        $this->assertInstanceOf(
            'Websoftwares\Domain\User\UserEntity',
            $this->userEntity);
    }

    public function testGetItterator()
    {
        $this->assertInstanceOf(
            '\ArrayIterator',
            $this->userEntity->getIterator());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInitFailsException()
    {
        $this->userEntity->init();
    }

    public function testInitSucceeds()
    {
        $data = [
                'id' => 1,
                'name' => 'Boris Verhaaff',
                'email' => 'boris@websoftwar.es',
                'password' => '123456',
                'active' => 1,
            ];

        $actual = $this->userEntity->init($data);

        $this->assertAttributeEquals(1, 'id', $this->userEntity);
        $this->assertAttributeEquals('Boris Verhaaff', 'name', $this->userEntity);
        $this->assertAttributeEquals('boris@websoftwar.es', 'email', $this->userEntity);
        $this->assertAttributeEquals('123456', 'password', $this->userEntity);
        $this->assertAttributeEquals(1, 'active', $this->userEntity);
    }

    public function testAttributesSucceeds()
    {
        $this->assertAttributeEquals(1, 'id', $this->userEntity);
        $this->assertAttributeEquals('Boris Verhaaff', 'name', $this->userEntity);
        $this->assertAttributeEquals('boris@websoftwar.es', 'email', $this->userEntity);
        $this->assertAttributeEquals('123456', 'password', $this->userEntity);
        $this->assertAttributeEquals(0, 'active', $this->userEntity);
    }
}
