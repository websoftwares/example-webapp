<?php

namespace Websoftwares\Application\Activation\Responder;

use Symfony\Component\HttpFoundation\Response;
use Websoftwares\Skeleton\AbstractResponder;

/**
 * ActivationResponder class.
 *
 * @author Boris <boris@websoftwar.es>
 */
class ActivationResponder extends AbstractResponder
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
     * __construct.
     *
     * @param Response $response
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

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
     * @return string
     */
    public function __invoke()
    {
        $this->response->setContent($this->render());
        $this->response->headers->set('Content-Type', 'text/html');
        $this->response->send();
    }
}
