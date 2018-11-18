<?php
/**
 * Class CardDetail
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayStandard
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank\WebpayStandard;

/**
 * Class CardDetail
 *
 * @package Freshwork\Transbank\WebpayStandard
 */
class CardDetail
{
    /** @var string $cardNumber Last 4 digits or full card numbering */
    public $cardNumber;

    /** @var string $cardExpirationDate Card expiration date */
    public $cardExpirationDate;
}
