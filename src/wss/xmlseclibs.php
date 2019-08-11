<?php

/**
 * xmlseclibs.php
 *
 * Copyright (c) 2007-2010, Robert Richards <rrichards@cdatazone.org>.
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
 * @author     Robert Richards <rrichards@cdatazone.org>
 * @copyright  2007-2010 Robert Richards <rrichards@cdatazone.org>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    1.3.0-dev
 */
/*
  Functions to generate simple cases of Exclusive Canonical XML - Callable function is C14NGeneral()
  i.e.: $canonical = C14NGeneral($domelement, TRUE);
 */

/* helper function */
function sortAndAddAttrs($element, $arAtts)
{
    $newAtts = [];
    foreach ($arAtts as $attnode) {
        $newAtts[$attnode->nodeName] = $attnode;
    }
    ksort($newAtts);
    foreach ($newAtts as $attnode) {
        $element->setAttribute($attnode->nodeName, $attnode->nodeValue);
    }
}

/* helper function */

function canonical($tree, $element, $withcomments)
{
    if ($tree->nodeType != XML_DOCUMENT_NODE) {
        $dom = $tree->ownerDocument;
    } else {
        $dom = $tree;
    }
    if ($element->nodeType != XML_ELEMENT_NODE) {
        if ($element->nodeType == XML_DOCUMENT_NODE) {
            foreach ($element->childNodes as $node) {
                canonical($dom, $node, $withcomments);
            }

            return;
        }
        if ($element->nodeType == XML_COMMENT_NODE && !$withcomments) {
            return;
        }
        $tree->appendChild($dom->importNode($element, true));

        return;
    }
    $arNS = [];
    if ($element->namespaceURI != "") {
        if ($element->prefix == "") {
            $elCopy = $dom->createElementNS($element->namespaceURI, $element->nodeName);
        } else {
            $prefix = $tree->lookupPrefix($element->namespaceURI);
            if ($prefix == $element->prefix) {
                $elCopy = $dom->createElementNS($element->namespaceURI, $element->nodeName);
            } else {
                $elCopy = $dom->createElement($element->nodeName);
                $arNS[$element->namespaceURI] = $element->prefix;
            }
        }
    } else {
        $elCopy = $dom->createElement($element->nodeName);
    }
    $tree->appendChild($elCopy);

    /* Create DOMXPath based on original document */
    $xPath = new DOMXPath($element->ownerDocument);

    /* Get namespaced attributes */
    $arAtts = $xPath->query('attribute::*[namespace-uri(.) != ""]', $element);

    /* Create an array with namespace URIs as keys, and sort them */
    foreach ($arAtts as $attnode) {
        if (array_key_exists($attnode->namespaceURI, $arNS) && ($arNS[$attnode->namespaceURI] == $attnode->prefix)) {
            continue;
        }
        $prefix = $tree->lookupPrefix($attnode->namespaceURI);
        if ($prefix != $attnode->prefix) {
            $arNS[$attnode->namespaceURI] = $attnode->prefix;
        } else {
            $arNS[$attnode->namespaceURI] = null;
        }
    }
    if (count($arNS) > 0) {
        asort($arNS);
    }

    /* Add namespace nodes */
    foreach ($arNS as $namespaceURI => $prefix) {
        if ($prefix != null) {
            $elCopy->setAttributeNS("http://www.w3.org/2000/xmlns/", "xmlns:" . $prefix, $namespaceURI);
        }
    }
    if (count($arNS) > 0) {
        ksort($arNS);
    }

    /* Get attributes not in a namespace, and then sort and add them */
    $arAtts = $xPath->query('attribute::*[namespace-uri(.) = ""]', $element);
    sortAndAddAttrs($elCopy, $arAtts);

    /* Loop through the URIs, and then sort and add attributes within that namespace */
    foreach ($arNS as $nsURI => $prefix) {
        $arAtts = $xPath->query('attribute::*[namespace-uri(.) = "' . $nsURI . '"]', $element);
        sortAndAddAttrs($elCopy, $arAtts);
    }

    foreach ($element->childNodes as $node) {
        canonical($elCopy, $node, $withcomments);
    }
}

/*
 * @author OrangePeople Software Ltda <soporte@orangepeople.cl>
 * helper function
 * Modification by Hermann Alexander Arriagada Méndez
 * for IssuerSerial
 */

function getIssuerName($X509Cert)
{
    if (is_file($X509Cert)) {
        $handler = fopen($X509Cert, "r");
        $cert = fread($handler, 8192);
        fclose($handler);
    } else {
        $cert = $X509Cert;
    }
    $cert_as_array = openssl_x509_parse($cert);
    $name = $cert_as_array['name'];
    $name = str_replace("/", ",", $name);
    $name = substr($name, 1, strlen($name));

    return $name;
}

/*
 * @author OrangePeople Software Ltda <soporte@orangepeople.cl>
 * helper function
 * Modification by Hermann Alexander Arriagada Méndez
 * for IssuerSerial
 */

function getSerialNumber($X509Cert)
{
    if (is_file($X509Cert)) {
        $handler = fopen($X509Cert, "r");
        $cert = fread($handler, 8192);
        fclose($handler);
    } else {
        $cert = $X509Cert;
    }
    $cert_as_array = openssl_x509_parse($cert);
    $serialNumber = $cert_as_array['serialNumber'];

    return $serialNumber;
}

/*
  $element - DOMElement for which to produce the canonical version of
  $exclusive - boolean to indicate exclusive canonicalization (must pass TRUE)
  $withcomments - boolean indicating wether or not to include comments in canonicalized form
 */

function C14NGeneral($element, $exclusive = false, $withcomments = false)
{
    /* IF PHP 5.2+ then use built in canonical functionality */
    $php_version = explode('.', PHP_VERSION);
    if (($php_version[0] > 5) || ($php_version[0] == 5 && $php_version[1] >= 2)) {
        return $element->C14N($exclusive, $withcomments);
    }

    /* Must be element or document */
    if (!$element instanceof DOMElement && !$element instanceof DOMDocument) {
        return null;
    }
    /* Currently only exclusive XML is supported */
    if ($exclusive == false) {
        throw new Exception("Only exclusive canonicalization is supported in this version of PHP");
    }

    $copyDoc = new DOMDocument();
    canonical($copyDoc, $element, $withcomments);

    return $copyDoc->saveXML($copyDoc->documentElement, LIBXML_NOEMPTYTAG);
}

class XMLSecurityKey
{
    const TRIPLEDES_CBC = 'http://www.w3.org/2001/04/xmlenc#tripledes-cbc';
    const AES128_CBC = 'http://www.w3.org/2001/04/xmlenc#aes128-cbc';
    const AES192_CBC = 'http://www.w3.org/2001/04/xmlenc#aes192-cbc';
    const AES256_CBC = 'http://www.w3.org/2001/04/xmlenc#aes256-cbc';
    const RSA_1_5 = 'http://www.w3.org/2001/04/xmlenc#rsa-1_5';
    const RSA_OAEP_MGF1P = 'http://www.w3.org/2001/04/xmlenc#rsa-oaep-mgf1p';
    const DSA_SHA1 = 'http://www.w3.org/2000/09/xmldsig#dsa-sha1';
    const RSA_SHA1 = 'http://www.w3.org/2000/09/xmldsig#rsa-sha1';
    const RSA_SHA256 = 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256';
    const RSA_SHA384 = 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha384';
    const RSA_SHA512 = 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha512';
    const HMAC_SHA1 = 'http://www.w3.org/2000/09/xmldsig#hmac-sha1';

    /** @var array */
    private $cryptParams = [];

    /** @var int|string */
    public $type = 0;

    /** @var mixed|null */
    public $key = null;

    /** @var string */
    public $passphrase = "";

    /** @var string|null */
    public $iv = null;

    /** @var string|null */
    public $name = null;

    /** @var mixed|null */
    public $keyChain = null;

    /** @var bool */
    public $isEncrypted = false;

    /** @var XMLSecEnc|null */
    public $encryptedCtx = null;

    /** @var mixed|null */
    public $guid = null;

    /**
     * This variable contains the certificate as a string if this key represents an X509-certificate.
     * If this key doesn't represent a certificate, this will be null.
     *
     * @var string|null
     */
    private $x509Certificate = null;

    /**
     * This variable contains the certificate thumbprint if we have loaded an X509-certificate.
     *
     * @var string|null
     */
    private $X509Thumbprint = null;

