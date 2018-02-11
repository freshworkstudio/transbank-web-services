<?php
namespace Freshwork\Transbank\WebpayStandard;

/**
 * Class wsTransactionDetail
 * @package Freshwork\Transbank\WebpayStandard
 */
class wsTransactionDetail

{
    /**
     * @var float
     */
    var $sharesAmount;
    /**
     * @var int
     */
    var $sharesNumber;
    /**
     * @var float
     */
    var $amount;
    /**
     * @var string
     */
    var $commerceCode;
    /**
     * @var string
     */
    var $buyOrder;
}