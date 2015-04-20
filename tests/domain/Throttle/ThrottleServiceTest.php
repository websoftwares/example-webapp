<?php

namespace Websoftwares\Tests\Domain\Throttle;

use FOA\DomainPayload\PayloadFactory;
use Websoftwares\Domain\Throttle\ThrottleFactory;
use Websoftwares\Domain\Throttle\ThrottleService;

/**
 * ThrottleServiceTest.
 *
 * @license http://opensource.org/licenses/MIT
 * @author Boris <boris@websoftwar.es>
 */
class ThrottleServiceTest extends \PHPUnit_Framework_TestCase
{
    protected $throttleFactory;
    protected $payloadFactory;
    protected $logger;
    protected $identifiers;

    protected $throttleService;

    public function setUp()
    {
        $this->throttleFactory = new ThrottleFactory;
        $this->payloadFactory = new PayloadFactory;
        $this->logger = $this->getMock('Psr\Log\LoggerInterface');

        $this->throttleService = new ThrottleService(
            $this->throttleFactory,
            $this->payloadFactory,
            $this->logger
        );

        $this->identifiers = array(
                'email' => 'boris@websoftwar.es',
                'ip' => '88.88.86.86'
            );
    }

    public function testInstantiateAsObjectSucceeds()
    {
        $this->assertInstanceOf(
            'Websoftwares\Domain\Throttle\ThrottleService',
            $this->throttleService
            );
    }

    public function testValidateFailsOnIp()
    {
        $throttleFactory = $this->getMock('Websoftwares\Domain\Throttle\ThrottleFactory');

        $userEmail = $this->getMockBuilder('Websoftwares\Throttle')
            ->disableOriginalConstructor()
            ->getMock();

        $userEmail->expects($this->once())
            ->method('validate')
            ->with($this->equalTo($this->identifiers['email']))
            ->will($this->returnValue(true));

        $ipAddressMock = $this->getMockBuilder('Websoftwares\Throttle')
            ->disableOriginalConstructor()
            ->getMock();

        $ipAddressMock->expects($this->once())
            ->method('validate')
            ->with($this->equalTo($this->identifiers['ip']))
            ->will($this->returnValue(false));   

        $throttleFactory->expects($this->any())
            ->method('userEmail')
            ->will($this->returnValue($userEmail));

        $throttleFactory->expects($this->once())
            ->method('ipAddress')
            ->will($this->returnValue($ipAddressMock));


        $throttleService = new ThrottleService(
            $throttleFactory,
            $this->payloadFactory,
            $this->logger
        );


        $actual = $throttleService->validate($this->identifiers);

        $this->assertInstanceOf('FOA\DomainPayload\NotValid', $actual);
        $this->assertEquals($this->identifiers['ip'], $actual->get('ip'));
    }

    public function testValidateFailsOnEmail()
    {
        $throttleFactory = $this->getMock('Websoftwares\Domain\Throttle\ThrottleFactory');

        // Email
        $userEmail = $this->getMockBuilder('Websoftwares\Throttle')
            ->disableOriginalConstructor()
            ->getMock();

        $userEmail->expects($this->once())
            ->method('validate')
            ->with($this->equalTo($this->identifiers['email']))
            ->will($this->returnValue(false));

        // Ip
        $ipAddressMock = $this->getMockBuilder('Websoftwares\Throttle')
            ->disableOriginalConstructor()
            ->getMock();

        $ipAddressMock->expects($this->once())
            ->method('validate')
            ->with($this->equalTo($this->identifiers['ip']))
            ->will($this->returnValue(true));

        // Factory methods
        $throttleFactory->expects($this->any())
            ->method('userEmail')
            ->will($this->returnValue($userEmail));

        $throttleFactory->expects($this->once())
            ->method('ipAddress')
            ->will($this->returnValue($ipAddressMock));


        $throttleService = new ThrottleService(
            $throttleFactory,
            $this->payloadFactory,
            $this->logger
        );


        $actual = $throttleService->validate($this->identifiers);

        $this->assertInstanceOf('FOA\DomainPayload\NotValid', $actual);
        $this->assertEquals($this->identifiers['email'], $actual->get('email'));
    }

