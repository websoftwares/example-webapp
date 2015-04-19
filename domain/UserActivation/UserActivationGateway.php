<?php

namespace Websoftwares\Domain\UserActivation;

use Websoftwares\Domain\BaseGateway;

/**
 * UserActivationGateway.
 *
 * @license http://opensource.org/licenses/MIT
 * @author Boris <boris@websoftwar.es>
 */
class UserActivationGateway extends BaseGateway
{
    /**
     * $db.
     *
     * @var object
     */
    protected $db;

    /**
     * __construct.
     *
     * @param \PDO $db
     */
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    /**
     * insert Returns entity with id on succcess.
     *
     * @param UserActivationEntity $entity
     *
     * @return UserActivationEntity
     */
    public function insert(UserActivationEntity $entity)
    {
        $sql = 'INSERT INTO user__activations(userId,token) VALUES(:userId,:token)';

        try {
            $stmt = $this->db->prepare($sql);
            $entityArray = get_object_vars($entity);
            unset($entityArray['id']);

            if ($stmt->execute(array_filter($entityArray, 'strlen'))) {
                $entity->id = $this->db->lastInsertId();
            }

            return $entity;

        // Catch and re-throw exception
        } catch (\PDOException $e) {
            throw $e;
        }

        return $entity;
    }

    /**
     * delete.
     *
     * @param UserActivationEntity $entity
     *
     * @return bool
     */
    public function delete(UserActivationEntity $entity)
    {
        $sql = 'DELETE FROM user__activations WHERE token = :token';

        try {
            $stmt = $this->db->prepare($sql);

            return $stmt->execute(array(':token' => $entity->token));

        // Catch and re-throw exception
        } catch (\PDOException $e) {
            throw $e;
        }

        return false;
    }

    /**
     * fetchOne.
     *
     * @param UserActivationEntity $entity
     *
     * @throws PDOException
     *
     * @return object UserActivationEntity
     */
    public function fetchOne(UserActivationEntity $entity)
    {
        $sql = 'SELECT * FROM user__activations WHERE token = :token';

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->setFetchMode(\PDO::FETCH_CLASS, 'Websoftwares\Domain\UserActivation\UserActivationEntity');
            $stmt->execute(array(':token' => $entity->token));

            // Return the entity if we have result
            if ($userEntity = $stmt->fetch(\PDO::FETCH_CLASS)) {
                return $userEntity;
            }
        // Catch and re-throw exception
        } catch (\PDOException $e) {
            throw $e;
        }

        // On failure we return entity
        return $entity;
    }
}
