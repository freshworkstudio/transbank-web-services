<?php
/**
 * Class CertificationBag
 *
 * @package Freshwork\Transbank
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank;

/**
 * Class CertificationBag
 *
 * @package Freshwork\Transbank
 */
class CertificationBag
{

    /** @const INTEGRATION Development environment */
    const INTEGRATION = 'integration';

    /** @const PRODUCTION Production environment */
    const PRODUCTION = 'production';

    /** @var string $client_private_key Content or path of client private key */
    protected $client_private_key;

    /** @var string $client_certificate Content or path of client public certificate */
    protected $client_certificate;

    /** @var null|string $server_certificate Content or path of Transbank public certificate */
    protected $server_certificate;

    /** @var string $environment Environment */
    protected $environment;

    /**
     * CertificationBag constructor
     *
     * @param string $client_private_key Content or path of client private key
     * @param string $client_certificate Content or path of client public certificate
     * @param null|string $server_certificate Content or path of Transbank public certificate
     * @param string $environment Environment
     */
    public function __construct(
        $client_private_key,
        $client_certificate,
        $server_certificate = null,
        $environment = self::INTEGRATION
    ) {
        if ($server_certificate === null) {
            if ($environment === self::PRODUCTION) {
                $server_certificate = dirname(__FILE__) . '/certs/transbank-production.pem';
            } else {
                $server_certificate = dirname(__FILE__) . '/certs/transbank.pem';
            }
        }
        $this->client_private_key = $client_private_key;
        $this->client_certificate = $client_certificate;
        $this->server_certificate = $server_certificate;
        $this->environment = $environment;

        if (empty($this->client_private_key)) {
            throw new Exceptions\EmptyClientPrivateKeyException();
        }
        if (empty($this->client_certificate)) {
            throw new Exceptions\EmptyClientCertificateException();
        }
    }

    /**
     * Get content or path of client private key
     *
     * @return string
     */
    public function getClientPrivateKey()
    {
        return $this->client_private_key;
    }

    /**
     * Set content or path of client private key
     *
     * @param string $client_private_key Content or path of client private key
     */
    public function setClientPrivateKey($client_private_key)
    {
        $this->client_private_key = $client_private_key;
    }

    /**
     * Get content or path of client public certificate
     *
     * @return string
     */
    public function getClientCertificate()
    {
        return $this->client_certificate;
    }

    /**
     * Set content or path of client public certificate
     *
     * @param string $client_certificate Content or path of client public certificate
     */
    public function setClientCertificate($client_certificate)
    {
        $this->client_certificate = $client_certificate;
    }

    /**
     * Get content or path of Transbank public certificate
     *
     * @return null|string
     */
    public function getServerCertificate()
    {
        return $this->server_certificate;
    }

    /**
     * Set content or path of Transbank public certificate
     *
     * @param string $server_certificate Content or path of Transbank public certificate
     */
    public function setServerCertificate($server_certificate)
    {
        $this->server_certificate = $server_certificate;
    }

    /**
     * Get environment
     *
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * Set environment
     *
     * @param string $environment Environment
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    }
}
