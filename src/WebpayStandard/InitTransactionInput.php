<?php
/**
 * Clase InitTransactionInput
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayStandard
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank\WebpayStandard;

/**
 * Clase InitTransactionInput
 *
 * @package Freshwork\Transbank\WebpayStandard
 */
class InitTransactionInput
{

    /** @var string $wSTransactionType Tipo de transacción */
    public $wSTransactionType;

    /** @var string $commerceId Identificador del comercio */
    public $commerceId;

    /** @var string $buyOrder Orden de compra de la tienda */
    public $buyOrder;

    /** @var string $sessionId Identificador de sesión, uso interno de comercio */
    public $sessionId;

    /** @var string $returnURL URL del comercio, a la cual Webpay redireccionará posterior al proceso de autorización */
    public $returnURL;

    /** @var string $finalURL URL del comercio a la cual Webpay redireccionará posterior al voucher de éxito de Webpay */
    public $finalURL;

    /** @var TransactionDetail[] Listado con los detalles de la transacción a realizar */
    public $transactionDetails;

    /** @var DetailInput $wPMDetail Detalles del cliente que se está suscribiendo */
    public $wPMDetail;
}
