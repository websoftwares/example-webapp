<?php

namespace Websoftwares\Domain\Mail;

/**
 * MailStrategyFactory.
 *
 * @license http://opensource.org/licenses/MIT
 * @author Boris <boris@websoftwar.es>
 */
class MailStrategyFactory
{
    /**
     * $strategies.
     *
     * @var array
     */
    protected $strategies = [
       'mail' => 'Websoftwares\Domain\Mail\Strategy\MailSwiftAdapter',
    ];

    /**
     * newInstance.
     *
     * @param string            $strategy
     * @param MailMessageEntity $mailMessageEntity
     *
     * @return object MailInterface
     */
    public function newInstance($strategy, MailMessageEntity $mailMessageEntity)
    {
        $className = $this->strategies[$strategy];

        return new $className($mailMessageEntity);
    }
}
