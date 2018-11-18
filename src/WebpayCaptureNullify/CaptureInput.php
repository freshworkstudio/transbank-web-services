<?php
/**
 * Class Capture
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayCaptureNullify
 * @author Luis Urrutia <luis@urrutia.me>
 * @version 1.1.0
 */

namespace Freshwork\Transbank\WebpayCaptureNullify;

/**
 * Class CaptureInput
 *
 * @package Freshwork\Transbank\WebpayCaptureNullify
 */
class CaptureInput
{
    /** @var int $commerceId Commerce's code */
    public $commerceId;

    /** @var string $buyOrder Order identifier */
    public $buyOrder;

    /** @var string $authorizationCode Transaction authorization code */
    public $authorizationCode;

    /** @var int|float $captureAmount Amount */
    public $captureAmount;
}
