<?php
namespace Freshwork\Transbank\WebpayOneClick;

class oneClickPayOutput
{
    var $authorizationCode;//string
    var $creditCardType;//creditCardType
    var $last4CardDigits;//string
    var $responseCode;//int
    var $transactionId;//long
}