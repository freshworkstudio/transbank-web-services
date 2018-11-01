<?php
namespace Freshwork\Transbank\WebpayStandard;

/**
 * Class TransactionDetail
 * @package Freshwork\Transbank\WebpayStandard
 */
class TransactionDetail
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
