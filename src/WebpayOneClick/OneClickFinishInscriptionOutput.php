<?php
/**
 * Class OneClickFinishInscriptionOutput
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayOneClick
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank\WebpayOneClick;

/**
 * Class OneClickFinishInscriptionOutput
 *
 * @package Freshwork\Transbank\WebpayOneClick
 */
class OneClickFinishInscriptionOutput
{

    /** @var string $authCode Authorization code of the inscription */
    public $authCode;

    /** @var string $creditCardType Customer's credit card brand */
    public $creditCardType;

    /** @var string $last4CardDigits Last 4 digits and/or BIN of the credit card */
    public $last4CardDigits;

    /** @var int $responseCode Response code of the inscription process */
    public $responseCode;

    /** @var string $tbkUser Unique customer inscription identifier */
    public $tbkUser;
}
