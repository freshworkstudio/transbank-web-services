<?php
/**
 * Clase LoggerFactory
 *
 * @package Freshwork\Transbank
 * @subpackage Log
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1.6 (15/07/2016)
 */

namespace Freshwork\Transbank\Log;

/**
 * Clase LoggerFactory
 *
 * @package Freshwork\Transbank\Log
 */
class LoggerFactory
{
    /** @var LoggerInterface $instance Instancia del registrador */
    private static $instance;

    /**
     * Establece la instancia del registrador a utilizar
     *
     * @param LoggerInterface $instance Instancia del registrador
     */
    public static function setLogger(LoggerInterface $instance)
    {
        self::$instance = $instance;
    }

    /**
     * Obtiene la instancia del registrador
     *
     * @return LoggerInterface
     */
    public static function logger()
    {
        if (!self::$instance) {
            self::initDefaultLogger();
        }

        return self::$instance;
    }

    /**
     * Genera un registrador vacío
     *
     * @return VoidLogger
     */
    public static function initDefaultLogger()
    {
        return self::$instance = new VoidLogger();
    }
}
