<?php
/**
 * Class WebpayCaptureNullifyWebService
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayCaptureNullify
 * @author Luis Urrutia <luis@urrutia.me>
 * @version 1.1.0
 */

namespace Freshwork\Transbank\WebpayCaptureNullify;

use Freshwork\Transbank\TransbankWebService;

/**
 * Class WebpayCaptureNullifyWebService
 *
 * @package Freshwork\Transbank\WebpayCaptureNullify
 */
class WebpayCaptureNullifyWebService extends TransbankWebService
{
    /** @const INTEGRATION_WSDL Development WSDL URL */
    const INTEGRATION_WSDL  = 'https://webpay3gint.transbank.cl/WSWebpayTransaction/cxf/WSCommerceIntegrationService?wsdl';

    /** @const PRODUCTION_WSDL Production WSDL URL */
    const PRODUCTION_WSDL   = 'https://webpay3g.transbank.cl/WSWebpayTransaction/cxf/WSCommerceIntegrationService?wsdl';

    /** @var array $classmap Association of WSDL types to classes */
    protected static $classmap = array(
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
     * Nullify a credit card based transaction
     *
     * @param NullificationInput $nullificationInput
     * @return mixed
     * @throws \Freshwork\Transbank\Exceptions\InvalidCertificateException
     * @throws \SoapFault
     */
    public function nullify(NullificationInput $nullificationInput)
    {
        $nullify = new Nullify();
        $nullify->nullificationInput = $nullificationInput;

        return $this->callSoapMethod('nullify', $nullify);
    }

    /**
     * Capture amount
     *
     * @param CaptureInput $captureInput
     * @return mixed
     * @throws \Freshwork\Transbank\Exceptions\InvalidCertificateException
     * @throws \SoapFault
     */
    public function capture(CaptureInput $captureInput)
    {
        $capture = new Capture();
        $capture->captureInput = $captureInput;

        return $this->callSoapMethod('capture', $capture);
    }
}
