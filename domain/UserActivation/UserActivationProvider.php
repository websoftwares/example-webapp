<?php

namespace Websoftwares\Domain\UserActivation;

use League\Container\ServiceProvider;

/**
 * UserActivationProvider.
 *
 * @license http://opensource.org/licenses/MIT
 * @author Boris <boris@websoftwar.es>
 */
class UserActivationProvider extends ServiceProvider
{
    /**
     * $provides.
     *
     * @var array
     */
    protected $provides = [
        'Websoftwares\Domain\UserActivation\UserActivationService',
        'Websoftwares\Domain\UserActivation\UserActivationFilter',
        'Websoftwares\Domain\UserActivation\UserActivationFactory',
        'Websoftwares\Domain\UserActivation\UserActivationGateway',
    ];

    public function register()
    {
        $container = $this->getContainer();

        $container->add('Websoftwares\Domain\UserActivation\UserActivationGateway',
            function () {
                $pdo = new \PDO(
                    'mysql:dbname='.$_ENV['DB_NAME'].';host='.$_ENV['DB_HOST'],
                    $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'],
                    [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
                    );

                return new \Websoftwares\Domain\UserActivation\UserActivationGateway($pdo);
            });

        $container->add('Monolog\Logger')
            ->withArgument('userActivation')
            ->withMethodCall('pushHandler', [
                new \Monolog\Handler\StreamHandler(__DIR__.'./logs/domain.log', \Monolog\Logger::WARNING), ]
        );

        $container->add('Websoftwares\Domain\UserActivation\UserActivationFactory');
        $container->add('Websoftwares\Domain\UserActivation\UserActivationFilter');

        $container->add('Websoftwares\Domain\UserActivation\UserActivationService')
            ->withArgument('Websoftwares\Domain\UserActivation\UserActivationGateway')
            ->withArgument('Websoftwares\Domain\UserActivation\UserActivationFactory')
            ->withArgument('Websoftwares\Domain\UserActivation\UserActivationFilter')
            ->withArgument('Websoftwares\Domain\RandomString')
            ->withArgument('FOA\DomainPayload\PayloadFactory')
            ->withArgument('Monolog\Logger');
    }
}
