<?php

namespace Websoftwares\Application\Index\Action;

use Websoftwares\Application\Index\Responder\BrowseResponder as Responder;
use Symfony\Component\HttpFoundation\Request;
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
     * @param Request            $request
     * @param Responder          $responder
     * @param UserService        $throttleService
     * @param ThrottleService    $userActivationService
     * @param SignatureGenerator $signer
     * @param CaptchaBuilder     $captchaBuilder
     */
    public function __construct(
        Request $request,
        Responder $responder,
        UserService $userService,
        ThrottleService $throttleService,
        SignatureGenerator $signer,
        CaptchaBuilder $captchaBuilder
        ) {
        $this->request = $request;
        $this->responder = $responder;
        $this->userService = $userService;
        $this->throttleService = $throttleService;
        $this->signer = $signer;

        $this->captchaBuilder = $captchaBuilder;
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

        // Start of weith false
        $login = false;

        // Get user input and ip
        $user = $this->request->get('user');
        $ip = $this->request->getClientIp();

        // Set identifiers
        $identifiers['ip'] = $this->request->getClientIp();

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
                && $this->request->get('captcha')['phrase'] == $_SESSION['phrase']) {
                $login = true;
                echo $login;
            }

            $_SESSION['phrase'] = $this->captchaBuilder->getPhrase();
        }

        // Login user
        if ($login) {
            $_SESSION['user'] = (array) $userData->get('user');
            header('Location: /');
            exit();
        }

        return $this->responder
            ->setView('form')
            ->setVariable('signature', $this->signer->getSignature())
            ->setFormat($params['format'])
            ->__invoke();
    }
}
