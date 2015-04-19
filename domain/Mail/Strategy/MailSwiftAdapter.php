<?php

namespace Websoftwares\Domain\Mail\Strategy;

use Websoftwares\Domain\Mail\MailInterface;
use Websoftwares\Domain\Mail\MailMessageEntity;

/**
 * MailAdapter.
 *
 * @license http://opensource.org/licenses/MIT
 * @author Boris <boris@websoftwar.es>
 */
class MailSwiftAdapter implements MailInterface
{
    /**
     * $message.
     *
     * @var object
     */
    protected $message;

    /**
     * $mailer.
     *
     * @var object
     */
    protected $mailer;

    /**
     * __construct.
     *
     * @param MailMessageEntity $message
     */
    public function __construct(MailMessageEntity $message)
    {
        // Mail
        $transport = \Swift_MailTransport::newInstance();
        $this->mailer = \Swift_Mailer::newInstance($transport);

        $this->message = \Swift_Message::newInstance($message->subject)
          ->setFrom($message->from)
          ->setTo($message->to)
          ->setBody($message->body)
          ;
    }

    /**
     * send.
     *
     * @return int
     */
    public function send()
    {
        try {
            return $this->mailer->send($this->message);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
