<?php
/**
 * Class TransbankService
 *
 * @package Freshwork\Transbank
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1.6 (15/07/2016)
 */

namespace Freshwork\Transbank;

use Freshwork\Transbank\Log\LoggerInterface;

/**
 * Class TransbankService
 *
 * @package Freshwork\Transbank
 */
abstract class TransbankService
{
    /**
     * Set a custom logger instance
     *
     * @param LoggerInterface|null $logger
     */
    public function debug(LoggerInterface $logger = null)
    {
        $this->service->setLogger($logger);
    }
}
