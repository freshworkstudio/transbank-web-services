<?php
namespace Freshwork\Transbank;

use Freshwork\Transbank\WebpayStandard\WebpayStandardWebService;
use Freshwork\Transbank\WebpayOneClick\WebpayOneClickWebService;

/**
 * Class TransbankServiceFactory
 * @package Freshwork\Transbank
 */
class TransbankServiceFactory
{

    /**
     * @param CertificationBag $certificationBag
     * @param string|null $wsdlUrl
     * @return WebpayOneClick
     */
    public static function oneclick(CertificationBag $certificationBag, $wsdlUrl = null)
    {
        $service = new WebpayOneClickWebService($certificationBag, $wsdlUrl);
        return new WebpayOneClick($service);
    }


    /**
     * @param CertificationBag $certificationBag
     * @return WebpayPatPass
     */
    public static function patpass(CertificationBag $certificationBag, $wsdlUrl = null)
    {
        $service = new WebpayStandardWebService($certificationBag, $wsdlUrl);
        return new WebpayPatPass($service);
    }

    /**
     * @param CertificationBag $certificationBag
     * @param null $wsdlUrl
     * @return WebpayNormal
     */
    public static function normal(CertificationBag $certificationBag, $wsdlUrl = null)
    {
        $service = new WebpayStandardWebService($certificationBag, $wsdlUrl);
        return new WebpayNormal($service);
    }

    /**
     * @param CertificationBag $certificationBag
     * @param null $wsdlUrl
     * @return WebpayMall
     */
    public static function mall(CertificationBag $certificationBag, $wsdlUrl = null)
    {
        $service = new WebpayStandardWebService($certificationBag, $wsdlUrl);
        return new WebpayMall($service);
    }
}
