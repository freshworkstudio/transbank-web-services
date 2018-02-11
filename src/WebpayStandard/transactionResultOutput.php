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
    var $accountingDate;
    /**
     * @var string
     */
    var $buyOrder;
    /**
     * @var cardDetail
     */
    var $cardDetail;
    /**
     * @var wsTransactionDetailOutput
     */
    var $detailOutput;
    /**
     * @var string
     */
    var $sessionId;
    /**
     * @var string
     */
    var $transactionDate;
    /**
     * @var string
     */
    var $urlRedirection;
    /**
     * @var string
     */
    var $VCI;
}