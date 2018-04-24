<?php
namespace Freshwork\Transbank;

/**
 * Class CertificationBag
 * @package Freshwork\Transbank
 */
class CertificationBag
{

    /**
     * Transbank integration environment
     */
    const INTEGRATION = 'integration';

    /**
     * Transbank production environment
     */
    const PRODUCTION = 'production';

    /**
     * @var string
     */
    private $client_private_key;
    /**
     * @var string
     */
    private $client_certificate;
    /**
     * @var null
     */
    private $server_certificate;
    /**
     * @var int
     */
    private $environment;

    /**
     * CertificationBag constructor.
     * @param string $client_private_key Client private key
     * @param string $client_certificate Client public certificate
     * @param null|string $server_certificate Transbank's server public certificate
     * @param int $environment The environment of the certification bag
     */
    public function __construct($client_private_key, $client_certificate, $server_certificate = null, $environment = self::INTEGRATION)
    {
        if ($server_certificate === null) {
            $server_certificate = dirname(__FILE__) . '/certs/transbank.pem';
        }
        $this->client_private_key = $client_private_key;
        $this->client_certificate = $client_certificate;
        $this->server_certificate = $server_certificate;
        $this->environment = $environment;
    }


    /**
     * @return mixed
     */
    public function getClientPrivateKey()
    {
        return $this->client_private_key;
    }

    /**
     * @param mixed $client_private_key
     */
    public function setClientPrivateKey($client_private_key)
    {
        $this->client_private_key = $client_private_key;
    }

    /**
     * @return mixed
     */
    public function getClientCertificate()
    {
        return $this->client_certificate;
    }

    /**
     * @param mixed $client_certificate
     */
    public function setClientCertificate($client_certificate)
    {
        $this->client_certificate = $client_certificate;
    }

    /**
     * @return null
     */
    public function getServerCertificate()
    {
        return $this->server_certificate;
    }

    /**
     * @param null $server_certificate
     */
    public function setServerCertificate($server_certificate)
    {
        $this->server_certificate = $server_certificate;
    }

    /**
     * @return int
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @param int $environment
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    }
}
