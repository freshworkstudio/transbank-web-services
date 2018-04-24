<?php
namespace Freshwork\Transbank\WebpayStandard;

/**
 * Class wpmDetailInput
 * @package Freshwork\Transbank\WebpayStandard
 */
class wpmDetailInput
{
    /**
     * @var string
     */
    public $serviceId;
    /**
     * @var string
     */
    public $cardHolderId;
    /**
     * @var string
     */
    public $cardHolderName;
    /**
     * @var string
     */
    public $cardHolderLastName1;
    /**
     * @var string
     */
    public $cardHolderLastName2;
    /**
     * @var string
     */
    public $cardHolderMail;
    /**
     * @var string
     */
    public $cellPhoneNumber;
    /**
     * @var string
     */
    public $expirationDate;
    /**
     * @var string
     */
    public $commerceMail;
    /**
     * @var boolean
     */
    public $ufFlag;
}
