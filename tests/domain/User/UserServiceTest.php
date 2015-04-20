<?php

namespace Websoftwares\Tests\Domain\User;

use Websoftwares\Domain\User\UserService;
use Websoftwares\Domain\User\UserGateway;
use Websoftwares\Domain\User\UserEntity;
use Websoftwares\Domain\User\UserFilter;
use Websoftwares\Domain\User\UserFactory;
use FOA\DomainPayload\PayloadFactory;

/**
 * Class UserServiceTest.
 */
class UserServiceTest extends \PHPUnit_Framework_TestCase
{
    public $userGateway;
    public $userFilter;
    public $userFactory;
    public $payloadFactory;
    public $logger;

    public function setUp()
    {
        $this->userGateway = $this->getMockBuilder('Websoftwares\Domain\User\UserGateway')
            ->disableOriginalConstructor()
            ->getMock();
        $this->userFilter = new UserFilter();
        $this->userFactory = new UserFactory();
        $this->payloadFactory = new PayloadFactory();
        $this->logger = $this->getMock('Psr\Log\LoggerInterface');
        $this->userService = new UserService(
            $this->userGateway,
            $this->userFilter,
            $this->userFactory,
            $this->payloadFactory,
            $this->logger
            );
    }

    public function testInstantiateAsObjectSucceeds()
    {
        $this->assertInstanceOf(
            'Websoftwares\Domain\User\UserService',
            $this->userService
            );
    }

    public function testCreateUserFailsOnFilter()
    {
        $data = [
            'email' => 'boris@websoftwar.es',
        ];

        $actual = $this->userService->createUser($data);
        $this->assertInstanceOf('FOA\DomainPayload\PayloadInterface', $actual);
        $this->assertInternalType('array', $actual->get('data'));
        $this->assertInternalType('array', $actual->get('messages'));
    }

    public function testCreateUserFailsOnInsert()
    {
        $data = [
            'name' => 'Boris Verhaaff',
            'email' => 'boris@websoftwar.es',
            'password' => 'W3bs0ftW@res',
        ];

        $this->userGateway->expects($this->once())->method('insert')
                ->will($this->returnValue(false));

        $userService = new UserService(
            $this->userGateway,
            $this->userFilter,
            $this->userFactory,
            $this->payloadFactory,
            $this->logger
            );

        $actual = $this->userService->createUser($data);

        $this->assertInstanceOf('FOA\DomainPayload\PayloadInterface', $actual);
        $this->assertInternalType('array', $actual->get('data'));
    }

    public function testCreateUserFailsOnException()
    {
        $data = [
            'name' => 'Boris Verhaaff',
            'email' => 'boris@websoftwar.es',
            'password' => 'W3bs0ftW@res',
        ];

        $exception = new \PDOException('test', 1);

        $this->userGateway->expects($this->once())->method('insert')
                ->will($this->throwException($exception));

        $userService = new UserService(
            $this->userGateway,
            $this->userFilter,
            $this->userFactory,
            $this->payloadFactory,
            $this->logger
            );

        $actual = $this->userService->createUser($data);

        $this->assertInstanceOf('FOA\DomainPayload\PayloadInterface', $actual);
        $this->assertInternalType('array', $actual->get('data'));
        $this->assertInstanceOf('\PDOException', $actual->get('exception'));
        $this->assertEquals(1, $actual->get('exception')->getCode());
        $this->assertEquals('test', $actual->get('exception')->getMessage());
    }

    public function testCreateUserSucceeds()
    {
        $data = [
            'name' => 'Boris Verhaaff',
            'email' => 'boris@websoftwar.es',
            'password' => 'W3bs0ftW@res',
        ];

        $this->userGateway->expects($this->once())->method('insert')
                ->will($this->returnValue(new UserEntity(
            $data + array('id' => 1)
            )));

        $userService = new UserService(
            $this->userGateway,
            $this->userFilter,
            $this->userFactory,
            $this->payloadFactory,
            $this->logger
            );

        $actual = $userService->createUser($data);

        $this->assertInstanceOf('FOA\DomainPayload\PayloadInterface', $actual);
        $this->assertInstanceOf('Websoftwares\Domain\User\UserEntity', $actual->get('user'));
        $this->assertEquals(1, $actual->get('user')->id);
    }

    public function testFetchUserByEmailNotFound()
    {
        $data = [
            'email' => 'boris@websoftwar.es',
        ];

        $this->userGateway->expects($this->once())->method('fetchOne')
                ->will($this->returnValue(new UserEntity(
            $data
            )));

        $userService = new UserService(
            $this->userGateway,
            $this->userFilter,
            $this->userFactory,
            $this->payloadFactory,
            $this->logger
            );

        $actual = $userService->fetchUserByEmail($data['email']);
        $this->assertInstanceOf('FOA\DomainPayload\PayloadInterface', $actual);
        $this->assertInternalType('string', $actual->get('email'));
        $this->assertEquals($data['email'], $actual->get('email'));
    }

