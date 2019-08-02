<?php
/**
 * Class InitTransactionOutput
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayStandard
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank\WebpayStandard;

/**
 * Class InitTransactionOutput
 *
 * @package Freshwork\Transbank\WebpayStandard
 */
class InitTransactionOutput
{
    /** @var string $token Webpay token */
    public $token;

    /** @var string $url URL to redirect the client to Webpay */
    public $url;
}
