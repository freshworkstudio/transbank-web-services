<?php
/**
 * Clase TransbankCertificationLogger
 *
 * @package Freshwork\Transbank
 * @subpackage Log
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1.6 (15/07/2016)
 */

namespace Freshwork\Transbank\Log;

/**
 * Clase TransbankCertificationLogger
 *
 * @package Freshwork\Transbank\Log
 */
class TransbankCertificationLogger implements LoggerInterface
{
    /** @var string $log_dir Ruta para escribir los registros */
    private $log_dir;

    /**
     * Constructor de TransbankCertificationLogger
     *
     * @param string $log_dir Ruta para escribir los registros
     */
    public function __construct($log_dir = '')
    {
        $this->log_dir = rtrim($log_dir, '/');

        if (!is_dir($this->log_dir)) {
            mkdir($this->log_dir, 0775, true);
        }
    }

    /**
     * Escribe o muestra un registro
     *
     * @param mixed $data Información a registrar o mostrar
     * @param string $level Tipo de evento
     * @param mixed $type Identificador adicional
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
     * Obtiene la ruta para escribir los registros
     *
     * @return string
     */
    protected function getLogDirectory()
    {
        return $this->log_dir;
    }

    /**
     * Obtiene el nombre de archivo a utilizar para escribir los registros
     *
     * @return string
     */
    protected function getLogFilename()
    {
        return date('Ymd') . '.transbank.log.txt';
    }

    /**
     * Obtiene registro con formato
     *
     * @param string $data Información a registrar
     * @param string $level Tipo de evento
     * @param string $type Identificador adicional
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