    public function testFetchUserByEmailException()
    {
        $data = [
            'email' => 'boris@websoftwar.es',
        ];

        $exception = new \PDOException('test', 1);

        $this->userGateway->expects($this->once())->method('fetchOne')
                ->will($this->throwException($exception));

        $userService = new UserService(
            $this->userGateway,
            $this->userFilter,
            $this->userFactory,
            $this->payloadFactory,
            $this->logger
            );

        $actual = $userService->fetchUserByEmail($data['email']);

        $this->assertInstanceOf('FOA\DomainPayload\PayloadInterface', $actual);
        $this->assertInternalType('string', $actual->get('email'));
        $this->assertEquals($data['email'], $actual->get('email'));
        $this->assertInstanceOf('\PDOException', $actual->get('exception'));
        $this->assertEquals(1, $actual->get('exception')->getCode());
        $this->assertEquals('test', $actual->get('exception')->getMessage());
    }

    public function testFetchUserByEmailSucceeds()
    {
        $data = [
            'email' => 'boris@websoftwar.es',
        ];

        $expected =  new UserEntity($data + array('name' => 'Boris Verhaaff',
            'email' => 'boris@websoftwar.es',
            'password' => 'W3bs0ftW@res',
            'id' => 1, ));

        $this->userGateway->expects($this->once())->method('fetchOne')
                ->will($this->returnValue(
                    $expected
                    )
                );

        $userService = new UserService(
            $this->userGateway,
            $this->userFilter,
            $this->userFactory,
            $this->payloadFactory,
            $this->logger
            );

        $actual = $userService->fetchUserByEmail($data['email']);
        $this->assertInstanceOf('FOA\DomainPayload\PayloadInterface', $actual);
        $this->assertInstanceOf('Websoftwares\Domain\User\UserEntity', $actual->get('user'));
        $this->assertEquals($expected, $actual->get('user'));
    }

    public function testUpdateUserFailsOnFilter()
    {
        $data = array(
            'name' => 'Boris Verhaaff',
            'email' => 'boris@websoftwar.es',
            'password' => 'W3bs0ftW@res',
        );

        $userEntity =  new UserEntity($data);

        $userService = new UserService(
            $this->userGateway,
            $this->userFilter,
            $this->userFactory,
            $this->payloadFactory,
            $this->logger
        );

        $actual = $userService->updateUser($data);
        $this->assertInstanceOf('FOA\DomainPayload\PayloadInterface', $actual);
        $this->assertInternalType('array', $actual->get('data'));
        $this->assertInternalType('array', $actual->get('messages'));
    }

    public function testUpdateUserFailsOnException()
    {
        $data = array(
            'id' => 1,
            'name' => 'Boris Verhaaff',
            'email' => 'boris@websoftwar.es',
            'password' => 'W3bs0ftW@res',
        );

        $userEntity =  new UserEntity($data);

        $userFilter = $this->getMock('Websoftwares\Domain\User\UserFilter');

        $userFilter->expects($this->once())->method('forUpdate')
                ->will($this->returnValue(true
                    )
                );

        $exception = new \PDOException('test', 1);

        $this->userGateway->expects($this->once())->method('update')
                ->will($this->throwException($exception));

        $userService = new UserService(
            $this->userGateway,
            $userFilter,
            $this->userFactory,
            $this->payloadFactory,
            $this->logger
        );

        $actual = $userService->updateUser($data);
        $this->assertInstanceOf('FOA\DomainPayload\PayloadInterface', $actual);
        $this->assertInternalType('array', $actual->get('data'));
        $this->assertInstanceOf('\PDOException', $actual->get('exception'));
        $this->assertEquals(1, $actual->get('exception')->getCode());
        $this->assertEquals('test', $actual->get('exception')->getMessage());
    }

    public function testUpdateUserSucceeds()
    {
        $data = [
            'id' => 1,
            'name' => 'Boris Verhaaff',
            'email' => 'boris@websoftwar.es',
            'password' => 'W3bs0ftW@res',
        ];

        $expected = new UserEntity($data);

        $userFilter = $this->getMock('Websoftwares\Domain\User\UserFilter');

        $userFilter->expects($this->once())->method('forUpdate')
                ->will($this->returnValue(true)
                );

        $this->userGateway->expects($this->once())->method('update')
                ->will($this->returnValue(true));

        $userService = new UserService(
            $this->userGateway,
            $userFilter,
            $this->userFactory,
            $this->payloadFactory,
            $this->logger
            );

        $actual = $userService->updateUser($data);

        $this->assertInstanceOf('FOA\DomainPayload\PayloadInterface', $actual);
        $this->assertInstanceOf('Websoftwares\Domain\User\UserEntity', $actual->get('user'));
        $this->assertEquals($expected, $actual->get('user'));
    }
}