    /**
     * @param string $type
     * @param null|array $params
     * @throws Exception
     */
    public function __construct($type, $params = null)
    {
        switch ($type) {
            case (self::TRIPLEDES_CBC):
                $this->cryptParams['library'] = 'openssl';
                $this->cryptParams['cipher'] = 'des-ede3-cbc';
                $this->cryptParams['type'] = 'symmetric';
                $this->cryptParams['method'] = 'http://www.w3.org/2001/04/xmlenc#tripledes-cbc';
                $this->cryptParams['keysize'] = 24;
                $this->cryptParams['blocksize'] = 8;
                break;
            case (self::AES128_CBC):
                $this->cryptParams['library'] = 'openssl';
                $this->cryptParams['cipher'] = 'aes-128-cbc';
                $this->cryptParams['type'] = 'symmetric';
                $this->cryptParams['method'] = 'http://www.w3.org/2001/04/xmlenc#aes128-cbc';
                $this->cryptParams['keysize'] = 16;
                $this->cryptParams['blocksize'] = 16;
                break;
            case (self::AES192_CBC):
                $this->cryptParams['library'] = 'openssl';
                $this->cryptParams['cipher'] = 'aes-192-cbc';
                $this->cryptParams['type'] = 'symmetric';
                $this->cryptParams['method'] = 'http://www.w3.org/2001/04/xmlenc#aes192-cbc';
                $this->cryptParams['keysize'] = 24;
                $this->cryptParams['blocksize'] = 16;
                break;
            case (self::AES256_CBC):
                $this->cryptParams['library'] = 'openssl';
                $this->cryptParams['cipher'] = 'aes-256-cbc';
                $this->cryptParams['type'] = 'symmetric';
                $this->cryptParams['method'] = 'http://www.w3.org/2001/04/xmlenc#aes256-cbc';
                $this->cryptParams['keysize'] = 32;
                $this->cryptParams['blocksize'] = 16;
                break;
            case (self::RSA_1_5):
                $this->cryptParams['library'] = 'openssl';
                $this->cryptParams['padding'] = OPENSSL_PKCS1_PADDING;
                $this->cryptParams['method'] = 'http://www.w3.org/2001/04/xmlenc#rsa-1_5';
                if (is_array($params) && !empty($params['type'])) {
                    if ($params['type'] == 'public' || $params['type'] == 'private') {
                        $this->cryptParams['type'] = $params['type'];
                        break;
                    }
                }
                throw new Exception('Certificate "type" (private/public) must be passed via parameters');
            case (self::RSA_OAEP_MGF1P):
                $this->cryptParams['library'] = 'openssl';
                $this->cryptParams['padding'] = OPENSSL_PKCS1_OAEP_PADDING;
                $this->cryptParams['method'] = 'http://www.w3.org/2001/04/xmlenc#rsa-oaep-mgf1p';
                $this->cryptParams['hash'] = null;
                if (is_array($params) && !empty($params['type'])) {
                    if ($params['type'] == 'public' || $params['type'] == 'private') {
                        $this->cryptParams['type'] = $params['type'];
                        break;
                    }
                }
                throw new Exception('Certificate "type" (private/public) must be passed via parameters');
            case (self::RSA_SHA1):
                $this->cryptParams['library'] = 'openssl';
                $this->cryptParams['method'] = 'http://www.w3.org/2000/09/xmldsig#rsa-sha1';
                $this->cryptParams['padding'] = OPENSSL_PKCS1_PADDING;
                if (is_array($params) && !empty($params['type'])) {
                    if ($params['type'] == 'public' || $params['type'] == 'private') {
                        $this->cryptParams['type'] = $params['type'];
                        break;
                    }
                }
                throw new Exception('Certificate "type" (private/public) must be passed via parameters');
            case (self::RSA_SHA256):
                $this->cryptParams['library'] = 'openssl';
                $this->cryptParams['method'] = 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256';
                $this->cryptParams['padding'] = OPENSSL_PKCS1_PADDING;
                $this->cryptParams['digest'] = 'SHA256';
                if (is_array($params) && !empty($params['type'])) {
                    if ($params['type'] == 'public' || $params['type'] == 'private') {
                        $this->cryptParams['type'] = $params['type'];
                        break;
                    }
                }
                throw new Exception('Certificate "type" (private/public) must be passed via parameters');
            case (self::RSA_SHA384):
                $this->cryptParams['library'] = 'openssl';
                $this->cryptParams['method'] = 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha384';
                $this->cryptParams['padding'] = OPENSSL_PKCS1_PADDING;
                $this->cryptParams['digest'] = 'SHA384';
                if (is_array($params) && !empty($params['type'])) {
                    if ($params['type'] == 'public' || $params['type'] == 'private') {
                        $this->cryptParams['type'] = $params['type'];
                        break;
                    }
                }
                throw new Exception('Certificate "type" (private/public) must be passed via parameters');
            case (self::RSA_SHA512):
                $this->cryptParams['library'] = 'openssl';
                $this->cryptParams['method'] = 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha512';
                $this->cryptParams['padding'] = OPENSSL_PKCS1_PADDING;
                $this->cryptParams['digest'] = 'SHA512';
                if (is_array($params) && !empty($params['type'])) {
                    if ($params['type'] == 'public' || $params['type'] == 'private') {
                        $this->cryptParams['type'] = $params['type'];
                        break;
                    }
                }
                throw new Exception('Certificate "type" (private/public) must be passed via parameters');
            case (self::HMAC_SHA1):
                $this->cryptParams['library'] = $type;
                $this->cryptParams['method'] = 'http://www.w3.org/2000/09/xmldsig#hmac-sha1';
                break;
            default:
                throw new Exception('Invalid Key Type');
        }
        $this->type = $type;
    }

    /**
     * Retrieve the key size for the symmetric encryption algorithm..
     *
     * If the key size is unknown, or this isn't a symmetric encryption algorithm,
     * null is returned.
     *
     * @return int|null  The number of bytes in the key.
     */
    public function getSymmetricKeySize()
    {
        if (!isset($this->cryptParams['keysize'])) {
            return null;
        }

        return $this->cryptParams['keysize'];
    }

    /**
     * Generates a session key using the openssl-extension.
     * In case of using DES3-CBC the key is checked for a proper parity bits set.
     *
     * @return string
     * @throws Exception
     */
    public function generateSessionKey()
    {
        if (!isset($this->cryptParams['keysize'])) {
            throw new Exception('Unknown key size for type "' . $this->type . '".');
        }
        $keysize = $this->cryptParams['keysize'];

        $key = openssl_random_pseudo_bytes($keysize);

        if ($this->type === self::TRIPLEDES_CBC) {
            /* Make sure that the generated key has the proper parity bits set.
             * Mcrypt doesn't care about the parity bits, but others may care.
            */
            for ($i = 0; $i < strlen($key); $i++) {
                $byte = ord($key[$i]) & 0xfe;
                $parity = 1;
                for ($j = 1; $j < 8; $j++) {
                    $parity ^= ($byte >> $j) & 1;
                }
                $byte |= $parity;
                $key[$i] = chr($byte);
            }
        }

        $this->key = $key;

        return $key;
    }

    /**
     * Get the raw thumbprint of a certificate
     *
     * @param string $cert
     * @return null|string
     */
    public static function getRawThumbprint($cert)
    {
        $arCert = explode("\n", $cert);
        $data = '';
        $inData = false;

        foreach ($arCert as $curData) {
            if (!$inData) {
                if (strncmp($curData, '-----BEGIN CERTIFICATE', 22) == 0) {
                    $inData = true;
                }
            } else {
                if (strncmp($curData, '-----END CERTIFICATE', 20) == 0) {
                    break;
                }
                $data .= trim($curData);
            }
        }

        if (!empty($data)) {
            return strtolower(sha1(base64_decode($data)));
        }

        return null;
    }

    /**
     * Loads the given key, or - with isFile set true - the key from the keyfile.
     *
     * @param string $key
     * @param bool $isFile
     * @param bool $isCert
     * @throws Exception
     */
    public function loadKey($key, $isCert = false)
    {
        if (is_file($key)) {
            $this->key = file_get_contents($key);
        } else {
            $this->key = $key;
        }
        if ($isCert) {
            $this->key = openssl_x509_read($this->key);
            openssl_x509_export($this->key, $str_cert);
            $this->x509Certificate = $str_cert;
            $this->key = $str_cert;
        } else {
            $this->x509Certificate = null;
        }
        if ($this->cryptParams['library'] == 'openssl') {
            switch ($this->cryptParams['type']) {
                case 'public':
                    if ($isCert) {
                        /* Load the thumbprint if this is an X509 certificate. */
                        $this->X509Thumbprint = self::getRawThumbprint($this->key);
                    }
                    $this->key = openssl_get_publickey($this->key);
                    if (!$this->key) {
                        throw new Exception('Unable to extract public key');
                    }
                    break;

                case 'private':
                    $this->key = openssl_get_privatekey($this->key, $this->passphrase);
                    break;

                case'symmetric':
                    if (strlen($this->key) < $this->cryptParams['keysize']) {
                        throw new Exception('Key must contain at least 25 characters for this cipher');
                    }
                    break;

                default:
                    throw new Exception('Unknown type');
            }
        }
    }

