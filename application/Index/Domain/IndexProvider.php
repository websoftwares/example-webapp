<?php
namespace Websoftwares\Application\Index\Domain;

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
        "index.browse",
    ];

    public function register()
    {
        $this->getContainer()->add('Websoftwares\Application\Index\Responder\BrowseResponder')
            ->withArgument('response');
        $this->getContainer()->add('index.browse', 'Websoftwares\Application\Index\Action\BrowseAction')
            ->withArgument('Websoftwares\Application\Index\Responder\BrowseResponder');
    }
}
