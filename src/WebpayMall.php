<?php
namespace Freshwork\Transbank;

class WebpayMall extends WebpayWebService
{
    public function init($returnURL, $finalURL, $buyOrder, $sessionId = null, $commerceCode = null)
    {
        return $this->initTransaction($returnURL, $finalURL, $sessionId, self::TIENDA_MALL, $buyOrder, $commerceCode);
    }
}
