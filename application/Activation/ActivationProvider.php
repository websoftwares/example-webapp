<?php

namespace Websoftwares\Application\Activation;

use League\Container\ServiceProvider;

/**
 * class ActivationProvider.
 */
class ActivationProvider extends ServiceProvider
{
    /**
     * $provides.
     *
     * @var array
     */
    protected $provides = [
        'activation.get'
    ];

    public function register()
    {
        $container = $this->getContainer();

        $container->addServiceProvider('Websoftwares\Domain\User\UserProvider');
        $container->addServiceProvider('Websoftwares\Domain\UserActivation\UserActivationProvider');

        $container->add('Websoftwares\Application\Activation\Responder\ActivationResponder')
            ->withArgument('response');

        $container->add('activation.get', 'Websoftwares\Application\Activation\Action\GetActivationAction')
            ->withArgument('request')
            ->withArgument('Websoftwares\Application\Activation\Responder\ActivationResponder')
            ->withArgument('Websoftwares\Domain\User\UserService')
            ->withArgument('Websoftwares\Domain\UserActivation\UserActivationService')
            ;
    }
}
