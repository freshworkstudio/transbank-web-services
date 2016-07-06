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
    static public function oneclick(CertificationBag $certificationBag, $wsdlUrl = null)
    {
        $service = new WebpayOneClickWebService($certificationBag, $wsdlUrl);
        return new WebpayOneClick($service);
    }


    /**
     * @param CertificationBag $certificationBag
     * @return WebpayWebService
     */
    static public function patpass(CertificationBag $certificationBag, $wsdlUrl = null) {
        $service = new WebpayStandardWebService($certificationBag, $wsdlUrl);
        return new WebpayPatPass($service);
    }

    /**
     * @param CertificationBag $certificationBag
     * @param null $wsdlUrl
     * @return WebpayWebService
     */
    public static function normal(CertificationBag $certificationBag, $wsdlUrl = null)
    {
        $service = new WebpayStandardWebService($certificationBag, $wsdlUrl);
        return new WebpayNormal($service);
    }
}