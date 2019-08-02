<?php
/**
 * Class TransbankCertificationLogger
 *
 * @package Freshwork\Transbank
 * @subpackage Log
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1.6 (15/07/2016)
 */

namespace Freshwork\Transbank\Log;

/**
 * Class TransbankCertificationLogger
 *
 * @package Freshwork\Transbank\Log
 */
class TransbankCertificationLogger implements LoggerInterface
{
    /** @var string $log_dir Path to write logs */
    private $log_dir;

    /**
     * Constructor de TransbankCertificationLogger
     *
     * @param string $log_dir Path to write logs
     */
    public function __construct($log_dir = '')
    {
        $this->log_dir = rtrim($log_dir, '/');

        if (!is_dir($this->log_dir)) {
            mkdir($this->log_dir, 0775, true);
        }
    }

    /**
     * Write or print a log
     *
     * @param mixed $data Data to log
     * @param string $level Event type
     * @param mixed $type Additional identifier
     * @return mixed|void
     */
    public function log($data, $level = self::LEVEL_INFO, $type = null)
    {
        if (!is_string($data)) {
            $data = print_r($data, true);
        }

        file_put_contents(
            $this->getLogDirectory() . '/' . $this->getLogFilename(),
            $this->getLogMessage($data, $level, $type),
            FILE_APPEND
        );
    }

    /**
     * Get path to write logs
     *
     * @return string
     */
    protected function getLogDirectory()
    {
        return $this->log_dir;
    }

    /**
     * Get filename to write logs
     *
     * @return string
     */
    protected function getLogFilename()
    {
        return date('Ymd') . '.transbank.log.txt';
    }

    /**
     * Get the formatted log
     *
     * @param string $data Data to log
     * @param string $level Event type
     * @param string $type Additional identifier
     * @return string
     */
    protected function getLogMessage($data, $level, $type)
    {
        $time = date('d/m/Y H:i:s');
        return "\n=================================\n$time - "
            . strtoupper($level)
            . " ($type):\n=================================\n$data";
    }
}
