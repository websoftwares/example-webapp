<?php

namespace Websoftwares\Application\Registration\Action;

use Websoftwares\Application\Registration\Responder\FormResponder as Responder;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Kunststube\CSRFP\SignatureGenerator;

/**
 * GetFormAction class.
 *
 * @author Boris <boris@websoftwar.es>
 */
class GetFormAction
{
    /**
     * $responder.
     *
     * @var object
     */
    protected $responder;

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
     * @param SignatureGenerator $signer
     */
    public function __construct(
        Responder $responder,
        SignatureGenerator $signer
        ) {
        $this->responder = $responder;
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
        return $this->responder
            ->setView('form')
            ->setVariable('signature', $this->signer->getSignature())
            ->setFormat($request->getAttribute('format'))
            ->__invoke($response);
    }
}
