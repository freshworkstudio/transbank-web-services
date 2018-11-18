<?php
/**
 * Class OneClickPayOutput
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayOneClick
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank\WebpayOneClick;

/**
 * Class OneClickPayOutput
 *
 * @package Freshwork\Transbank\WebpayOneClick
 */
class OneClickPayOutput
{
    /** @var string $authorizationCode Payment authorization code */
    public $authorizationCode;

    /** @var string $creditCardType Customer's credit card brand */
    public $creditCardType;

    /** @var string $last4CardDigits Last 4 digits and/or BIN of the credit card */
    public $last4CardDigits;

    /** @var int $responseCode Response code of the payment process */
    public $responseCode;

    /** @var int $transactionId Unique transaction identifier */
    public $transactionId;
}
