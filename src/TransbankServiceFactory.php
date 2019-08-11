<?php
/**
 * Class TransbankServiceFactory
 *
 * @package Freshwork\Transbank
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank;

use Freshwork\Transbank\WebpayCaptureNullify\WebpayCaptureNullifyWebService;
use Freshwork\Transbank\WebpayStandard\WebpayStandardWebService;
use Freshwork\Transbank\WebpayOneClick\WebpayOneClickWebService;

/**
 * Class TransbankServiceFactory
 * @package Freshwork\Transbank
 */
class TransbankServiceFactory
{

    /**
     * Create a WebpayOneClick instance
     *
     * @param CertificationBag $certificationBag Keys and certificates instance
     * @param string|null $wsdlUrl WSDL URL
     * @return WebpayOneClick
     */
    public static function oneclick(CertificationBag $certificationBag, $wsdlUrl = null)
    {
        $service = new WebpayOneClickWebService($certificationBag, $wsdlUrl);
        return new WebpayOneClick($service);
    }


    /**
     * Create a WebpayPatPass instance
     *
     * @param CertificationBag $certificationBag Keys and certificates instance
     * @param string|null $wsdlUrl WSDL URL
     * @return WebpayPatPass
     */
    public static function patpass(CertificationBag $certificationBag, $wsdlUrl = null)
    {
        $service = new WebpayStandardWebService($certificationBag, $wsdlUrl);
        return new WebpayPatPass($service);
    }

    /**
     * Create a WebpayNormal instance
     *
     * @param CertificationBag $certificationBag Keys and certificates instance
     * @param string|null $wsdlUrl WSDL URL
     * @return WebpayNormal
     */
    public static function normal(CertificationBag $certificationBag, $wsdlUrl = null)
    {
        $service = new WebpayStandardWebService($certificationBag, $wsdlUrl);
        return new WebpayNormal($service);
    }

    /**
     * Create a WebpayMall instance
     *
     * @param CertificationBag $certificationBag Keys and certificates instance
     * @param string|null $wsdlUrl WSDL URL
     * @return WebpayMall
     */
    public static function mall(CertificationBag $certificationBag, $wsdlUrl = null)
    {
        $service = new WebpayStandardWebService($certificationBag, $wsdlUrl);
        return new WebpayMall($service);
    }

    /**
     * @param CertificationBag $certificationBag
     * @param null $wsdlUrl
     * @return WebpayNormal
     */
    public static function deferred(CertificationBag $certificationBag, $wsdlUrl = null)
    {
        $service = new WebpayStandardWebService($certificationBag, $wsdlUrl);
        return new WebpayDeferred($service);
    }

    /**
     * @param CertificationBag $certificationBag
     * @param null $wsdlUrl
     * @return WebpayCaptureNullify
     */
    public static function captureNullify(CertificationBag $certificationBag, $wsdlUrl = null)
    {
        $service = new WebpayCaptureNullifyWebService($certificationBag, $wsdlUrl);
        return new WebpayCaptureNullify($service);
    }
}
