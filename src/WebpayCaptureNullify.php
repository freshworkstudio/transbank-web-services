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
    protected $service;

    public function __construct(WebpayCaptureNullifyWebService $service)
    {
        $this->service = $service;
    }

    /**
     * Capture amount
     *
     * @param $authorizationCode
     * @param $buyOrder
     * @param $captureAmount
     * @param $commerceCode
     * @return WebpayCaptureNullify\CaptureOutput
     * @throws Exceptions\InvalidCertificateException
     * @throws \SoapFault
     */
    public function capture(
        $authorizationCode,
        $buyOrder,
        $captureAmount,
        $commerceCode = null
    ) {
        $capture = new CaptureInput();
        $capture->authorizationCode = $authorizationCode;
        $capture->buyOrder = $buyOrder;
        $capture->captureAmount = $captureAmount;
        $capture->commerceId = $commerceCode ? $commerceCode : SecurityHelper::getCommonName(
            $this->service->getCertificationBag()->getClientCertificate()
        );

        return $this->service->capture($capture);
    }

    /**
     * Nullify a credit card based transaction
     *
     * @param $authorizationCode
     * @param $authorizedAmount
     * @param $buyOrder
     * @param $nullifyAmount
     * @param $commerceCode
     * @return WebpayCaptureNullify\NullificationOutput
     * @throws Exceptions\InvalidCertificateException
     * @throws \SoapFault
     */
    public function nullify(
        $authorizationCode,
        $authorizedAmount,
        $buyOrder,
        $nullifyAmount,
        $commerceCode = null
    ) {
        $nullify = new NullificationInput();
        $nullify->authorizationCode = $authorizationCode;
        $nullify->authorizedAmount = $authorizedAmount;
        $nullify->buyOrder = $buyOrder;
        $nullify->nullifyAmount = $nullifyAmount;
        $nullify->commerceId = $commerceCode ? $commerceCode : SecurityHelper::getCommonName(
            $this->service->getCertificationBag()->getClientCertificate()
        );;

        return $this->service->nullify($nullify);
    }
}
