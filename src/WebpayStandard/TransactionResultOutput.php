<?php
/**
 * Clase TransactionResultOutput
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayStandard
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank\WebpayStandard;

/**
 * Clase TransactionResultOutput
 *
 * @package Freshwork\Transbank\WebpayStandard
 */
class TransactionResultOutput
{
    /** @var string $accountingDate Fecha de la autorización */
    public $accountingDate;

    /** @var string $buyOrder Orden de compra de la tienda */
    public $buyOrder;

    /** @var CardDetail Datos de la tarjeta de crédito del tarjeta habiente */
    public $cardDetail;

    /** @var TransactionDetailOutput $detailOutput Resultado de cada TransactionDetail */
    public $detailOutput;

    /** @var string $sessionId Identificador de sesión */
    public $sessionId;

    /** @var string $transactionDate Fecha y hora de la autorización */
    public $transactionDate;

    /** @var string $urlRedirection URL de redirección para visualización de voucher */
    public $urlRedirection;

    /** @var string $VCI Resultado de la autenticación del tarjetahabiente */
    public $VCI;
}
