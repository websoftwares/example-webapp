<?php

namespace Websoftwares\Application\Index\Action;

use Websoftwares\Application\Index\Responder\BrowseResponder as Responder;
use Websoftwares\Domain\User\UserService;
use Symfony\Component\HttpFoundation\Request;
use Kunststube\CSRFP\SignatureGenerator;

/**
 * BrowseGetAction class.
 *
 * @author Boris <boris@websoftwar.es>
 */
class BrowseGetAction
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
     * $signer.
     *
     * @var object
     */
    protected $signer;

    /**
     * __construct.
     *
     * @param Request               $request
     * @param Responder             $responder
     * @param UserService           $userService
     * @param SignatureGenerator    $signer
     */
    public function __construct(
        Request $request,
        Responder $responder,
        UserService $userService,
        SignatureGenerator $signer
        ) {
        $this->request = $request;
        $this->responder = $responder;
        $this->userService = $userService;
        $this->signer = $signer;
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
        // See if user is logged in
        if (! isset($_SESSION['user'])) {
            $this->responder->setView('form');
        } else {
            $this->responder
                ->setView('browse')
                ->setVariable('data', [
                    'title' => 'Example web application ', 
                    'body' => 'Hello ' . $_SESSION['user']['name']
                    ]);
        }
        return $this->responder
            ->setVariable('signature', $this->signer->getSignature())
            ->setFormat($params['format'])
            ->__invoke();
    }
}
