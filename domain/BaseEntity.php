<?php

namespace Websoftwares\Domain;

/**
 * BaseEntity.
 *
 * @license http://opensource.org/licenses/MIT
 * @author Boris <boris@websoftwar.es>
 */
class BaseEntity implements \IteratorAggregate
{
    /**
     * __construct.
     *
     * @param mixed $data
     */
    public function __construct($data = null)
    {
        if ($data) {
            $this->init($data);
        }
    }

    /**
     * init.
     *
     * @param mixed $data object|arrays
     *
     * @return object
     */
    public function init($data = null)
    {
        if (!$data) {
            throw new \InvalidArgumentException('data is a required argument');
        }

        if (is_object($data)) {
            $data = get_object_vars($data);
        }

        foreach ($data as $key => $value) {
            $method = 'set'.ucfirst($key);
            // Find method else set property
            if (method_exists($this, $method)) {
                $this->$method($value);
            } elseif (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }

        return $this;
    }

    /**
     * getIterator.
     *
     * @see http://php.net/manual/en/class.iteratoraggregate.php
     *
     * @return object ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this);
    }
}