    public function testValidateFailsOnBrange()
    {
        $throttleFactory = $this->getMock('Websoftwares\Domain\Throttle\ThrottleFactory');

        // Email
        $userEmail = $this->getMockBuilder('Websoftwares\Throttle')
            ->disableOriginalConstructor()
            ->getMock();

        $userEmail->expects($this->once())
            ->method('validate')
            ->with($this->equalTo($this->identifiers['email']))
            ->will($this->returnValue(true));

        // Ip
        $ipAddressMock = $this->getMockBuilder('Websoftwares\Throttle')
            ->disableOriginalConstructor()
            ->getMock();

        $ipAddressMock->expects($this->once())
            ->method('validate')
            ->with($this->equalTo($this->identifiers['ip']))
            ->will($this->returnValue(true));

        // B range
        $bRangeMock = $this->getMockBuilder('Websoftwares\Throttle')
            ->disableOriginalConstructor()
            ->getMock();

        $bRangeMock->expects($this->once())
            ->method('validate')
            ->with($this->equalTo('88.0.0.0'))
            ->will($this->returnValue(false));

        // C range
        $cRangeMock = $this->getMockBuilder('Websoftwares\Throttle')
            ->disableOriginalConstructor()
            ->getMock();

        $cRangeMock->expects($this->once())
            ->method('validate')
            ->with($this->equalTo('88.88.0.0'))
            ->will($this->returnValue(true));   

        // Factory method
        $throttleFactory->expects($this->once())
            ->method('bRange')
            ->will($this->returnValue($bRangeMock));

        $throttleFactory->expects($this->once())
            ->method('cRange')  
            ->will($this->returnValue($cRangeMock));

        $throttleFactory->expects($this->once())
            ->method('ipAddress')
            ->will($this->returnValue($ipAddressMock));

        $throttleFactory->expects($this->any())
            ->method('userEmail')
            ->will($this->returnValue($userEmail));

        $throttleService = new ThrottleService(
            $throttleFactory,
            $this->payloadFactory,
            $this->logger
        );

        $actual = $throttleService->validate($this->identifiers);

        $this->assertInstanceOf('FOA\DomainPayload\NotValid', $actual);
 
        $this->assertEquals('88.0.0.0', $actual->get('bRange'));
    }

    public function testValidateFailsOnCrange()
    {
        $throttleFactory = $this->getMock('Websoftwares\Domain\Throttle\ThrottleFactory');

        // Email
        $userEmail = $this->getMockBuilder('Websoftwares\Throttle')
            ->disableOriginalConstructor()
            ->getMock();

        $userEmail->expects($this->once())
            ->method('validate')
            ->with($this->equalTo($this->identifiers['email']))
            ->will($this->returnValue(true));

        // Ip
        $ipAddressMock = $this->getMockBuilder('Websoftwares\Throttle')
            ->disableOriginalConstructor()
            ->getMock();

        $ipAddressMock->expects($this->once())
            ->method('validate')
            ->with($this->equalTo($this->identifiers['ip']))
            ->will($this->returnValue(true));

        // B range
        $bRangeMock = $this->getMockBuilder('Websoftwares\Throttle')
            ->disableOriginalConstructor()
            ->getMock();

        $bRangeMock->expects($this->once())
            ->method('validate')
            ->with($this->equalTo('88.0.0.0'))
            ->will($this->returnValue(true));

        // C range
        $cRangeMock = $this->getMockBuilder('Websoftwares\Throttle')
            ->disableOriginalConstructor()
            ->getMock();

        $cRangeMock->expects($this->once())
            ->method('validate')
            ->with($this->equalTo('88.88.0.0'))
            ->will($this->returnValue(false));

        // Factory method
        $throttleFactory->expects($this->once())
            ->method('bRange')
            ->will($this->returnValue($bRangeMock));

        $throttleFactory->expects($this->once())
            ->method('cRange')  
            ->will($this->returnValue($cRangeMock));

        $throttleFactory->expects($this->once())
            ->method('ipAddress')
            ->will($this->returnValue($ipAddressMock));

        $throttleFactory->expects($this->any())
            ->method('userEmail')
            ->will($this->returnValue($userEmail));

        $throttleService = new ThrottleService(
            $throttleFactory,
            $this->payloadFactory,
            $this->logger
        );

        $actual = $throttleService->validate($this->identifiers);
        $this->assertInstanceOf('FOA\DomainPayload\NotValid', $actual);
 
        $this->assertEquals('88.88.0.0', $actual->get('cRange'));
    }

