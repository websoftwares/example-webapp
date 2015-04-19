<?php

namespace Websoftwares\Tests\Domain\UserActivtion;

use Websoftwares\Domain\UserActivation\UserActivationEntity;

/**
 * Class UserActivationEntityTest.
 */
class UserActivationEntityTest extends \PHPUnit_Framework_TestCase
{
    public $userActivationEntity;

    public function setUp()
    {
        $this->userActivationEntity = new UserActivationEntity([
                'id' => 1,
                'userId' => 1,
                'token' => 'webwebweb',
                'created' => '12-30-2014 10:10:10',
            ]
        );
    }

    public function testInstantiateAsObjectSucceeds()
    {
        $this->assertInstanceOf(
            'Websoftwares\Domain\UserActivation\UserActivationEntity',
            $this->userActivationEntity);
    }

    public function testGetItterator()
    {
        $this->assertInstanceOf(
            '\ArrayIterator',
            $this->userActivationEntity->getIterator());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInitFailsException()
    {
        $this->userActivationEntity->init();
    }

    public function testInitSucceeds()
    {
        $data = [
                'id' => 1,
                'userId' => 1,
                'token' => 'webwebweb',
                'created' => '12-30-2014 10:10:10',
            ];

        $actual = $this->userActivationEntity->init($data);

        $this->assertAttributeEquals(1, 'id', $this->userActivationEntity);
        $this->assertAttributeEquals(1, 'userId', $this->userActivationEntity);
        $this->assertAttributeEquals('webwebweb', 'token', $this->userActivationEntity);
        $this->assertAttributeEquals('12-30-2014 10:10:10', 'created', $this->userActivationEntity);
    }

    public function testAttributesSucceeds()
    {
        $this->assertAttributeEquals(1, 'id', $this->userActivationEntity);
        $this->assertAttributeEquals(1, 'userId', $this->userActivationEntity);
        $this->assertAttributeEquals('webwebweb', 'token', $this->userActivationEntity);
        $this->assertAttributeEquals('12-30-2014 10:10:10', 'created', $this->userActivationEntity);
    }
}
