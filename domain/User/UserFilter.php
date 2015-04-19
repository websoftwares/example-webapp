<?php

namespace Websoftwares\Domain\User;

use Websoftwares\Domain\BaseFilter;

/**
 * UserFilter.
 *
 * @license http://opensource.org/licenses/MIT
 * @author Boris <boris@websoftwar.es>
 */
class UserFilter extends BaseFilter
{
    /**
     * $userEntity.
     *
     * @var object
     */
    protected $userEntity;

    /**
     * $userGateway.
     *
     * @var object
     */
    protected $userGateway;

    /**
     * forInsert.
     *
     * @param UserEntity  $userEntity
     * @param UserGateway $userGateway
     *
     * @return bool
     */
    public function forInsert(UserEntity $userEntity, UserGateway $userGateway)
    {
        $this->userEntity = $userEntity;
        $this->userGateway = $userGateway;
        $this->messages = array();

        $this->name();
        $this->email();
        $this->password();
        $this->activeInsert();

        return $this->isValid();
    }

    /**
     * forUpdate.
     *
     * @param UserEntity  $userEntity
     * @param UserGateway $userGateway
     *
     * @return bool
     */
    public function forUpdate(UserEntity $userEntity, UserGateway $userGateway)
    {
        $this->userEntity = $userEntity;
        $this->userGateway = $userGateway;
        $this->messages = array();

        $this->id();
        $this->email();

        return $this->isValid();
    }

    /**
     * password.
     *
     * @return mixed
     */
    protected function password()
    {

        // Empty
        $this->userEntity->password = trim($this->userEntity->password);
        if (! $this->userEntity->password) {
            return $this->messages['password'] = 'Password cannot be empty.';
        }

        // Uppercase
        $uppercase = preg_match('/[A-Z]/', $this->userEntity->password);
        if (! $uppercase) {
            return $this->messages['password'] = 'Password must have an uppercase character.';
        }

        // Lowercase
        $lowercase = preg_match('/[a-z]/', $this->userEntity->password);
        if (! $lowercase) {
            return $this->messages['password'] = 'Password must have a lowercase character.';
        }

        // Special character
        $specialCharacter = preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $this->userEntity->password);
        if (! $specialCharacter) {
            return $this->messages['password'] = 'Password must have a special character.';
        }

        // Numbers
        $numbers = preg_match('/[0-9]/', $this->userEntity->password);
        if (! $numbers) {
            return $this->messages['password'] = 'Password must have a number character.';
        }

        // Length
        if (strlen($this->userEntity->password) < 8) {
            return $this->messages['password'] = 'Password length must be atleast 8 characters.';
        }
    }

    /**
     * email.
     *
     * @return mixed
     */
    protected function email()
    {
        // Empty
        $this->userEntity->email = trim($this->userEntity->email);
        if (! $this->userEntity->email) {
            $this->messages['email'] = 'Email cannot be empty.';

            return false;
        }

        // Valid
        if (! filter_var($this->userEntity->email, FILTER_VALIDATE_EMAIL)) {
            $this->messages['email'] = 'Email is not valid.';

            return false;
        }

        // Unique
        $exists = $this->userGateway->fetchOne($this->userEntity);
        if (isset($exists->id)) {
            $this->messages['email'] = 'Email already exists.';

            return false;
        }

        return true;
    }

    /**
     * name.
     *
     * @return bool
     */
    protected function name()
    {
        // Empty
        $this->userEntity->name = trim($this->userEntity->name);
        if (! $this->userEntity->name) {
            $this->messages['name'] = 'Name cannot be empty.';

            return false;
        }

        return true;
    }

    /**
     * activeInsert.
     *
     * @return bool
     */
    protected function activeInsert()
    {
        $this->active();

        // Non empty and 0 (zero)
        $this->userEntity->active = (int) $this->userEntity->active;
        if ($this->userEntity->active !== 0) {
            $this->messages['active'] = 'Active must be 0 (zero) integer.';

            return false;
        }

        return true;
    }

    /**
     * active.
     *
     * @return bool
     */
    protected function active()
    {
        // Empty
        $this->userEntity->active = trim($this->userEntity->active);
        if ($this->userEntity->active === '') {
            $this->messages['active'] = 'Active cannot be empty.';

            return false;
        }

        return true;
    }

    /**
     * id.
     *
     * @return bool
     */
    protected function id()
    {
        // Empty
        $this->userEntity->id = (int) $this->userEntity->id;
        if (! $this->userEntity->id) {
            $this->messages['id'] = 'Id cannot be empty.';

            return false;
        }

        return true;
    }
}
