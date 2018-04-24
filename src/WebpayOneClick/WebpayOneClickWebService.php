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
        'removeUser' => removeUser::class,
        'oneClickRemoveUserInput' => oneClickRemoveUserInput::class,
        'baseBean' => baseBean::class,
        'removeUserResponse' => removeUserResponse::class,
        'initInscription' => initInscription::class,
        'oneClickInscriptionInput' => oneClickInscriptionInput::class,
        'initInscriptionResponse' => initInscriptionResponse::class,
        'oneClickInscriptionOutput' => oneClickInscriptionOutput::class,
        'finishInscription' => finishInscription::class,
        'oneClickFinishInscriptionInput' => oneClickFinishInscriptionInput::class,
        'finishInscriptionResponse' => finishInscriptionResponse::class,
        'oneClickFinishInscriptionOutput' => oneClickFinishInscriptionOutput::class,
        'codeReverseOneClick' => codeReverseOneClick::class,
        'oneClickReverseInput' => oneClickReverseInput::class,
        'codeReverseOneClickResponse' => codeReverseOneClickResponse::class,
        'oneClickReverseOutput' => oneClickReverseOutput::class,
        'authorize' => authorize::class,
        'oneClickPayInput' => oneClickPayInput::class,
        'authorizeResponse' => authorizeResponse::class,
        'oneClickPayOutput' => oneClickPayOutput::class,
        'reverse' => reverse::class,
        'reverseResponse' => reverseResponse::class
    );


    /**
     * Permite realizar la inscripción del tarjetahabiente e información de su tarjeta de crédito. Retorna como respuesta un token que representa la transacción de inscripción y una URL (UrlWebpay), que corresponde a la URL de inscripción de One Click.
     * Una vez que se llama a este servicio Web, el usuario debe ser redireccionado vía POST a urlWebpay con parámetro TBK_TOKEN igual al token obtenido.
     *
     * @param oneClickInscriptionInput $oneClickInscriptionInput
     * @return initInscriptionResponse
     */
    public function initInscription(oneClickInscriptionInput $oneClickInscriptionInput)
    {
        $initInscription = new initInscription();
        $initInscription->arg0 = $oneClickInscriptionInput;
        return $this->callSoapMethod('initInscription', $initInscription);
    }

    /**
     * Permite finalizar el proceso de inscripción del tarjetahabiente en Oneclick. Entre otras cosas, retorna el identificador del usuario en Oneclick, el cual será utilizado para realizar las transacciones de pago.
     * Una vez terminado el flujo de inscripción en Transbank el usuario es enviado a la URL de fin de inscripción que definió el comercio. En ese instante el comercio debe llamar a finishInscription.
     *
     * @param oneClickFinishInscriptionInput $finishInscriptionInput
     * @return finishInscriptionResponse
     */
    public function finishInscription(oneClickFinishInscriptionInput $finishInscriptionInput)
    {
        $finishInscription = new finishInscription();
        $finishInscription->arg0 = $finishInscriptionInput;

        return $this->callSoapMethod('finishInscription', $finishInscription);
    }

    /**
     * Permite realizar transacciones de pago. Retorna el resultado de la autorización. Este método que debe ser ejecutado, cada vez que el
     * usuario selecciona pagar con Oneclick.
     *
     * @param \Freshwork\Transbank\WebpayOneClick\oneClickPayInput $authorizeInput
     * @return \Freshwork\Transbank\WebpayOneClick\authorizeResponse
     */
    public function authorize(oneClickPayInput $authorizeInput)
    {
        $authorize = new authorize();
        $authorize->arg0 = $authorizeInput;
        return $this->callSoapMethod('authorize', $authorize);
    }

    /**
     * Permite reversar una transacción de venta autorizada con anterioridad. Este método retorna como respuesta un identificador único de la
     * transacción de reversa.
     *
     * @param \Freshwork\Transbank\WebpayOneClick\oneClickReverseInput $codeReverseOneClickInput
     * @return \Freshwork\Transbank\WebpayOneClick\codeReverseOneClickResponse
     */
    public function codeReverseOneClick(oneClickReverseInput $codeReverseOneClickInput)
    {
        $codeReverseOneClick = new codeReverseOneClick();
        $codeReverseOneClick->arg0 = $codeReverseOneClickInput;

        return $this->callSoapMethod('codeReverseOneClick', $codeReverseOneClick);
    }

    /**
     * Permite eliminar una inscripción de usuario en Transbank
     *
     * @param \Freshwork\Transbank\WebpayOneClick\oneClickRemoveUserInput $removeUserInput
     * @return removeUserResponse
     */
    public function removeUser(oneClickRemoveUserInput $removeUserInput)
    {
        $removeUser = new removeUser();
        $removeUser->arg0 = $removeUserInput;
        return $this->callSoapMethod('removeUser', $removeUser);
    }
}
