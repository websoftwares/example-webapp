<?php

namespace Websoftwares\Application\Registration;

use League\Container\ServiceProvider;

/**
 * class RegistrationProvider.
 */
class RegistrationProvider extends ServiceProvider
{
    /**
     * $provides.
     *
     * @var array
     */
    protected $provides = [
        'registration.get.form',
        'registration.post.form',
    ];

    public function register()
    {
        $container = $this->getContainer();

        $container->addServiceProvider('Websoftwares\Domain\User\UserProvider');
        $container->addServiceProvider('Websoftwares\Domain\UserActivation\UserActivationProvider');
        $container->addServiceProvider('Websoftwares\Domain\Mail\MailProvider');

        $container->add('Kunststube\CSRFP\SignatureGenerator', function() {
            return new \Kunststube\CSRFP\SignatureGenerator($_ENV['APP_SECRET']);
        });

        $container->add('Websoftwares\Application\Registration\Responder\FormResponder')
            ->withArgument('response');

        $container->add('registration.get.form', 'Websoftwares\Application\Registration\Action\GetFormAction')
            ->withArgument('request')
            ->withArgument('Websoftwares\Application\Registration\Responder\FormResponder')
            ->withArgument('Kunststube\CSRFP\SignatureGenerator')
            ;

        $container->add('Websoftwares\Application\Registration\Responder\FormResponder')
            ->withArgument('response');

        $container->add('registration.post.form', 'Websoftwares\Application\Registration\Action\PostFormAction')
            ->withArgument('request')
            ->withArgument('Websoftwares\Application\Registration\Responder\FormResponder')
            ->withArgument('Websoftwares\Domain\User\UserService')
            ->withArgument('Websoftwares\Domain\UserActivation\UserActivationService')
            ->withArgument('Websoftwares\Domain\Mail\MailService')
            ->withArgument('Kunststube\CSRFP\SignatureGenerator')
            ;
    }
}
