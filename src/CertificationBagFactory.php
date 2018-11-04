<?php
/**
 * Clase CertificationBagFactory
 *
 * @package Freshwork\Transbank
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank;

/**
 * Clase CertificationBagFactory
 *
 * @package Freshwork\Transbank
 */
class CertificationBagFactory
{
    /**
     * Generar instancia de llaves y certificados para Webpay OneClick
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
     * Generar instancia de llaves y certificados para Webpay Normal
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
     * Generar instancia de llaves y certificados para Webpay PatPass
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
     * Generador instancia para llaves y certificados productivos
     *
     * @param string $client_private_key Ruta o contenido de la llave privada del cliente
     * @param string $client_certificate Ruta o contenido del certificado público del cliente
     * @param string|null $server_certificate Ruta o contenido del certificado de Transbank
     * @return CertificationBag
     */
    public static function production($client_private_key, $client_certificate, $server_certificate = null)
    {
        $env = CertificationBag::PRODUCTION;
        return new CertificationBag($client_private_key, $client_certificate, $server_certificate, $env);
    }

    /**
     * Generador de instnacia de llaves y certificados
     *
     * @param string $client_private_key Ruta o contenido de la llave privada del cliente
     * @param string $client_certificate Ruta o contenido del certificado público del cliente
     * @param string|null $server_certificate Ruta o contenido del certificado de Transbank
     * @param string $environment Tipo de ambiente que se está utilizando
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
