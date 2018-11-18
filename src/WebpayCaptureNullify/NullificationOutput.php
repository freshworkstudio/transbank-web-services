<?php
/**
 * Class NullificationOutput
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayCaptureNullify
 * @author Luis Urrutia <luis@urrutia.me>
 * @version 1.1.0
 */

namespace Freshwork\Transbank\WebpayCaptureNullify;

/**
 * Class NullificationOutput
 *
 * @package Freshwork\Transbank\WebpayCaptureNullify
 */
class NullificationOutput
{
    /** @var string $authorizationCode Nullification authorization code */
    public $authorizationCode;

    /** @var string $authorizationDate Date and time of authorization */
    public $authorizationDate;

    /** @var int|float $balance Remaining balance */
    public $balance;

    /** @var int|float $nullifiedAmount Nullified amount */
    public $nullifiedAmount;

    /** @var string $token Webpay token */
    public $token;
}
