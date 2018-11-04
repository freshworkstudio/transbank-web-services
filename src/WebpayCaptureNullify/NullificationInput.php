<?php
/**
 * Clase NullificationInput
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayCaptureNullify
 * @author Luis Urrutia <luis@urrutia.me>
 * @version 1.1.0
 */

namespace Freshwork\Transbank\WebpayCaptureNullify;

/**
 * Clase NullificationInput
 *
 * @package Freshwork\Transbank\WebpayCaptureNullify
 */
class NullificationInput
{
    /** @var int $commerceId Código de comercio o tienda mall que realizó la transacción */
    public $commerceId;

    /** @var string $buyOrder Orden de compra de la transacción que se requiere anular */
    public $buyOrder;

    /** @var int|float $authorizedAmount Monto autorizado de la transacción que se requiere anular */
    public $authorizedAmount;

    /** @var string $authorizationCode Código de autorización de la transacción que se requiere anular */
    public $authorizationCode;

    /** @var int|float $nullifyAmount Monto que se desea anular de la transacción */
    public $nullifyAmount;
}