    /**
     * ISO 10126 Padding
     *
     * @param string $data
     * @param integer $blockSize
     * @return string
     * @throws Exception
     */
    private function padISO10126($data, $blockSize)
    {
        if ($blockSize > 256) {
            throw new Exception('Block size higher than 256 not allowed');
        }
        $padChr = $blockSize - (strlen($data) % $blockSize);
        $pattern = chr($padChr);

        return $data . str_repeat($pattern, $padChr);
    }

    /**
     * Remove ISO 10126 Padding
     *
     * @param string $data
     * @return string
     */
    private function unpadISO10126($data)
    {
        $padChr = substr($data, -1);
        $padLen = ord($padChr);

        return substr($data, 0, -$padLen);
    }

    /**
     * Encrypts the given data (string) using the openssl-extension
     *
     * @param string $data
     * @return string
     * @throws Exception
     */
    private function encryptSymmetric($data)
    {
        $this->iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->cryptParams['cipher']));
        $data = $this->padISO10126($data, $this->cryptParams['blocksize']);
        $encrypted = openssl_encrypt($data, $this->cryptParams['cipher'], $this->key,
            OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $this->iv);
        if (false === $encrypted) {
            throw new Exception('Failure encrypting Data (openssl symmetric) - ' . openssl_error_string());
        }

        return $this->iv . $encrypted;
    }

    /**
     * Decrypts the given data (string) using the openssl-extension
     *
     * @param string $data
     * @return string
     * @throws Exception
     */
    private function decryptSymmetric($data)
    {
        $iv_length = openssl_cipher_iv_length($this->cryptParams['cipher']);
        $this->iv = substr($data, 0, $iv_length);
        $data = substr($data, $iv_length);
        $decrypted = openssl_decrypt($data, $this->cryptParams['cipher'], $this->key,
            OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $this->iv);
        if (false === $decrypted) {
            throw new Exception('Failure decrypting Data (openssl symmetric) - ' . openssl_error_string());
        }

        return $this->unpadISO10126($decrypted);
    }

    /**
     * Encrypts the given public data (string) using the openssl-extension
     *
     * @param string $data
     * @return string
     * @throws Exception
     */
    private function encryptPublic($data)
    {
        if (!openssl_public_encrypt($data, $encrypted, $this->key, $this->cryptParams['padding'])) {
            throw new Exception('Failure encrypting Data (openssl public) - ' . openssl_error_string());
        }

        return $encrypted;
    }

    /**
     * Decrypts the given public data (string) using the openssl-extension
     *
     * @param string $data
     * @return string
     * @throws Exception
     */
    private function decryptPublic($data)
    {
        if (!openssl_public_decrypt($data, $decrypted, $this->key, $this->cryptParams['padding'])) {
            throw new Exception('Failure decrypting Data (openssl public) - ' . openssl_error_string());
        }

        return $decrypted;
    }

    /**
     * Encrypts the given private data (string) using the openssl-extension
     *
     * @param string $data
     * @return string
     * @throws Exception
     */
    private function encryptPrivate($data)
    {
        if (!openssl_private_encrypt($data, $encrypted, $this->key, $this->cryptParams['padding'])) {
            throw new Exception('Failure encrypting Data (openssl private) - ' . openssl_error_string());
        }

        return $encrypted;
    }

    /**
     * Decrypts the given private data (string) using the openssl-extension
     *
     * @param string $data
     * @return string
     * @throws Exception
     */
    private function decryptPrivate($data)
    {
        if (!openssl_private_decrypt($data, $decrypted, $this->key, $this->cryptParams['padding'])) {
            throw new Exception('Failure decrypting Data (openssl private) - ' . openssl_error_string());
        }

        return $decrypted;
    }

    /**
     * Signs the given data (string) using the openssl-extension
     *
     * @param string $data
     * @return string
     * @throws Exception
     */
    private function signOpenSSL($data)
    {
        $algo = OPENSSL_ALGO_SHA1;
        if (!empty($this->cryptParams['digest'])) {
            $algo = $this->cryptParams['digest'];
        }
        if (!openssl_sign($data, $signature, $this->key, $algo)) {
            throw new Exception('Failure Signing Data: ' . openssl_error_string() . ' - ' . $algo);
        }

        return $signature;
    }

    /**
     * Verifies the given data (string) belonging to the given signature using the openssl-extension
     *
     * Returns:
     *  1 on succesful signature verification,
     *  0 when signature verification failed,
     *  -1 if an error occurred during processing.
     *
     * NOTE: be very careful when checking the return value, because in PHP,
     * -1 will be cast to True when in boolean context. So always check the
     * return value in a strictly typed way, e.g. "$obj->verify(...) === 1".
     *
     * @param string $data
     * @param string $signature
     * @return int
     */
    private function verifyOpenSSL($data, $signature)
    {
        $algo = OPENSSL_ALGO_SHA1;
        if (!empty($this->cryptParams['digest'])) {
            $algo = $this->cryptParams['digest'];
        }

        return openssl_verify($data, $signature, $this->key, $algo);
    }

    /**
     * Encrypts the given data (string) using the regarding php-extension, depending on the library assigned to
     * algorithm in the contructor.
     *
     * @param string $data
     * @return mixed|string
     * @throws Exception
     */
    public function encryptData($data)
    {
        if ($this->cryptParams['library'] === 'openssl') {
            switch ($this->cryptParams['type']) {
                case 'symmetric':
                    return $this->encryptSymmetric($data);
                case 'public':
                    return $this->encryptPublic($data);
                case 'private':
                    return $this->encryptPrivate($data);
            }
        }
    }

    /**
     * Decrypts the given data (string) using the regarding php-extension, depending on the library assigned to
     * algorithm in the contructor.
     *
     * @param string $data
     * @return mixed|string
     * @throws Exception
     */
    public function decryptData($data)
    {
        if ($this->cryptParams['library'] === 'openssl') {
            switch ($this->cryptParams['type']) {
                case 'symmetric':
                    return $this->decryptSymmetric($data);
                case 'public':
                    return $this->decryptPublic($data);
                case 'private':
                    return $this->decryptPrivate($data);
            }
        }
    }

    /**
     * Signs the data (string) using the extension assigned to the type in the constructor.
     *
     * @param string $data
     * @return mixed|string
     * @throws Exception
     */
    public function signData($data)
    {
        switch ($this->cryptParams['library']) {
            case 'openssl':
                return $this->signOpenSSL($data);
            case (self::HMAC_SHA1):
                return hash_hmac("sha1", $data, $this->key, true);
        }
    }

    /**
     * Verifies the data (string) against the given signature using the extension assigned to the type in the
     * constructor.
     *
     * @param string $data
     * @param string $signature
     * @return bool|int
     */
    public function verifySignature($data, $signature)
    {
        switch ($this->cryptParams['library']) {
            case 'openssl':
                return $this->verifyOpenSSL($data, $signature);
            case (self::HMAC_SHA1):
                $expectedSignature = hash_hmac("sha1", $data, $this->key, true);

                return strcmp($signature, $expectedSignature) == 0;
        }
    }

    /**
     * @return mixed
     * @see getAlgorithm()
     * @deprecated
     */
    public function getAlgorith()
    {
        return $this->getAlgorithm();
    }

    /**
     * @return mixed
     */
    public function getAlgorithm()
    {
        return $this->cryptParams['method'];
    }

    /**
     *
     * @param int $type
     * @param string $string
     * @return null|string
     */
    public static function makeAsnSegment($type, $string)
    {
        switch ($type) {
            case 0x02:
                if (ord($string) > 0x7f) {
                    $string = chr(0) . $string;
                }
                break;
            case 0x03:
                $string = chr(0) . $string;
                break;
        }

        $length = strlen($string);

        if ($length < 128) {
            $output = sprintf("%c%c%s", $type, $length, $string);
        } elseif ($length < 0x0100) {
            $output = sprintf("%c%c%c%s", $type, 0x81, $length, $string);
        } elseif ($length < 0x010000) {
            $output = sprintf("%c%c%c%c%s", $type, 0x82, $length / 0x0100, $length % 0x0100, $string);
        } else {
            $output = null;
        }

        return $output;
    }

    /**
     *
     * Hint: Modulus and Exponent must already be base64 decoded
     *
     * @param string $modulus
     * @param string $exponent
     * @return string
     */
    public static function convertRSA($modulus, $exponent)
    {
        /* make an ASN publicKeyInfo */
        $exponentEncoding = self::makeAsnSegment(0x02, $exponent);
        $modulusEncoding = self::makeAsnSegment(0x02, $modulus);
        $sequenceEncoding = self::makeAsnSegment(0x30, $modulusEncoding . $exponentEncoding);
        $bitstringEncoding = self::makeAsnSegment(0x03, $sequenceEncoding);
        $rsaAlgorithmIdentifier = pack("H*", "300D06092A864886F70D0101010500");
        $publicKeyInfo = self::makeAsnSegment(0x30, $rsaAlgorithmIdentifier . $bitstringEncoding);

        /* encode the publicKeyInfo in base64 and add PEM brackets */
        $publicKeyInfoBase64 = base64_encode($publicKeyInfo);
        $encoding = "-----BEGIN PUBLIC KEY-----\n";
        $offset = 0;
        while ($segment = substr($publicKeyInfoBase64, $offset, 64)) {
            $encoding = $encoding . $segment . "\n";
            $offset += 64;
        }

        return $encoding . "-----END PUBLIC KEY-----\n";
    }

    /**
     * @param mixed $parent
     */
    public function serializeKey($parent)
    {
    }

    /**
     * Retrieve the X509 certificate this key represents.
     *
     * Will return the X509 certificate in PEM-format if this key represents
     * an X509 certificate.
     *
     * @return string The X509 certificate or null if this key doesn't represent an X509-certificate.
     */
    public function getX509Certificate()
    {
        return $this->x509Certificate;
    }

    /**
     * Get the thumbprint of this X509 certificate.
     *
     * Returns:
     *  The thumbprint as a lowercase 40-character hexadecimal number, or null
     *  if this isn't a X509 certificate.
     *
     * @return string Lowercase 40-character hexadecimal number of thumbprint
     */
    public function getX509Thumbprint()
    {
        return $this->X509Thumbprint;
    }

    /**
     * Create key from an EncryptedKey-element.
     *
     * @param DOMElement $element The EncryptedKey-element.
     * @return XMLSecurityKey The new key.
     * @throws Exception
     *
     */
    public static function fromEncryptedKeyElement(DOMElement $element)
    {
        $objenc = new XMLSecEnc();
        $objenc->setNode($element);
        if (!$objKey = $objenc->locateKey()) {
            throw new Exception("Unable to locate algorithm for this Encrypted Key");
        }
        $objKey->isEncrypted = true;
        $objKey->encryptedCtx = $objenc;
        XMLSecEnc::staticLocateKeyInfo($objKey, $element);

        return $objKey;
    }
}

