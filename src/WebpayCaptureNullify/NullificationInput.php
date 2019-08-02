<?php
/**
 * Class NullificationInput
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayCaptureNullify
 * @author Luis Urrutia <luis@urrutia.me>
 * @version 1.1.0
 */

namespace Freshwork\Transbank\WebpayCaptureNullify;

/**
 * Class NullificationInput
 *
 * @package Freshwork\Transbank\WebpayCaptureNullify
 */
class NullificationInput
{
    /** @var int $commerceId Commerce's code */
    public $commerceId;

    /** @var string $buyOrder Order identifier */
    public $buyOrder;

    /** @var int|float $authorizedAmount Transaction authorized amount */
    public $authorizedAmount;

    /** @var string $authorizationCode Transaction authorization code */
    public $authorizationCode;

    /** @var int|float $nullifyAmount Amount to nullify */
    public $nullifyAmount;
}
