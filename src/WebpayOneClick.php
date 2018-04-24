<?php
namespace Freshwork\Transbank;

use Freshwork\Transbank\WebpayOneClick\oneClickFinishInscriptionInput;
use Freshwork\Transbank\WebpayOneClick\oneClickInscriptionInput;
use Freshwork\Transbank\WebpayOneClick\oneClickPayInput;
use Freshwork\Transbank\WebpayOneClick\oneClickRemoveUserInput;
use Freshwork\Transbank\WebpayOneClick\oneClickReverseInput;
use Freshwork\Transbank\WebpayOneClick\WebpayOneClickWebService;

/**
 * Class WebpayOneClick
 * @package Freshwork\Transbank
 */
class WebpayOneClick extends TransbankService
{
    /**
     * @var WebpayOneClickWebService
     */
    private $service;

    /**
     * WebpayOneClick constructor.
     * @param WebpayOneClickWebService $service
     */
    public function __construct(WebpayOneClickWebService $service)
    {
        $this->service = $service;
    }

    /**
     * Permite realizar la inscripción del tarjetahabiente e información de su tarjeta de crédito. Retorna como respuesta un token que representa la transacción de inscripción y una URL (UrlWebpay), que corresponde a la URL de inscripción de One Click.
     * Una vez que se llama a este servicio Web, el usuario debe ser redireccionado vía POST a urlWebpay con parámetro TBK_TOKEN igual al token obtenido.
     *
     * @param string $username Username
     * @param string $email User Email
     * @param string $responseURL Any Url
     *
     * @return WebpayOneClick\oneClickInscriptionOutput
     */
    public function initInscription($username, $email, $responseURL)
    {
        $initInscriptionInput = new oneClickInscriptionInput();
        $initInscriptionInput->username = $username;
        $initInscriptionInput->email = $email;
        $initInscriptionInput->responseURL = $responseURL;
        return $this->service->initInscription($initInscriptionInput)->return;
    }

    /**
     * Permite finalizar el proceso de inscripción del tarjetahabiente en Oneclick. Entre otras cosas, retorna el identificador del usuario en Oneclick, el cual será utilizado para realizar las transacciones de pago.
     * Una vez terminado el flujo de inscripción en Transbank el usuario es enviado a la URL de fin de inscripción que definió el comercio. En ese instante el comercio debe llamar a finishInscription.
     *
     * @param string $token Token received on the responseURL defined on initInscription
     *
     * @return WebpayOneClick\oneClickFinishInscriptionOutput
     */
    public function finishInscription($token = null)
    {
        if (!$token) {
            $token = $_POST['TBK_TOKEN'];
        }

        $finishInscriptionInput = new oneClickFinishInscriptionInput();
        $finishInscriptionInput->token = $token;

        return $this->service->finishInscription($finishInscriptionInput)->return;
    }

    /**
     *  Permite realizar transacciones de pago. Retorna el resultado de la autorización. Este método que debe ser ejecutado, cada vez que el
     * usuario selecciona pagar con Oneclick.
     *
     * @param int $amount
     * @param string $buyOrder
     * @param string $username
     * @param string $userToken
     *
     * @return WebpayOneClick\oneClickPayOutput
     */
    public function authorize($amount, $buyOrder, $username, $userToken)
    {
        $authorizeInput = new oneClickPayInput();
        $authorizeInput->username = $username;
        $authorizeInput->amount = $amount;
        $authorizeInput->buyOrder = $buyOrder;
        $authorizeInput->tbkUser = $userToken;

        return $this->service->authorize($authorizeInput)->return;
    }

    /**
     * Permite reversar una transacción de venta autorizada con anterioridad. Este método retorna como respuesta un identificador único de la
     * transacción de reversa.
     *
     * @param string $buyOrder
     * @return WebpayOneClick\oneClickReverseOutput
     */
    public function codeReverseOneClick($buyOrder)
    {
        $codeReverseOneClickInput = new oneClickReverseInput();
        $codeReverseOneClickInput->buyorder = $buyOrder;

        return $this->service->codeReverseOneClick($codeReverseOneClickInput)->return;
    }

    /**
     * Permite eliminar una inscripción de usuario en Transbank
     *
     * @param string $userToken
     * @param string $username
     *
     * @return bool
     */
    public function removeUser($userToken, $username)
    {
        $removeUserInput = new oneClickRemoveUserInput();
        $removeUserInput->tbkUser = $userToken;
        $removeUserInput->username = $username;

        return $this->service->removeUser($removeUserInput)->return;
    }
}
