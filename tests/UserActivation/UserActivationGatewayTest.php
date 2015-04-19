<?php

namespace Websoftwares\Tests\Domain\UserActivation;

use Websoftwares\Domain\UserActivation\UserActivationGateway;
use Websoftwares\Domain\UserActivation\UserActivationEntity;

/**
 * Class UserActivationGatewayTest.
 */
class UserActivationGatewayTest extends \PHPUnit_Framework_TestCase
{
    public $mockPdo;
    public $userActivationGateway;

    public function setUp()
    {
        $this->mockPdo = $this->getMock(
            'Websoftwares\Tests\Domain\MockPdo',
            array('prepare', 'lastInsertId')
            );
        $this->userActivationGateway = new UserActivationGateway($this->mockPdo);
    }

    public function testInstantiateAsObjectSucceeds()
    {
        $this->assertInstanceOf(
            'Websoftwares\Domain\UserActivation\UserActivationGateway',
            $this->userActivationGateway);
    }

    public function testInsertDidNotInsert()
    {
        $sql = 'INSERT INTO user__activations(userId,token) VALUES(:userId,:token)';

        $data = [
            'userId' => 1,
            'token' => 'SuperRandomString',
        ];

        $userActivationEntity = new UserActivationEntity($data);

        $stmt = $this->getMock('PDOStatement', array('execute'));

        $stmt->expects($this->once())->method('execute')
            ->with($this->equalTo($data))
            ->will($this->returnValue(false));

        $this->mockPdo->expects($this->once())->method('prepare')
                ->with($this->equalTo($sql))
                ->will($this->returnValue($stmt));

        $actual = $this->userActivationGateway->insert($userActivationEntity);
        $this->assertEquals($userActivationEntity, $actual);
    }

    /**
     * @expectedException PDOException
     */
    public function testInsertException()
    {
        $sql = 'INSERT INTO user__activations(userId,token) VALUES(:userId,:token)';

        $data = [
            'userId' => 1,
            'token' => 'SuperRandomString',
        ];

        $exception = new \PDOException('test', 1);

        $userActivationEntity = new UserActivationEntity($data);

        $stmt = $this->getMock('PDOStatement', array('execute'));

        $this->mockPdo->expects($this->once())->method('prepare')
                ->with($this->equalTo($sql))
                ->will($this->throwException($exception));

        $actual = $this->userActivationGateway->insert($userActivationEntity);
    }

    public function testInsertSucceeds()
    {
        $sql = 'INSERT INTO user__activations(userId,token) VALUES(:userId,:token)';

        $data = [
            'userId' => 1,
            'token' => 'SuperRandomString',
        ];

        $userActivationEntity = new UserActivationEntity($data);

        $expected = clone $userActivationEntity;
        $expected->id = 1;

        $stmt = $this->getMock('PDOStatement', array('execute'));

        $stmt->expects($this->once())->method('execute')
            ->with($this->equalTo($data))
            ->will($this->returnValue(true));

        $this->mockPdo->expects($this->once())->method('lastInsertId')
            ->will($this->returnValue(1));

        $this->mockPdo->expects($this->once())->method('prepare')
                ->with($this->equalTo($sql))
                ->will($this->returnValue($stmt));

        $actual = (new userActivationGateway($this->mockPdo))->insert($userActivationEntity);

        $this->assertEquals($actual, $expected);
        $this->assertObjectHasAttribute('id', $actual);

        $this->assertEquals(1, $actual->id);
    }

    public function testDidNotDelete()
    {
        $sql = 'DELETE FROM user__activations WHERE token = :token';

        $data = [
            'token' => 'SuperRandomString',
        ];

        $userActivationEntity = new UserActivationEntity($data);

        $stmt = $this->getMock('PDOStatement', array('execute'));

        $stmt->expects($this->once())->method('execute')
            ->with($this->equalTo(array(':token' => 'SuperRandomString')))
            ->will($this->returnValue(false));

        $this->mockPdo->expects($this->once())->method('prepare')
                ->with($this->equalTo($sql))
                ->will($this->returnValue($stmt));

        $actual = $this->userActivationGateway->delete($userActivationEntity);
        $this->assertFalse($actual);
    }

