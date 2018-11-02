<?php
namespace Freshwork\Transbank\WebpayOneClick;

use Freshwork\Transbank\TransbankWebService;

/**
 * Class WebpayOneClick
 * @package Freshwork\Transbank\WebpayOneClick
 */
class WebpayOneClickWebService extends TransbankWebService
{
    /**
     * Integration URL
     */
    const INTEGRATION_WSDL = 'https://webpay3gint.transbank.cl/webpayserver/wswebpay/OneClickPaymentService?wsdl';

    /**
     * Production URL
     */
    const PRODUCTION_WSDL = 'https://webpay3g.transbank.cl/webpayserver/wswebpay/OneClickPaymentService?wsdl';

    /**
     * @var array
     */
    protected static $classmap = array(
        'removeUser' => RemoveUser::class,
        'oneClickRemoveUserInput' => OneClickRemoveUserInput::class,
        'baseBean' => BaseBean::class,
        'removeUserResponse' => RemoveUserResponse::class,
        'initInscription' => InitInscription::class,
        'oneClickInscriptionInput' => OneClickInscriptionInput::class,
        'initInscriptionResponse' => InitInscriptionResponse::class,
        'oneClickInscriptionOutput' => OneClickInscriptionOutput::class,
        'finishInscription' => FinishInscription::class,
        'oneClickFinishInscriptionInput' => OneClickFinishInscriptionInput::class,
        'finishInscriptionResponse' => FinishInscriptionResponse::class,
        'oneClickFinishInscriptionOutput' => OneClickFinishInscriptionOutput::class,
        'codeReverseOneClick' => CodeReverseOneClick::class,
        'oneClickReverseInput' => OneClickReverseInput::class,
        'codeReverseOneClickResponse' => CodeReverseOneClickResponse::class,
        'oneClickReverseOutput' => OneClickReverseOutput::class,
        'authorize' => Authorize::class,
        'oneClickPayInput' => OneClickPayInput::class,
        'authorizeResponse' => AuthorizeResponse::class,
        'oneClickPayOutput' => OneClickPayOutput::class,
        'reverse' => Reverse::class,
        'reverseResponse' => ReverseResponse::class
    );


    /**
     * Permite realizar la inscripción del tarjetahabiente e información de su tarjeta de crédito.
     * Retorna como respuesta un token que representa la transacción de inscripción y una URL (UrlWebpay),
     * que corresponde a la URL de inscripción de One Click.
     * Una vez que se llama a este servicio Web, el usuario debe ser redireccionado vía POST a urlWebpay
     * con parámetro TBK_TOKEN igual al token obtenido.
     *
     * @param OneClickInscriptionInput $oneClickInscriptionInput
     * @return InitInscriptionResponse
     */
    public function initInscription(oneClickInscriptionInput $oneClickInscriptionInput)
    {
        $initInscription = new InitInscription();
        $initInscription->arg0 = $oneClickInscriptionInput;
        return $this->callSoapMethod('initInscription', $initInscription);
    }

    /**
     * Permite finalizar el proceso de inscripción del tarjetahabiente en Oneclick.
     * Entre otras cosas, retorna el identificador del usuario en Oneclick, el cual será utilizado
     * para realizar las transacciones de pago.
     * Una vez terminado el flujo de inscripción en Transbank el usuario es enviado a la URL de fin de inscripción
     * que definió el comercio. En ese instante el comercio debe llamar a finishInscription.
     *
     * @param OneClickFinishInscriptionInput $finishInscriptionInput
     * @return FinishInscriptionResponse
     */
    public function finishInscription(OneClickFinishInscriptionInput $finishInscriptionInput)
    {
        $finishInscription = new FinishInscription();
        $finishInscription->arg0 = $finishInscriptionInput;

        return $this->callSoapMethod('finishInscription', $finishInscription);
    }

    /**
     * Permite realizar transacciones de pago. Retorna el resultado de la autorización.
     * Este método que debe ser ejecutado, cada vez que el usuario selecciona pagar con Oneclick.
     *
     * @param \Freshwork\Transbank\WebpayOneClick\OneClickPayInput $authorizeInput
     * @return \Freshwork\Transbank\WebpayOneClick\AuthorizeResponse
     */
    public function authorize(OneClickPayInput $authorizeInput)
    {
        $authorize = new Authorize();
        $authorize->arg0 = $authorizeInput;
        return $this->callSoapMethod('authorize', $authorize);
    }

    /**
     * Permite reversar una transacción de venta autorizada con anterioridad.
     * Este método retorna como respuesta un identificador único de la transacción de reversa.
     *
     * @param \Freshwork\Transbank\WebpayOneClick\OneClickReverseInput $codeReverseOneClickInput
     * @return \Freshwork\Transbank\WebpayOneClick\CodeReverseOneClickResponse
     */
    public function codeReverseOneClick(OneClickReverseInput $codeReverseOneClickInput)
    {
        $codeReverseOneClick = new CodeReverseOneClick();
        $codeReverseOneClick->arg0 = $codeReverseOneClickInput;

        return $this->callSoapMethod('codeReverseOneClick', $codeReverseOneClick);
    }

    /**
     * Permite eliminar una inscripción de usuario en Transbank
     *
     * @param \Freshwork\Transbank\WebpayOneClick\OneClickRemoveUserInput $removeUserInput
     * @return removeUserResponse
     */
    public function removeUser(OneClickRemoveUserInput $removeUserInput)
    {
        $removeUser = new RemoveUser();
        $removeUser->arg0 = $removeUserInput;
        return $this->callSoapMethod('removeUser', $removeUser);
    }
}
