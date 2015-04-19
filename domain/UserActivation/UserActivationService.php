<?php

namespace Websoftwares\Domain\UserActivation;

use FOA\DomainPayload\PayloadFactory;
use Psr\Log\LoggerInterface;
use Websoftwares\Domain\RandomString;

/**
 * UserActivationService.
 *
 * @license http://opensource.org/licenses/MIT
 * @author Boris <boris@websoftwar.es>
 */
class UserActivationService
{
    /**
     * $userActivationGateway.
     *
     * @var object
     */
    protected $userActivationGateway;

    /**
     * $userActivationFactory.
     *
     * @var object
     */
    protected $userActivationFactory;

    /**
     * $userActivationFilter.
     *
     * @var object
     */
    protected $userActivationFilter;

    /**
     * $randomString.
     *
     * @var object
     */
    protected $randomString;

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
     * @param UserActivationGateway $userActivationGateway
     * @param UserActivationFactory $UserActivationFactory
     * @param UserActivationFilter  $userActivationFilter
     * @param RandomString          $randomString
     * @param PayloadFactory        $payloadFactory
     * @param LoggerInterface       $logger
     */
    public function __construct(
        UserActivationGateway $userActivationGateway,
        UserActivationFactory $userActivationFactory,
        UserActivationFilter $userActivationFilter,
        RandomString $randomString,
        PayloadFactory $payloadFactory,
        LoggerInterface $logger
        ) {
        $this->userActivationGateway = $userActivationGateway;
        $this->userActivationFactory = $userActivationFactory;
        $this->userActivationFilter = $userActivationFilter;
        $this->randomString = $randomString;
        $this->payloadFactory = $payloadFactory;
        $this->logger = $logger;
    }

    /**
     * saveUserActivationToken.
     *
     * @param int $userId
     *
     * @return object PayloadInterface
     */
    public function saveUserActivationToken($userId = null)
    {
        try {

            // instantiate a new entity
            $userActivationEntity = $this->userActivationFactory->newEntity(
                array(
                    'userId' => $userId,
                    'token' => $this->randomString->generate(64, true),
                    )
                );

            // validate the entity
            if (! $this->userActivationFilter->forInsert($userActivationEntity)) {
                return $this->payloadFactory->notValid(array(
                    'userId' => $userId,
                    'messages' => $this->userActivationFilter->getMessages(),
                ));
            }

            $savedUserActivationEntity = $this->userActivationGateway->insert($userActivationEntity);

            // insert the entity
            if (! isset($savedUserActivationEntity->id)) {
                return $this->payloadFactory->notCreated(array(
                    'userId' => $userId,
                ));
            }

            // success
            return $this->payloadFactory->created(array(
               'userActivation' => $savedUserActivationEntity,
            ));

        // Catch and log
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(),
                array(
                    'userId' => $userId,
                )
            );

            return $this->payloadFactory->error(array(
                'exception' => $e,
                'userId' => $userId,
            ));
        }
    }

    /**
     * fetchUserActivationByToken.
     *
     * @param string $token
     *
     * @return object PayloadInterface
     */
    public function fetchUserActivationByToken($token)
    {
        try {
            $userActivationEntity = $this->userActivationGateway->fetchOne(
                $this->userActivationFactory->newEntity(
                    array('token' => $token)
                )
            );

            if (! isset($userActivationEntity->id)) {
                return $this->payloadFactory->notFound(array(
                    'token' => $token,
                ));
            }

            return $this->payloadFactory->found(array(
                'userActivation' => $userActivationEntity,
            ));
        // Catch and log
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), [$token]);

            return $this->payloadFactory->error(array(
                'exception' => $e,
                'token' => $token,
            ));
        }
    }

    /**
     * deleteUserActivationByToken.
     *
     * @param string $token
     *
     * @return object PayloadInterface
     */
    public function deleteUserActivationByToken($token = null)
    {
        try {

            // instantiate a new entity
            $userActivationEntity = $this->userActivationFactory->newEntity(
                array(
                    'token' => $token,
                    )
                );

            // validate the entity
            if (! $this->userActivationFilter->forDelete($userActivationEntity)) {
                return $this->payloadFactory->notValid(array(
                    'token' => $token,
                    'messages' => $this->userActivationFilter->getMessages(),
                ));
            }

            // delete the entity
            if (! $this->userActivationGateway->delete($userActivationEntity)) {
                return $this->payloadFactory->notDeleted(array(
                    'token' => $token,
                ));
            }
            // success
            return $this->payloadFactory->deleted(array(
                'userActivation' => $userActivationEntity,
            ));
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), [$token]);

            return $this->payloadFactory->error(array(
                'exception' => $e,
                'token' => $token,
            ));
        }
    }
}
