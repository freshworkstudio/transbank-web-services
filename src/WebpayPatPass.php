<?php
/**
 * Clase WebpayPatPass
 *
 * @package Freshwork\Transbank
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank;

/**
 * Implementación de servicios PatPass
 *
 * Clase WebpayPatPass
 * @package Freshwork\Transbank
 */
class WebpayPatPass extends WebpayWebService
{
    /**
     * Inicializa una transacción de suscripción de pago
     *
     * @param string $returnURL URL del comercio, a la cual Webpay redireccionará posterior al proceso de autorización
     * @param string $finalURL URL del comercio a la cual Webpay redireccionará posterior al voucher de éxito de Webpay
     * @param string|null $sessionId Identificador de sesión, uso interno de comercio
     * @return WebpayStandard\InitTransactionOutput
     * @throws Exceptions\EmptyTransactionException
     */
    public function initTransaction($returnURL, $finalURL, $sessionId = null)
    {
        return parent::initTransaction($returnURL, $finalURL, $sessionId, self::PATPASS);
    }
}
