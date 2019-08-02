<?php
/**
 * Class LogHandler
 *
 * @package Freshwork\Transbank
 * @subpackage Log
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1.6 (15/07/2016)
 */

namespace Freshwork\Transbank\Log;

/**
 * Class LogHandler
 *
 * @package Freshwork\Transbank\Log
 */
class LogHandler
{
    /**
     * Log an event
     *
     * @param mixed $data Data to log
     * @param string $level Event type
     * @param mixed $type Additional information
     */
    public static function log($data, $level = LoggerInterface::LEVEL_INFO, $type = null)
    {
        LoggerFactory::logger()->log($data, $level, $type);
    }
}