class XMLSecurityDSig
{
    const XMLDSIGNS = 'http://www.w3.org/2000/09/xmldsig#';
    const SHA1 = 'http://www.w3.org/2000/09/xmldsig#sha1';
    const SHA256 = 'http://www.w3.org/2001/04/xmlenc#sha256';
    const SHA512 = 'http://www.w3.org/2001/04/xmlenc#sha512';
    const RIPEMD160 = 'http://www.w3.org/2001/04/xmlenc#ripemd160';
    const C14N = 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315';
    const C14N_COMMENTS = 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315#WithComments';
    const EXC_C14N = 'http://www.w3.org/2001/10/xml-exc-c14n#';
    const EXC_C14N_COMMENTS = 'http://www.w3.org/2001/10/xml-exc-c14n#WithComments';
    const NS_ENVELOPE = 'xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/"';
    const NS_XMLDSIG = 'xmlns:ds="http://www.w3.org/2000/09/xmldsig#"';
    const template = '<ds:Signature xmlns:ds="http://www.w3.org/2000/09/xmldsig#">
  <ds:SignedInfo>
    <ds:SignatureMethod />
  </ds:SignedInfo>
</ds:Signature>';

    public $sigNode = null;
    public $idKeys = [];
    public $idNS = [];
    private $signedInfo = null;
    private $body = null;
    private $xPathCtx = null;
    private $canonicalMethod = null;
    private $prefix = 'ds';
    private $searchpfx = 'secdsig';

    /* This variable contains an associative array of validated nodes. */
    private $validatedNodes = null;

    public function __construct()
    {
        $sigdoc = new DOMDocument();
        $sigdoc->loadXML(XMLSecurityDSig::template);
        $this->sigNode = $sigdoc->documentElement;
    }

    private function resetXPathObj()
    {
        $this->xPathCtx = null;
    }

    private function getXPathObj()
    {
        if (empty($this->xPathCtx) && !empty($this->sigNode)) {
            $xpath = new DOMXPath($this->sigNode->ownerDocument);
            $xpath->registerNamespace('secdsig', XMLSecurityDSig::XMLDSIGNS);
            $this->xPathCtx = $xpath;
        }

        return $this->xPathCtx;
    }

    public static function generate_GUID($prefix = 'pfx')
    {
        $uuid = md5(uniqid(rand(), true));
        $guid = $prefix . substr($uuid, 0, 8) . "-" . substr($uuid, 8, 4) . "-" . substr($uuid, 12,
                4) . "-" . substr($uuid, 16, 4) . "-" . substr($uuid, 20, 12);

        return $guid;
    }

    public function locateSignature($objDoc)
    {
        if ($objDoc instanceof DOMDocument) {
            $doc = $objDoc;
        } else {
            $doc = $objDoc->ownerDocument;
        }
        if ($doc) {
            $xpath = new DOMXPath($doc);
            $xpath->registerNamespace('secdsig', XMLSecurityDSig::XMLDSIGNS);
            $query = ".//secdsig:Signature";
            $nodeset = $xpath->query($query, $objDoc);
            $this->sigNode = $nodeset->item(0);

            return $this->sigNode;
        }

        return null;
    }

    public function createNewSignNode($name, $value = null)
    {
        $doc = $this->sigNode->ownerDocument;
        if (!is_null($value)) {
            $node = $doc->createElementNS(XMLSecurityDSig::XMLDSIGNS, $this->prefix . ':' . $name, $value);
        } else {
            $node = $doc->createElementNS(XMLSecurityDSig::XMLDSIGNS, $this->prefix . ':' . $name);
        }

        return $node;
    }

    public function setCanonicalMethod($method)
    {
        switch ($method) {
            case 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315':
            case 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315#WithComments':
            case 'http://www.w3.org/2001/10/xml-exc-c14n#':
            case 'http://www.w3.org/2001/10/xml-exc-c14n#WithComments':
                $this->canonicalMethod = $method;
                break;
            default:
                throw new Exception('Invalid Canonical Method');
        }
        if ($xpath = $this->getXPathObj()) {
            $query = './' . $this->searchpfx . ':SignedInfo';
            $nodeset = $xpath->query($query, $this->sigNode);
            if ($sinfo = $nodeset->item(0)) {
                $query = './' . $this->searchpfx . 'CanonicalizationMethod';
                $nodeset = $xpath->query($query, $sinfo);
                if (!($canonNode = $nodeset->item(0))) {
                    $canonNode = $this->createNewSignNode('CanonicalizationMethod');
                    $sinfo->insertBefore($canonNode, $sinfo->firstChild);
                }
                $canonNode->setAttribute('Algorithm', $this->canonicalMethod);
            }
        }
    }

    private function canonicalizeData($node, $canonicalmethod, $arXPath = null, $prefixList = null)
    {
        $exclusive = false;
        $withComments = false;

        switch ($canonicalmethod) {
            case 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315':
                $exclusive = false;
                $withComments = false;
                break;
            case 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315#WithComments':
                $withComments = true;
                break;
            case 'http://www.w3.org/2001/10/xml-exc-c14n#':
                $exclusive = true;
                break;
            case 'http://www.w3.org/2001/10/xml-exc-c14n#WithComments':
                $exclusive = true;
                $withComments = true;
                break;
        }
        /* Support PHP versions < 5.2 not containing C14N methods in DOM extension */
        $php_version = explode('.', PHP_VERSION);
        if (($php_version[0] < 5) || ($php_version[0] == 5 && $php_version[1] < 2)) {
            if (!is_null($arXPath)) {
                throw new Exception("PHP 5.2.0 or higher is required to perform XPath Transformations");
            }

            return C14NGeneral($node, $exclusive, $withComments);
        }

        return $node->C14N($exclusive, $withComments, $arXPath, $prefixList);
    }

    public function canonicalizeSignedInfo()
    {
        $doc = $this->sigNode->ownerDocument;
        $canonicalmethod = null;
        if ($doc) {
            $xpath = $this->getXPathObj();
            $query = "./secdsig:SignedInfo";
            $nodeset = $xpath->query($query, $this->sigNode);
            if ($signInfoNode = $nodeset->item(0)) {
                $query = "./secdsig:CanonicalizationMethod";
                $nodeset = $xpath->query($query, $signInfoNode);
                if ($canonNode = $nodeset->item(0)) {
                    $canonicalmethod = $canonNode->getAttribute('Algorithm');
                }

                $this->signedInfo = $this->canonicalizeData($signInfoNode, $canonicalmethod);
                $this->addEnvelopeNamespace();

                return $this->signedInfo;
            }
        }

        return null;
    }

