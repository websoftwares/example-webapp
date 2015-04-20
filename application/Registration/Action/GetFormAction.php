<?php

namespace Websoftwares\Application\Registration\Action;

use Websoftwares\Application\Registration\Responder\FormResponder as Responder;
use Symfony\Component\HttpFoundation\Request;
use Kunststube\CSRFP\SignatureGenerator;

/**
 * GetFormAction class.
 *
 * @author Boris <boris@websoftwar.es>
 */
class GetFormAction
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
     * $signer
     * 
     * @var object
     */
    protected $signer;

    /**
     * __construct.
     *
     * @param Request request
     * @param Responder $responder
     * @param SignatureGenerator $signer
     */
    public function __construct(
        Request $request, 
        Responder $responder,
        SignatureGenerator $signer
        )
    {
        $this->request = $request;
        $this->responder = $responder;
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
        return $this->responder
            ->setView('form')
            ->setVariable('signature', $this->signer->getSignature())
            ->setFormat($params['format'])
            ->__invoke();
    }
}
