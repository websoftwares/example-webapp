<?php

namespace Websoftwares\Domain\User;

/**
 * UserFactory.
 *
 * @license http://opensource.org/licenses/MIT
 * @author Boris <boris@websoftwar.es>
 */
class UserFactory
{
    /**
     * newEntity.
     *
     * @param array $data
     *
     * @return object UserEntity
     */
    public function newEntity(array $data)
    {
        return new UserEntity($data);
    }
}
