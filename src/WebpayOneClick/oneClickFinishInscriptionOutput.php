<?php
namespace Freshwork\Transbank\WebpayOneClick;

class oneClickFinishInscriptionOutput
{

    /** @var string */
    public $authCode;

    /** @var string creditCardType */
    public $creditCardType;

    /** @var string */
    public $last4CardDigits;

    /** @var int */
    public $responseCode;

    /** @var string */
    public $tbkUser;
}
