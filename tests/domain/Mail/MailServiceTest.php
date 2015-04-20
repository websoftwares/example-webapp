<?php

namespace Websoftwares\Tests\domain\Mail;

use Websoftwares\Domain\Mail\MailService;
use Websoftwares\Domain\Mail\MailStrategyFactory;
use Websoftwares\Domain\Mail\MailMessageEntity;
use Websoftwares\Domain\Mail\MailFactory;
use Websoftwares\Domain\Mail\MailPayloadFactory;

/**
 * Class MailServiceTest.
 */
class MailServiceTest extends \PHPUnit_Framework_TestCase
{
    public $mailStrategyFactory;
    public $mailFactory;

    public $payloadFactory;

    public $mailService;

    public function setUp()
    {
        $this->mailStrategyFactory = new MailStrategyFactory();
        $this->mailFactory = new MailFactory();
        $this->payloadFactory = new MailPayloadFactory();
        $this->logger = $this->getMock('Psr\Log\LoggerInterface');

        $this->mailService = new MailService(
            $this->mailStrategyFactory,
            $this->mailFactory,
            $this->payloadFactory,
            $this->logger,
            'mail'
        );
    }

    public function testInstantiateAsObjectSucceeds()
    {
        $this->assertInstanceOf(
            'Websoftwares\Domain\Mail\MailService',
            $this->mailService
            );
    }

    public function testSendMailFailsNotDelivered()
    {
        $subject = 'Hello World';
        $fromEmail = 'boris@websoftwar.es';

        $toEmail = 'boris@websoftwar.es';
        $body = 'Nice to meet you, thanks!';

        $mailSendMock = $this->getMock('Websoftwares\Domain\Mail\MailInterface');
        $mailSendMock->expects($this->once())
            ->method('send')
            ->will($this->returnValue(0));

        $mailStrategyFactoryMock = $this->getMock('Websoftwares\Domain\Mail\MailStrategyFactory');
        $mailStrategyFactoryMock->expects($this->once())
            ->method('newInstance')
            ->will($this->returnValue($mailSendMock));

        $mailService = new MailService(
                $mailStrategyFactoryMock,
                $this->mailFactory,
                $this->payloadFactory,
                $this->logger,
                'mail'
            );

        $actual = $mailService->sendEmail(
            $subject,
            $fromEmail,
            $toEmail,
            $body
        );

        $this->assertInstanceOf('FOA\DomainPayload\PayloadInterface', $actual);
        $this->assertEquals(0, $actual->get('delivered'));
    }

    public function testSendMailFailsException()
    {
        $subject = 'Hello World';
        $fromEmail = 'boris@websoftwar.es';

        $toEmail = 'boris@websoftwar.es';
        $body = 'Nice to meet you, thanks!';

        $exception = new \Exception('test', 1);

        $mailSendMock = $this->getMock('Websoftwares\Domain\Mail\MailInterface');
        $mailSendMock->expects($this->once())
            ->method('send')
            ->will($this->throwException($exception));

        $mailStrategyFactoryMock = $this->getMock('Websoftwares\Domain\Mail\MailStrategyFactory');
        $mailStrategyFactoryMock->expects($this->once())
            ->method('newInstance')
            ->will($this->returnValue($mailSendMock));

        $mailService = new MailService(
                $mailStrategyFactoryMock,
                $this->mailFactory,
                $this->payloadFactory,
                $this->logger,
                'mail'
            );

        $actual = $mailService->sendEmail(
            $subject,
            $fromEmail,
            $toEmail,
            $body
        );

        $this->assertInstanceOf('FOA\DomainPayload\PayloadInterface', $actual);
        $this->assertEquals(new mailMessageEntity(array(
                        'subject' => $subject,
                        'from' => $fromEmail,
                        'to' => $toEmail,
                        'body' => $body,
                    )), $actual->get('mailMessage'));
        $this->assertInstanceOf('\Exception', $actual->get('exception'));
        $this->assertEquals(1, $actual->get('exception')->getCode());
        $this->assertEquals('test', $actual->get('exception')->getMessage());
    }

    public function testSendMailSucceeds()
    {
        $subject = 'Hello World';
        $fromEmail = 'boris@websoftwar.es';

        $toEmail = 'boris@websoftwar.es';
        $body = 'Nice to meet you, thanks!';

        $mailSendMock = $this->getMock('Websoftwares\Domain\Mail\MailInterface');
        $mailSendMock->expects($this->once())
            ->method('send')
            ->will($this->returnValue(1));

        $mailStrategyFactoryMock = $this->getMock('Websoftwares\Domain\Mail\MailStrategyFactory');
        $mailStrategyFactoryMock->expects($this->once())
            ->method('newInstance')
            ->will($this->returnValue($mailSendMock));

        $mailService = new MailService(
                $mailStrategyFactoryMock,
                $this->mailFactory,
                $this->payloadFactory,
                $this->logger,
                'mail'
            );

        $actual = $mailService->sendEmail(
            $subject,
            $fromEmail,
            $toEmail,
            $body
        );

        $this->assertInstanceOf('FOA\DomainPayload\PayloadInterface', $actual);
        $this->assertEquals(1, $actual->get('delivered'));
    }
}
