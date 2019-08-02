<?php
/**
 * Class WebpayPatPass
 *
 * @package Freshwork\Transbank
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank;

/**
 * Implements PatPass services
 *
 * Class WebpayPatPass
 * @package Freshwork\Transbank
 */
class WebpayPatPass extends WebpayWebService
{
    /**
     * Initializes a paid subscription transaction
     *
     * @param string $returnURL URL of the commerce, to which Webpay will redirect after the authorization process
     * @param string $finalURL URL of the commerce, to which Webpay will redirect subsequent to Webpay's success voucher
     * @param string|null $sessionId Session identifier, internal use of commerce
     * @param string $transactionType
     * @param null $buyOrder
     * @param null $ecommerceCode
     * @return WebpayStandard\InitTransactionOutput
     * @throws Exceptions\EmptyTransactionException
     * @throws Exceptions\InvalidCertificateException
     * @throws \SoapFault
     */
    public function initTransaction($returnURL,
                                    $finalURL,
                                    $sessionId = null,
                                    $transactionType = self::TIENDA_NORMAL,
                                    $buyOrder = null,
                                    $ecommerceCode = NULL)
    {
        return parent::initTransaction($returnURL, $finalURL, $sessionId, self::PATPASS);
    }
}
