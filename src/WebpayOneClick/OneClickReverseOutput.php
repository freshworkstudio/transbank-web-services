<?php
/**
 * Class OneClickReverseOutput
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayOneClick
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank\WebpayOneClick;

/**
 * Class OneClickReverseOutput
 *
 * @package Freshwork\Transbank\WebpayOneClick
 */
class OneClickReverseOutput
{
    /** @var int $reverseCode Unique identifier of reverse */
    public $reverseCode;

    /** @var bool $reversed Reverse result */
    public $reversed;
}
