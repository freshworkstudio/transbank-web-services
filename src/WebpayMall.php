<?php
/**
 * Class WebpayMall
 *
 * @package Freshwork\Transbank
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1.3 (07/07/2016)
 */

namespace Freshwork\Transbank;

/**
 * Class WebpayMall
 *
 * @package Freshwork\Transbank
 */
class WebpayMall extends WebpayWebService
{
    /**
     * Initialize a Mall transaction on Webpay
     *
     * @param string $returnURL URL of the commerce, to which Webpay will redirect after the authorization process
     * @param string $finalURL URL of the commerce, to which Webpay will redirect subsequent to Webpay's success voucher
     * @param string|null $buyOrder Order identifier
     * @param string|null $sessionId Session identifier, internal use of commerce
     * @param string|null $commerceCode Commerce's code
     *
     * @return WebpayStandard\InitTransactionOutput
     * @throws Exceptions\EmptyTransactionException
     * @throws Exceptions\InvalidCertificateException
     * @throws \SoapFault
     */
    public function initTransaction($returnURL, $finalURL, $buyOrder, $sessionId = null, $commerceCode = null)
    {
        return parent::initTransaction($returnURL, $finalURL, $sessionId, self::TIENDA_MALL, $buyOrder, $commerceCode);
    }
}
