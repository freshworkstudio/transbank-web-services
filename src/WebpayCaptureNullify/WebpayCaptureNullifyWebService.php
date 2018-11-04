<?php
/**
 * Clase WebpayCaptureNullifyWebService
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayCaptureNullify
 * @author Luis Urrutia <luis@urrutia.me>
 * @version 1.1.0
 */

namespace Freshwork\Transbank\WebpayCaptureNullify;

use Freshwork\Transbank\TransbankWebService;

/**
 * Clase WebpayCaptureNullifyWebService
 *
 * @package Freshwork\Transbank\WebpayCaptureNullify
 */
class WebpayCaptureNullifyWebService extends TransbankWebService
{
    /** @const INTEGRATION_WSDL URL del WSDL de integración */
    const INTEGRATION_WSDL  = 'https://tbk.orangepeople.cl/WSWebpayTransaction/cxf/WSCommerceIntegrationService?wsdl';

    /** @const PRODUCTION_WSDL URL del WSDL de producción */
    const PRODUCTION_WSDL   = 'https://webpay3g.transbank.cl/WSWebpayTransaction/cxf/WSCommerceIntegrationService?wsdl';

    /** @var array $classmap Listado de asociaciones de tipos del WSDL a clases */
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
     * Anular una venta normal
     *
     * @param NullificationInput $nullificationInput
     * @return mixed
     * @throws \SoapFault
     */
    public function nullify(NullificationInput $nullificationInput)
    {
        $nullify = new Nullify();
        $nullify->nullificationInput = $nullificationInput;

        return $this->callSoapMethod('nullify', $nullify);
    }

    /**
     * Realiza una única captura por autorización
     *
     * @param CaptureInput $captureInput
     * @return mixed
     * @throws \SoapFault
     */
    public function capture(CaptureInput $captureInput)
    {
        $capture = new Capture();
        $capture->captureInput = $captureInput;

        return $this->callSoapMethod('capture', $capture);
    }
}
