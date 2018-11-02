<?php
namespace Freshwork\Transbank\WebpayOneClick;

class OneClickPayOutput
{
    public $authorizationCode; // string
    public $creditCardType; // CreditCardType
    public $last4CardDigits; // string
    public $responseCode; // int
    public $transactionId; // long
}
