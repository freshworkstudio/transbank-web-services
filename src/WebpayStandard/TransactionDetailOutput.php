<?php
/**
 * Clase TransactionDetailOutput
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayStandard
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank\WebpayStandard;

/**
 * Clase TransactionDetailOutput
 *
 * @package Freshwork\Transbank\WebpayStandard
 */
class TransactionDetailOutput
{
    /** @var string $authorizationCode Código de autorización de la transacción */
    public $authorizationCode;

    /** @var string $paymentTypeCode Tipo de pago de la transacción */
    public $paymentTypeCode;

    /** @var int $responseCode Código de respuesta de la autorización */
    public $responseCode;

    /** @var int $sharesNumber Cantidad de cuotas */
    public $sharesNumber;

    /** @var int|float $amount Monto de la transacción */
    public $amount;

    /** @var string $commerceCode Identificador del comercio */
    public $commerceCode;

    /** @var string $buyOrder Orden de compra de la tienda */
    public $buyOrder;
}
