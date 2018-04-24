<?php
namespace Freshwork\Transbank\WebpayStandard;

/**
 * Class transactionResultOutput
 * @package Freshwork\Transbank\WebpayStandard
 */
class transactionResultOutput
{
    /**
     * @var string
     */
    public $accountingDate;
    /**
     * @var string
     */
    public $buyOrder;
    /**
     * @var cardDetail
     */
    public $cardDetail;
    /**
     * @var wsTransactionDetailOutput
     */
    public $detailOutput;
    /**
     * @var string
     */
    public $sessionId;
    /**
     * @var string
     */
    public $transactionDate;
    /**
     * @var string
     */
    public $urlRedirection;
    /**
     * @var string
     */
    public $VCI;
}