    public function addEnvelopeNamespace()
    {
        $this->signedInfo = str_replace(self::NS_XMLDSIG, self::NS_XMLDSIG . " " . self::NS_ENVELOPE,
            $this->signedInfo);
    }

    public function canonicalizeBody()
    {
        $doc = $this->sigNode->ownerDocument;
        $canonicalmethod = null;
        if ($doc) {
            $xpath = $this->getXPathObj();
            $query = '//*[local-name()="Body"]';
            $nodeset = $xpath->query($query);
            $bodyNode = $nodeset->item(0);

            $query = "./secdsig:SignedInfo";
            $nodeset = $xpath->query($query, $this->sigNode);
            if ($signInfoNode = $nodeset->item(0)) {
                $query = "./secdsig:CanonicalizationMethod";
                $nodeset = $xpath->query($query, $signInfoNode);
                if ($canonNode = $nodeset->item(0)) {
                    $canonicalmethod = $canonNode->getAttribute('Algorithm');
                }
                $this->body = $this->canonicalizeData($bodyNode, $canonicalmethod);

                return $this->body;
            }
        }

        return null;
    }

    public function calculateDigest($digestAlgorithm, $data)
    {
        switch ($digestAlgorithm) {
            case XMLSecurityDSig::SHA1:
                $alg = 'sha1';
                break;
            case XMLSecurityDSig::SHA256:
                $alg = 'sha256';
                break;
            case XMLSecurityDSig::SHA512:
                $alg = 'sha512';
                break;
            case XMLSecurityDSig::RIPEMD160:
                $alg = 'ripemd160';
                break;
            default:
                throw new Exception("Cannot validate digest: Unsupported Algorith <$digestAlgorithm>");
        }

        if (function_exists('hash')) {
            return base64_encode(hash($alg, $data, true));
        } elseif (function_exists('mhash')) {
            $alg = "MHASH_" . strtoupper($alg);

            return base64_encode(mhash(constant($alg), $data));
        } elseif ($alg === 'sha1') {
            return base64_encode(sha1($data, true));
        } else {
            throw new Exception('xmlseclibs is unable to calculate a digest. Maybe you need the mhash library?');
        }
    }

    public function validateDigest($refNode, $data)
    {
        $xpath = new DOMXPath($refNode->ownerDocument);
        $xpath->registerNamespace('secdsig', XMLSecurityDSig::XMLDSIGNS);
        $query = 'string(./secdsig:DigestMethod/@Algorithm)';
        $digestAlgorithm = $xpath->evaluate($query, $refNode);
        $digValue = $this->calculateDigest($digestAlgorithm, $data);
        $query = 'string(./secdsig:DigestValue)';
        $digestValue = $xpath->evaluate($query, $refNode);

        return ($digValue == $digestValue);
    }

    public function processTransforms($refNode, $objData)
    {
        $data = $objData;
        $xpath = new DOMXPath($refNode->ownerDocument);
        $xpath->registerNamespace('secdsig', XMLSecurityDSig::XMLDSIGNS);
        $query = './secdsig:Transforms/secdsig:Transform';
        $nodelist = $xpath->query($query, $refNode);
        $canonicalMethod = 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315';
        $arXPath = null;
        $prefixList = null;
        foreach ($nodelist as $transform) {
            $algorithm = $transform->getAttribute("Algorithm");
            switch ($algorithm) {
                case 'http://www.w3.org/2001/10/xml-exc-c14n#':
                case 'http://www.w3.org/2001/10/xml-exc-c14n#WithComments':
                    $node = $transform->firstChild;
                    while ($node) {
                        if ($node->localName == 'InclusiveNamespaces') {
                            if ($pfx = $node->getAttribute('PrefixList')) {
                                $arpfx = [];
                                $pfxlist = explode(" ", $pfx);
                                foreach ($pfxlist as $pfx) {
                                    $val = trim($pfx);
                                    if (!empty($val)) {
                                        $arpfx[] = $val;
                                    }
                                }
                                if (count($arpfx) > 0) {
                                    $prefixList = $arpfx;
                                }
                            }
                            break;
                        }
                        $node = $node->nextSibling;
                    }
                // no break
                case 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315':
                case 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315#WithComments':
                    $canonicalMethod = $algorithm;
                    break;
                case 'http://www.w3.org/TR/1999/REC-xpath-19991116':
                    $node = $transform->firstChild;
                    while ($node) {
                        if ($node->localName == 'XPath') {
                            $arXPath = [];
                            $arXPath['query'] = '(.//. | .//@* | .//namespace::*)[' . $node->nodeValue . ']';
                            $arXpath['namespaces'] = [];
                            $nslist = $xpath->query('./namespace::*', $node);
                            foreach ($nslist as $nsnode) {
                                if ($nsnode->localName != "xml") {
                                    $arXPath['namespaces'][$nsnode->localName] = $nsnode->nodeValue;
                                }
                            }
                            break;
                        }
                        $node = $node->nextSibling;
                    }
                    break;
            }
        }
        if ($data instanceof DOMNode) {
            $data = $this->canonicalizeData($objData, $canonicalMethod, $arXPath, $prefixList);
        }

        return $data;
    }

    public function processRefNode($refNode)
    {
        $dataObject = null;
        if ($uri = $refNode->getAttribute("URI")) {
            $arUrl = parse_url($uri);
            if (empty($arUrl['path'])) {
                if ($identifier = $arUrl['fragment']) {
                    $xPath = new DOMXPath($refNode->ownerDocument);
                    if ($this->idNS && is_array($this->idNS)) {
                        foreach ($this->idNS as $nspf => $ns) {
                            $xPath->registerNamespace($nspf, $ns);
                        }
                    }
                    $iDlist = '@Id="' . $identifier . '"';
                    if (is_array($this->idKeys)) {
                        foreach ($this->idKeys as $idKey) {
                            $iDlist .= " or @$idKey='$identifier'";
                        }
                    }
                    $query = '//*[' . $iDlist . ']';
                    $dataObject = $xPath->query($query)->item(0);
                } else {
                    $dataObject = $refNode->ownerDocument;
                }
            } else {
                $dataObject = file_get_contents($arUrl);
            }
        } else {
            $dataObject = $refNode->ownerDocument;
        }
        $data = $this->processTransforms($refNode, $dataObject);
        if (!$this->validateDigest($refNode, $data)) {
            return false;
        }

        if ($dataObject instanceof DOMNode) {
            /* Add this node to the list of validated nodes. */
            if (!empty($identifier)) {
                $this->validatedNodes[$identifier] = $dataObject;
            } else {
                $this->validatedNodes[] = $dataObject;
            }
        }

        return true;
    }

    public function getRefNodeID($refNode)
    {
        if ($uri = $refNode->getAttribute("URI")) {
            $arUrl = parse_url($uri);
            if (empty($arUrl['path'])) {
                if ($identifier = $arUrl['fragment']) {
                    return $identifier;
                }
            }
        }

        return null;
    }

    public function getRefIDs()
    {
        $refids = [];
        $doc = $this->sigNode->ownerDocument;

        $xpath = $this->getXPathObj();
        $query = "./secdsig:SignedInfo/secdsig:Reference";
        $nodeset = $xpath->query($query, $this->sigNode);
        if ($nodeset->length == 0) {
            throw new Exception("Reference nodes not found");
        }
        foreach ($nodeset as $refNode) {
            $refids[] = $this->getRefNodeID($refNode);
        }

        return $refids;
    }

    public function validateReference()
    {
        $doc = $this->sigNode->ownerDocument;
        if (!$doc->isSameNode($this->sigNode)) {
            $this->sigNode->parentNode->removeChild($this->sigNode);
        }
        $xpath = $this->getXPathObj();
        $query = "./secdsig:SignedInfo/secdsig:Reference";
        $nodeset = $xpath->query($query, $this->sigNode);
        if ($nodeset->length == 0) {
            throw new Exception("Reference nodes not found");
        }

        /* Initialize/reset the list of validated nodes. */
        $this->validatedNodes = [];

        foreach ($nodeset as $refNode) {
            if (!$this->processRefNode($refNode)) {
                /* Clear the list of validated nodes. */
                $this->validatedNodes = null;
                throw new Exception("Reference validation failed");
            }
        }

        return true;
    }

