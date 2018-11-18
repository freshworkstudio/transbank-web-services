<?php
/**
 * Class CaptureOutput
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayCaptureNullify
 * @author Luis Urrutia <luis@urrutia.me>
 * @version 1.1.0
 */

namespace Freshwork\Transbank\WebpayCaptureNullify;

/**
 * Class CaptureOutput
 *
 * @package Freshwork\Transbank\WebpayCaptureNullify
 */
class CaptureOutput
{
    /** @var string $authorizationCode Capture authorization code */
    public $authorizationCode;

    /** @var string $authorizationDate Date and time of capture authorization */
    public $authorizationDate;

    /** @var int|float $capturedAmount Captured amount */
    public $capturedAmount;

    /** @var string $token Webpay token */
    public $token;
}
