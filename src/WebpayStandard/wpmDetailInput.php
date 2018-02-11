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
    var $serviceId;
    /**
     * @var string
     */
    var $cardHolderId;
    /**
     * @var string
     */
    var $cardHolderName;
    /**
     * @var string
     */
    var $cardHolderLastName1;
    /**
     * @var string
     */
    var $cardHolderLastName2;
    /**
     * @var string
     */
    var $cardHolderMail;
    /**
     * @var string
     */
    var $cellPhoneNumber;
    /**
     * @var string
     */
    var $expirationDate;
    /**
     * @var string
     */
    var $commerceMail;
    /**
     * @var boolean
     */
    var $ufFlag;
}