<?php
/**
 * Clase WebpayStandardWebService
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayStandard
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank\WebpayStandard;

use Freshwork\Transbank\TransbankWebService;

/**
 * Clase WebpayStandardWebService
 *
 * @package Freshwork\Transbank\WebpayStandard
 */
class WebpayStandardWebService extends TransbankWebService
{

    /** @const INTEGRATION_WSDL URL del WSDL de integración */
    const INTEGRATION_WSDL  = 'https://webpay3gint.transbank.cl/WSWebpayTransaction/cxf/WSWebpayService?wsdl';

    /** @const PRODUCTION_WSDL URL del WSDL de producción */
    const PRODUCTION_WSDL   = 'https://webpay3g.transbank.cl/WSWebpayTransaction/cxf/WSWebpayService?wsdl';

    /** @var array $classmap Listado de asociaciones de tipos del WSDL a clases */
    protected static $classmap = [
        'initTransaction' => InitTransaction::class,
        'initTransactionResponse' => InitTransactionResponse::class,
        'wsInitTransactionInput' => InitTransactionInput::class,
        'wsInitTransactionOutput' => InitTransactionOutput::class,
        'wpmDetailInput' => DetailInput::class,
        'getTransactionResult' => TransactionResult::class,
        'getTransactionResultResponse' => TransactionResultResponse::class,
        'transactionResultOutput' => TransactionResultOutput::class,
        'cardDetail' => CardDetail::class,
        'wsTransactionDetailOutput' => TransactionDetailOutput::class,
        'wsTransactionDetail' => TransactionDetail::class,
        'acknowledgeTransaction' => AcknowledgeTransaction::class,
        'acknowledgeTransactionResponse' => AcknowledgeTransactionResponse::class,
    ];

    /**
     * Permite inicializar una transacción en Webpay
     *
     * @param InitTransactionInput $initTransactionInput Datos de la transacción
     * @return mixed
     * @throws \Freshwork\Transbank\Exceptions\InvalidCertificateException
     * @throws \SoapFault
     */
    public function initTransaction(InitTransactionInput $initTransactionInput)
    {
        $initInscription = new InitTransaction();
        $initInscription->wsInitTransactionInput = $initTransactionInput;

        return $this->callSoapMethod('initTransaction', $initInscription);
    }

    /**
     * Obtiene el resultado de la transacción una vez que Webpay ha resuelto su autorización financiera
     *
     * @param string $token Token de la transacción
     * @return mixed
     * @throws \Freshwork\Transbank\Exceptions\InvalidCertificateException
     * @throws \SoapFault
     */
    public function getTransactionResult($token)
    {
        $transactionResult = new TransactionResult();
        $transactionResult->tokenInput = $token;

        return $this->callSoapMethod('getTransactionResult', $transactionResult);
    }

    /**
     * Indica a Webpay que se ha recibido conforme el resultado de la transacción
     *
     * @param string $token Token de la transacción
     * @return mixed
     * @throws \Freshwork\Transbank\Exceptions\InvalidCertificateException
     * @throws \SoapFault
     */
    public function acknowledgeTransaction($token)
    {
        $acknowledgeTransactionInput = new AcknowledgeTransaction();
        $acknowledgeTransactionInput->tokenInput = $token;

        return $this->callSoapMethod('acknowledgeTransaction', $acknowledgeTransactionInput);
    }
}
