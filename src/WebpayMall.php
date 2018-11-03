<?php
/**
 * Clase WebpayMall
 *
 * @package Freshwork\Transbank
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1.3 (07/07/2016)
 */

namespace Freshwork\Transbank;

/**
 * Clase WebpayMall
 *
 * @package Freshwork\Transbank
 */
class WebpayMall extends WebpayWebService
{
    /**
     * Inicializa una transacción Mall en Webpay
     *
     * @param string $returnURL URL del comercio, a la cual Webpay redireccionará posterior al proceso de autorización
     * @param string $finalURL URL del comercio a la cual Webpay redireccionará posterior al voucher de éxito de Webpay
     * @param string|null $buyOrder Orden de compra de la tienda
     * @param string|null $sessionId Identificador de sesión, uso interno de comercio
     * @param string|null $commerceCode Código del comercio
     *
     * @return WebpayStandard\InitTransactionOutput
     * @throws EmptyTransactionException
     */
    public function initTransaction($returnURL, $finalURL, $buyOrder, $sessionId = null, $commerceCode = null)
    {
        return parent::initTransaction($returnURL, $finalURL, $sessionId, self::TIENDA_MALL, $buyOrder, $commerceCode);
    }
}
