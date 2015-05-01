<?php

namespace Websoftwares\Application\Index\Responder;

use Websoftwares\Skeleton\AbstractResponder;
use Psr\Http\Message\ResponseInterface as Response;

class BrowseResponder extends AbstractResponder
{
    /**
     * $view.
     *
     * @var string
     */
    protected $view;

    /**
     * $format.
     *
     * @var string
     */
    protected $format;
    /**
     * setFormat.
     *
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * getFormat.
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * setView.
     *
     * @param string $name
     */
    public function setView($view)
    {
        $this->view = $view;

        return $this;
    }

    /**
     * getView.
     *
     * @return string
     */
    public function getView()
    {
        $file = __DIR__.'/../views/'.$this->view.$this->getFormat().'.php';
        if (! file_exists($file)) {
            throw new \OutOfRangeException('the file: '.$file.' could not be retrieved');
        }

        return  $file;
    }

    /**
     * __invoke.
     *
     * @param Response $response
     *
     * @return Response $response
     */
    public function __invoke(Response $response)
    {
        $response = $response->withStatus(200);
        $response->getBody()->write($this->render());

        return $response;
    }
}
