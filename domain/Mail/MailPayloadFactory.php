<?php

namespace Websoftwares\Domain\Mail;

use Websoftwares\Domain\Mail\Payload\Delivered;
use Websoftwares\Domain\Mail\Payload\NotDelivered;
use FOA\DomainPayload\PayloadFactory;

/**
 * MailPayloadFactory.
 *
 * @license http://opensource.org/licenses/MIT
 * @author Boris <boris@websoftwar.es>
 */
class MailPayloadFactory extends PayloadFactory
{
    public function delivered(array $payload)
    {
        return new Delivered($payload);
    }

    public function notDelivered(array $payload)
    {
        return new NotDelivered($payload);
    }
}
