<?php
namespace Websoftwares\Skeleton;

/**
 * AbstractResponder class.
 * 
 * @package Websoftwares
 * @subpackage Skeleton
 * @author Boris <boris@websoftwar.es>
 */
abstract class AbstractResponder
{
    /**
     * $variables.
     *
     * @var array
     */
    protected $variables = array();

    /**
     * Getter for variables.
     *
     * @return mixed
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * Setter for variables.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return self
     */
    public function setVariable($key, $value)
    {
        $this->variables[$key] = $value;

        return $this;
    }

    /**
     * getView.
     *
     * @return mixed
     */
    abstract public function getView();

    /**
     * render a view.
     *
     * @return string
     */
    public function render()
    {
        extract($this->getVariables());

        ob_start();
        include $this->getView();
        $renderedView = ob_get_clean();

        return $renderedView;
    }
}
