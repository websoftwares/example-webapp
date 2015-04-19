<?php
namespace Websoftwares\Application\Index\Responder;

use Symfony\Component\HttpFoundation\Response;
use Websoftwares\Skeleton\AbstractResponder;

/**
 * BrowseResponder class.
 *
 * @author Boris <boris@websoftwar.es>
 */
class BrowseResponder extends AbstractResponder
{
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
     * getView.
     *
     * @return string
     */
    public function getView()
    {
        $file = __DIR__.'/../views/browse'.$this->getFormat().'.php';
        if (! file_exists($file)) {
            throw new \OutOfRangeException("the file: ".$file." could not be retrieved");
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
