<?php
/**
 * Clase CardDetail
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayStandard
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank\WebpayStandard;

/**
 * Clase CardDetail
 *
 * @package Freshwork\Transbank\WebpayStandard
 */
class CardDetail
{
    /** @var string $cardNumber Últimos 4 dígitos o numeración completa de la tarjeta */
    public $cardNumber;

    /** @var string $cardExpirationDate Fecha de expiración de la tarjeta */
    public $cardExpirationDate;
}
