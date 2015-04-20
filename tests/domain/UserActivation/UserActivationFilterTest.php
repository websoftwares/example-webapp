<?php

namespace Websoftwares\Tests\domain\UserActivation;

use Websoftwares\Domain\UserActivation\UserActivationFilter;
use Websoftwares\Domain\UserActivation\UserActivationEntity;

/**
 * Class UserFilterTest.
 */
class UserActivationFilterTest extends \PHPUnit_Framework_TestCase
{
    public $userActivationFilter;
    public $userActivationEntity;

    public function setUp()
    {
        $this->userActivationFilter = new UserActivationFilter();
        $this->userActivationEntity = new UserActivationEntity([
                'userId' => null,
                'token' => '',
            ]
        );
    }

    public function testInstantiateAsObjectSucceeds()
    {
        $this->assertInstanceOf(
            'Websoftwares\Domain\UserActivation\UserActivationFilter',
            $this->userActivationFilter);
    }

    public function testForInsertReturnsFalseEmpty()
    {
        // Empty
        $actual = $this->userActivationFilter->forInsert($this->userActivationEntity);
        $this->assertFalse($actual);

        $messages = $this->userActivationFilter->getMessages();

        $actual = $messages['userId'];
        $expected = 'UserId cannot be empty.';
        $this->assertEquals($actual, $expected);

        $actual = $messages['token'];
        $expected = 'Token cannot be empty.';
        $this->assertEquals($actual, $expected);
    }
    public function testForInsertSucceeds()
    {
        $this->userActivationEntity->token = 'wewewewe';
        $this->userActivationEntity->userId = 1;

        $actual = $this->userActivationFilter->forDelete($this->userActivationEntity);
        $this->assertTrue($actual);
    }

    public function testForDeleteReturnsFalseEmpty()
    {
        // Empty
        $actual = $this->userActivationFilter->forDelete($this->userActivationEntity);
        $this->assertFalse($actual);

        $messages = $this->userActivationFilter->getMessages();

        $actual = $messages['token'];
        $expected = 'Token cannot be empty.';
        $this->assertEquals($actual, $expected);
    }

    public function testForDeleteSucceeds()
    {
        $this->userActivationEntity->token = 'wewewewe';

        $actual = $this->userActivationFilter->forDelete($this->userActivationEntity);
        $this->assertTrue($actual);
    }
}
