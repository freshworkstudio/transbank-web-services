<?php
/**
 * Clase CertificationBag
 *
 * @package Freshwork\Transbank
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank;

/**
 * Clase CertificationBag
 *
 * @package Freshwork\Transbank
 */
class CertificationBag
{

    /** @const INTEGRATION Identifica el ambiente de integración */
    const INTEGRATION = 'integration';

    /** @const PRODUCTION Identifica el ambiente de producción */
    const PRODUCTION = 'production';

    /** @var string $client_private_key Ruta o contenido de la llave privada del cliente */
    private $client_private_key;

    /** @var string $client_certificate Ruta o contenido del certificado público del cliente */
    private $client_certificate;

    /** @var null|string $server_certificate Ruta o contenido del certificado provisto por Transbank */
    private $server_certificate;

    /** @var string $environment Identifica el ambiente que se está utilizando */
    private $environment;

    /**
     * CertificationBag constructor
     *
     * @param string $client_private_key Ruta o contenido de la llave privada del cliente
     * @param string $client_certificate Ruta o contenido del certificado público del cliente
     * @param null|string $server_certificate Ruta o contenido del certificado provisto por Transbank
     * @param string $environment Tipo de ambiente que se está utilizando
     */
    public function __construct(
        $client_private_key,
        $client_certificate,
        $server_certificate = null,
        $environment = self::INTEGRATION
    ) {
        if ($server_certificate === null) {
            $server_certificate = dirname(__FILE__) . '/certs/transbank.pem';
        }
        $this->client_private_key = $client_private_key;
        $this->client_certificate = $client_certificate;
        $this->server_certificate = $server_certificate;
        $this->environment = $environment;
    }

    /**
     * Obtiene la ruta o contenido de la llave privada del cliente
     *
     * @return string
     */
    public function getClientPrivateKey()
    {
        return $this->client_private_key;
    }

    /**
     * Establece la ruta o contenido de la llave privada del cliente
     *
     * @param string $client_private_key Ruta o contenido de la llave privada del cliente
     */
    public function setClientPrivateKey($client_private_key)
    {
        $this->client_private_key = $client_private_key;
    }

    /**
     * Obtiene la ruta o contenido del certificado público del cliente
     *
     * @return string
     */
    public function getClientCertificate()
    {
        return $this->client_certificate;
    }

    /**
     * Establece la ruta o contenido del certificado público del cliente
     *
     * @param string $client_certificate Ruta o contenido del certificado público del cliente
     */
    public function setClientCertificate($client_certificate)
    {
        $this->client_certificate = $client_certificate;
    }

    /**
     * Obtiene la ruta o contenido del certificado de Transbank
     *
     * @return null|string
     */
    public function getServerCertificate()
    {
        return $this->server_certificate;
    }

    /**
     * Establece la ruta o contenido del certificado de Transbank
     *
     * @param string $server_certificate Ruta o contenido del certificado de Transbank
     */
    public function setServerCertificate($server_certificate)
    {
        $this->server_certificate = $server_certificate;
    }

    /**
     * Obtiene el ambiente utilizado
     *
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * Establece el ambiente utilizado
     *
     * @param string $environment Ambiente
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    }
}
