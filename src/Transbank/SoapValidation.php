<?php
namespace Freshwork\Transbank\Transbank;
use DOMDocument;
use DOMXPath;
use Exception;
use XMLSecurityDSig;

/**
 * soap-validation.php
 *
 * Copyright (c) 2012, OrangePeople Software Ltda <soporte@orangepeople.cl>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Robert Richards nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @author     Hernan Arriagada <soporte@orangepeople.cl>
 * @copyright  2012 OrangePeople Software LTDA. <soporte@orangepeople.cl>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    1.0.0
 */

class SoapValidation
{
    const WSSENS = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';
    const WSSENS_2003 = 'http://schemas.xmlsoap.org/ws/2003/06/secext';
    const WSUNS = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd';
    const WSSEPFX = 'wsse';
    const WSUPFX = 'wsu';

    private $soapNS;
    private $soapPFX;
    private $certServerPath;
    private $soapDoc = null;
    private $envelope = null;
    private $SOAPXPath = null;
    private $secNode = null;
    private $result = false;
    public $signAllHeaders = false;
    public $errorMessage = null;

    /**
     * SoapValidation constructor.
     * @param $xmlSoap
     * @param $certServerPath
     */
    public function __construct($xmlSoap, $certServerPath)
    {
        $doc = new DOMDocument("1.0");
        $doc->loadXML($xmlSoap);
        $this->soapDoc = $doc;
        $this->envelope = $doc->documentElement;
        $this->soapNS = $this->envelope->namespaceURI;
        $this->soapPFX = $this->envelope->prefix;

        $this->SOAPXPath = new DOMXPath($doc);
        $this->SOAPXPath->registerNamespace('wssoap', $this->soapNS);
        $this->SOAPXPath->registerNamespace('wswsu', self::WSUNS);

        $this->certServerPath = $certServerPath;

        $wsNamespace = $this->locateSecurityHeader();

        if (!empty($wsNamespace)) {
            $this->SOAPXPath->registerNamespace('wswsse', $wsNamespace);
        }

        $this->result = $this->process();
    }

    private function locateSecurityHeader($setActor = null)
    {
        $wsNamespace = null;
        if ($this->secNode == null) {
            $headers = $this->SOAPXPath->query('//wssoap:Envelope/wssoap:Header');
            if ($header = $headers->item(0)) {
                $secnodes = $this->SOAPXPath->query('./*[local-name()="Security"]', $header);
                $secnode = null;
                foreach ($secnodes as $node) {
                    $nsURI = $node->namespaceURI;
                    if (($nsURI == self::WSSENS) || ($nsURI == self::WSSENS_2003)) {
                        $actor = $node->getAttributeNS($this->soapNS, 'actor');
                        if (empty($actor) || ($actor == $setActor)) {
                            $secnode = $node;
                            $wsNamespace = $nsURI;
                            break;
                        }
                    }
                }
            }
            $this->secNode = $secnode;
        }
        return $wsNamespace;
    }

    public function processSignature($refNode)
    {
        $objXMLSecDSig = new XMLSecurityDSig();
        $objXMLSecDSig->idKeys[] = 'wswsu:Id';
        $objXMLSecDSig->idNS['wswsu'] = self::WSUNS;
        $objXMLSecDSig->sigNode = $refNode;

        $objXMLSecDSig->canonicalizeSignedInfo();
        $canonBody = $objXMLSecDSig->canonicalizeBody();

        $retVal = $objXMLSecDSig->validateReference();

        if (!$retVal) {
            throw new Exception("Validation Failed");
        }

        $key = null;
        $objKey = $objXMLSecDSig->locateKey();

        do {
            if (empty($objKey->key)) {
                if (is_file($this->certServerPath)) {
                    $handler = fopen($this->certServerPath, "r");
                    $x509cert = fread($handler, 8192);
                    fclose($handler);
                } else {
                    $x509cert = $this->certServerPath;
                }

                $objKey->loadKey($x509cert, true);
                break;

                throw new Exception("Error loading key to handle Signature");
            }
        } while (0);

        if ($objXMLSecDSig->verify($objKey) &&
                $objXMLSecDSig->compareDigest($canonBody)) {
            return true;
        } else {
            return false;
        }
    }

    public function process()
    {
        if (empty($this->secNode)) {
            return;
        }
        $node = $this->secNode->firstChild;
        while ($node) {
            $nextNode = $node->nextSibling;
            switch ($node->localName) {
                case "Signature":
                    if ($this->processSignature($node)) {
                        if ($node->parentNode) {
                            $node->parentNode->removeChild($node);
                        }
                    } else {
                        /* throw fault */
                        return false;
                    }
            }
            $node = $nextNode;
        }
        $this->secNode->parentNode->removeChild($this->secNode);
        $this->secNode = null;
        return true;
    }

    public function getValidationResult()
    {
        return $this->result;
    }
}
