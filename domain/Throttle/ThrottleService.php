<?php

namespace Websoftwares\Domain\Throttle;

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
     * @param ThrottleFactory $throttle
     * @param PayloadFactory  $payloadFactory
     * @param LoggerInterface $logger
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

    /**
     * validate.
     *
     * @param array $identifiers
     *
     * @todo  Idea could be moved to a filter object
     *
     * @return bool
     */
    public function validate(array $identifiers)
    {
        try {
            $payLoad = [];

            // Validate ipadress based identifiers
            if (isset($identifiers['ip'])) {
                $ipArray = explode('.', $identifiers['ip']);

                $identifiers['bRange'] = $ipArray[0].'.0.0.0';
                $identifiers['cRange'] = $ipArray[0].'.'.$ipArray[1].'.0.0';

                $ipAddress = $this->throttleFactory->ipAddress($this->logger)->validate($identifiers['ip']);
            }

            $userEmail = true;
            // Validate email identifier
            if (isset($identifiers['email'])) {
                $userEmail = $this->throttleFactory->userEmail($this->logger)->validate($identifiers['email']);
                $payLoad['email'] = $this->throttleFactory->userEmail($this->logger)->remaining($identifiers['email']);
            }

            // Assign defaults
            $bRange = true;
            $cRange = true;

            // If not failed update counters for B, C range identifiers
            if ($ipAddress && $userEmail) {
                $bRange = $this->throttleFactory->bRange($this->logger)->validate($identifiers['bRange']);
                $cRange = $this->throttleFactory->cRange($this->logger)->validate($identifiers['cRange']);
            }

            // B range failed
            if (! $bRange) {
                // Return
                return $this->payloadFactory->notValid(array(
                    'bRange' => $identifiers['bRange'],
                ));
            }

            // C range failed
            if (! $cRange) {
                // Return
                return $this->payloadFactory->notValid(array(
                    'cRange' => $identifiers['cRange'],
                ));
            }

            // Ip adress failed
            if (! $ipAddress) {
                // Return
                return $this->payloadFactory->notValid(array(
                    'ip' => $identifiers['ip'],
                ));
            }

            // userEmail failed
            if (! $userEmail) {
                // Return
                return $this->payloadFactory->notValid(array(
                    'email' => $identifiers['email'],
                ));
            }

            // Success
            return $this->payloadFactory->valid(
                $payLoad  + array(
                    'bRange' =>  $this->throttleFactory->bRange($this->logger)->remaining($identifiers['bRange']),
                    'cRange' =>  $this->throttleFactory->cRange($this->logger)->remaining($identifiers['cRange']),
                    'ip' =>  $this->throttleFactory->ipAddress($this->logger)->remaining($identifiers['ip']),
                    )
                );
        } catch (\Exception $e) {

            // Log
            $this->logger->error($e->getMessage(), (array) $identifiers);

            // Return
            return $this->payloadFactory->error(array(
                'exception' => $e,
                'identifiers' => $identifiers,
            ));
        }
    }
}
