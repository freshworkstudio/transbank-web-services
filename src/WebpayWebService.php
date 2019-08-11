<?php
/**
 * Class WebpayWebService
 *
 * @package Freshwork\Transbank
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank;

use Freshwork\Transbank\Exceptions\EmptyTransactionException;
use Freshwork\Transbank\WebpayStandard\AcknowledgeTransactionResponse;
use Freshwork\Transbank\WebpayStandard\WebpayStandardWebService;
use Freshwork\Transbank\WebpayStandard\DetailInput;
use Freshwork\Transbank\WebpayStandard\InitTransactionInput;
use Freshwork\Transbank\WebpayStandard\TransactionDetail;

/**
 * Base to implement services based on Webpay Plus
 *
 * Class WebpayWebService
 * @package Freshwork\Transbank
 */
class WebpayWebService extends TransbankService
{
    /** @const TIENDA_NORMAL Identifies a normal transaction */
    const TIENDA_NORMAL = 'TR_NORMAL_WS';

    /** @const TIENDA_MALL Identifies a Mall transaction */
    const TIENDA_MALL = 'TR_MALL_WS';

    /** @const PATPASS Identifies a PatPass transaction */
    const PATPASS = 'TR_NORMAL_WS_WPM';

    const RESULT_OK = 0;

    /**
     * @var WebpayStandardWebService $service SOAP Client used in the implemented service
     */
    protected $service;

    /**
     * @var TransactionDetail[] $transactionDetails Details of the transaction
     */
    protected $transactionDetails = [];

    /** @var  DetailInput $inscriptionInformation Details of the customer who is subscribing */
    protected $inscriptionInformation;

    /**
     * WebpayWebService constructor
     *
     * @param WebpayStandardWebService $service SOAP Client instance
     */
    public function __construct(WebpayStandardWebService $service)
    {
        $this->service = $service;
    }

    /**
     * Add transactions details
     *
     * @param float|int $amount Transaction amount
     * @param string $buyOrder Order identifier
     * @param string $commerceCode Commerce's code
     * @return $this
     */
    public function addTransactionDetail($amount, $buyOrder, $commerceCode = null)
    {
        $detail = new TransactionDetail();
        $detail->amount = $amount;
        $detail->buyOrder = $buyOrder;
        $detail->commerceCode = $commerceCode ? $commerceCode : SecurityHelper::getCommonName(
            $this->service->getCertificationBag()->getClientCertificate()
        );
        $this->transactionDetails[] = $detail;

        return $this;
    }

    /**
     * Add customer and subscription information
     *
     * @param string $serviceId Service identifier
     * @param string $rut Customer's national identifier number
     * @param string $firstName Customer's names
     * @param string $lastName1 Customer's paternal surname
     * @param string $lastName2 Customer's maternal surname
     * @param string $clientEmail Customer's e-mail
     * @param string $phoneNumber Customer's phone number
     * @param string $expirationDate Expiry date of the subscription
     * @param string $commerceMail Commerce e-mail
     * @param bool $uf Indicates if the payment is made in UF
     * @return $this
     */
    public function addInscriptionInfo(
        $serviceId,
        $rut,
        $firstName,
        $lastName1,
        $lastName2,
        $clientEmail,
        $phoneNumber,
        $expirationDate,
        $commerceMail,
        $uf = false
    ) {
        $detail = new DetailInput();
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
     * Initialize a Webpay transaction
     *
     * @param string $returnURL URL of the commerce, to which Webpay will redirect after the authorization process
     * @param string $finalURL URL of the commerce, to which Webpay will redirect subsequent to Webpay's voucher
     * @param string|null $sessionId Session identifier, internal use of commerce
     * @param string $transactionType Type of transaction
     * @param string|null $buyOrder Order identifier
     * @param string|null $commerceCode Commerce's code
     *
     * @return WebpayStandard\InitTransactionOutput
     * @throws EmptyTransactionException
     * @throws Exceptions\InvalidCertificateException
     * @throws \SoapFault
     */
    public function initTransaction(
        $returnURL,
        $finalURL,
        $sessionId = null,
        $transactionType = self::TIENDA_NORMAL,
        $buyOrder = null,
        $commerceCode = null
    ) {
        $this->validateTransactionDetails();

        $input = new InitTransactionInput();

        $this->validateParametersBasedOnTransactionType($transactionType, $buyOrder, $commerceCode, $input);

        $input->sessionId = $sessionId;
        $input->returnURL = $returnURL;
        $input->finalURL = $finalURL;
        $input->wSTransactionType = $transactionType;
        $input->transactionDetails = $this->transactionDetails;


        return $this->service->initTransaction($input)->return;
    }

    /**
     * Get the transaction result once Webpay has resolved the financial authorization
     *
     * @param string|null $token Webpay token
     * @return WebpayStandard\transactionResultOutput
     * @throws Exceptions\InvalidCertificateException
     * @throws \SoapFault
     */
    public function getTransactionResult($token = null)
    {
        if (!$token) {
            $token = $_POST['token_ws'];
        }

        return $this->service->getTransactionResult($token)->return;
    }

    /**
     * Indicates to Webpay that the transaction result was received
     *
     * @param string|null $token Webpay token
     * @return AcknowledgeTransactionResponse
     * @throws Exceptions\InvalidCertificateException
     * @throws \SoapFault
     */
    public function acknowledgeTransaction($token = null)
    {
        if (!$token) {
            $token = $_POST['token_ws'];
        }

        return $this->service->acknowledgeTransaction($token);
    }

    /**
     * Validates that the transaction has at least one detail
     *
     * @throws EmptyTransactionException
     */
    protected function validateTransactionDetails()
    {
        if (count($this->transactionDetails) <= 0) {
            throw new EmptyTransactionException(
                'You have to add at least one detail to the transaction with ->addTransactionDetail(...)'
            );
        }
    }

    /**
     * Validates parameters based on transaction type
     *
     * @param string $transactionType Transaction type
     * @param string $buyOrder Order identifier
     * @param string $commerceCode Commerce's code
     * @param InitTransactionInput $input Details of the transaction initialization
     */
    public function validateParametersBasedOnTransactionType($transactionType, $buyOrder, $commerceCode, $input)
    {
        if ($transactionType == self::TIENDA_MALL) {
            if (!$commerceCode) {
                $commerceCode = SecurityHelper::getCommonName(
                    $this->service->getCertificationBag()->getClientCertificate()
                );
            }
            if (!$buyOrder) {
                throw new \InvalidArgumentException(
                    'Mall transactions needs a buyOrder defined for the transaction itself 
                    and a buyOrder per transactionDetail. Please add a buyOrder on ->initTransaction()'
                );
            }

            $input->buyOrder = $buyOrder;
            $input->commerceId = $commerceCode;
        }

        if ($transactionType == self::PATPASS) {
            if (!$this->inscriptionInformation) {
                throw new \InvalidArgumentException(
                    'Patpass transactions needs inscriptionInformation to perform the contract with the user. 
                    You need to call ->addInscriptionInfo(..) before ->initInscription().'
                );
            }

            $input->wPMDetail = $this->inscriptionInformation;
        }
    }
}
