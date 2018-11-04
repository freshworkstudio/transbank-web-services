<?php
/**
 * Clase InitTransactionResponse
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayStandard
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank\WebpayStandard;

/**
 * Clase InitTransactionResponse
 * @package Freshwork\Transbank\WebpayStandard
 */
class InitTransactionResponse
{
    /** @var InitTransactionOutput $return Detalles para la redirección hacia Webpay */
    public $return;
}
