<?php
/**
 * Clase WebpayWebService
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
 * Base para implementar servicios basados en Webpay Plus
 *
 * Clase WebpayWebService
 * @package Freshwork\Transbank
 */
class WebpayWebService extends TransbankService
{
    /** @const TIENDA_NORMAL Identifica una transacción normal */
    const TIENDA_NORMAL = 'TR_NORMAL_WS';

    /** @const TIENDA_MALL Identifica una trasacción de tipo Mall */
    const TIENDA_MALL = 'TR_MALL_WS';

    /** @const PATPASS Indentifica una transacción PatPass */
    const PATPASS = 'TR_NORMAL_WS_WPM';

    /**
     * @var WebpayStandardWebService $service Cliente SOAP involucrado en el servicio implementado
     */
    protected $service;

    /**
     * @var TransactionDetail[] $transactionDetails Listado con los detalles de la transacción a realizar
     */
    protected $transactionDetails = [];

    /** @var  DetailInput $inscriptionInformation Detalles del cliente que se está suscribiendo */
    protected $inscriptionInformation;

    /**
     * WebpayWebService constructor
     *
     * @param WebpayStandardWebService $service Instancia del cliente SOAP
     */
    public function __construct(WebpayStandardWebService $service)
    {
        $this->service = $service;
    }

    /**
     * Añade detalles de la transacción a relizar
     *
     * @param $amount Monto de la transacción
     * @param $buyOrder Número de orden de compra
     * @param null $commerceCode Código de comercio
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
     * Añade información del cliente y de la suscripción
     *
     * @param string $serviceId Identificador del servicio
     * @param string $rut RUT del cliente
     * @param string $firstName Nombres del cliente
     * @param string $lastName1 Apellido paterno del cliente
     * @param string $lastName2 Apellido materno del cliente
     * @param string $clientEmail Correo electrónico del cliente
     * @param string $phoneNumber Teléfono del cliente
     * @param string $expirationDate Fecha de expiración de la suscripción
     * @param string $commerceMail Correo electrónico del comercio
     * @param bool $uf Idica si el cobro es realizado en UF
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
     * Inicializa una transacción en Webpay
     *
     * @param string $returnURL URL del comercio, a la cual Webpay redireccionará posterior al proceso de autorización
     * @param string $finalURL URL del comercio a la cual Webpay redireccionará posterior al voucher de éxito de Webpay
     * @param string|null $sessionId Identificador de sesión, uso interno de comercio
     * @param string $transactionType Indica el tipo de transacción
     * @param string|null $buyOrder Orden de compra de la tienda
     * @param string|null $commerceCode Código del comercio
     *
     * @return WebpayStandard\InitTransactionOutput
     * @throws EmptyTransactionException
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
     * Obtiene el resultado de la transacción una vez que Webpay ha resuelto su autorización financiera.
     *
     * @param string|null $token Token de la transacción
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
     * Indica a Webpay que se ha recibido conforme el resultado de la transacción.
     *
     * @param string|null $token Token de la transacción
     * @return AcknowledgeTransactionResponse
     */
    public function acknowledgeTransaction($token = null)
    {
        if (!$token) {
            $token = $_POST['token_ws'];
        }

        return $this->service->acknowledgeTransaction($token);
    }

    /**
     * Valida que la transacción tenga al menos un detalle
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
     * Valida los parámetros basado en el tipo de transacción
     *
     * @param string $transactionType Tipo de transacción
     * @param string $buyOrder Orden de compra de la tienda
     * @param string $commerceCode Código de comercio
     * @param InitTransactionInput $input Detalles del inicio de la transacción
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
                    and a buyOrder per transactionDetail. Please add a buyOrder on initTransaction()'
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
