<?php

namespace Websoftwares\Tests\Domain\Mail\Strategy;

use Websoftwares\Domain\Mail\Strategy\MailSwiftAdapter;
use Websoftwares\Domain\Mail\MailMessageEntity;

/**
 * Class MailSwiftAdapterTest.
 */
class MailSwiftAdapterTest extends \PHPUnit_Framework_TestCase
{
    public $mailSwiftAdapter;

    public function setUp()
    {
        $this->data = [
                'subject' => 'Hello World',
                'to' => 'boris@Websoftwar.es',
                'from' => 'boris@Websoftwar.es',
                'body' => 'Welcome you, thanks!',
            ];
        $this->mailSwiftAdapter = new MailSwiftAdapter(
            new MailMessageEntity($this->data)
            );
    }

    public function testInstantiateAsObjectSucceeds()
    {
        $this->assertInstanceOf(
            'Websoftwares\Domain\Mail\Strategy\MailSwiftAdapter',
            $this->mailSwiftAdapter);
    }

    /**
     * @expectedException Swift_RfcComplianceException
     */
    public function testSendMailException()
    {
        $this->data['to'] = 'notvalidEmail';

        (new MailSwiftAdapter(
            new MailMessageEntity($this->data)
            ))->send();
    }

    public function testSendMailSucceeds()
    {
        $mailer = $this->getMockBuilder('\Swift_Mailer')
        ->disableOriginalConstructor()
        ->getMock();

        $mailer->expects($this->once())
         ->method('send')
         ->will($this->returnValue(1));

        $reflection = new \ReflectionClass($this->mailSwiftAdapter);
        $reflection_property = $reflection->getProperty('mailer');
        $reflection_property->setAccessible(true);

        $reflection_property->setValue($this->mailSwiftAdapter, $mailer);

        $actual = $this->mailSwiftAdapter->send();
        $this->assertEquals(1, $actual);
    }
}
