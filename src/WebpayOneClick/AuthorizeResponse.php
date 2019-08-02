<?php
/**
 * Class AuthorizeResponse
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayOneClick
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank\WebpayOneClick;

/**
 * Class AuthorizeResponse
 * @package Freshwork\Transbank\WebpayOneClick
 */
class AuthorizeResponse
{
    /** @var OneClickPayOutput $return Payment authorization details */
    public $return;
}
