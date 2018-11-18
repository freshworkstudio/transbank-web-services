<?php
/**
 * Class InitTransactionResponse
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayStandard
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank\WebpayStandard;

/**
 * Class InitTransactionResponse
 * @package Freshwork\Transbank\WebpayStandard
 */
class InitTransactionResponse
{
    /** @var InitTransactionOutput $return Details for Webpay redirection */
    public $return;
}
