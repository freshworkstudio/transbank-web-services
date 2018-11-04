<?php
/**
 * Clase TransbankServiceFactory
 *
 * @package Freshwork\Transbank
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank;

use Freshwork\Transbank\WebpayStandard\WebpayStandardWebService;
use Freshwork\Transbank\WebpayOneClick\WebpayOneClickWebService;

/**
 * Clase TransbankServiceFactory
 * @package Freshwork\Transbank
 */
class TransbankServiceFactory
{

    /**
     * Genera instancia de WebpayOneClick
     *
     * @param CertificationBag $certificationBag Instancia con certificados y llaves
     * @param string|null $wsdlUrl URL del WSDL
     * @return WebpayOneClick
     */
    public static function oneclick(CertificationBag $certificationBag, $wsdlUrl = null)
    {
        $service = new WebpayOneClickWebService($certificationBag, $wsdlUrl);
        return new WebpayOneClick($service);
    }


    /**
     * Genera una instancia de WebpayPatPass
     *
     * @param CertificationBag $certificationBag Instancia con certificados y llaves
     * @param string|null $wsdlUrl URL del WSDL
     * @return WebpayPatPass
     */
    public static function patpass(CertificationBag $certificationBag, $wsdlUrl = null)
    {
        $service = new WebpayStandardWebService($certificationBag, $wsdlUrl);
        return new WebpayPatPass($service);
    }

    /**
     * Genera una instancia de WebpayNormal
     *
     * @param CertificationBag $certificationBag Instancia con certificados y llaves
     * @param string|null $wsdlUrl URL del WSDL
     * @return WebpayNormal
     */
    public static function normal(CertificationBag $certificationBag, $wsdlUrl = null)
    {
        $service = new WebpayStandardWebService($certificationBag, $wsdlUrl);
        return new WebpayNormal($service);
    }

    /**
     * Genera una instancia de WebpayMall
     *
     * @param CertificationBag $certificationBag Instancia con certificados y llaves
     * @param string|null $wsdlUrl URL del WSDL
     * @return WebpayMall
     */
    public static function mall(CertificationBag $certificationBag, $wsdlUrl = null)
    {
        $service = new WebpayStandardWebService($certificationBag, $wsdlUrl);
        return new WebpayMall($service);
    }
}
