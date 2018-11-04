<?php
/**
 * Clase SecurityHelper
 *
 * @package Freshwork\Transbank
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank;

/**
 * Clase SecurityHelper
 *
 * @package Freshwork\Transbank
 */
class SecurityHelper
{
    /**
     * @param string $X509Cert Ruta o contenido del certificado público
     * @return string
     */
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
