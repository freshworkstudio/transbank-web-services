<?php
/**
 * Class CertificationBagFactory
 *
 * @package Freshwork\Transbank
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank;

/**
 * Class CertificationBagFactory
 *
 * @package Freshwork\Transbank
 */
class CertificationBagFactory
{
    /**
     * Create an instance of Webpay OneClick keys and certificates
     *
     * @return CertificationBag
     */
    public static function integrationOneClick()
    {
        $private_key = dirname(__FILE__) . '/certs/webpay-oneclick-integration/597020000547.key';
        $client_certificate = dirname(__FILE__) . '/certs/webpay-oneclick-integration/597020000547.crt';

        return new CertificationBag($private_key, $client_certificate, null, CertificationBag::INTEGRATION);
    }

    /**
     * Create an instance of Webpay Normal keys and certificates
     *
     * @return CertificationBag
     */
    public static function integrationWebpayNormal()
    {
        $private_key = dirname(__FILE__) . '/certs/webpay-plus-integration/597020000541.key';
        $client_certificate = dirname(__FILE__) . '/certs/webpay-plus-integration/597020000541.crt';

        return new CertificationBag($private_key, $client_certificate, null, CertificationBag::INTEGRATION);
    }

    /**
     * Create an instance of Webpay PatPass keys and certificates
     *
     * @return CertificationBag
     */
    public static function integrationPatPass()
    {
        $private_key = dirname(__FILE__) . '/certs/webpay-patpass-integration/597020000548.key';
        $client_certificate = dirname(__FILE__) . '/certs/webpay-patpass-integration/597020000548.crt';

        return new CertificationBag($private_key, $client_certificate, null, CertificationBag::INTEGRATION);
    }

    /**
     * @return CertificationBag
     */
    public static function integrationWebpayDeferred()
    {
        $private_key = dirname(__FILE__) . '/certs/webpay-deferred-integration/597044444404.key';
        $client_certificate = dirname(__FILE__) . '/certs/webpay-deferred-integration/597044444404.crt';
        $server_certificate = dirname(__FILE__) . '/certs/webpay-deferred-integration/transbank.pem';

        return new CertificationBag(
            $private_key,
            $client_certificate,
            $server_certificate,
            CertificationBag::INTEGRATION
        );
    }

    /**
     * Create a production instance of keys and certificates
     *
     * @param string $client_private_key Content or path of client private key
     * @param string $client_certificate Content or path of client public certificate
     * @param string|null $server_certificate Content or path of Transbank public certificate
     * @return CertificationBag
     */
    public static function production($client_private_key, $client_certificate, $server_certificate = null)
    {
        $env = CertificationBag::PRODUCTION;
        return new CertificationBag($client_private_key, $client_certificate, $server_certificate, $env);
    }

    /**
     * Create a generic instance of keys and certificates
     *
     * @param string $client_private_key Content or path of client private key
     * @param string $client_certificate Content or path of client public certificate
     * @param string|null $server_certificate Content or path of Transbank public certificate
     * @param string $environment Environment
     * @return CertificationBag
     */
    public static function create(
        $client_private_key,
        $client_certificate,
        $server_certificate = null,
        $environment = CertificationBag::INTEGRATION
    ) {
        return new CertificationBag($client_private_key, $client_certificate, $server_certificate, $environment);
    }
}
