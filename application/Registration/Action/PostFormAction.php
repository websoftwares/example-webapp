<?php

namespace Websoftwares\Application\Registration\Action;

use Websoftwares\Application\Registration\Responder\FormResponder as Responder;
use Symfony\Component\HttpFoundation\Request;
use Websoftwares\Domain\User\UserService;
use Websoftwares\Domain\UserActivation\UserActivationService;
use Websoftwares\Domain\Mail\MailService;
use Kunststube\CSRFP\SignatureGenerator;

/**
 * PostFormAction class.
 *
 * @author Boris <boris@websoftwar.es>
 */
class PostFormAction
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
     * $mailService.
     *
     * @var object
     */
    protected $mailService;

    /**
     * $signer
     * 
     * @var object
     */
    protected $signer;


    /**
     * __construct
     * 
     * @param Request               $request
     * @param Responder             $responder
     * @param UserService           $userService
     * @param UserActivationService $userActivationService
     * @param MailService           $mailService
     * @param SignatureGenerator    $signer
     */
    public function __construct(
        Request $request,
        Responder $responder,
        UserService $userService,
        UserActivationService $userActivationService,
        MailService $mailService,
        SignatureGenerator $signer
        ) {
        $this->request = $request;
        $this->responder = $responder;
        $this->userService = $userService;
        $this->userActivationService = $userActivationService;
        $this->mailService = $mailService;
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
        // Invalid request made
        if (!$this->signer->validateSignature($this->request->get('_token'))) {
            throw new \Exception('Invalid request');
        }

        // Get form data
        $data = $this->request->get('user');

        // Try to create user
        $user = $this->userService->createUser($data);

        // User not created
        if ($user instanceof \FOA\DomainPayload\NotValid) {
            $this->responder->setVariable('data', $user->get('data'));
            $this->responder->setVariable('messages', $user->get('messages'));
            $this->responder->setView('form');
        }

        $activation = null;

        // User created
        if ($user instanceof \FOA\DomainPayload\Created) {
            // Assign to user now with id
            $user = $user->get('user');
            // Create activation
            $activation = $this
                ->userActivationService
                ->saveUserActivationToken($user->id);
        }

        // Activation created lets send email
        if ($activation && $activation instanceof \FOA\DomainPayload\Created) {

            // For now static subject ,from(move to config, env later) and ugly mail body
            $subject = 'Welcome to example web application';
            $to = array($user->email => $user->name);
            $from = array('boris@websoftwar.es' => 'Example web application');
            $body = 'Hello '.$user->name.PHP_EOL;
            $body .= 'Please activate your account by using this link:'.PHP_EOL;
            $body .= 'http://'.$_ENV['BASE_URL'].'/activation/'.$activation->get('userActivation')->token;

            // Send email
            $mailed = $this->mailService->sendEmail($subject, $from, $to, $body);

            // Mail succesfully delivered
            if ($mailed->get('delivered') === 1) {
                $this->responder->setView('success');
            }
        }

        return $this->responder
            ->setVariable('signature', $this->signer->getSignature())
            ->setFormat($params['format'])
            ->__invoke();
    }
}
