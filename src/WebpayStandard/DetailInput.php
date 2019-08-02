<?php
/**
 * Class DetailInput
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayStandard
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank\WebpayStandard;

/**
 * Class DetailInput
 *
 * @package Freshwork\Transbank\WebpayStandard
 */
class DetailInput
{
    /** @var string $serviceId Service identifier */
    public $serviceId;

    /** @var string $cardHolderId Customer's national identifier number */
    public $cardHolderId;

    /** @var string $cardHolderName Customer's names */
    public $cardHolderName;

    /** @var string $cardHolderLastName1 Customer's paternal surname */
    public $cardHolderLastName1;

    /** @var string $cardHolderLastName2 Customer's maternal surname */
    public $cardHolderLastName2;

    /** @var string $cardHolderMail Customer's e-mail */
    public $cardHolderMail;

    /** @var string $cellPhoneNumber Customer's phone number */
    public $cellPhoneNumber;

    /** @var string $expirationDate Expiry date of the subscription */
    public $expirationDate;

    /** @var string $commerceMail Commerce e-mail */
    public $commerceMail;

    /** @var string $ufFlag Indicates if the payment is made in UF */
    public $ufFlag;
}
