<?php
/**
 * Clase Capture
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayCaptureNullify
 * @author Luis Urrutia <luis@urrutia.me>
 * @version 1.1.0
 */

namespace Freshwork\Transbank\WebpayCaptureNullify;

/**
 * Clase CaptureInput
 *
 * @package Freshwork\Transbank\WebpayCaptureNullify
 */
class CaptureInput
{
    /** @var int $commerceId Código de comercio o tienda mall que realizó la transacción */
    public $commerceId;

    /** @var string $buyOrder Orden de compra de la transacción que se requiere capturar */
    public $buyOrder;

    /** @var string $authorizationCode Código de autorización de la transacción que se requiere capturar */
    public $authorizationCode;

    /** @var int|float $captureAmount Monto que se desea capturar */
    public $captureAmount;
}
