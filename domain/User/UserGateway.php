<?php

namespace Websoftwares\Domain\User;

use Websoftwares\Domain\BaseGateway;

/**
 * UserGateway.
 *
 * @license http://opensource.org/licenses/MIT
 * @author Boris <boris@websoftwar.es>
 */
class UserGateway extends BaseGateway
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
     * fetchOne.
     *
     * @param UserEntity $entity
     *
     * @throws PDOException
     *
     * @return object UserEntity
     */
    public function fetchOne(UserEntity $entity)
    {
        $sql = 'SELECT * FROM users';

        if ($entity->email) {
            $sql .= ' WHERE email = :email';
            $params = [':email' => $entity->email];
        } elseif ($entity->id) {
            $sql .= ' WHERE id = :id';
            $params = [':id' => $entity->id];
        }

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->setFetchMode(\PDO::FETCH_CLASS, 'Websoftwares\Domain\User\UserEntity');
            $stmt->execute($params);

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

    /**
     * insert.
     *
     * @param UserEntity $entity
     *
     * @return UserEntity Return on success with the lastInsertId
     */
    public function insert(UserEntity $entity)
    {
        $sql = 'INSERT INTO users (name, email, password) VALUES (:name, :email, :password)';

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
     * update.
     *
     * @param UserEntity $entity
     *
     * @return bool
     */
    public function update(UserEntity $entity)
    {
        $update = $this->buildUpdateQuery($entity, 'users', ' WHERE id = :id');

        try {
            $stmt = $this->db->prepare($update['query']);
            if ($stmt->execute($update['values'])) {
                return true;
            }

        // Catch and re-throw exception
        } catch (\PDOException $e) {
            throw $e;
        }

        return false;
    }
}
