<?php
/**
 * Class TransactionResultOutput
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayStandard
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank\WebpayStandard;

/**
 * Class TransactionResultOutput
 *
 * @package Freshwork\Transbank\WebpayStandard
 */
class TransactionResultOutput
{
    /** @var string $accountingDate Authorization date */
    public $accountingDate;

    /** @var string $buyOrder Order identifier */
    public $buyOrder;

    /** @var CardDetail Customer's credit card details */
    public $cardDetail;

    /** @var TransactionDetailOutput $detailOutput Result of every TransactionDetail */
    public $detailOutput;

    /** @var string $sessionId Session identifier */
    public $sessionId;

    /** @var string $transactionDate Date and time of authorization */
    public $transactionDate;

    /** @var string $urlRedirection URL redirection for Webpay voucher */
    public $urlRedirection;

    /** @var string $VCI Result of customer authentication */
    public $VCI;
}
