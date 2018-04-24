<?php
namespace Freshwork\Transbank;

use Freshwork\Transbank\Exceptions\EmptyTransactionException;
use Freshwork\Transbank\WebpayStandard\acknowledgeTransactionResponse;
use Freshwork\Transbank\WebpayStandard\WebpayStandardWebService;
use Freshwork\Transbank\WebpayStandard\wpmDetailInput;
use Freshwork\Transbank\WebpayStandard\wsInitTransactionInput;
use Freshwork\Transbank\WebpayStandard\wsTransactionDetail;

/**
 * Class WebpayPlus
 * @package Freshwork\Transbank
 */
class WebpayWebService extends TransbankService
{
    const TIENDA_NORMAL = 'TR_NORMAL_WS';

    const TIENDA_MALL = 'TR_MALL_WS';

    const PATPASS = 'TR_NORMAL_WS_WPM';

    /**
     * @var WebpayStandardWebService
     */
    protected $service;

    /**
     * @var wsTransactionDetail[]
     */
    protected $transactionDetails = [];

    /** @var  wpmDetailInput */
    protected $inscriptionInformation;

    /**
     * WebpayOneClick constructor.
     * @param WebpayStandardWebService $service
     */
    public function __construct(WebpayStandardWebService $service)
    {
        $this->service = $service;
    }

    /**
     * @param $amount
     * @param $buyOrder
     * @param null $commerceCode
     *
     * @return $this
     */
    public function addTransactionDetail($amount, $buyOrder, $commerceCode = null)
    {
        $detail = new wsTransactionDetail();
        $detail->amount = $amount;
        $detail->buyOrder = $buyOrder;
        $detail->commerceCode = $commerceCode ? $commerceCode : SecurityHelper::getCommonName($this->service->getCertificationBag()->getClientCertificate());
        $this->transactionDetails[] = $detail;

        return $this;
    }

    /**
     * @param $serviceId
     * @param $rut
     * @param $firstName
     * @param $lastName1
     * @param $lastName2
     * @param $clientEmail
     * @param $phoneNumber
     * @param $expirationDate
     * @param $commerceMail
     * @param bool $uf
     *
     * @return $this
     */
    public function addInscriptionInfo($serviceId, $rut, $firstName, $lastName1, $lastName2, $clientEmail, $phoneNumber, $expirationDate, $commerceMail, $uf = false)
    {
        $detail = new wpmDetailInput();
        $detail->serviceId = $serviceId;
        $detail->cardHolderId = $rut;
        $detail->cardHolderName = $firstName;
        $detail->cardHolderLastName1 = $lastName1;
        $detail->cardHolderLastName2 = $lastName2;
        $detail->cardHolderMail = $clientEmail;
        $detail->cellPhoneNumber = $phoneNumber;
        $detail->expirationDate = $expirationDate;
        $detail->commerceMail = $commerceMail;
        $detail->ufFlag = $uf;

        $this->inscriptionInformation = $detail;

        return $this;
    }

    /**
     * Método que permite iniciar una transacción de pago Webpay.
     *
     * @param string $returnURL URL del comercio, a la cual Webpay redireccionará posterior al proceso de autorización.
     * @param string $finalURL URL del comercio a la cual Webpay redireccionará posterior al voucher de éxito de Webpay.
     * @param string|null $sessionId Identificador de sesión, uso interno de comercio, este valor es
     * devuelto al final de la transacción.
     * @param string $transactionType
     * @param null $buyOrder
     * @param null $commerceCode
     *
     * @return WebpayStandard\wsInitTransactionOutput
     * @throws EmptyTransactionException
     */
    public function initTransaction($returnURL, $finalURL, $sessionId = null, $transactionType = self::TIENDA_NORMAL, $buyOrder = null, $commerceCode = null)
    {
        $this->validateTransactionDetails();

        $input = new wsInitTransactionInput();

        $this->validateParametersBasedOnTransactionType($transactionType, $buyOrder, $commerceCode, $input);

        $input->sessionId = $sessionId;
        $input->returnURL = $returnURL;
        $input->finalURL = $finalURL;
        $input->wSTransactionType = $transactionType;
        $input->transactionDetails = $this->transactionDetails;
        $input->transactionDetails;


        return $this->service->initTransaction($input)->return;
    }

    /**
     * Método que permite obtener el resultado de la transacción y los datos de la misma.
     *
     * @param string $token
     * @return WebpayStandard\transactionResultOutput
     */
    public function getTransactionResult($token = null)
    {
        if (!$token) {
            $token = $_POST['token_ws'];
        }

        return $this->service->getTransactionResult($token)->return;
    }

    /**
     * Método que permite informar a Webpay la correcta recepción del resultado de la transacción.
     *
     * @param string $token
     * @return acknowledgeTransactionResponse
     */
    public function acknowledgeTransaction($token = null)
    {
        if (!$token) {
            $token = $_POST['token_ws'];
        }

        return $this->service->acknowledgeTransaction($token);
    }

    /**
     * Assert the transactions has at least one detail
     *
     * @throws EmptyTransactionException
     */
    protected function validateTransactionDetails()
    {
        if (count($this->transactionDetails) <= 0) {
            throw new EmptyTransactionException('You have to add at least one detail to the transaction with ->addDetail(...)');
        }
    }

    /**
     * @param $transactionType
     * @param $buyOrder
     * @param $commerceCode
     * @param $input
     *
     * @throws \InvalidArgumentException
     */
    public function validateParametersBasedOnTransactionType($transactionType, $buyOrder, $commerceCode, $input)
    {
        if ($transactionType == self::TIENDA_MALL) {
            if (!$commerceCode) {
                $commerceCode = SecurityHelper::getCommonName($this->service->getCertificationBag()->getClientCertificate());
            }
            if (!$buyOrder) {
                throw new \InvalidArgumentException('Mall transactions needs a buyOrder defined for the transaction itself and a buyOrder per transactionDetail. Please add a buyOrder on initTransaction()');
            }

            $input->buyOrder = $buyOrder;
            $input->commerceId = $commerceCode;
        }

        if ($transactionType == self::PATPASS) {
            if (!$this->inscriptionInformation) {
                throw new \InvalidArgumentException('Patpass transactions needs inscriptionInformation to perform the contract with the user. You need to call ->addInscriptionInfo(..) before ->initInscription().');
            }

            $input->wPMDetail = $this->inscriptionInformation;
        }
    }
}
