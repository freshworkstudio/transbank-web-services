<?php
/**
 * Clase OneClickPayInput
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayOneClick
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank\WebpayOneClick;

/**
 * Clase OneClickPayInput
 *
 * @package Freshwork\Transbank\WebpayOneClick
 */
class OneClickPayInput
{

    /** @var int|float $amount Monto del pago en pesos */
    public $amount;

    /** @var int $buyOrder Identificador de la compra. Timestamp [yyyymmddhhMMss] + correlativo de tres dígitos */
    public $buyOrder;

    /** @var string $tbkUser Identificador único de la inscripción del cliente  */
    public $tbkUser;

    /** @var string $username Identificador único del cliente en el comercio */
    public $username;
}
