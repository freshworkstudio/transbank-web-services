<?php

namespace Freshwork\Transbank;


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
    static public function createOneClick(CertificationBag $certificationBag, $wsdlUrl = null)
    {
        $service = new WebpayOneClickWebService($certificationBag, $wsdlUrl);
        return new WebpayOneClick($service);
    }

    /**
     * Create Web
     * @param string $client_private_key
     * @param string $client_certificate
     * @param bool $production_environment
     * @param string|null $wsdlUrl
     * @return WebpayOneClick
     */
    static public function createOneClickWith($client_private_key, $client_certificate, $production_environment = false, $wsdlUrl = null)
    {
        $env = ($production_environment) ? CertificationBag::PRODUCTION : CertificationBag::INTEGRATION;
        $certificationBag = new CertificationBag($client_private_key, $client_certificate, null, $env);

        $service = new WebpayOneClickWebService($certificationBag, $wsdlUrl);

        return new WebpayOneClick($service);
    }
}