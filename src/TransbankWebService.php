<?php
/**
 * Clase TransbankWebService
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
 * Clase TransbankWebService
 *
 * @package Freshwork\Transbank
 */
abstract class TransbankWebService
{
    /**
     * @var TransbankSoap $soapClient Cliente SOAP
     */
    protected $soapClient;

    /**
     * @var CertificationBag $certificationBag Instancia con certificados y llaves
     */
    protected $certificationBag;

    /**
     * @var array $classmap Asociación de tipos WSDL a clases
     */
    protected static $classmap = [];

    /**
     * TransbankWebService constructor
     *
     * @param CertificationBag $certificationBag Instancia con certificados y llaves
     * @param string|null $url URL del WSDL
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
     * Obtiene la instancia de certificados y llaves utilizada
     *
     * @return CertificationBag
     */
    public function getCertificationBag()
    {
        return $this->certificationBag;
    }

    /**
     * Establece instancia de certificados y llaves a utilizar
     *
     * @param CertificationBag $certificationBag Instancia con certificados y llaves
     */
    public function setCertificationBag(CertificationBag $certificationBag)
    {
        $this->certificationBag = $certificationBag;
    }

    /**
     * Obtiene la instancia del cliente SOAP
     *
     * @return TransbankSoap
     */
    public function getSoapClient()
    {
        return $this->soapClient;
    }

    /**
     * Verifica la autenticidad de la última respuesta del cliente SOAP
     *
     * @throws InvalidCertificateException
     */
    public function validateResponseCertificate()
    {
        $xmlResponse = $this->getLastRawResponse();

        $soapValidation = new SoapValidation($xmlResponse, $this->certificationBag->getServerCertificate());
        $validation =  $soapValidation->getValidationResult(); //Esto valida si el mensaje está firmado por Transbank

        if ($validation !== true) {
            $msg = 'Transbank response fails on the certificate signature validation. 
            Response does not comes from Transbank or the certificate expired.';
            LogHandler::log($msg, LoggerInterface::LEVEL_ERROR);

            throw new InvalidCertificateException($msg);
        }
    }

    /**
     * Realiza llamadas a métodos del cliente SOAP y valida su respuesta
     *
     * @param string $method Nombre del método a ejecutar
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
            throw new \SoapFault($e->faultcode, $e->faultstring);
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
     * Permite ejecutar dinamicamente métodos del cliente SOAP
     *
     * @param string $name Nombre del método a ejecutar
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        array_unshift($arguments, $name);
        return call_user_func_array([$this, 'callSoapMethod'], $arguments);
    }

    /**
     * Obtiene la última respuesta del cliente SOAP
     *
     * @return string XML con la respuesta
     */
    protected function getLastRawResponse()
    {
        $xmlResponse = $this->getSoapClient()->__getLastResponse();
        return $xmlResponse;
    }

    /**
     * Obtiene la URL del WSDL utilizado
     *
     * @param CertificationBag $certificationBag Instancia con certificados y llaves
     * @param string|null $url URL del WSDL
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
