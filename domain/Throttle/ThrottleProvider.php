<?php

namespace Websoftwares\Domain\Throttle;

use League\Container\ServiceProvider;

/**
 * class ThrottleProvider.
 *
 * @license http://opensource.org/licenses/MIT
 * @author Boris <boris@websoftwar.es>
 */
class ThrottleProvider extends ServiceProvider
{
    /**
     * $provides.
     *
     * @var array
     */
    protected $provides = [
        'Websoftwares\Domain\Throttle\ThrottleFactory',
        'Websoftwares\Domain\Throttle\ThrottleService',
    ];

    public function register()
    {
        $container = $this->getContainer();

        $container->add('Monolog\Logger')
            ->withArgument('throttle')
            ->withMethodCall('pushHandler', [
                    new \Monolog\Handler\StreamHandler(__DIR__.'/../../logs/domain.log', \Monolog\Logger::WARNING),
                ]
            );
        $container->add('Websoftwares\Domain\Throttle\ThrottleFactory');

        $container->add('Websoftwares\Domain\Throttle\ThrottleService')
            ->withArgument('Websoftwares\Domain\Throttle\ThrottleFactory')
            ->withArgument('FOA\DomainPayload\PayloadFactory')
            ->withArgument('Monolog\Logger');
    }
}
