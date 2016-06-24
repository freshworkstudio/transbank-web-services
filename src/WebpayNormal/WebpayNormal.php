<?php
namespace Freshwork\Transbank\WebpayNormal;

use Freshwork\Transbank\TransbankSoap;
use Freshwork\Transbank\TransbankWebService;
use Freshwork\Transbank\WebpayNormal\acknowledgeTransaction;
use Freshwork\Transbank\WebpayNormal\acknowledgeTransactionResponse;
use Freshwork\Transbank\WebpayNormal\cardDetail;
use Freshwork\Transbank\WebpayNormal\getTransactionResult;
use Freshwork\Transbank\WebpayNormal\getTransactionResultResponse;
use Freshwork\Transbank\WebpayNormal\initTransaction;
use Freshwork\Transbank\WebpayNormal\initTransactionResponse;
use Freshwork\Transbank\WebpayNormal\transactionResultOutput;
use Freshwork\Transbank\WebpayNormal\wpmDetailInput;
use Freshwork\Transbank\WebpayNormal\wsInitTransactionInput;
use Freshwork\Transbank\WebpayNormal\wsInitTransactionOutpu;
use Freshwork\Transbank\WebpayNormal\wsTransactionDetail;
use Freshwork\Transbank\WebpayNormal\wsTransactionDetailOutput;

/**
 * Class WebpayNormal
 * @package Freshwork\Transbank\WebpayNormal
 */
class WebpayNormal extends TransbankWebService
{
    /**
     * @var
     */
    var $soapClient;
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
        'wsInitTransactionOutput' => wsInitTransactionOutput::class
    ];

    /**
     * Integration URL
     */
    const INTEGRATION_WSDL  = 'https://tbk.orangepeople.cl/WSWebpayTransaction/cxf/WSWebpayService?wsdl';

    /**
     * Production URL
     */
    const PRODUCTION_WSDL   = 'https://webpay3g.transbank.cl/WSWebpayTransaction/cxf/WSWebpayService?wsdl';

    /**
     * @param wsInitTransactionInput $initTransactionInput
     * @return mixed
     */
    function initTransaction(wsInitTransactionInput $initTransactionInput)
    {
        $initInscription = new initTransaction();
        $initInscription->wsInitTransactionInput = $initTransactionInput;

        return $this->callSoapMethod('initInscription', $initInscription);
    }

    /**
     * @param string $token
     * @return mixed
     */
    function getTransactionResult($token)
    {
        $getTransactionResult = new getTransactionResult();
        $getTransactionResult->tokenInput = $token;

        return $this->callSoapMethod('getTransactionResult', $getTransactionResult);
    }

    /**
     * @param string $token
     * @return mixed
     */
    function acknowledgeTransaction($token)
    {
        $acknowledgeTransactionInput = new acknowledgeTransaction();
        $acknowledgeTransactionInput->tokenInput = $token;

        return $this->callSoapMethod('acknowledgeTransaction', $acknowledgeTransactionInput);
    }
}

?>