    /**
     * @expectedException PDOException
     */
    public function testDeleteException()
    {
        $sql = 'DELETE FROM user__activations WHERE token = :token';

        $data = [
            'userId' => 1,
            'token' => 'SuperRandomString',
        ];

        $exception = new \PDOException('test', 1);

        $userActivationEntity = new UserActivationEntity($data);

        $stmt = $this->getMock('PDOStatement', array('execute'));

        $this->mockPdo->expects($this->once())->method('prepare')
                ->with($this->equalTo($sql))
                ->will($this->throwException($exception));

        $actual = $this->userActivationGateway->delete($userActivationEntity);
    }

    public function testDeleteSucceeds()
    {
        $sql = 'DELETE FROM user__activations WHERE token = :token';

        $data = [
            'token' => 'SuperRandomString',
        ];

        $userActivationEntity = new UserActivationEntity($data);

        $stmt = $this->getMock('PDOStatement', array('execute'));

        $stmt->expects($this->once())->method('execute')
            ->with($this->equalTo(array(':token' => 'SuperRandomString')))
            ->will($this->returnValue(true));

        $this->mockPdo->expects($this->once())->method('prepare')
                ->with($this->equalTo($sql))
                ->will($this->returnValue($stmt));

        $actual = (new userActivationGateway($this->mockPdo))->delete($userActivationEntity);

        $this->assertTrue($actual);
    }

    public function testFetchOneSucceeds()
    {
        $token = 'webwebwebweb';
        $sql =  $sql = 'SELECT * FROM user__activations WHERE token = :token';
        $params = array(':token' => $token);

        $expected = new UserActivationEntity([
                'id' => 1,
                'userId' => 1,
                'token' => $token,
                'created' => '12-30-2014 10:10:10',
            ]
        );

        $data = new UserActivationEntity([
                'token' => $token,
            ]
        );

        $stmt = $this->getMock('PDOStatement', array('execute', 'fetch'));

        $stmt->expects($this->once())->method('execute')->with($this->equalTo($params));

        $stmt->expects($this->once())->method('fetch')
            ->with($this->equalTo(\PDO::FETCH_CLASS))
            ->will($this->returnValue($expected));

        $this->mockPdo->expects($this->once())->method('prepare')
                ->with($this->equalTo($sql))
                ->will($this->returnValue($stmt));

        $userActivationGateway = new UserActivationGateway($this->mockPdo);

        $actual = $userActivationGateway->fetchOne($data);

        $this->assertEquals($expected, $actual);
    }

    public function testFetchOneNotFound()
    {
        $token = 'webwebwebweb';
        $sql =  $sql = 'SELECT * FROM user__activations WHERE token = :token';
        $params = array(':token' => $token);

        $data = new UserActivationEntity([
                'token' => $token,
            ]
        );

        $stmt = $this->getMock('PDOStatement', array('execute', 'fetch'));

        $stmt->expects($this->once())->method('execute')->with($this->equalTo($params));

        $stmt->expects($this->once())->method('fetch')
            ->with($this->equalTo(\PDO::FETCH_CLASS))
            ->will($this->returnValue($token));

        $this->mockPdo->expects($this->once())->method('prepare')
                ->with($this->equalTo($sql))
                ->will($this->returnValue($stmt));

        $userActivationGateway = new UserActivationGateway($this->mockPdo);

        $actual = $userActivationGateway->fetchOne($data);

        $this->assertEquals($token, $actual);
    }

    /**
     * @expectedException PDOException
     */
    public function testFetchOneFailsException()
    {
        $token = 'webwebwebweb';
        $sql =  $sql = 'SELECT * FROM user__activations WHERE token = :token';
        $params = array(':token' => $token);

        $data = new UserActivationEntity([
                'token' => $token,
            ]
        );
        $exception = new \PDOException('test', 1);

        $stmt = $this->getMock('PDOStatement', array('execute', 'fetch'));

        $this->mockPdo->expects($this->once())->method('prepare')
                ->with($this->equalTo($sql))
                ->will($this->throwException($exception));

        $userActivationGateway = new UserActivationGateway($this->mockPdo);

        $actual = $userActivationGateway->fetchOne($data);

        $this->assertEquals($expected, $data);
    }
}
