<?php
/**
 * Class TransbankSoap
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
 * Class TransbankSoap
 *
 * @package Freshwork\Transbank
 */
class TransbankSoap extends SoapClient
{
    /** @var string $privateKey Content or path of client private key */
    protected $privateKey;

    /** @var string $certificate Content or path of client public certificate */
    protected $certificate;

    /**
     * Get the content or path of client private key
     *
     * @return string
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    /**
     * Set the content or path of client private key
     *
     * @param string $privateKey
     */
    public function setPrivateKey($privateKey)
    {
        $this->privateKey = $privateKey;
    }

    /**
     * Get the content or path of client public certificate
     *
     * @return string
     */
    public function getCertificate()
    {
        return $this->certificate;
    }

    /**
     * Set the content or path of client public certificate
     *
     * @param string $certificate
     */
    public function setCertificate($certificate)
    {
        $this->certificate = $certificate;
    }

    /**
     * Execute SOAP request
     *
     * @param string $request request XML
     * @param string $location request URL
     * @param string $action SOAP action
     * @param int $version SOAP version
     * @param int $oneWay Indicates whether there will be a return
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
