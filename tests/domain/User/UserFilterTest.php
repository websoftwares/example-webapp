<?php

namespace Websoftwares\Tests\Domain\User;

use Websoftwares\Domain\User\UserFilter;
use Websoftwares\Domain\User\UserEntity;

/**
 * Class UserFilterTest.
 */
class UserFilterTest extends \PHPUnit_Framework_TestCase
{
    public $userFilter;
    public $userGateway;
    public $userEntity;

    public function setUp()
    {
        $this->userGateway = $this->getMockBuilder('Websoftwares\Domain\User\UserGateway')
            ->disableOriginalConstructor()
            ->getMock();

        $this->userFilter = new UserFilter();
        $this->userEntity = new UserEntity([
                'id' => null,
                'name' => '',
                'email' => '',
                'password' => '',
                'active' => null,
            ]
        );
    }

    public function testInstantiateAsObjectSucceeds()
    {
        $this->assertInstanceOf(
            'Websoftwares\Domain\User\UserFilter',
            $this->userFilter);
    }

    public function testForInsertReturnsFalseEmpty()
    {

        // Empty
        $actual = $this->userFilter->forInsert($this->userEntity, $this->userGateway);
        $this->assertFalse($actual);

        $messages = $this->userFilter->getMessages();

        $actual = $messages['name'];
        $expected = 'Name cannot be empty.';
        $this->assertEquals($actual, $expected);

        $actual = $messages['email'];
        $expected = 'Email cannot be empty.';
        $this->assertEquals($actual, $expected);

        $actual = $messages['password'];
        $expected = 'Password cannot be empty.';
        $this->assertEquals($actual, $expected);

        $actual = $messages['active'];
        $expected = 'Active cannot be empty.';
        $this->assertEquals($actual, $expected);
    }

    public function testForInsertReturnsFalsePasswordStrength()
    {
        // Lowercase
        $this->userEntity->password = 'W';

        $actual = $this->userFilter->forInsert($this->userEntity, $this->userGateway);
        $this->assertFalse($actual);

        $messages = $this->userFilter->getMessages();

        $actual = $messages['password'];
        $expected = 'Password must have a lowercase character.';
        $this->assertEquals($actual, $expected);

        // Uppercase
        $this->userEntity->password = 'w';

        $actual = $this->userFilter->forInsert($this->userEntity, $this->userGateway);

        $messages = $this->userFilter->getMessages();

        $actual = $messages['password'];
        $expected = 'Password must have an uppercase character.';
        $this->assertEquals($actual, $expected);

        // Special
        $this->userEntity->password = 'We';

        $actual = $this->userFilter->forInsert($this->userEntity, $this->userGateway);

        $messages = $this->userFilter->getMessages();

        $actual = $messages['password'];
        $expected = 'Password must have a special character.';
        $this->assertEquals($actual, $expected);

        // Number
        $this->userEntity->password = 'WB@e';

        $actual = $this->userFilter->forInsert($this->userEntity, $this->userGateway);

        $messages = $this->userFilter->getMessages();

        $actual = $messages['password'];
        $expected = 'Password must have a number character.';
        $this->assertEquals($actual, $expected);

        // Length
        $this->userEntity->password = 'WB@e3';
        $actual = $this->userFilter->forInsert($this->userEntity, $this->userGateway);

        $messages = $this->userFilter->getMessages();

        $actual = $messages['password'];
        $expected = 'Password length must be atleast 8 characters.';
        $this->assertEquals($actual, $expected);
    }

    public function testForInsertReturnsFalseEmailInValidNotUnique()
    {
        $this->userEntity->email = 'boris/websoftwar.es';

        // Invalid
        $actual = $this->userFilter->forInsert($this->userEntity, $this->userGateway);

        $messages = $this->userFilter->getMessages();

        $actual = $messages['email'];
        $expected = 'Email is not valid.';
        $this->assertEquals($actual, $expected);

        // Unique
        $this->userEntity->email = 'boris@websoftwar.es';

        $returnValue = clone $this->userEntity;
        $returnValue->id = 1;

        $this->userGateway->expects($this->once())->method('fetchOne')
                ->will($this->returnValue($returnValue));

        $actual = $this->userFilter->forInsert($this->userEntity, $this->userGateway);

        $messages = $this->userFilter->getMessages();

        $actual = $messages['email'];
        $expected = 'Email already exists.';
    }

    public function testForInsertReturnsFalseActiveNotZero()
    {
        $this->userEntity->active = 1;

        // Active
        $actual = $this->userFilter->forInsert($this->userEntity, $this->userGateway);

        $messages = $this->userFilter->getMessages();

        $actual = $messages['active'];
        $expected = 'Active must be 0 (zero) integer.';
        $this->assertEquals($actual, $expected);
    }

    public function testForUpdateReturnsFalseId()
    {
        $this->userEntity->id = '';

        // Active
        $actual = $this->userFilter->forUpdate($this->userEntity, $this->userGateway);

        $messages = $this->userFilter->getMessages();

        $actual = $messages['id'];
        $expected = 'Id cannot be empty.';
        $this->assertEquals($actual, $expected);
    }

    public function testForInsertSucceeds()
    {
        $this->userEntity->name = 'Boris Verhaaff';
        $this->userEntity->email = 'boris@websoftwar.es';
        $this->userEntity->password = 'w3bs0fTw@r3s';
        $this->userEntity->active = 0;

        $this->userGateway->expects($this->once())->method('fetchOne')
                ->will($this->returnValue($this->userEntity->email));

        $actual = $this->userFilter->forInsert($this->userEntity, $this->userGateway);
        $this->assertTrue($actual);
    }

    public function testForUpdateSucceeds()
    {
        $this->userEntity->name = 'Boris Verhaaff';
        $this->userEntity->email = 'boris@websoftwar.es';
        $this->userEntity->password = 'w3bs0fTw@r3s';
        $this->userEntity->active = 0;
        $this->userEntity->id = 1;
        $actual = $this->userFilter->forInsert($this->userEntity, $this->userGateway);
        $this->assertTrue($actual);
    }
}
