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
    public $sharesAmount;
    /**
     * @var int
     */
    public $sharesNumber;
    /**
     * @var float
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
