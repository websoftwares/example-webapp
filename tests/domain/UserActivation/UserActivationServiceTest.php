<?php

namespace Websoftwares\Tests\domain\UserActivation;

use Websoftwares\Domain\UserActivation\UserActivationService;
use Websoftwares\Domain\UserActivation\UserActivationGateway;
use Websoftwares\Domain\UserActivation\UserActivationEntity;
use Websoftwares\Domain\UserActivation\UserActivationFilter;
use Websoftwares\Domain\UserActivation\UserActivationFactory;
use Websoftwares\Domain\RandomString;
use FOA\DomainPayload\PayloadFactory;

/**
 * Class UserServiceTest.
 */
class UserActivationServiceTest extends \PHPUnit_Framework_TestCase
{
    public $userActivationGateway;
    public $userActivationFactory;
    public $userActivationFilter;
    public $randomString;
    public $payloadFactory;
    public $logger;

    public $userActivationService;

    public function setUp()
    {
        $this->userActivationGateway = $this->getMockBuilder(
                'Websoftwares\Domain\UserActivation\UserActivationGateway'
                )
            ->disableOriginalConstructor()
            ->getMock();

        $this->userActivationFactory = new UserActivationFactory();
        $this->userActivationFilter = new UserActivationFilter();
        $this->randomString = new RandomString();
        $this->payloadFactory = new PayloadFactory();
        $this->logger = $this->getMock('Psr\Log\LoggerInterface');

        $this->userActivationService = new UserActivationService(
            $this->userActivationGateway,
            $this->userActivationFactory,
            $this->userActivationFilter,
            $this->randomString,
            $this->payloadFactory,
            $this->logger
            );
    }

    public function testInstantiateAsObjectSucceeds()
    {
        $this->assertInstanceOf(
            'Websoftwares\Domain\UserActivation\UserActivationService',
            $this->userActivationService
            );
    }

    public function testSaveUserActivationTokenFailsOnFilter()
    {
        $actual = $this->userActivationService->saveUserActivationToken();
        $this->assertInstanceOf('FOA\DomainPayload\PayloadInterface', $actual);

        $this->assertNull($actual->get('userId'));
        $this->assertInternalType('array', $actual->get('messages'));
    }

    public function testSaveUserActivationTokenFailsOnInsert()
    {
        $this->userActivationGateway->expects($this->once())->method('insert')
                ->will($this->returnValue(false));

        $actual = $this->userActivationService->saveUserActivationToken(1);

        $this->assertInstanceOf('FOA\DomainPayload\PayloadInterface', $actual);
        $this->assertEquals(1, $actual->get('userId'));
    }

    public function testSaveUserActivationTokenFailsOnException()
    {
        $exception = new \PDOException('test', 1);

        $this->userActivationGateway->expects($this->once())->method('insert')
                ->will($this->throwException($exception));

        $actual = $this->userActivationService->saveUserActivationToken(1);

        $this->assertInstanceOf('FOA\DomainPayload\PayloadInterface', $actual);
        $this->assertEquals(1, $actual->get('userId'));
        $this->assertInstanceOf('\PDOException', $actual->get('exception'));
        $this->assertEquals(1, $actual->get('exception')->getCode());
        $this->assertEquals('test', $actual->get('exception')->getMessage());
    }

    public function testSaveUserActivationTokenSucceeds()
    {
        $data = [
            'userId' => 1,
            'token' => 'W3bs0ftW@res',
        ];

        $this->userActivationGateway->expects($this->once())->method('insert')
                ->will($this->returnValue(new UserActivationEntity(
            $data + array('id' => 1)
            )));

        $actual = $this->userActivationService->saveUserActivationToken(1);

        $this->assertInstanceOf('FOA\DomainPayload\PayloadInterface', $actual);
        $this->assertInstanceOf('Websoftwares\Domain\UserActivation\UserActivationEntity', $actual->get('userActivation'));
        $this->assertEquals(1, $actual->get('userActivation')->id);
    }

