<?php
/**
 * Clase LogHandler
 *
 * @package Freshwork\Transbank
 * @subpackage Log
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1.6 (15/07/2016)
 */

namespace Freshwork\Transbank\Log;

/**
 * Clase LogHandler
 *
 * @package Freshwork\Transbank\Log
 */
class LogHandler
{
    /**
     * Registra un evento
     *
     * @param mixed $data Información a registrar
     * @param string $level Tipo de evento
     * @param mixed $type Identificador adicional
     */
    public static function log($data, $level = LoggerInterface::LEVEL_INFO, $type = null)
    {
        LoggerFactory::logger()->log($data, $level, $type);
    }
}