    private function addRefInternal($sinfoNode, $node, $algorithm, $arTransforms = null, $options = null)
    {
        $prefix = null;
        $prefix_ns = null;
        $id_name = 'Id';
        $overwrite_id = true;
        $force_uri = false;

        if (is_array($options)) {
            $prefix = empty($options['prefix']) ? null : $options['prefix'];
            $prefix_ns = empty($options['prefix_ns']) ? null : $options['prefix_ns'];
            $id_name = empty($options['id_name']) ? 'Id' : $options['id_name'];
            $overwrite_id = !isset($options['overwrite']) ? true : (bool)$options['overwrite'];
            $force_uri = !isset($options['force_uri']) ? false : (bool)$options['force_uri'];
        }

        $attname = $id_name;
        if (!empty($prefix)) {
            $attname = $prefix . ':' . $attname;
        }

        $refNode = $this->createNewSignNode('Reference');
        $sinfoNode->appendChild($refNode);

        if (!$node instanceof DOMDocument) {
            $uri = null;
            if (!$overwrite_id) {
                $uri = $node->getAttributeNS($prefix_ns, $attname);
            }
            if (empty($uri)) {
                $uri = XMLSecurityDSig::generate_GUID();
                $node->setAttributeNS($prefix_ns, $attname, $uri);
            }
            $refNode->setAttribute("URI", '#' . $uri);
        } elseif ($force_uri) {
            $refNode->setAttribute("URI", '');
        }

        $transNodes = $this->createNewSignNode('Transforms');
        $refNode->appendChild($transNodes);

        if (is_array($arTransforms)) {
            foreach ($arTransforms as $transform) {
                $transNode = $this->createNewSignNode('Transform');
                $transNodes->appendChild($transNode);
                if (is_array($transform) && (!empty($transform['http://www.w3.org/TR/1999/REC-xpath-19991116'])) && (!empty($transform['http://www.w3.org/TR/1999/REC-xpath-19991116']['query']))) {
                    $transNode->setAttribute('Algorithm', 'http://www.w3.org/TR/1999/REC-xpath-19991116');
                    $XPathNode = $this->createNewSignNode('XPath',
                        $transform['http://www.w3.org/TR/1999/REC-xpath-19991116']['query']);
                    $transNode->appendChild($XPathNode);
                    if (!empty($transform['http://www.w3.org/TR/1999/REC-xpath-19991116']['namespaces'])) {
                        foreach ($transform['http://www.w3.org/TR/1999/REC-xpath-19991116']['namespaces'] as $prefix => $namespace) {
                            $XPathNode->setAttributeNS("http://www.w3.org/2000/xmlns/", "xmlns:$prefix", $namespace);
                        }
                    }
                } else {
                    $transNode->setAttribute('Algorithm', $transform);
                }
            }
        } elseif (!empty($this->canonicalMethod)) {
            $transNode = $this->createNewSignNode('Transform');
            $transNodes->appendChild($transNode);
            $transNode->setAttribute('Algorithm', $this->canonicalMethod);
        }

        $canonicalData = $this->processTransforms($refNode, $node);

        $digValue = $this->calculateDigest($algorithm, $canonicalData);

        $digestMethod = $this->createNewSignNode('DigestMethod');
        $refNode->appendChild($digestMethod);
        $digestMethod->setAttribute('Algorithm', $algorithm);

        $digestValue = $this->createNewSignNode('DigestValue', $digValue);
        $refNode->appendChild($digestValue);
    }

    public function addReference($node, $algorithm, $arTransforms = null, $options = null)
    {
        if ($xpath = $this->getXPathObj()) {
            $query = "./secdsig:SignedInfo";
            $nodeset = $xpath->query($query, $this->sigNode);
            if ($sInfo = $nodeset->item(0)) {
                $this->addRefInternal($sInfo, $node, $algorithm, $arTransforms, $options);
            }
        }
    }

    public function addReferenceList($arNodes, $algorithm, $arTransforms = null, $options = null)
    {
        if ($xpath = $this->getXPathObj()) {
            $query = "./secdsig:SignedInfo";
            $nodeset = $xpath->query($query, $this->sigNode);
            if ($sInfo = $nodeset->item(0)) {
                foreach ($arNodes as $node) {
                    $this->addRefInternal($sInfo, $node, $algorithm, $arTransforms, $options);
                }
            }
        }
    }

    public function addObject($data, $mimetype = null, $encoding = null)
    {
        $objNode = $this->createNewSignNode('Object');
        $this->sigNode->appendChild($objNode);
        if (!empty($mimetype)) {
            $objNode->setAtribute('MimeType', $mimetype);
        }
        if (!empty($encoding)) {
            $objNode->setAttribute('Encoding', $encoding);
        }

        if ($data instanceof DOMElement) {
            $newData = $this->sigNode->ownerDocument->importNode($data, true);
        } else {
            $newData = $this->sigNode->ownerDocument->createTextNode($data);
        }
        $objNode->appendChild($newData);

        return $objNode;
    }

    public function locateKey($node = null)
    {
        if (empty($node)) {
            $node = $this->sigNode;
        }
        if (!$node instanceof DOMNode) {
            return null;
        }
        if ($doc = $node->ownerDocument) {
            $xpath = new DOMXPath($doc);
            $xpath->registerNamespace('secdsig', XMLSecurityDSig::XMLDSIGNS);
            $query = "string(./secdsig:SignedInfo/secdsig:SignatureMethod/@Algorithm)";
            $algorithm = $xpath->evaluate($query, $node);
            if ($algorithm) {
                try {
                    $objKey = new XMLSecurityKey($algorithm, ['type' => 'public']);
                } catch (Exception $e) {
                    return null;
                }

                return $objKey;
            }
        }

        return null;
    }

    public function verify($objKey)
    {
        $doc = $this->sigNode->ownerDocument;
        $xpath = new DOMXPath($doc);
        $xpath->registerNamespace('secdsig', XMLSecurityDSig::XMLDSIGNS);
        $query = "string(./secdsig:SignatureValue)";
        $sigValue = $xpath->evaluate($query, $this->sigNode);

        if (empty($sigValue)) {
            throw new Exception("Unable to locate SignatureValue");
        }

        return $objKey->verifySignature($this->signedInfo, base64_decode($sigValue));
    }

    public function getSigValue()
    {
        $doc = $this->sigNode->ownerDocument;
        $xpath = new DOMXPath($doc);
        $xpath->registerNamespace('secdsig', XMLSecurityDSig::XMLDSIGNS);
        $query = "string(./secdsig:SignatureValue)";
        $sigValue = $xpath->evaluate($query, $this->sigNode);

        if (empty($sigValue)) {
            throw new Exception("Unable to locate SignatureValue");
        }

        return $sigValue;
    }

    /* Compare with SHA1 algorithm */

    public function compareDigest($data)
    {
        $digestValue = $this->sigNode->firstChild->nodeValue;
        $digestValueBody = $this->calculateDigest(self::SHA1, $data);

        return ($digestValue == $digestValueBody);
    }

    public function signData($objKey, $data)
    {
        return $objKey->signData($data);
    }

    public function sign($objKey, $appendToNode = null)
    {
        // If we have a parent node append it now so C14N properly works
        if ($appendToNode != null) {
            $this->resetXPathObj();
            $this->appendSignature($appendToNode);
            $this->sigNode = $appendToNode->lastChild;
        }
        if ($xpath = $this->getXPathObj()) {
            $query = "./secdsig:SignedInfo";
            $nodeset = $xpath->query($query, $this->sigNode);
            if ($sInfo = $nodeset->item(0)) {
                $query = "./secdsig:SignatureMethod";
                $nodeset = $xpath->query($query, $sInfo);
                $sMethod = $nodeset->item(0);
                $sMethod->setAttribute('Algorithm', $objKey->type);
                $data = $this->canonicalizeData($sInfo, $this->canonicalMethod);
                $sigValue = base64_encode($this->signData($objKey, $data));
                $sigValueNode = $this->createNewSignNode('SignatureValue', $sigValue);
                if ($infoSibling = $sInfo->nextSibling) {
                    $infoSibling->parentNode->insertBefore($sigValueNode, $infoSibling);
                } else {
                    $this->sigNode->appendChild($sigValueNode);
                }
            }
        }
    }

    public function appendCert()
    {
    }

    public function appendKey($objKey, $parent = null)
    {
        $objKey->serializeKey($parent);
    }

    /**
     * This function inserts the signature element.
     *
     * The signature element will be appended to the element, unless $beforeNode is specified. If $beforeNode
     * is specified, the signature element will be inserted as the last element before $beforeNode.
     *
     * @param $node  The node the signature element should be inserted into.
     * @param $beforeNode  The node the signature element should be located before.
     */
    public function insertSignature($node, $beforeNode = null)
    {
        $document = $node->ownerDocument;
        $signatureElement = $document->importNode($this->sigNode, true);

        if ($beforeNode == null) {
            $node->insertBefore($signatureElement);
        } else {
            $node->insertBefore($signatureElement, $beforeNode);
        }
    }

