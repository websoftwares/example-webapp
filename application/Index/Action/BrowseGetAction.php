<?php

namespace Websoftwares\Application\Index\Action;

use Websoftwares\Application\Index\Responder\BrowseResponder as Responder;
use Websoftwares\Domain\User\UserService;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Kunststube\CSRFP\SignatureGenerator;

/**
 * BrowseGetAction class.
 *
 * @author Boris <boris@websoftwar.es>
 */
class BrowseGetAction
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
     * $signer.
     *
     * @var object
     */
    protected $signer;

    /**
     * __construct.
     *
     * @param Responder          $responder
     * @param UserService        $userService
     * @param SignatureGenerator $signer
     */
    public function __construct(
        Responder $responder,
        UserService $userService,
        SignatureGenerator $signer
        ) {
        $this->responder = $responder;
        $this->userService = $userService;
        $this->signer = $signer;
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
        // See if user is logged in
        if (! isset($_SESSION['user'])) {
            $this->responder->setView('form');
        } else {
            $this->responder
                ->setView('browse')
                ->setVariable('data', [
                    'title' => 'Example web application ',
                    'body' => 'Hello '.$_SESSION['user']['name'],
                    ]);
        }

        $responder = $this->responder
            ->setVariable('signature', $this->signer->getSignature())
            ->setFormat($request->getAttribute('format'));

        return $responder($response);
    }
}
