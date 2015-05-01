<?php

namespace Websoftwares\Application\Activation\Action;

use Websoftwares\Application\Activation\Responder\ActivationResponder as Responder;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
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
     * $responder.
     *
     * @var object
     */
    protected $responder;

    /**
     * $userService.
     *
     * @var object
     */
    protected $userService;

    /**
     * $userActivationService.
     *
     * @var object
     */
    protected $userActivationService;

    /**
     * __construct.
     *
     * @param Responder             $responder
     * @param UserService           $userService
     * @param UserActivationService $userActivationService
     */
    public function __construct(
        Responder $responder,
        UserService $userService,
        UserActivationService $userActivationService
        ) {
        $this->responder = $responder;
        $this->userService = $userService;
        $this->userActivationService = $userActivationService;
    }

    /**
     * __invoke.
     *
     * @param  Request
     * @param  Response
     *
     * @return Response
     */
    public function __invoke(Request $request, Response $response)
    {
        $userActivation = $this->userActivationService->fetchUserActivationByToken($request->getAttribute('token'));

        $user = null;

        // userActivation token data found
        if ($userActivation instanceof \FOA\DomainPayload\Found) {

            // Assign userActivation
            $userActivation = $userActivation->get('userActivation');

            // Activate user
            $user = $this->userService->updateUser(array(
                'id' => $userActivation->userId,
                'active' => 1,
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
            ->setFormat($request->getAttribute('format'))
            ->__invoke($response);
    }
}
