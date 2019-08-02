<?php
/**
 * Class VoidLogger
 *
 * @package Freshwork\Transbank
 * @subpackage Log
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1.6 (15/07/2016)
 */

namespace Freshwork\Transbank\Log;

/**
 * Class VoidLogger
 *
 * @package Freshwork\Transbank\Log
 */
class VoidLogger implements LoggerInterface
{
    /**
     * Logger without functionality
     *
     * @param mixed $data Data to log
     * @param string $level Event type
     * @param mixed $type Additional identifier
     * @return void
     */
    public function log($data, $level = self::INFO, $type = null)
    {
    }
}
