<?php

namespace Websoftwares\Application\Index;

use League\Container\ServiceProvider;

/**
 * class IndexProvider.
 */
class IndexProvider extends ServiceProvider
{
    /**
     * $provides.
     *
     * @var array
     */
    protected $provides = [
        'index.browse.get',
        'index.browse.post',
    ];

    public function register()
    {
        $container = $this->getContainer();

        $container->addServiceProvider('Websoftwares\Domain\User\UserProvider');
        $container->addServiceProvider('Websoftwares\Domain\Throttle\ThrottleProvider');

        $container->add('Kunststube\CSRFP\SignatureGenerator', function () {
            return new \Kunststube\CSRFP\SignatureGenerator($_ENV['APP_SECRET']);
        });

        $container->add('Websoftwares\Application\Index\Responder\BrowseResponder')
            ->withArgument('response');

        $container->add('index.browse.get', 'Websoftwares\Application\Index\Action\BrowseGetAction')
            ->withArgument('request')
            ->withArgument('Websoftwares\Application\Index\Responder\BrowseResponder')
            ->withArgument('Websoftwares\Domain\User\UserService')
            ->withArgument('Kunststube\CSRFP\SignatureGenerator')
            ;

        $container->add('Gregwar\Captcha\CaptchaBuilder', function() {
            return new \Gregwar\Captcha\CaptchaBuilder;
        });

        $container->add('index.browse.post', 'Websoftwares\Application\Index\Action\BrowsePostAction')
            ->withArgument('request')
            ->withArgument('Websoftwares\Application\Index\Responder\BrowseResponder')
            ->withArgument('Websoftwares\Domain\User\UserService')
            ->withArgument('Websoftwares\Domain\Throttle\ThrottleService')
            ->withArgument('Kunststube\CSRFP\SignatureGenerator')
            ->withArgument('Gregwar\Captcha\CaptchaBuilder')
            ;
    }
}