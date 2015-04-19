<?php

namespace Websoftwares\Tests\Domain\User;

use Websoftwares\Domain\User\UserGateway;
use Websoftwares\Domain\User\UserEntity;

/**
 * Class UserGatewayTest.
 */
class UserGatewayTest extends \PHPUnit_Framework_TestCase
{
    public $mockPdo;
    public $userGateway;

    public function setUp()
    {
        $this->mockPdo = $this->getMock(
            'Websoftwares\Tests\Domain\MockPdo',
            array('prepare', 'lastInsertId')
            );
        $this->userGateway = new UserGateway($this->mockPdo);
    }

    public function testInstantiateAsObjectSucceeds()
    {
        $this->assertInstanceOf(
            'Websoftwares\Domain\User\UserGateway',
            $this->userGateway);
    }

    public function testFetchOneSucceeds()
    {
        $email = 'boris@websoftwar.es';
        $sql = 'SELECT * FROM users WHERE email = :email';
        $params = array(':email' => $email);

        $expected = new UserEntity([
                'id' => 1,
                'name' => 'Boris Verhaaff',
                'email' => 'boris@websoftwar.es',
                'password' => '123456',
                'active' => 0,
            ]
        );

        $data = new UserEntity([
                'email' => $email,
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

        $userGateway = new UserGateway($this->mockPdo);

        $actual = $userGateway->fetchOne($data);

        $this->assertEquals($expected, $actual);
    }

    public function testFetchOneNotFound()
    {
        $email = 'boris@weboftwar.es';
        $sql = 'SELECT * FROM users WHERE email = :email';
        $params = array(':email' => $email);

        $data = new UserEntity([
                'email' => $email,
            ]
        );

        $stmt = $this->getMock('PDOStatement', array('execute', 'fetch'));

        $stmt->expects($this->once())->method('execute')->with($this->equalTo($params));

        $stmt->expects($this->once())->method('fetch')
            ->with($this->equalTo(\PDO::FETCH_CLASS))
            ->will($this->returnValue($email));

        $this->mockPdo->expects($this->once())->method('prepare')
                ->with($this->equalTo($sql))
                ->will($this->returnValue($stmt));

        $userGateway = new UserGateway($this->mockPdo);

        $actual = $userGateway->fetchOne($data);

        $this->assertEquals($email, $actual);
    }

    /**
     * @expectedException PDOException
     */
    public function testFetchOneFailsException()
    {
        $email = 'boris@weboftwar.es';
        $sql = 'SELECT * FROM users WHERE email = :email';
        $params = array(':email' => $email);
        $exception = new \PDOException('test', 1);

        $data = new UserEntity([
                'email' => 'boris@websoftwar.es',
            ]
        );

        $stmt = $this->getMock('PDOStatement', array('execute', 'fetch'));

        $this->mockPdo->expects($this->once())->method('prepare')
                ->with($this->equalTo($sql))
                ->will($this->throwException($exception));

        $userGateway = new UserGateway($this->mockPdo);

        $actual = $userGateway->fetchOne($data);

        $this->assertEquals($expected, $data);
    }

    public function testInsertSucceeds()
    {
        $sql = 'INSERT INTO users (name, email, password) VALUES (:name, :email, :password)';
        $params = [
                'name' => 'Boris Verhaaff',
                'email' => 'boris@websoftwar.es',
                'password' => '123456',
                'active' => 0,
            ];

        $userEntity = new UserEntity($params);
        $expected = clone $userEntity;
        $expected->id = 1;

        $stmt = $this->getMock('PDOStatement', array('execute'));

        $stmt->expects($this->once())->method('execute')
            ->with($this->equalTo($params))
            ->will($this->returnValue(true));

        $this->mockPdo->expects($this->once())->method('prepare')
                ->with($this->equalTo($sql))
                ->will($this->returnValue($stmt));

        $this->mockPdo->expects($this->once())->method('lastInsertId')
                ->will($this->returnValue(1));

        $userGateway = new UserGateway($this->mockPdo);

        $actual = $userGateway->insert($userEntity);

        $this->assertEquals($expected, $actual);
    }

    public function testDidNotInsert()
    {
        $sql = 'INSERT INTO users (name, email, password) VALUES (:name, :email, :password)';
        $params = [
                'name' => 'Boris Verhaaff',
                'email' => 'boris@websoftwar.es',
                'password' => '123456',
                'active' => 0,
            ];

        $userEntity = new UserEntity($params);

        $stmt = $this->getMock('PDOStatement', array('execute'));

        $stmt->expects($this->once())->method('execute')
            ->with($this->equalTo($params))
            ->will($this->returnValue(false));

        $this->mockPdo->expects($this->once())->method('prepare')
                ->with($this->equalTo($sql))
                ->will($this->returnValue($stmt));

        $userGateway = new UserGateway($this->mockPdo);

        $actual = $userGateway->insert($userEntity);

        $this->assertEquals($userEntity, $actual);
    }

    /**
     * @expectedException PDOException
     */
    public function testInsertFailsException()
    {
        $sql = 'INSERT INTO users (name, email, password) VALUES (:name, :email, :password)';
        $params = [
                'name' => 'Boris Verhaaff',
                'email' => 'boris@websoftwar.es',
                'password' => '123456',
                'active' => 0,
            ];
        $exception = new \PDOException('test', 1);

        $userEntity = new UserEntity($params);

        $this->mockPdo->expects($this->once())->method('prepare')
                ->with($this->equalTo($sql))
                ->will($this->throwException($exception));

        $userGateway = new UserGateway($this->mockPdo);

        $actual = $userGateway->insert($userEntity);

        $this->assertEquals($userEntity, $actual);
    }

    public function testUpdateSucceeds()
    {
        $sql = 'UPDATE users SET id = :id, email = :email, name = :name, password = :password, active = :active WHERE id = :id';

        $params = [
                'id' => 1,
                'name' => 'Boris Verhaaff',
                'email' => 'boris@websoftwar.es',
                'password' => '123456',
                'active' => 0,
            ];

        $execute = [
                   ':id' => 1,
                ':email' => 'boris@websoftwar.es',
                ':name' => 'Boris Verhaaff',
                ':password' => '123456',
                ':active' => 0,
            ];

        $expected = true;

        $userEntity = new UserEntity($params);

        $stmt = $this->getMock('PDOStatement', array('execute'));

        $stmt->expects($this->once())->method('execute')
            ->with($this->equalTo($execute))
            ->will($this->returnValue(true));

        $this->mockPdo->expects($this->once())->method('prepare')
                ->with($this->equalTo($sql))
                ->will($this->returnValue($stmt));

        $userGateway = new UserGateway($this->mockPdo);

        $actual = $userGateway->update($userEntity);

        $this->assertEquals($expected, $actual);
    }

    public function testDidNotUpdate()
    {
        $sql = 'UPDATE users SET id = :id, email = :email, name = :name, password = :password, active = :active WHERE id = :id';

        $params = [
                'id' => 1,
                'name' => 'Boris Verhaaff',
                'email' => 'boris@websoftwar.es',
                'password' => '123456',
                'active' => 0,
            ];

        $execute = [
                   ':id' => 1,
                ':email' => 'boris@websoftwar.es',
                ':name' => 'Boris Verhaaff',
                ':password' => '123456',
                ':active' => 0,
            ];

        $expected = false;

        $userEntity = new UserEntity($params);

        $stmt = $this->getMock('PDOStatement', array('execute'));

        $stmt->expects($this->once())->method('execute')
            ->with($this->equalTo($execute))
            ->will($this->returnValue(false));

        $this->mockPdo->expects($this->once())->method('prepare')
                ->with($this->equalTo($sql))
                ->will($this->returnValue($stmt));

        $userGateway = new UserGateway($this->mockPdo);

        $actual = $userGateway->update($userEntity);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @expectedException PDOException
     */
    public function testUpdateFailsException()
    {
        $exception = new \PDOException('test', 1);

        $sql = 'UPDATE users SET id = :id, email = :email, name = :name, password = :password, active = :active WHERE id = :id';

        $params = [
                'id' => 1,
                'name' => 'Boris Verhaaff',
                'email' => 'boris@websoftwar.es',
                'password' => '123456',
                'active' => 0,
            ];

        $execute = [
                   ':id' => 1,
                ':email' => 'boris@websoftwar.es',
                ':name' => 'Boris Verhaaff',
                ':password' => '123456',
                ':active' => 0,
            ];

        $expected = false;

        $userEntity = new UserEntity($params);

        $stmt = $this->getMock('PDOStatement', array('execute'));

        $this->mockPdo->expects($this->once())->method('prepare')
                ->with($this->equalTo($sql))
                ->will($this->throwException($exception));

        $userGateway = new UserGateway($this->mockPdo);

        $actual = $userGateway->update($userEntity);

        $this->assertEquals($expected, $actual);
    }
}
