<?php
namespace Freshwork\Transbank\WebpayCaptureNullify;

use Freshwork\Transbank\TransbankWebService;

/**
 * Class WebpayCaptureNullifyWebService
 * @package Freshwork\Transbank\WebpayCaptureNullify
 */
class WebpayCaptureNullifyWebService extends TransbankWebService
{
    /**
     * Integration URL
     */
    const INTEGRATION_WSDL  = 'https://tbk.orangepeople.cl/WSWebpayTransaction/cxf/WSCommerceIntegrationService?wsdl';

    /**
     * Production URL
     */
    const PRODUCTION_WSDL   = 'https://webpay3g.transbank.cl/WSWebpayTransaction/cxf/WSCommerceIntegrationService?wsdl';

    /**
     * @var array
     */
    private static $classmap = array(
        'nullify' => Nullify::class,
        'nullificationInput' => NullificationInput::class,
        'baseBean' => BaseBean::class,
        'nullifyResponse' => NullifyResponse::class,
        'nullificationOutput' => NullificationOutput::class,
        'capture' => Capture::class,
        'captureInput' => CaptureInput::class,
        'captureResponse' => CaptureResponse::class,
        'captureOutput' => CaptureOutput::class
    );

    /**
     * Método que permite anular una venta normal
     *
     * @param NullificationInput $nullificationInput
     * @return mixed
     * @throws \SoapFault
     */
    public function nullify(NullificationInput $nullificationInput)
    {
        $nullify = new Nullify();
        $nullify->nullificationInput = $nullificationInput;

        return $this->callSoapMethod('nullify', $initInscription);
    }

    /**
     * Método que permite realizar una única captura por autorización
     *
     * @param CaptureInput $captureInput
     * @return mixed
     * @throws \SoapFault
     */
    public function capture(CaptureInput $captureInput)
    {
        $capture = new Capture();
        $capture->captureInput = $captureInput;

        return $this->callSoapMethod('capture', $initInscription);
    }
}
