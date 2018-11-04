<?php
/**
 * Clase TransbankSoap
 *
 * @package Freshwork\Transbank
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank;

use DOMDocument;
use Freshwork\Transbank\Log\LoggerInterface;
use Freshwork\Transbank\Log\LogHandler;
use SoapClient;
use WSSESoap;
use XMLSecurityKey;

/**
 * Clase TransbankSoap
 *
 * @package Freshwork\Transbank
 */
class TransbankSoap extends SoapClient
{
    /** @var string $privateKey Ruta o contenido de la llave privada del cliente */
    protected $privateKey;

    /** @var string $certificate Ruta o contenido del certificado público del cliente */
    protected $certificate;

    /**
     * Obtiene la ruta o contenido de la llave privada del cliente
     *
     * @return string
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    /**
     * Establece la ruta o contenido de la llave privada del cliente
     *
     * @param string $privateKey
     */
    public function setPrivateKey($privateKey)
    {
        $this->privateKey = $privateKey;
    }

    /**
     * Obtiene la ruta o contenido del certificado público del cliente
     *
     * @return string
     */
    public function getCertificate()
    {
        return $this->certificate;
    }

    /**
     * Establece la ruta o contenido del certificado público del cliente
     *
     * @param string $certificate
     */
    public function setCertificate($certificate)
    {
        $this->certificate = $certificate;
    }

    /**
     * Realiza una petición SOAP
     *
     * @param string $request XML de la petición
     * @param string $location URL donde realizar la petición
     * @param string $action Acción del SOAP
     * @param int $version Versión del SOAP
     * @param int $oneWay Establece si existirá un retorno
     * @return string
     * @throws \Exception
     */
    public function __doRequest($request, $location, $action, $version, $oneWay = 0)
    {
        LogHandler::log([
            'location' => $location,
            'xml' => $request
        ], LoggerInterface::LEVEL_INFO, 'unsigned_request_raw');

        $doc = new DOMDocument('1.0');
        $doc->loadXML($request);
        $objWSSE = new WSSESoap($doc);
        $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, array('type' =>
            'private'));
        $objKey->loadKey($this->getPrivateKey());
        $options = array("insertBefore" => true);
        $objWSSE->signSoapDoc($objKey, $options);
        $objWSSE->addIssuerSerial($this->getCertificate());
        $objKey = new XMLSecurityKey(XMLSecurityKey::AES256_CBC);
        $objKey->generateSessionKey();

        $signed_request = $objWSSE->saveXML();
        LogHandler::log([
            'location' => $location,
            'xml' => $signed_request
        ], LoggerInterface::LEVEL_INFO, 'signed_request_raw');

        $retVal = parent::__doRequest(
            $signed_request,
            $location,
            $action,
            $version,
            $oneWay
        );
        $doc = new DOMDocument();
        $doc->loadXML($retVal);
        LogHandler::log([
            'location' => $location,
            'xml' => $retVal
        ], LoggerInterface::LEVEL_INFO, 'response_raw');
        return $doc->saveXML();
    }
}
