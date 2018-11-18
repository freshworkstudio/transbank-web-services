<?php
/**
 * Class TransactionDetailOutput
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayStandard
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank\WebpayStandard;

/**
 * Class TransactionDetailOutput
 *
 * @package Freshwork\Transbank\WebpayStandard
 */
class TransactionDetailOutput
{
    /** @var string $authorizationCode Transaction authorization code */
    public $authorizationCode;

    /** @var string $paymentTypeCode Payment type */
    public $paymentTypeCode;

    /** @var int $responseCode Authorization response code */
    public $responseCode;

    /** @var int $sharesNumber Shares number */
    public $sharesNumber;

    /** @var int|float $amount Transaction amount */
    public $amount;

    /** @var string $commerceCode Commerce's code */
    public $commerceCode;

    /** @var string $buyOrder Order identifier */
    public $buyOrder;
}
