<?php
/**
 * Clase VoidLogger
 *
 * @package Freshwork\Transbank
 * @subpackage Log
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1.6 (15/07/2016)
 */

namespace Freshwork\Transbank\Log;

/**
 * Clase VoidLogger
 *
 * @package Freshwork\Transbank\Log
 */
class VoidLogger implements LoggerInterface
{
    /**
     * No registra eventos
     *
     * @param mixed $data Información a registrar
     * @param string $level Tipo de evento
     * @param mixed $type Identificador adicional
     * @return void
     */
    public function log($data, $level = self::INFO, $type = null)
    {
    }
}