    public function appendSignature($parentNode, $insertBefore = false)
    {
        $beforeNode = $insertBefore ? $parentNode->firstChild : null;
        $this->insertSignature($parentNode, $beforeNode);
    }

    public static function get509XCert($cert, $isPEMFormat = true)
    {
        $certs = XMLSecurityDSig::staticGet509XCerts($cert, $isPEMFormat);
        if (!empty($certs)) {
            return $certs[0];
        }

        return '';
    }

    public static function staticGet509XCerts($certs, $isPEMFormat = true)
    {
        if ($isPEMFormat) {
            $data = '';
            $certlist = [];
            $arCert = explode("\n", $certs);
            $inData = false;
            foreach ($arCert as $curData) {
                if (!$inData) {
                    if (strncmp($curData, '-----BEGIN CERTIFICATE', 22) == 0) {
                        $inData = true;
                    }
                } else {
                    if (strncmp($curData, '-----END CERTIFICATE', 20) == 0) {
                        $inData = false;
                        $certlist[] = $data;
                        $data = '';
                        continue;
                    }
                    $data .= trim($curData);
                }
            }

            return $certlist;
        } else {
            return [$certs];
        }
    }

    public static function staticAdd509Cert($parentRef, $cert, $isPEMFormat = true, $isURL = false, $xpath = null)
    {
        if ($isURL) {
            $cert = file_get_contents($cert);
        }
        if (!$parentRef instanceof DOMElement) {
            throw new Exception('Invalid parent Node parameter');
        }
        $baseDoc = $parentRef->ownerDocument;

        if (empty($xpath)) {
            $xpath = new DOMXPath($parentRef->ownerDocument);
            $xpath->registerNamespace('secdsig', XMLSecurityDSig::XMLDSIGNS);
        }

        $query = "./secdsig:KeyInfo";
        $nodeset = $xpath->query($query, $parentRef);
        $keyInfo = $nodeset->item(0);
        if (!$keyInfo) {
            $inserted = false;
            $keyInfo = $baseDoc->createElementNS(XMLSecurityDSig::XMLDSIGNS, 'ds:KeyInfo');

            $query = "./secdsig:Object";
            $nodeset = $xpath->query($query, $parentRef);
            if ($sObject = $nodeset->item(0)) {
                $sObject->parentNode->insertBefore($keyInfo, $sObject);
                $inserted = true;
            }

            if (!$inserted) {
                $parentRef->appendChild($keyInfo);
            }
        }

        // Add all certs if there are more than one
        $certs = XMLSecurityDSig::staticGet509XCerts($cert, $isPEMFormat);

        // Atach X509 data node
        $x509DataNode = $baseDoc->createElementNS(XMLSecurityDSig::XMLDSIGNS, 'ds:X509Data');
        $keyInfo->appendChild($x509DataNode);

        // Atach all certificate nodes
        foreach ($certs as $X509Cert) {
            $x509CertNode = $baseDoc->createElementNS(XMLSecurityDSig::XMLDSIGNS, 'ds:X509Certificate', $X509Cert);
            $x509DataNode->appendChild($x509CertNode);
        }
    }

    public function add509Cert($cert, $isPEMFormat = true, $isURL = false)
    {
        if ($xpath = $this->getXPathObj()) {
            self::staticAdd509Cert($this->sigNode, $cert, $isPEMFormat, $isURL, $xpath);
        }
    }

    /* This function retrieves an associative array of the validated nodes.
     *
     * The array will contain the id of the referenced node as the key and the node itself
     * as the value.
     *
     * Returns:
     *  An associative array of validated nodes or NULL if no nodes have been validated.
     */

    public function getValidatedNodes()
    {
        return $this->validatedNodes;
    }
}

class XMLSecEnc
{
    const template = "<xenc:EncryptedData xmlns:xenc='http://www.w3.org/2001/04/xmlenc#'>
   <xenc:CipherData>
      <xenc:CipherValue></xenc:CipherValue>
   </xenc:CipherData>
</xenc:EncryptedData>";
    const Element = 'http://www.w3.org/2001/04/xmlenc#Element';
    const Content = 'http://www.w3.org/2001/04/xmlenc#Content';
    const URI = 3;
    const XMLENCNS = 'http://www.w3.org/2001/04/xmlenc#';

    private $encdoc = null;
    private $rawNode = null;
    public $type = null;
    public $encKey = null;
    private $references = [];

    public function __construct()
    {
        $this->_resetTemplate();
    }

    private function _resetTemplate()
    {
        $this->encdoc = new DOMDocument();
        $this->encdoc->loadXML(XMLSecEnc::template);
    }

    public function addReference($name, $node, $type)
    {
        if (!$node instanceof DOMNode) {
            throw new Exception('$node is not of type DOMNode');
        }
        $curencdoc = $this->encdoc;
        $this->_resetTemplate();
        $encdoc = $this->encdoc;
        $this->encdoc = $curencdoc;
        $refuri = XMLSecurityDSig::generate_GUID();
        $element = $encdoc->documentElement;
        $element->setAttribute("Id", $refuri);
        $this->references[$name] = ["node" => $node, "type" => $type, "encnode" => $encdoc, "refuri" => $refuri];
    }

    public function setNode($node)
    {
        $this->rawNode = $node;
    }

    public function encryptNode($objKey, $replace = true)
    {
        $data = '';
        if (empty($this->rawNode)) {
            throw new Exception('Node to encrypt has not been set');
        }
        if (!$objKey instanceof XMLSecurityKey) {
            throw new Exception('Invalid Key');
        }
        $doc = $this->rawNode->ownerDocument;
        $xPath = new DOMXPath($this->encdoc);
        $objList = $xPath->query('/xenc:EncryptedData/xenc:CipherData/xenc:CipherValue');
        $cipherValue = $objList->item(0);
        if ($cipherValue == null) {
            throw new Exception('Error locating CipherValue element within template');
        }
        switch ($this->type) {
            case (XMLSecEnc::Element):
                $data = $doc->saveXML($this->rawNode);
                $this->encdoc->documentElement->setAttribute('Type', XMLSecEnc::Element);
                break;
            case (XMLSecEnc::Content):
                $children = $this->rawNode->childNodes;
                foreach ($children as $child) {
                    $data .= $doc->saveXML($child);
                }
                $this->encdoc->documentElement->setAttribute('Type', XMLSecEnc::Content);
                break;
            default:
                throw new Exception('Type is currently not supported');

                return;
        }

        $encMethod = $this->encdoc->documentElement->appendChild($this->encdoc->createElementNS(XMLSecEnc::XMLENCNS,
            'xenc:EncryptionMethod'));
        $encMethod->setAttribute('Algorithm', $objKey->getAlgorith());
        $cipherValue->parentNode->parentNode->insertBefore($encMethod, $cipherValue->parentNode);

        $strEncrypt = base64_encode($objKey->encryptData($data));
        $value = $this->encdoc->createTextNode($strEncrypt);
        $cipherValue->appendChild($value);

        if ($replace) {
            switch ($this->type) {
                case (XMLSecEnc::Element):
                    if ($this->rawNode->nodeType == XML_DOCUMENT_NODE) {
                        return $this->encdoc;
                    }
                    $importEnc = $this->rawNode->ownerDocument->importNode($this->encdoc->documentElement, true);
                    $this->rawNode->parentNode->replaceChild($importEnc, $this->rawNode);

                    return $importEnc;
                    break;
                case (XMLSecEnc::Content):
                    $importEnc = $this->rawNode->ownerDocument->importNode($this->encdoc->documentElement, true);
                    while ($this->rawNode->firstChild) {
                        $this->rawNode->removeChild($this->rawNode->firstChild);
                    }
                    $this->rawNode->appendChild($importEnc);

                    return $importEnc;
                    break;
            }
        }
    }

    public function encryptReferences($objKey)
    {
        $curRawNode = $this->rawNode;
        $curType = $this->type;
        foreach ($this->references as $name => $reference) {
            $this->encdoc = $reference["encnode"];
            $this->rawNode = $reference["node"];
            $this->type = $reference["type"];
            try {
                $encNode = $this->encryptNode($objKey);
                $this->references[$name]["encnode"] = $encNode;
            } catch (Exception $e) {
                $this->rawNode = $curRawNode;
                $this->type = $curType;
                throw $e;
            }
        }
        $this->rawNode = $curRawNode;
        $this->type = $curType;
    }

