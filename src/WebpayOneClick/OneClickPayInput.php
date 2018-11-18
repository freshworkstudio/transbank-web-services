<?php
/**
 * Class OneClickPayInput
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayOneClick
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank\WebpayOneClick;

/**
 * Class OneClickPayInput
 *
 * @package Freshwork\Transbank\WebpayOneClick
 */
class OneClickPayInput
{

    /** @var int|float $amount Amount */
    public $amount;

    /** @var int $buyOrder Order identifier. Timestamp [yyyymmddhhMMss] + three-digit correlative */
    public $buyOrder;

    /** @var string $tbkUser Unique customer inscription identifier  */
    public $tbkUser;

    /** @var string $username Customer username */
    public $username;
}