    public function fetchUserActivationByTokenNotFound()
    {
        $data = [
            'token' => 'webwebwebweb',
        ];

        $this->userActivationGateway->expects($this->once())->method('fetchOne')
                ->will($this->returnValue(new UserActivationEntity(
            $data
            )));

        $actual = $this->userActivationService->fetchUserActivationByToken($data['token']);
        $this->assertInstanceOf('FOA\DomainPayload\PayloadInterface', $actual);
        $this->assertInternalType('string', $actual->get('token'));
        $this->assertEquals($data['token'], $actual->get('token'));
    }

    public function fetchUserActivationByTokenException()
    {
        $data = [
            'token' => 'webwebwebweb',
        ];

        $exception = new \PDOException('test', 1);

        $this->userActivationGateway->expects($this->once())->method('fetchOne')
                ->will($this->throwException($exception));

        $actual = $this->userActivationService->fetchUserActivationByToken($data['token']);

        $this->assertInstanceOf('FOA\DomainPayload\PayloadInterface', $actual);
        $this->assertInternalType('string', $actual->get('token'));
        $this->assertEquals($data['token'], $actual->get('token'));
        $this->assertInstanceOf('\PDOException', $actual->get('exception'));
        $this->assertEquals(1, $actual->get('exception')->getCode());
        $this->assertEquals('test', $actual->get('exception')->getMessage());
    }

    public function testFetchUserActivationByTokenSucceeds()
    {
        $data = [
            'token' => 'webwebwebweb',
        ];

        $expected =  new UserActivationEntity($data + array('id' => 1));

        $this->userActivationGateway->expects($this->once())->method('fetchOne')
                ->will($this->returnValue(
                    $expected
                    )
                );

        $actual = $this->userActivationService->fetchUserActivationByToken($data['token']);
        $this->assertInstanceOf('FOA\DomainPayload\PayloadInterface', $actual);
        $this->assertInstanceOf('Websoftwares\Domain\UserActivation\UserActivationEntity', $actual->get('userActivation'));
        $this->assertEquals(1, $actual->get('userActivation')->id);
    }

    public function testDeleteUserActivationByTokenFailsOnFilter()
    {
        $actual = $this->userActivationService->deleteUserActivationByToken();
        $this->assertInstanceOf('FOA\DomainPayload\PayloadInterface', $actual);

        $this->assertNull($actual->get('token'));
        $this->assertInternalType('array', $actual->get('messages'));
    }

    public function testDeleteUserActivationByTokenFailsOnDelete()
    {
        $token = 'wewewewe';
        $this->userActivationGateway->expects($this->once())->method('delete')
                ->will($this->returnValue(false));

        $actual = $this->userActivationService->deleteUserActivationByToken($token);

        $this->assertInstanceOf('FOA\DomainPayload\PayloadInterface', $actual);
        $this->assertEquals($token, $actual->get('token'));
    }

    public function testDeleteUserActivationByTokenFailsOnException()
    {
        $token = 'wewewewe';

        $exception = new \PDOException('test', 1);

        $this->userActivationGateway->expects($this->once())->method('delete')
                ->will($this->throwException($exception));

        $actual = $this->userActivationService->deleteUserActivationByToken($token);

        $this->assertInstanceOf('FOA\DomainPayload\PayloadInterface', $actual);
        $this->assertEquals($token, $actual->get('token'));
        $this->assertInstanceOf('\PDOException', $actual->get('exception'));
        $this->assertEquals(1, $actual->get('exception')->getCode());
        $this->assertEquals('test', $actual->get('exception')->getMessage());
    }

    public function testDeleteUserActivationByTokenSucceeds()
    {
        $data = [
            'token' => 'W3bs0ftW@res',
        ];

        $this->userActivationGateway->expects($this->once())->method('delete')
                ->will($this->returnValue(new UserActivationEntity(
            $data
            )));

        $actual = $this->userActivationService->deleteUserActivationByToken($data['token']);

        $this->assertInstanceOf('FOA\DomainPayload\PayloadInterface', $actual);

        $this->assertInstanceOf('Websoftwares\Domain\UserActivation\UserActivationEntity', $actual->get('userActivation'));
    }
}
