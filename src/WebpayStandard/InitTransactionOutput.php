<?php
/**
 * Clase InitTransactionOutput
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayStandard
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank\WebpayStandard;

/**
 * Clase InitTransactionOutput
 *
 * @package Freshwork\Transbank\WebpayStandard
 */
class InitTransactionOutput
{
    /** @var string $token Token provisto por Webpay para la transacción */
    public $token;

    /** @var string $url URL para redireccionar al cliente hacia Webpay */
    public $url;
}
