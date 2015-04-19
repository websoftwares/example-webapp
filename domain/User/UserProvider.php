<?php

namespace Websoftwares\Domain\User;

use League\Container\ServiceProvider;

/**
 * class UserProvider.
 *
 * @license http://opensource.org/licenses/MIT
 * @author Boris <boris@websoftwar.es>
 */
class UserProvider extends ServiceProvider
{
    /**
     * $provides.
     *
     * @var array
     */
    protected $provides = [
        'Websoftwares\Domain\User\UserService',
        'Websoftwares\Domain\User\UserFilter',
        'Websoftwares\Domain\User\UserFactory',
        'Websoftwares\Domain\User\UserGateway',
    ];

    public function register()
    {
        $container = $this->getContainer();

        $container->add('Websoftwares\Domain\User\UserGateway',
            function () {
                $pdo = new \PDO(
                    'mysql:dbname='.$_ENV['DB_NAME'].';host='.$_ENV['DB_HOST'],
                    $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'],
                    [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
                    );

                return new \Websoftwares\Domain\User\UserGateway($pdo);
            });

        $container->add('Monolog\Logger')
            ->withArgument('user')
            ->withMethodCall('pushHandler', [
                new \Monolog\Handler\StreamHandler(__DIR__.'./logs/domain.log', \Monolog\Logger::WARNING), ]);

        $container->add('Websoftwares\Domain\User\UserFactory');
        $container->add('Websoftwares\Domain\User\UserFilter');
        $container->add('Websoftwares\Domain\User\UserService')
            ->withArgument('Websoftwares\Domain\User\UserGateway')
            ->withArgument('Websoftwares\Domain\User\UserFilter')
            ->withArgument('Websoftwares\Domain\User\UserFactory')
            ->withArgument('FOA\DomainPayload\PayloadFactory')
            ->withArgument('Monolog\Logger');
    }
}
