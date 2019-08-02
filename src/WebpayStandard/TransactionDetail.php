<?php
/**
 * Class TransactionDetail
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayStandard
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank\WebpayStandard;

/**
 * Class TransactionDetail
 *
 * @package Freshwork\Transbank\WebpayStandard
 */
class TransactionDetail
{
    /** @var float $sharesAmount Shares amount */
    public $sharesAmount;

    /** @var int $sharesNumber Shares number */
    public $sharesNumber;

    /** @var int|float $amount Amount */
    public $amount;

    /** @var string $commerceCode Commerce's code */
    public $commerceCode;

    /** @var string $buyOrder Order identifier */
    public $buyOrder;
}
