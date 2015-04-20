<?php

namespace Websoftwares\Domain\Throttle;

use Psr\Log\LoggerInterface;
use Websoftwares\Throttle;
use Websoftwares\Storage\Memcached;

/**
 * ThrottleFactory.
 *
 * @license http://opensource.org/licenses/MIT
 * @author Boris <boris@websoftwar.es>
 */
class ThrottleFactory
{
    protected $ttl = 3600;

    /**
     * cRange.
     *
     * @param LoggerInterface $logger
     *
     * @return object Throttle
     */
    public function cRange(LoggerInterface $logger)
    {
        $options = array(
            'banned' => 500,
            'logged' => 501,
            'timespan' => $this->ttl,
            );

        return new Throttle($logger, new Memcached(), $options);
    }

    /**
     * bRange.
     *
     * @param LoggerInterface $logger
     *
     * @return object Throttle
     */
    public function bRange(LoggerInterface $logger)
    {
        $options = array(
            'banned' => 1000,
            'logged' => 1001,
            'timespan' => $this->ttl,
            );

        return new Throttle($logger, new Memcached(), $options);
    }

    /**
     * ipAddress.
     *
     * @param LoggerInterface $logger
     *
     * @return object Throttle
     */
    public function ipAddress(LoggerInterface $logger)
    {
        $options = array(
            'banned' => 3,
            'logged' => 4,
            'timespan' => $this->ttl,
            );

        return new Throttle($logger, new Memcached(), $options);
    }

    /**
     * userEmail.
     *
     * @param LoggerInterface $logger
     *
     * @return object Throttle
     */
    public function userEmail(LoggerInterface $logger)
    {
        $options = array(
            'banned' => 3,
            'logged' => 4,
            'timespan' => $this->ttl,
            );

        return new Throttle($logger, new Memcached(), $options);
    }
}
