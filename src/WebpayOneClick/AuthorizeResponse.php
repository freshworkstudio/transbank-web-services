<?php
/**
 * Clase AuthorizeResponse
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayOneClick
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank\WebpayOneClick;

/**
 * Clase AuthorizeResponse
 * @package Freshwork\Transbank\WebpayOneClick
 */
class AuthorizeResponse
{
    /** @var OneClickPayOutput $return Detalles de la autorización del pago */
    public $return;
}
