<?php
/**
 * Clase OneClickPayOutput
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayOneClick
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank\WebpayOneClick;

/**
 * Clase OneClickPayOutput
 *
 * @package Freshwork\Transbank\WebpayOneClick
 */
class OneClickPayOutput
{
    /** @var string $authorizationCode Código de autorización de la transacción de pago */
    public $authorizationCode;

    /** @var string $creditCardType Indica el tipo de tarjeta que fue inscrita por el cliente  */
    public $creditCardType;

    /** @var string $last4CardDigits Los últimos 4 dígitos de la tarjeta usada en la transacción */
    public $last4CardDigits;

    /** @var int $responseCode Código de retorno del proceso de pago */
    public $responseCode;

    /** @var int $transactionId Identificador único de la transacción de pago */
    public $transactionId;
}
