<?php

namespace Websoftwares\Application\Index\Action;

use Websoftwares\Application\Index\Responder\BrowseResponder as Responder;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Websoftwares\Domain\User\UserService;
use Websoftwares\Domain\Throttle\ThrottleService;
use Kunststube\CSRFP\SignatureGenerator;
use Gregwar\Captcha\CaptchaBuilder;

/**
 * BrowsePostAction class.
 *
 * @author Boris <boris@websoftwar.es>
 */
class BrowsePostAction
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
     * $throttleService.
     *
     * @var object
     */
    protected $throttleService;

    /**
     * $signer.
     *
     * @var object
     */
    protected $signer;

    /**
     * $captchaBuilder.
     *
     * @var object
     */
    protected $captchaBuilder;

    /**
     * __construct.
     *
     * @param Responder          $responder
     * @param UserService        $throttleService
     * @param ThrottleService    $userActivationService
     * @param SignatureGenerator $signer
     * @param CaptchaBuilder     $captchaBuilder
     */
    public function __construct(
        Responder $responder,
        UserService $userService,
        ThrottleService $throttleService,
        SignatureGenerator $signer,
        CaptchaBuilder $captchaBuilder
        ) {
        $this->responder = $responder;
        $this->userService = $userService;
        $this->throttleService = $throttleService;
        $this->signer = $signer;

        $this->captchaBuilder = $captchaBuilder;
    }

    /**
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
        $body = $request->getParsedBody();

        // Invalid request made
        if (!$this->signer->validateSignature($body['_token'])) {
            throw new \Exception('Invalid request');
        }

        // Start of weith false
        $login = false;

        // Get user input and ip
        $user = $body['user'];
        $ip = $request->getServerParams()['REMOTE_ADDR'];

        // Set identifiers
        $identifiers['ip'] = $ip;

        // If we have email set indentifier and get user data
        if ($email = $user['email']) {
            $identifiers['email'] = $email;
            $userData = $this->userService->fetchUserByEmail($email);

            // User Found
            if ($userData instanceof \FOA\DomainPayload\Found) {

                // Verify password and see if account is active
                if (\password_verify($user['password'], $userData->get('user')->password) &&
                    $userData->get('user')->active == 1) {
                    $login = true;
                }
            }
        }

        $showCaptcha = $this->throttleService->validate($identifiers);

        //Build captcha
        if ($showCaptcha instanceof \FOA\DomainPayload\NotValid) {
            $this->captchaBuilder->build();
            $this->responder->setVariable('captcha',  $this->captchaBuilder);

            if (isset($_SESSION['phrase'])
                && isset($body['captcha'])
                && $body['captcha']['phrase'] == $_SESSION['phrase']) {
                $login = true;
            }

            $_SESSION['phrase'] = $this->captchaBuilder->getPhrase();
        }

        // Login user
        if ($login) {
            $_SESSION['user'] = (array) $userData->get('user');
            header('Location: /');
            exit();
        }

        $responder = $this->responder
            ->setView('form')
            ->setVariable('signature', $this->signer->getSignature())
            ->setFormat($request->getAttribute('format'));

        return $responder($response);
    }
}
