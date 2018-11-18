<?php
/**
 * Class InitTransactionInput
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayStandard
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank\WebpayStandard;

/**
 * Class InitTransactionInput
 *
 * @package Freshwork\Transbank\WebpayStandard
 */
class InitTransactionInput
{

    /** @var string $wSTransactionType Transaction type */
    public $wSTransactionType;

    /** @var string $commerceId Commerce's code */
    public $commerceId;

    /** @var string $buyOrder Order identifier */
    public $buyOrder;

    /** @var string $sessionId Session identifier, internal use of commerce */
    public $sessionId;

    /** @var string $returnURL URL of the commerce, to which Webpay will redirect after the authorization process */
    public $returnURL;

    /** @var string $finalURL URL of the commerce, to which Webpay will redirect subsequent to Webpay's voucher */
    public $finalURL;

    /** @var TransactionDetail[] List of transaction details */
    public $transactionDetails;

    /** @var DetailInput $wPMDetail Subscribing customer details */
    public $wPMDetail;
}
