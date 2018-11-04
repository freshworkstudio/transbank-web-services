<?php
/**
 * Clase TransbankService
 *
 * @package Freshwork\Transbank
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1.6 (15/07/2016)
 */

namespace Freshwork\Transbank;

use Freshwork\Transbank\Log\LoggerInterface;

/**
 * Clase TransbankService
 *
 * @package Freshwork\Transbank
 */
abstract class TransbankService
{
    /**
     * Permite establecer una instancia personalizada de registro de eventos
     *
     * @param LoggerInterface|null $logger
     */
    public function debug(LoggerInterface $logger = null)
    {
        $this->service->setLogger($logger);
    }
}
