<?php
/**
 * Class WebpayStandardWebService
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayStandard
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank\WebpayStandard;

use Freshwork\Transbank\TransbankWebService;

/**
 * Class WebpayStandardWebService
 *
 * @package Freshwork\Transbank\WebpayStandard
 */
class WebpayStandardWebService extends TransbankWebService
{

    /** @const INTEGRATION_WSDL Development WSDL URL */
    const INTEGRATION_WSDL  = 'https://webpay3gint.transbank.cl/WSWebpayTransaction/cxf/WSWebpayService?wsdl';

    /** @const PRODUCTION_WSDL Production WSDL URL */
    const PRODUCTION_WSDL   = 'https://webpay3g.transbank.cl/WSWebpayTransaction/cxf/WSWebpayService?wsdl';

    /** @var array $classmap Association of WSDL types to classes */
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
     * Initialize a Webpay transaction
     *
     * @param InitTransactionInput $initTransactionInput Transaction information
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
     * Get the transaction result once Webpay has resolved the financial authorization
     *
     * @param string $token Webpay token
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
     * Indicates to Webpay that the transaction result was received
     *
     * @param string $token Webpay token
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
