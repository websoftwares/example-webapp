<?php

namespace Websoftwares\Domain\Throttle;

use Websoftwares\Domain\Throttle\ThrottleFactory;
use FOA\DomainPayload\PayloadFactory;
use Psr\Log\LoggerInterface;

/**
 * ThrottleService.
 *
 * @license http://opensource.org/licenses/MIT
 * @author Boris <boris@websoftwar.es>
 */
class ThrottleService
{
    /**
     * $throttleFactory.
     *
     * @var object
     */
    protected $throttleFactory;

    /**
     * $payloadFactory.
     *
     * @var object
     */
    protected $payloadFactory;

    /**
     * $logger.
     *
     * @var object
     */
    protected $logger;

    /**
     * __construct.
     *
     * @param ThrottleFactory       $throttle
     * @param PayloadFactory        $payloadFactory
     * @param LoggerInterface       $logger
     */
    public function __construct(
        ThrottleFactory $throttleFactory,
        PayloadFactory $payloadFactory,
        LoggerInterface $logger
        ) {
        $this->throttleFactory = $throttleFactory;
        $this->payloadFactory = $payloadFactory;
        $this->logger = $logger;
    }
}