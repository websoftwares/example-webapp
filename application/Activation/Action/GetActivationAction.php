<?php

namespace Websoftwares\Application\Activation\Action;

use Websoftwares\Application\Activation\Responder\ActivationResponder as Responder;
use Symfony\Component\HttpFoundation\Request;
use Websoftwares\Domain\User\UserService;
use Websoftwares\Domain\UserActivation\UserActivationService;

/**
 * GetActivationAction class.
 *
 * @author Boris <boris@websoftwar.es>
 */
class GetActivationAction
{
    /**
     * $request.
     *
     * @var object
     */
    protected $request;

    /**
     * $responder.
     *
     * @var object
     */
    protected $responder;

    /**
     * $request.
     *
     * @var object
     */
    protected $userService;

    /**
     * $responder.
     *
     * @var object
     */
    protected $userActivationService;

    /**
     * __construct.
     *
     * @param Request               $request
     * @param Responder             $responder
     * @param UserService           $userService
     * @param UserActivationService $userActivationService
     */
    public function __construct(
        Request $request, 
        Responder $responder,
        UserService $userService,
        UserActivationService $userActivationService
        )
    {
        $this->request = $request;
        $this->responder = $responder;
        $this->userService = $userService;
        $this->userActivationService = $userActivationService;
    }

    /**
     * __invoke.
     *
     * @param array $params
     *
     * @return string
     */
    public function __invoke(array $params = [])
    {

        $userActivation = $this->userActivationService->fetchUserActivationByToken($params['token']);


        $user = null;

        // userActivation token data found
        if ($userActivation instanceof \FOA\DomainPayload\Found) {

            // Assign userActivation
            $userActivation = $userActivation->get('userActivation');

            // Activate user
            $user = $this->userService->updateUser(array(
                'id' => $userActivation->userId,
                'active' => 1
                )
            );
        }

        // If succesfully updated delete token and redirect to login
        if ($user instanceof \FOA\DomainPayload\Updated) {
            $this->userActivationService->deleteUserActivationByToken($userActivation->token);

            header('Location: /');
            exit();
        }

        return $this->responder
            ->setView('failed')
            ->setFormat($params['format'])
            ->__invoke();
    }
}
