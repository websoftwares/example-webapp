<?php

namespace Websoftwares\Domain\User;

use FOA\DomainPayload\PayloadFactory;
use Psr\Log\LoggerInterface;

/**
 * UserService.
 *
 * @license http://opensource.org/licenses/MIT
 * @author Boris <boris@websoftwar.es>
 */
class UserService
{
    /**
     * $userGateway.
     *
     * @var object
     */
    protected $userGateway;

    /**
     * $userFilter.
     *
     * @var object
     */
    protected $userFilter;

    /**
     * $userFactory.
     *
     * @var object
     */
    protected $userFactory;

    /**
     * $payloadFactory.
     *
     * @var object
     */
    protected $payloadFactory;

    /**
     * $logger.
     *
     * @var object
     */
    protected $logger;

    /**
     * __construct.
     *
     * @param UserGateway     $userGateway
     * @param UserFilter      $userFilter
     * @param UserFactory     $userFactory
     * @param PayloadFactory  $payloadFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        UserGateway $userGateway,
        UserFilter $userFilter,
        UserFactory $userFactory,
        PayloadFactory $payloadFactory,
        LoggerInterface $logger
        ) {
        $this->userGateway = $userGateway;
        $this->userFilter = $userFilter;
        $this->userFactory = $userFactory;
        $this->payloadFactory = $payloadFactory;
        $this->logger = $logger;
    }

    /**
     * createUser.
     *
     * @param array $data
     *
     * @return object PayloadInterface
     */
    public function createUser(array $data)
    {
        try {
            // instantiate a new entity
            $userEntity = $this->userFactory->newEntity($data);

            $userEntity->active = 0;

            // validate the entity
            if (! $this->userFilter->forInsert($userEntity, $this->userGateway)) {
                return $this->payloadFactory->notValid(array(
                    'data' => $data,
                    'messages' => $this->userFilter->getMessages(),
                ));
            }

            $saveUserEntity = clone $userEntity;
            $saveUserEntity->password = \password_hash($userEntity->password, PASSWORD_DEFAULT);

            $savedUserEntity = $this->userGateway->insert($saveUserEntity);

            // insert the entity
            if (! isset($savedUserEntity->id)) {
                return $this->payloadFactory->notCreated(array(
                    'data' => $data,
                ));
            }
            // success
            return $this->payloadFactory->created(array(
               'user' => $savedUserEntity,
            ));
        // Catch and log
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), $data);

            return $this->payloadFactory->error(array(
                'exception' => $e,
                'data' => $data,
            ));
        }
    }

    /**
     * fetchUserByEmail.
     *
     * @param string $email
     *
     * @return object PayloadInterface
     */
    public function fetchUserByEmail($email)
    {
        try {
            $userEntity = $this->userGateway->fetchOne(
                $this->userFactory->newEntity(['email' => $email])
            );

            if (! isset($userEntity->id)) {
                return $this->payloadFactory->notFound(array(
                    'email' => $email,
                ));
            }

            return $this->payloadFactory->found(array(
                'user' => $userEntity,
            ));
        // Catch and log
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), [$email]);

            return $this->payloadFactory->error(array(
                'exception' => $e,
                'email' => $email,
            ));
        }
    }

    /**
     * updateUser.
     *
     * @param array $data
     *
     * @return object PayloadInterface
     */
    public function updateUser(array $data)
    {
        try {
            $userEntity =  $this->userFactory->newEntity($data);

            // validate the entity
            if (! $this->userFilter->forUpdate($userEntity, $this->userGateway)) {
                return $this->payloadFactory->notValid(array(
                    'data' => $data,
                    'messages' => $this->userFilter->getMessages(),
                ));
            }

            // update the entity
            if (! $this->userGateway->update($userEntity)) {
                return $this->payloadFactory->notUpdated(array(
                    'data' => $data,
                ));
            }

            // success
            return $this->payloadFactory->updated(array(
                'user' => $userEntity,
            ));
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), $data);

            return $this->payloadFactory->error(array(
                'exception' => $e,
                'data' => $data,
            ));
        }
    }
}
