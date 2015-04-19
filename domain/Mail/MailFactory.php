<?php

namespace Websoftwares\Domain\Mail;

/**
 * MailFactory.
 *
 * @license http://opensource.org/licenses/MIT
 * @author Boris <boris@websoftwar.es>
 */
class MailFactory
{
    /**
     * newMailMessageEntity.
     *
     * @param array $data
     *
     * @return object MailMessageEntity
     */
    public function newMailMessageEntity(array $data)
    {
        return new MailMessageEntity($data);
    }
}
