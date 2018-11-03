<?php
/**
 * Clase WebpayOneClick
 *
 * @package Freshwork\Transbank
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank;

use Freshwork\Transbank\WebpayOneClick\oneClickFinishInscriptionInput;
use Freshwork\Transbank\WebpayOneClick\oneClickInscriptionInput;
use Freshwork\Transbank\WebpayOneClick\oneClickPayInput;
use Freshwork\Transbank\WebpayOneClick\oneClickRemoveUserInput;
use Freshwork\Transbank\WebpayOneClick\oneClickReverseInput;
use Freshwork\Transbank\WebpayOneClick\WebpayOneClickWebService;

/**
 * Clase WebpayOneClick
 * @package Freshwork\Transbank
 */
class WebpayOneClick extends TransbankService
{
    /**
     * @var WebpayOneClickWebService Cliente SOAP involucrado en la comunicación de OneClick
     */
    private $service;

    /**
     * WebpayOneClick constructor
     *
     * @param WebpayOneClickWebService $service Instancia del cliente SOAP de OneClick
     */
    public function __construct(WebpayOneClickWebService $service)
    {
        $this->service = $service;
    }

    /**
     * Permite realizar la inscripción del tarjetahabiente e información de su tarjeta de crédito
     *
     * Retorna como respuesta un objeto que contiene un token que representa la transacción de inscripción
     * y una URL que corresponde a la URL de inscripción de OneClick.
     *
     * @param string $username Nombre de usuario del cliente
     * @param string $email Correo electrónico del cliente
     * @param string $responseURL URL del comercio a la cual Webpay redireccionará posterior al proceso de inscripción
     *
     * @return WebpayOneClick\OneClickInscriptionOutput
     */
    public function initInscription($username, $email, $responseURL)
    {
        $initInscriptionInput = new OneClickInscriptionInput();
        $initInscriptionInput->username = $username;
        $initInscriptionInput->email = $email;
        $initInscriptionInput->responseURL = $responseURL;
        return $this->service->initInscription($initInscriptionInput)->return;
    }

    /**
     * Finaliza el proceso de inscripción del tarjetahabiente en Oneclick.
     *
     * Retorna un objeto que contiene, entre otras cosas, el token del usuario en Oneclick que deberá ser utilizado
     * para realizar las transacciones de pago.
     *
     * @param string $token Token recibido luego de la inscripción del cliente
     *
     * @return WebpayOneClick\OneClickFinishInscriptionOutput
     */
    public function finishInscription($token = null)
    {
        if (is_null($token) || empty($token)) {
            $token = $_POST['TBK_TOKEN'];
        }

        $finishInscriptionInput = new OneClickFinishInscriptionInput();
        $finishInscriptionInput->token = $token;

        return $this->service->finishInscription($finishInscriptionInput)->return;
    }

    /**
     * Reliza transacciones de pago.
     *
     * Retorna un objeto que contiene el resultado de la autorización y debe ser ejecutado cada vez que el
     * usuario selecciona pagar con Oneclick.
     *
     * @param int $amount Monto del pago en pesos
     * @param string $buyOrder Orden de compra de la tienda
     * @param string $username Nombre de usuario del cliente
     * @param string $userToken Token de OneClick asociado al cliente
     *
     * @return WebpayOneClick\OneClickPayOutput
     */
    public function authorize($amount, $buyOrder, $username, $userToken)
    {
        $authorizeInput = new OneClickPayInput();
        $authorizeInput->username = $username;
        $authorizeInput->amount = $amount;
        $authorizeInput->buyOrder = $buyOrder;
        $authorizeInput->tbkUser = $userToken;

        return $this->service->authorize($authorizeInput)->return;
    }


    /**
     * Reversa una transacción de venta autorizada con anterioridad
     *
     * Retorna un objeto que contiene un identificador único de la transacción de reversa
     *
     * @param string $buyOrder Orden de compra de la tienda
     * @return WebpayOneClick\OneClickReverseOutput
     */
    public function codeReverseOneClick($buyOrder)
    {
        $codeReverseOneClickInput = new OneClickReverseInput();
        $codeReverseOneClickInput->buyorder = $buyOrder;

        return $this->service->codeReverseOneClick($codeReverseOneClickInput)->return;
    }

    /**
     * Elimina una inscripción de usuario en Transbank
     *
     * @param string $userToken Token de OneClick asociado al cliente
     * @param string $username Nombre de usuario del cliente
     * @return bool
     */
    public function removeUser($userToken, $username)
    {
        $removeUserInput = new OneClickRemoveUserInput();
        $removeUserInput->tbkUser = $userToken;
        $removeUserInput->username = $username;

        return $this->service->removeUser($removeUserInput)->return;
    }
}
