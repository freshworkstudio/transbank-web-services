<?php
namespace Freshwork\Transbank;

use DOMDocument;
use Freshwork\Transbank\Log\LoggerInterface;
use Freshwork\Transbank\Log\LogHandler;
use SoapClient;
use WSSESoap;
use XMLSecurityKey;

/**
 * Class TransbankSoap
 * @package Freshwork\Transbank
 */
class TransbankSoap extends SoapClient
{
    /**
     * Client's private key
     * @var string
     */
    protected $privateKey;

    /**
     * Client's public certificate
     * @var string
     */
    protected $certificate;

    /**
     * @return mixed
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    /**
     * @param mixed $privateKey
     */
    public function setPrivateKey($privateKey)
    {
        $this->privateKey = $privateKey;
    }

    /**
     * @return mixed
     */
    public function getCertificate()
    {
        return $this->certificate;
    }

    /**
     * @param mixed $certificate
     */
    public function setCertificate($certificate)
    {
        $this->certificate = $certificate;
    }

    /**
     * @param string $request
     * @param string $location
     * @param string $saction
     * @param int $version
     * @param null $one_way
     * @return string
     * @throws \Exception
     */
    public function __doRequest($request, $location, $saction, $version, $one_way = null)
    {
        LogHandler::log(['location' => $location, 'xml' => $request], LoggerInterface::LEVEL_INFO, 'unsigned_request_raw');

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
        LogHandler::log(['location' => $location, 'xml' => $signed_request], LoggerInterface::LEVEL_INFO, 'signed_request_raw');

        $retVal = parent::__doRequest(
            $signed_request,
            $location,
            $saction,
            $version,
            $one_way
        );
        $doc = new DOMDocument();
        $doc->loadXML($retVal);
        LogHandler::log(['location' => $location, 'xml' => $retVal], LoggerInterface::LEVEL_INFO, 'response_raw');
        return $doc->saveXML();
    }
}
