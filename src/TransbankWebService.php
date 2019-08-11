<?php
/**
 * Class TransbankWebService
 *
 * @package Freshwork\Transbank
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank;

use Freshwork\Transbank\Exceptions\InvalidCertificateException;
use Freshwork\Transbank\Log\LogHandler;
use Freshwork\Transbank\Transbank\SoapValidation;
use Freshwork\Transbank\Log\LoggerInterface;

/**
 * Class TransbankWebService
 *
 * @package Freshwork\Transbank
 */
abstract class TransbankWebService
{
    /**
     * @var TransbankSoap $soapClient SOAP client
     */
    protected $soapClient;

    /**
     * @var CertificationBag $certificationBag Keys and certificates instance
     */
    protected $certificationBag;

    /**
     * @var array $classmap Association of WSDL types to classes
     */
    protected static $classmap = [];

    /**
     * TransbankWebService constructor
     *
     * @param CertificationBag $certificationBag Keys and certificates instance
     * @param string|null $url WSDL URL
     */
    public function __construct(CertificationBag $certificationBag, $url = null)
    {
        $url = $this->getWsdlUrl($certificationBag, $url);

        $this->certificationBag = $certificationBag;

        $this->soapClient = new TransbankSoap($url, [
            "classmap" => static::$classmap,
            "trace" => true,
            "exceptions" => true
        ]);

        $this->soapClient->setCertificate($this->certificationBag->getClientCertificate());
        $this->soapClient->setPrivateKey($this->certificationBag->getClientPrivateKey());
    }

    /**
     * Get the instance of keys and certificates
     *
     * @return CertificationBag
     */
    public function getCertificationBag()
    {
        return $this->certificationBag;
    }

    /**
     * Set the instance of keys and certificates
     *
     * @param CertificationBag $certificationBag Keys and certificates instance
     */
    public function setCertificationBag(CertificationBag $certificationBag)
    {
        $this->certificationBag = $certificationBag;
    }

    /**
     * Get SOAP client instance
     *
     * @return TransbankSoap
     */
    public function getSoapClient()
    {
        return $this->soapClient;
    }

    /**
     * Verifies the authenticity of the last SOAP response
     *
     * @throws InvalidCertificateException
     */
    public function validateResponseCertificate()
    {
        $xmlResponse = $this->getLastRawResponse();

        $soapValidation = new SoapValidation($xmlResponse, $this->certificationBag->getServerCertificate());
        $validation =  $soapValidation->getValidationResult(); // Validates if the message is signed by Transbank

        if ($validation !== true) {
            $msg = 'Transbank response fails on the certificate signature validation. 
            Response does not comes from Transbank or the certificate expired.';
            LogHandler::log($msg, LoggerInterface::LEVEL_ERROR);

            throw new InvalidCertificateException($msg);
        }
    }

    /**
     * Call to SOAP client methods and validate their response
     *
     * @param string $method Method name
     * @return mixed
     * @throws InvalidCertificateException
     * @throws \SoapFault
     */
    protected function callSoapMethod($method)
    {
        $args = func_get_args();
        array_shift($args);

        LogHandler::log($args, LoggerInterface::LEVEL_INFO, 'request_object');

        try {
            $response = call_user_func_array([$this->getSoapClient(), $method], $args);
            LogHandler::log($response, LoggerInterface::LEVEL_INFO, 'response_object');
        } catch (\SoapFault $e) {
            LogHandler::log(
                'SOAP ERROR (' . $e->faultcode . '): ' . $e->getMessage(),
                LoggerInterface::LEVEL_ERROR,
                'error'
            );
            throw $e;
        }

        $this->validateResponseCertificate();

        LogHandler::log(
            "Response certificate validated successfully",
            LoggerInterface::LEVEL_INFO,
            'response_certificate_validated'
        );

        return $response;
    }

    /**
     * Dynamically executes methods from the SOAP client
     *
     * @param string $name Method to execute
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, array $arguments)
    {
        array_unshift($arguments, $name);
        return call_user_func_array([$this, 'callSoapMethod'], $arguments);
    }

    /**
     * Get the last response from the SOAP client
     *
     * @return string Raw XML
     */
    protected function getLastRawResponse()
    {
        $xmlResponse = $this->getSoapClient()->__getLastResponse();
        return $xmlResponse;
    }

    /**
     * Get the WSDL URL
     *
     * @param CertificationBag $certificationBag Keys and certificates instance
     * @param string|null $url WSDL URL
     * @return string
     */
    public function getWsdlUrl(CertificationBag $certificationBag, $url = null)
    {
        if ($url) {
            return $url;
        }

        if ($certificationBag->getEnvironment() == CertificationBag::PRODUCTION) {
            return static::PRODUCTION_WSDL;
        }

        return static::INTEGRATION_WSDL;
    }
}
