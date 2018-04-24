<?php
namespace Freshwork\Transbank;

class WebpayPatPass extends WebpayWebService
{
    public function init($returnURL, $finalURL, $sessionId = null)
    {
        return $this->initTransaction($returnURL, $finalURL, $sessionId, self::PATPASS);
    }
}
