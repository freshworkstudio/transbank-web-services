<?php
namespace Freshwork\Transbank\WebpayPatPass;

use Freshwork\Transbank\TransbankWebService;
use Freshwork\Transbank\WebpayPatPass\getTransactionResult;
use \Freshwork\Transbank\WebpayPatPass\getTransactionResultResponse;
use \Freshwork\Transbank\WebpayPatPass\transactionResultOutput;
use \Freshwork\Transbank\WebpayPatPass\cardDetail;
use \Freshwork\Transbank\WebpayPatPass\wsTransactionDetailOutput;
use \Freshwork\Transbank\WebpayPatPass\wsTransactionDetail;
use \Freshwork\Transbank\WebpayPatPass\acknowledgeTransaction;
use \Freshwork\Transbank\WebpayPatPass\acknowledgeTransactionResponse;
use \Freshwork\Transbank\WebpayPatPass\initTransaction;
use \Freshwork\Transbank\WebpayPatPass\wsInitTransactionInput;
use \Freshwork\Transbank\WebpayPatPass\wpmDetailInput;
use \Freshwork\Transbank\WebpayPatPass\initTransactionResponse;
use \Freshwork\Transbank\WebpayPatPass\wsInitTransactionOutput;

/**
 * Class WebpayPatPassWebService
 * @package Freshwork\Transbank\WebpayPatPass
 */
class WebpayPatPassWebService extends TransbankWebService
{
    /**
     * Integration URL
     */
    const INTEGRATION_WSDL = 'https://webpay3gint.transbank.cl/WSWebpayTransaction/cxf/WSWebpayService?wsdl';

    /**
     * Production URL
     */
    const PRODUCTION_WSDL = 'https://webpay3g.transbank.cl/WSWebpayTransaction/cxf/WSWebpayService?wsdl';

    /**
     * @var array
     */
    protected static $classmap = [
        'getTransactionResult' => getTransactionResult::class,
        'getTransactionResultResponse' => getTransactionResultResponse::class,
        'transactionResultOutput' => transactionResultOutput::class,
        'cardDetail' => cardDetail::class,
        'wsTransactionDetailOutput' => wsTransactionDetailOutput::class,
        'wsTransactionDetail' => wsTransactionDetail::class,
        'acknowledgeTransaction' => acknowledgeTransaction::class,
        'acknowledgeTransactionResponse' => acknowledgeTransactionResponse::class,
        'initTransaction' => initTransaction::class,
        'wsInitTransactionInput' => wsInitTransactionInput::class,
        'wpmDetailInput' => wpmDetailInput::class,
        'initTransactionResponse' => initTransactionResponse::class,
        'wsInitTransactionOutput' => wsInitTransactionOutput::class,
    ];

    /**
     * Permite inicializar una transacción de PatPass by Webpay, como respuesta a la
     * invocación se genera un token que representa en forma única una transacción.
     * Es importante considerar que una vez invocado este método, el token que es entregado
     * tiene un periodo reducido de vida de 5 minutos, posterior a esto el token es caducado.
     *
     * @param wsInitTransactionInput $wsInitTransactionInput
     * @return initTransactionResponse
     */
    function initTransaction(wsInitTransactionInput $wsInitTransactionInput)
    {
        $initTransaction = new initTransaction();
        $initTransaction->wsInitTransactionInput = $wsInitTransactionInput;

        return $this->callSoapMethod('initTransaction', $initTransaction);
    }

    /**
     * Permite obtener el resultado de la transacción una vez Webpay ha resuelto su autorización financiera.
     *
     * @param string $token
     * @return getTransactionResultResponse
     */
    function getTransactionResult($token)
    {
        $getTransactionResult = new getTransactionResult();
        $getTransactionResult->tokenInput = $token;

        return $this->callSoapMethod('getTransactionResult', $getTransactionResult);

    }

    /**
     * Permite indicar a Webpay que se ha recibido conforme el resultado de la transacción.
     *
     * @param string $token
     * @return acknowledgeTransactionResponse
     */
    function acknowledgeTransaction($token)
    {
        $acknowledgeTransaction = new acknowledgeTransaction();
        $acknowledgeTransaction->tokenInput = $token;

        return $this->callSoapMethod('acknowledgeTransaction', $acknowledgeTransaction);
    }

}


?>