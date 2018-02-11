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
    var $authorizationCode; //string
    /**
     * @var string
     */
    var $paymentTypeCode; //string
    /**
     * @var integer
     */
    var $responseCode; //int
}