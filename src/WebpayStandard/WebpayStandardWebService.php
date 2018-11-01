<?php
namespace Freshwork\Transbank\WebpayStandard;

use Freshwork\Transbank\TransbankWebService;

/**
 * Class WebpayNormal
 * @package Freshwork\Transbank\WebpayNormal
 */
class WebpayStandardWebService extends TransbankWebService
{
    /**
     * Integration URL
     */
    const INTEGRATION_WSDL  = 'https://webpay3gint.transbank.cl/WSWebpayTransaction/cxf/WSWebpayService?wsdl';

    /**
     * Production URL
     */
    const PRODUCTION_WSDL   = 'https://webpay3g.transbank.cl/WSWebpayTransaction/cxf/WSWebpayService?wsdl';

    /**
     * @var array
     */
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
     * Método que permite iniciar una transacción de pago Webpay.
     *
     * @param InitTransactionInput $initTransactionInput
     * @return InitTransactionResponse
     */
    public function initTransaction(InitTransactionInput $initTransactionInput)
    {
        $initInscription = new InitTransaction();
        $initInscription->wsInitTransactionInput = $initTransactionInput;

        return $this->callSoapMethod('initTransaction', $initInscription);
    }

    /**
     * Método que permite obtener el resultado de la transacción y los datos de la misma.
     *
     * @param string $token
     * @return TransactionResultResponse
     */
    public function getTransactionResult($token)
    {
        $transactionResult = new TransactionResult();
        $transactionResult->tokenInput = $token;

        return $this->callSoapMethod('getTransactionResult', $transactionResult);
    }

    /**
     * Método que permite informar a Webpay la correcta recepción del resultado de la transacción.
     *
     * @param string $token
     * @return AcknowledgeTransactionResponse
     */
    public function acknowledgeTransaction($token)
    {
        $acknowledgeTransactionInput = new AcknowledgeTransaction();
        $acknowledgeTransactionInput->tokenInput = $token;

        return $this->callSoapMethod('acknowledgeTransaction', $acknowledgeTransactionInput);
    }
}
