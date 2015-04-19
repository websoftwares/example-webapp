<?php

namespace Websoftwares\Domain\UserActivation;

use Websoftwares\Domain\BaseFilter;

/**
 * UserActivationFilter.
 *
 * @license http://opensource.org/licenses/MIT
 * @author Boris <boris@websoftwar.es>
 */
class UserActivationFilter extends BaseFilter
{
    /**
     * $userActivationEntity.
     *
     * @var object
     */
    protected $userActivationEntity;

    /**
     * forInsert.
     *
     * @param UserActivationEntity $userActivationEntity
     *
     * @return bool
     */
    public function forInsert(UserActivationEntity $userActivationEntity)
    {
        $this->userActivationEntity = $userActivationEntity;
        $this->messages = array();

        $this->token();
        $this->userId();

        return $this->isValid();
    }

    /**
     * forDelete.
     *
     * @param UserActivationEntity $userEntity
     *
     * @return bool
     */
    public function forDelete(UserActivationEntity $userActivationEntity)
    {
        $this->userActivationEntity = $userActivationEntity;
        $this->messages = array();

        $this->token();

        return $this->isValid();
    }

    /**
     * token.
     *
     * @return bool
     */
    protected function token()
    {
        // Empty
        $this->userActivationEntity->token = trim($this->userActivationEntity->token);
        if (! $this->userActivationEntity->token) {
            $this->messages['token'] = 'Token cannot be empty.';

            return false;
        }

        return true;
    }

    /**
     * userId.
     *
     * @return bool
     */
    protected function userId()
    {
        // Empty
        $this->userActivationEntity->userId = (int) $this->userActivationEntity->userId;
        if (! $this->userActivationEntity->userId) {
            $this->messages['userId'] = 'UserId cannot be empty.';

            return false;
        }

        return true;
    }
}
