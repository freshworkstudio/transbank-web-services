<?php
/**
 * Class LoggerInterface
 *
 * @package Freshwork\Transbank
 * @subpackage Log
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1.6 (15/07/2016)
 */

namespace Freshwork\Transbank\Log;

/**
 * LoggerInterface Interface
 *
 * @package Freshwork\Transbank\Log
 */
interface LoggerInterface
{
    /** @const LEVEL_ERROR Identifies an error  */
    const LEVEL_ERROR = 'error';

    /** @const LEVEL_WARNING Identifies a warning  */
    const LEVEL_WARNING = 'warning';

    /** @const LEVEL_INFO Identifies an information */
    const LEVEL_INFO = 'info';

    /**
     * Log an event
     *
     * @param mixed $data Data to log
     * @param string $level Event type
     * @param mixed $type Additional identifier
     * @return mixed
     */
    public function log($data, $level = self::LEVEL_INFO, $type = null);
}
