<?php

namespace Websoftwares\Domain\Mail;

use Websoftwares\Domain\BaseEntity;

/**
 * MailEntity.
 *
 * @license http://opensource.org/licenses/MIT
 * @author Boris <boris@websoftwar.es>
 */
class MailMessageEntity extends BaseEntity
{
    public $subject;
    public $to;
    public $from;
    public $body;
}
