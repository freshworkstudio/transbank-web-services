<?php
namespace Freshwork\Transbank\WebpayOneClick;

class OneClickFinishInscriptionOutput
{

    /** @var string */
    public $authCode;

    /** @var string CreditCardType */
    public $creditCardType;

    /** @var string */
    public $last4CardDigits;

    /** @var int */
    public $responseCode;

    /** @var string */
    public $tbkUser;
}
