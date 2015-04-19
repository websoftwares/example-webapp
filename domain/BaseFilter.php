<?php

namespace Websoftwares\Domain;

/**
 * BaseFilter.
 *
 * @license http://opensource.org/licenses/MIT
 * @author Boris <boris@websoftwar.es>
 */
class BaseFilter
{
    /**
     * $messages.
     *
     * @var array
     */
    protected $messages = array();

    /**
     * getMessages.
     *
     * @return array
     * @return bool
     */
    protected function isValid()
    {
        if ($this->messages) {
            return false;
        }

        return true;
    }

    /**
     * getMessages.
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
