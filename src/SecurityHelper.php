<?php
namespace Freshwork\Transbank;

class SecurityHelper
{
    public static function getCommonName($X509Cert)
    {
        if (is_file($X509Cert)) {
            $handler = fopen($X509Cert, "r");
            $cert = fread($handler, 8192);
            fclose($handler);
        } else {
            $cert = $X509Cert;
        }

        $cert_as_array = openssl_x509_parse($cert);
        return $cert_as_array['subject']['CN'];
    }
}
