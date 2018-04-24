<?php

namespace Freshwork\Transbank\Log;

/**
 * Class LoggerFactory
 * @package Freshwork\Transbank\Log
 */
class LoggerFactory
{
    /**
     * @var LoggerInterface
     */
    private static $instance;

    /**
     * @param LoggerInterface $instance
     */
    public static function setLogger(LoggerInterface $instance)
    {
        self::$instance = $instance;
    }

    /**
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
     * @return VoidLogger
     */
    public static function initDefaultLogger()
    {
        return self::$instance = new VoidLogger();
    }
}
