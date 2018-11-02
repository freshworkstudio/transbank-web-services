<?php
namespace Freshwork\Transbank;

use Freshwork\Transbank\WebpayCaptureNullify\CaptureInput;
use Freshwork\Transbank\WebpayCaptureNullify\NullificationInput;
use Freshwork\Transbank\WebpayCaptureNullify\WebpayCaptureNullifyWebService;

class WebpayCaptureNullify extends TransbankService
{
    /**
     * @var WebpayCaptureNullifyWebService
     */
    private $service;

    public function __construct(WebpayCaptureNullifyWebService $service)
    {
        $this->service = $service;
    }

    /**
     * Método que permite realizar la captura de cierto monto en una transacción diferida
     *
     * @param $authorizationCode
     * @param $buyOrder
     * @param $captureAmount
     * @param $commerceId
     * @return Freshwork\Transbank\WebpayCaptureNullify\CaptureOutput
     * @throws \SoapFault
     */
    public function capture(
        $authorizationCode,
        $buyOrder,
        $captureAmount,
        $commerceId
    ) {
        $capture = new CaptureInput();
        $capture->authorizationCode = $authorizationCode;
        $capture->buyOrder = $buyOrder;
        $capture->captureAmount = $captureAmount;
        $capture->commerceId = $commerceId;

        return $this->service->capture($capture);
    }

    /**
     * Método que permite anular total o parcialmente una transacción
     *
     * @param $authorizationCode
     * @param $authorizedAmount
     * @param $buyOrder
     * @param $nullifyAmount
     * @param $commerceId
     * @return WebpayCaptureNullify\NullificationOutput
     * @throws \SoapFault
     */
    public function nullify(
        $authorizationCode,
        $authorizedAmount,
        $buyOrder,
        $nullifyAmount,
        $commerceId
    ) {
        $nullify = new NullificationInput();
        $nullify->authorizationCode = $authorizationCode;
        $nullify->authorizedAmount = $authorizedAmount;
        $nullify->buyOrder = $buyOrder;
        $nullify->nullifyAmount = $nullifyAmount;
        $nullify->commerceId = $commerceId;

        return $this->service->nullify($nullify);
    }
}
