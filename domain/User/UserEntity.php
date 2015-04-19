<?php

namespace Websoftwares\Domain\User;

use Websoftwares\Domain\BaseEntity;

/**
 * UserEntity.
 *
 * @license http://opensource.org/licenses/MIT
 * @author Boris <boris@websoftwar.es>
 */
class UserEntity extends BaseEntity
{
    public $id;
    public $email;
    public $name;
    public $password;
    public $active;
}
