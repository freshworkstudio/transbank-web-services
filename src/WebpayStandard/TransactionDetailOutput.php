<?php
namespace Freshwork\Transbank\WebpayStandard;

/**
 * Class TransactionDetailOutput
 * @package Freshwork\Transbank\WebpayStandard
 */
class TransactionDetailOutput
{
    /**
     * @var string
     */
    public $authorizationCode;

    /**
     * @var string
     */
    public $paymentTypeCode;

    /**
     * @var integer
     */
    public $responseCode;

    /**
     * @var integer
     */
    public $sharesNumber;

    /**
     * @var string
     */
    public $amount;

    /**
     * @var string
     */
    public $commerceCode;

    /**
     * @var string
     */
    public $buyOrder;
}
