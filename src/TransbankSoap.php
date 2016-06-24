<?php


namespace Freshwork\Transbank;


use DOMDocument;
use SoapClient;
use WSSESoap;
use XMLSecurityKey;

/**
 * Class TransbankSoap
 * @package Freshwork\Transbank
 */
class TransbankSoap extends SoapClient {
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
    function __doRequest($request, $location, $saction, $version, $one_way = NULL) {
        $doc = new DOMDocument('1.0');
        $doc->loadXML($request);
        $objWSSE = new WSSESoap($doc);
        $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1,array('type' =>
            'private'));
        $objKey->loadKey($this->getPrivateKey(), TRUE);
        $options = array("insertBefore" => TRUE); $objWSSE->signSoapDoc($objKey, $options); $objWSSE->addIssuerSerial($this->getCertificate());
        $objKey = new XMLSecurityKey(XMLSecurityKey::AES256_CBC); $objKey->generateSessionKey();
        $retVal = parent::__doRequest($objWSSE->saveXML(), $location, $saction,
            $version);
        $doc = new DOMDocument();
        $doc->loadXML($retVal);
        return $doc->saveXML();
    }


}
