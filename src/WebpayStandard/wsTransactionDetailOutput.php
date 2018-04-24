<?php
namespace Freshwork\Transbank\WebpayStandard;

/**
 * Class wsTransactionDetailOutput
 * @package Freshwork\Transbank\WebpayStandard
 */
class wsTransactionDetailOutput
{
    /**
     * @var string
     */
    public $authorizationCode; //string
    /**
     * @var string
     */
    public $paymentTypeCode; //string
    /**
     * @var integer
     */
    public $responseCode; //int
}
