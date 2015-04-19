<?php

namespace Websoftwares\Domain\UserActivation;

/**
 * UserActivationFactory.
 *
 * @license http://opensource.org/licenses/MIT
 * @author Boris <boris@websoftwar.es>
 */
class UserActivationFactory
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
        return new UserActivationEntity($data);
    }
}