    public function testValidateFailsOnException()
    {

        $exception = new \Exception('test', 1);

        $throttleFactory = $this->getMock('Websoftwares\Domain\Throttle\ThrottleFactory');

        // Ip
        $ipAddressMock = $this->getMockBuilder('Websoftwares\Throttle')
            ->disableOriginalConstructor()
            ->getMock();

        $ipAddressMock->expects($this->once())
            ->method('validate')
            ->with($this->equalTo($this->identifiers['ip']))
            ->will($this->throwException($exception));

        $throttleFactory->expects($this->once())
            ->method('ipAddress')
            ->will($this->returnValue($ipAddressMock));

        $throttleService = new ThrottleService(
            $throttleFactory,
            $this->payloadFactory,
            $this->logger
        );

        $actual = $throttleService->validate($this->identifiers);
        $this->assertInstanceOf('\Exception', $actual->get('exception'));
        $this->assertEquals(1, $actual->get('exception')->getCode());
        $this->assertEquals('test', $actual->get('exception')->getMessage());
    }

    public function testValidateSucceeds()
    {
        $throttleFactory = $this->getMock('Websoftwares\Domain\Throttle\ThrottleFactory');

        // Email
        $userEmail = $this->getMockBuilder('Websoftwares\Throttle')
            ->disableOriginalConstructor()
            ->getMock();

        $userEmail->expects($this->once())
            ->method('validate')
            ->with($this->equalTo($this->identifiers['email']))
            ->will($this->returnValue(true));

        $userEmail->expects($this->once())
            ->method('remaining')
            ->with($this->equalTo($this->identifiers['email']))
            ->will($this->returnValue(2));

        // Ip
        $ipAddressMock = $this->getMockBuilder('Websoftwares\Throttle')
            ->disableOriginalConstructor()
            ->getMock();

        $ipAddressMock->expects($this->once())
            ->method('validate')
            ->with($this->equalTo($this->identifiers['ip']))
            ->will($this->returnValue(true));

        $ipAddressMock->expects($this->once())
            ->method('remaining')
            ->with($this->equalTo($this->identifiers['ip']))
            ->will($this->returnValue(2));

        // B range
        $bRangeMock = $this->getMockBuilder('Websoftwares\Throttle')
            ->disableOriginalConstructor()
            ->getMock();

        $bRangeMock->expects($this->once())
            ->method('validate')
            ->with($this->equalTo('88.0.0.0'))
            ->will($this->returnValue(true));

        $bRangeMock->expects($this->once())
            ->method('remaining')
            ->with($this->equalTo('88.0.0.0'))
            ->will($this->returnValue(999));

        // C range
        $cRangeMock = $this->getMockBuilder('Websoftwares\Throttle')
            ->disableOriginalConstructor()
            ->getMock();

        $cRangeMock->expects($this->once())
            ->method('validate')
            ->with($this->equalTo('88.88.0.0'))
            ->will($this->returnValue(true));

        $cRangeMock->expects($this->once())
            ->method('remaining')
            ->with($this->equalTo('88.88.0.0'))
            ->will($this->returnValue(499));

        // Factory method
        $throttleFactory->expects($this->any())
            ->method('bRange')
            ->will($this->returnValue($bRangeMock));

        $throttleFactory->expects($this->any())
            ->method('cRange')  
            ->will($this->returnValue($cRangeMock));

        $throttleFactory->expects($this->any())
            ->method('ipAddress')
            ->will($this->returnValue($ipAddressMock));

        $throttleFactory->expects($this->any())
            ->method('userEmail')
            ->will($this->returnValue($userEmail));

        $throttleService = new ThrottleService(
            $throttleFactory,
            $this->payloadFactory,
            $this->logger
        );

        $actual = $throttleService->validate($this->identifiers);
        $this->assertInstanceOf('FOA\DomainPayload\Valid', $actual);
        $this->assertEquals(2,  $actual->get('ip'));
        $this->assertEquals(2,  $actual->get('email'));
        $this->assertEquals(999,  $actual->get('bRange'));
        $this->assertEquals(499,  $actual->get('cRange'));
    }
}
