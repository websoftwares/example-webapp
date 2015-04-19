<?php
namespace Websoftwares\Application\Index\Action;

use Websoftwares\Application\Index\Responder\BrowseResponder as Responder;

/**
 * BrowseResponder class.
 *
 * @author Boris <boris@websoftwar.es>
 */
class BrowseAction
{
    /**
     * __construct.
     *
     * @param Responder $responder
     */
    public function __construct(Responder $responder)
    {
        $this->responder = $responder;
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
        return $this->responder
            ->setVariable("data", ["title" => "Hello World", "body" => "Hello World"])
            ->setFormat($params["format"])
            ->__invoke();
    }
}
