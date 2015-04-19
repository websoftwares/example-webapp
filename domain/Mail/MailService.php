<?php

namespace Websoftwares\Domain\Mail;

use FOA\DomainPayload\PayloadFactory;
use Psr\Log\LoggerInterface;

/**
 * MailService.
 *
 * @license http://opensource.org/licenses/MIT
 * @author Boris <boris@websoftwar.es>
 */
class MailService
{
    /**
     * $mailFactory.
     *
     * @var object
     */
    protected $mailStrategyFactory;

    /**
     * $mailFactory.
     *
     * @var object
     */
    protected $mailFactory;

    /**
     * $transport.
     *
     * @var string
     */
    protected $transport;

    /**
     * $payloadFactory.
     *
     * @var object
     */
    protected $payloadFactory;

    /**
     * $logger.
     *
     * @var object
     */
    protected $logger;

    public function __construct(
        MailStrategyFactory $mailStrategyFactory,
        MailFactory $mailFactory,
        PayloadFactory $payloadFactory,
        LoggerInterface $logger,
        $transport = null
        ) {
        $this->mailStrategyFactory = $mailStrategyFactory;
        $this->mailFactory = $mailFactory;
        $this->payloadFactory = $payloadFactory;
        $this->logger = $logger;
        $this->transport = $transport;

        // If no transporter is default to mail
        if (! $transport) {
            $this->transport = 'mail';
        }
    }

    /**
     * sendEmail.
     *
     * @param string $subject
     * @param string $fromEmail
     * @param string $toEmail
     * @param string $body
     *
     * @return object PayloadInterface
     */
    public function sendEmail($subject, $fromEmail, $toEmail, $body)
    {
        try {

            // New mail message instance
            $mailMessageEntity = $this->mailFactory
                ->newMailMessageEntity(
                    array(
                        'subject' => $subject,
                        'from' => $fromEmail,
                        'to' => $toEmail,
                        'body' => $body,
                    )
                )
            ;

            // The number of mails delivered
            $delivered = $this->mailStrategyFactory
                ->newInstance(
                    $this->transport,
                    $mailMessageEntity
                )->send();

            // Not Delivered
            if (! $delivered) {
                return $this->payloadFactory->notDelivered(array(
                    'delivered' => $delivered,
                ));
            }

            // Success
            return $this->payloadFactory->delivered(array(
               'delivered' => $delivered,
            ));
        } catch (\Exception $e) {

            // Log
            $this->logger->error($e->getMessage(), (array) $mailMessageEntity);

            // Return
            return $this->payloadFactory->error(array(
                'exception' => $e,
                'mailMessage' => $mailMessageEntity,
            ));
        }
    }
}
