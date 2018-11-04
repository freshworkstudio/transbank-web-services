<?php
/**
 * Clase LoggerInterface
 *
 * @package Freshwork\Transbank
 * @subpackage Log
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1.6 (15/07/2016)
 */

namespace Freshwork\Transbank\Log;

/**
 * Interfaz LoggerInterface
 *
 * @package Freshwork\Transbank\Log
 */
interface LoggerInterface
{
    /** @const LEVEL_ERROR Identifica un error  */
    const LEVEL_ERROR = 'error';

    /** @const LEVEL_WARNING Identifica una advertencia  */
    const LEVEL_WARNING = 'warning';

    /** @const LEVEL_INFO Identifica una información  */
    const LEVEL_INFO = 'info';

    /**
     * Registra un evento
     *
     * @param mixed $data Información a registrar
     * @param string $level Tipo de evento
     * @param mixed $type Identificador adicional
     * @return mixed
     */
    public function log($data, $level = self::LEVEL_INFO, $type = null);
}
