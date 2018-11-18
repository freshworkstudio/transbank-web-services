<?php
/**
 * Class LoggerFactory
 *
 * @package Freshwork\Transbank
 * @subpackage Log
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1.6 (15/07/2016)
 */

namespace Freshwork\Transbank\Log;

/**
 * Class LoggerFactory
 *
 * @package Freshwork\Transbank\Log
 */
class LoggerFactory
{
    /** @var LoggerInterface $instance Logger instance */
    private static $instance;

    /**
     * Set a logger instance
     *
     * @param LoggerInterface $instance Logger instance
     */
    public static function setLogger(LoggerInterface $instance)
    {
        self::$instance = $instance;
    }

    /**
     * Get logger instance
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
     * Create an void logger
     *
     * @return VoidLogger
     */
    public static function initDefaultLogger()
    {
        return self::$instance = new VoidLogger();
    }
}
