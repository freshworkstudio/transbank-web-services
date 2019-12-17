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
     * @throws Exceptions\EmptyClientCertificateException
     * @throws Exceptions\EmptyClientPrivateKeyException
     */
    public static function integrationOneClick()
    {
        $private_key = __DIR__ . '/certs/webpay-oneclick-integration/597044444405.key';
        $client_certificate = __DIR__ . '/certs/webpay-oneclick-integration/597044444405.crt';

        return static::create($private_key, $client_certificate);
    }
    
    public static function integrationOneClickMall()
    {
        $private_key = __DIR__ . '/certs/webpay-oneclick-mall-integration/597044444429.key';
        $client_certificate = __DIR__ . '/certs/webpay-oneclick-mall-integration/597044444429.crt';
        
        return static::create($private_key, $client_certificate);
    }
    
    public static function integrationOneClickMallDeferred()
    {
        $private_key = __DIR__ . '/certs/webpay-oneclick-mall-deferred-integration/597044444433.key';
        $client_certificate = __DIR__ . '/certs/webpay-oneclick-mall-deferred-integration/597044444433.crt';
        
        return static::create($private_key, $client_certificate);
    }
    
    /**
     * Create an instance of Webpay Normal keys and certificates
     *
     * @alias integrationWebpayPlus
     * @return CertificationBag
     * @throws Exceptions\EmptyClientCertificateException
     * @throws Exceptions\EmptyClientPrivateKeyException
     */
    public static function integrationWebpayNormal()
    {
        return static::integrationWebpayPlus();
    }
    
    /**
     * Create an instance of Webpay Normal keys and certificates
     *
     * @return CertificationBag
     * @throws Exceptions\EmptyClientCertificateException
     * @throws Exceptions\EmptyClientPrivateKeyException
     */
    public static function integrationWebpayPlus()
    {
        $private_key = __DIR__ . '/certs/webpay-plus-integration/597020000540.key';
        $client_certificate = __DIR__ . '/certs/webpay-plus-integration/597020000540.crt';
        
        return static::create($private_key, $client_certificate);
    }
    
    /**
     * Create an instance of Webpay Plus Deferred keys and certificates
     *
     * @return CertificationBag
     * @throws Exceptions\EmptyClientCertificateException
     * @throws Exceptions\EmptyClientPrivateKeyException
     */
    public static function integrationWebpayPlusDeferred()
    {
        $private_key = __DIR__ . '/certs/webpay-plus-deferred-integration/597044444404.key';
        $client_certificate = __DIR__ . '/certs/webpay-plus-deferred-integration/597044444404.crt';
        
        return static::create($private_key, $client_certificate);
    }
    
    /**
     * Create an instance of Webpay Normal keys and certificates
     *
     * @return CertificationBag
     * @throws Exceptions\EmptyClientCertificateException
     * @throws Exceptions\EmptyClientPrivateKeyException
     */
    public static function integrationWebpayPlusMall()
    {
        $private_key = __DIR__ . '/certs/webpay-plus-mall-integration/597044444401.key';
        $client_certificate = __DIR__ . '/certs/webpay-plus-mall-integration/597044444401.crt';
        
        return static::create($private_key, $client_certificate);
    }
    
    /**
     * Create an instance of Webpay Normal keys and certificates
     *
     * @return CertificationBag
     * @throws Exceptions\EmptyClientCertificateException
     * @throws Exceptions\EmptyClientPrivateKeyException
     */
    public static function integrationWebpayPlusMallDeferred()
    {
        $private_key = __DIR__ . '/certs/webpay-plus-mall-deferred-integration/597044444424.key';
        $client_certificate = __DIR__ . '/certs/webpay-plus-mall-deferred-integration/597044444424.crt';
        
        return static::create($private_key, $client_certificate);
    }
    
    /**
     * Create an instance of Webpay PatPass keys and certificates
     *
     * @return CertificationBag
     * @throws Exceptions\EmptyClientCertificateException
     * @throws Exceptions\EmptyClientPrivateKeyException
     */
    public static function integrationPatPass()
    {
        $private_key = __DIR__ . '/certs/webpay-patpass-integration/597044444432.key';
        $client_certificate = __DIR__ . '/certs/webpay-patpass-integration/597044444432.crt';

        return static::create($private_key, $client_certificate);
    }

    /**
     * @alias integrationWebpayPlusDeferred
     * @return CertificationBag
     */
    public static function integrationWebpayDeferred()
    {
        return static::integrationWebpayPlusDeferred();
    }
    
    /**
     * Create a production instance of keys and certificates
     *
     * @param string $client_private_key Content or path of client private key
     * @param string $client_certificate Content or path of client public certificate
     * @param string|null $server_certificate Content or path of Transbank public certificate
     * @return CertificationBag
     * @throws Exceptions\EmptyClientCertificateException
     * @throws Exceptions\EmptyClientPrivateKeyException
     */
    public static function production($client_private_key, $client_certificate, $server_certificate = null)
    {
        $env = CertificationBag::PRODUCTION;
        return static::create($client_private_key, $client_certificate, $server_certificate, $env);
    }
    
    /**
     * Create a generic instance of keys and certificates
     *
     * @param string $client_private_key Content or path of client private key
     * @param string $client_certificate Content or path of client public certificate
     * @param string|null $server_certificate Content or path of Transbank public certificate
     * @param string $environment Environment
     * @return CertificationBag
     * @throws Exceptions\EmptyClientCertificateException
     * @throws Exceptions\EmptyClientPrivateKeyException
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
