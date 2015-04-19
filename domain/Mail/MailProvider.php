<?php

namespace Websoftwares\Domain\Mail;

use League\Container\ServiceProvider;

/**
 * class MailProvider.
 *
 * @license http://opensource.org/licenses/MIT
 * @author Boris <boris@websoftwar.es>
 */
class MailProvider extends ServiceProvider
{
    /**
     * $provides.
     *
     * @var array
     */
    protected $provides = [
        'Websoftwares\Domain\Mail\MailService',
        'Websoftwares\Domain\Mail\MailFactory',
        'Websoftwares\Domain\Mail\MailStrategyFactory',
        'Websoftwares\Domain\Mail\MailPayloadFactory',
    ];

    public function register()
    {
        $container = $this->getContainer();

        $container->add('Monolog\Logger')
            ->withArgument('mail')
            ->withMethodCall('pushHandler', [
                    new \Monolog\Handler\StreamHandler(__DIR__.'./logs/domain.log', \Monolog\Logger::WARNING),
                ]
            );

        $container->add('Websoftwares\Domain\Mail\MailFactory');
        $container->add('Websoftwares\Domain\Mail\MailStrategyFactory');
        $container->add('Websoftwares\Domain\Mail\MailPayloadFactory');

        $container->add('Websoftwares\Domain\Mail\MailService')
            ->withArgument('Websoftwares\Domain\Mail\MailStrategyFactory')
            ->withArgument('Websoftwares\Domain\Mail\MailFactory')
            ->withArgument('Websoftwares\Domain\Mail\MailPayloadFactory')
            ->withArgument('Monolog\Logger');
    }
}
