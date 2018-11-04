<?php
/**
 * Clase TransactionDetail
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayStandard
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank\WebpayStandard;

/**
 * Clase TransactionDetail
 *
 * @package Freshwork\Transbank\WebpayStandard
 */
class TransactionDetail
{
    /** @var float $sharesAmount Monto de las cuotas */
    public $sharesAmount;

    /** @var int $sharesNumber Cantidad de cuotas */
    public $sharesNumber;

    /** @var int|float $amount Monto de la transacción */
    public $amount;

    /** @var string $commerceCode Identificador del comercio */
    public $commerceCode;

    /** @var string $buyOrder Orden de compra de la tienda */
    public $buyOrder;
}