    public function decryptNode($objKey, $replace = true)
    {
        $data = '';
        if (empty($this->rawNode)) {
            throw new Exception('Node to decrypt has not been set');
        }
        if (!$objKey instanceof XMLSecurityKey) {
            throw new Exception('Invalid Key');
        }
        $doc = $this->rawNode->ownerDocument;
        $xPath = new DOMXPath($doc);
        $xPath->registerNamespace('xmlencr', XMLSecEnc::XMLENCNS);
        /* Only handles embedded content right now and not a reference */
        $query = "./xmlencr:CipherData/xmlencr:CipherValue";
        $nodeset = $xPath->query($query, $this->rawNode);

        if ($node = $nodeset->item(0)) {
            $encryptedData = base64_decode($node->nodeValue);
            $decrypted = $objKey->decryptData($encryptedData);
            if ($replace) {
                switch ($this->type) {
                    case (XMLSecEnc::Element):
                        $newdoc = new DOMDocument();
                        $newdoc->loadXML($decrypted);
                        if ($this->rawNode->nodeType == XML_DOCUMENT_NODE) {
                            return $newdoc;
                        }
                        $importEnc = $this->rawNode->ownerDocument->importNode($newdoc->documentElement, true);
                        $this->rawNode->parentNode->replaceChild($importEnc, $this->rawNode);

                        return $importEnc;
                        break;
                    case (XMLSecEnc::Content):
                        if ($this->rawNode->nodeType == XML_DOCUMENT_NODE) {
                            $doc = $this->rawNode;
                        } else {
                            $doc = $this->rawNode->ownerDocument;
                        }
                        $newFrag = $doc->createDocumentFragment();
                        $newFrag->appendXML($decrypted);
                        $parent = $this->rawNode->parentNode;
                        $parent->replaceChild($newFrag, $this->rawNode);

                        return $parent;
                        break;
                    default:
                        return $decrypted;
                }
            } else {
                return $decrypted;
            }
        } else {
            throw new Exception("Cannot locate encrypted data");
        }
    }

    public function encryptKey($srcKey, $rawKey, $append = true)
    {
        if ((!$srcKey instanceof XMLSecurityKey) || (!$rawKey instanceof XMLSecurityKey)) {
            throw new Exception('Invalid Key');
        }
        $strEncKey = base64_encode($srcKey->encryptData($rawKey->key));
        $root = $this->encdoc->documentElement;
        $encKey = $this->encdoc->createElementNS(XMLSecEnc::XMLENCNS, 'xenc:EncryptedKey');
        if ($append) {
            $keyInfo = $root->appendChild($this->encdoc->createElementNS('http://www.w3.org/2000/09/xmldsig#',
                'dsig:KeyInfo'));
            $keyInfo->appendChild($encKey);
        } else {
            $this->encKey = $encKey;
        }
        $encMethod = $encKey->appendChild($this->encdoc->createElementNS(XMLSecEnc::XMLENCNS, 'xenc:EncryptionMethod'));
        $encMethod->setAttribute('Algorithm', $srcKey->getAlgorith());
        if (!empty($srcKey->name)) {
            $keyInfo = $encKey->appendChild($this->encdoc->createElementNS('http://www.w3.org/2000/09/xmldsig#',
                'dsig:KeyInfo'));
            $keyInfo->appendChild($this->encdoc->createElementNS('http://www.w3.org/2000/09/xmldsig#', 'dsig:KeyName',
                $srcKey->name));
        }
        $cipherData = $encKey->appendChild($this->encdoc->createElementNS(XMLSecEnc::XMLENCNS, 'xenc:CipherData'));
        $cipherData->appendChild($this->encdoc->createElementNS(XMLSecEnc::XMLENCNS, 'xenc:CipherValue', $strEncKey));
        if (is_array($this->references) && count($this->references) > 0) {
            $refList = $encKey->appendChild($this->encdoc->createElementNS(XMLSecEnc::XMLENCNS, 'xenc:ReferenceList'));
            foreach ($this->references as $name => $reference) {
                $refuri = $reference["refuri"];
                $dataRef = $refList->appendChild($this->encdoc->createElementNS(XMLSecEnc::XMLENCNS,
                    'xenc:DataReference'));
                $dataRef->setAttribute("URI", '#' . $refuri);
            }
        }

        return;
    }

    public function decryptKey($encKey)
    {
        if (!$encKey->isEncrypted) {
            throw new Exception("Key is not Encrypted");
        }
        if (empty($encKey->key)) {
            throw new Exception("Key is missing data to perform the decryption");
        }

        return $this->decryptNode($encKey, false);
    }

    public function locateEncryptedData($element)
    {
        if ($element instanceof DOMDocument) {
            $doc = $element;
        } else {
            $doc = $element->ownerDocument;
        }
        if ($doc) {
            $xpath = new DOMXPath($doc);
            $query = "//*[local-name()='EncryptedData' and namespace-uri()='" . XMLSecEnc::XMLENCNS . "']";
            $nodeset = $xpath->query($query);

            return $nodeset->item(0);
        }

        return null;
    }

    public function locateKey($node = null)
    {
        if (empty($node)) {
            $node = $this->rawNode;
        }
        if (!$node instanceof DOMNode) {
            return null;
        }
        if ($doc = $node->ownerDocument) {
            $xpath = new DOMXPath($doc);
            $xpath->registerNamespace('xmlsecenc', XMLSecEnc::XMLENCNS);
            $query = ".//xmlsecenc:EncryptionMethod";
            $nodeset = $xpath->query($query, $node);
            if ($encmeth = $nodeset->item(0)) {
                $attrAlgorithm = $encmeth->getAttribute("Algorithm");
                try {
                    $objKey = new XMLSecurityKey($attrAlgorithm, ['type' => 'private']);
                } catch (Exception $e) {
                    return null;
                }

                return $objKey;
            }
        }

        return null;
    }

    public static function staticLocateKeyInfo($objBaseKey = null, $node = null)
    {
        if (empty($node) || (!$node instanceof DOMNode)) {
            return null;
        }
        if ($doc = $node->ownerDocument) {
            $xpath = new DOMXPath($doc);
            $xpath->registerNamespace('xmlsecenc', XMLSecEnc::XMLENCNS);
            $xpath->registerNamespace('xmlsecdsig', XMLSecurityDSig::XMLDSIGNS);
            $query = "./xmlsecdsig:KeyInfo";
            $nodeset = $xpath->query($query, $node);
            if ($encmeth = $nodeset->item(0)) {
                foreach ($encmeth->childNodes as $child) {
                    switch ($child->localName) {
                        case 'KeyName':
                            if (!empty($objBaseKey)) {
                                $objBaseKey->name = $child->nodeValue;
                            }
                            break;
                        case 'KeyValue':
                            foreach ($child->childNodes as $keyval) {
                                switch ($keyval->localName) {
                                    case 'DSAKeyValue':
                                        throw new Exception("DSAKeyValue currently not supported");
                                        break;
                                    case 'RSAKeyValue':
                                        $modulus = null;
                                        $exponent = null;
                                        if ($modulusNode = $keyval->getElementsByTagName('Modulus')->item(0)) {
                                            $modulus = base64_decode($modulusNode->nodeValue);
                                        }
                                        if ($exponentNode = $keyval->getElementsByTagName('Exponent')->item(0)) {
                                            $exponent = base64_decode($exponentNode->nodeValue);
                                        }
                                        if (empty($modulus) || empty($exponent)) {
                                            throw new Exception("Missing Modulus or Exponent");
                                        }
                                        $publicKey = XMLSecurityKey::convertRSA($modulus, $exponent);
                                        $objBaseKey->loadKey($publicKey);
                                        break;
                                }
                            }
                            break;
                        case 'RetrievalMethod':
                            /* Not currently supported */ break;
                        case 'EncryptedKey':
                            $objenc = new XMLSecEnc();
                            $objenc->setNode($child);
                            if (!$objKey = $objenc->locateKey()) {
                                throw new Exception("Unable to locate algorithm for this Encrypted Key");
                            }
                            $objKey->isEncrypted = true;
                            $objKey->encryptedCtx = $objenc;
                            XMLSecEnc::staticLocateKeyInfo($objKey, $child);

                            return $objKey;
                            break;
                        case 'X509Data':
                            if ($x509certNodes = $child->getElementsByTagName('X509Certificate')) {
                                if ($x509certNodes->length > 0) {
                                    $x509cert = $x509certNodes->item(0)->textContent;
                                    $x509cert = str_replace(["\r", "\n"], "", $x509cert);
                                    $x509cert = "-----BEGIN CERTIFICATE-----\n" . chunk_split($x509cert, 64,
                                            "\n") . "-----END CERTIFICATE-----\n";
                                    $objBaseKey->loadKey($x509cert, true);
                                }
                            }
                            break;
                    }
                }
            }

            return $objBaseKey;
        }

        return null;
    }

    public function locateKeyInfo($objBaseKey = null, $node = null)
    {
        if (empty($node)) {
            $node = $this->rawNode;
        }

        return XMLSecEnc::staticLocateKeyInfo($objBaseKey, $node);
    }
}
