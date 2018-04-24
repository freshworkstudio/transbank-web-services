<?php

namespace Freshwork\Transbank\Log;

class TransbankCertificationLogger implements LoggerInterface
{
    /**
     * The logs directory. You need write permissions on that folder.
     * @var string
     */
    private $log_dir;

    /**
     * TransbankCertificationLogHandler constructor.
     * @param string $log_dir
     */
    public function __construct($log_dir = '')
    {
        $this->log_dir = rtrim($log_dir, '/');

        if (!is_dir($this->log_dir)) {
            mkdir($this->log_dir, 0775, true);
        }
    }

    public function log($data, $level = self::LEVEL_INFO, $type = null)
    {
        if (!is_string($data)) {
            $data = print_r($data, true);
        }

        file_put_contents(
            $this->getLogDirectory() . '/' . $this->getLogFilname(),
            $this->getLogMessage($data, $level, $type),
            FILE_APPEND
        );
    }

    protected function getLogDirectory()
    {
        return $this->log_dir;
    }

    protected function getLogFilname()
    {
        return date('Ymd') . '.transbank.log.txt';
    }

    protected function getLogMessage($data, $level, $type)
    {
        $time = date('d/m/Y H:i:s');
        return "\n=================================\n$time - " . strtoupper($level) . " ($type):\n=================================\n$data";
    }
}
