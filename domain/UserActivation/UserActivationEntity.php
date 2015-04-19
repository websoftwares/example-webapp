<?php

namespace Websoftwares\Domain\UserActivation;

use Websoftwares\Domain\BaseEntity;

/**
 * UserActivationEntity	.
 *
 * @license http://opensource.org/licenses/MIT
 * @author Boris <boris@websoftwar.es>
 */
class UserActivationEntity extends BaseEntity
{
    public $id;
    public $userId;
    public $token;
    public $created;
}
