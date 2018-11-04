<?php
/**
 * Clase WebpayOneClickWebService
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayOneClick
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank\WebpayOneClick;

use Freshwork\Transbank\TransbankWebService;

/**
 * Clase WebpayOneClickWebService
 *
 * @package Freshwork\Transbank\WebpayOneClick
 */
class WebpayOneClickWebService extends TransbankWebService
{
    /** @const INTEGRATION_WSDL URL del WSDL de integración */
    const INTEGRATION_WSDL = 'https://webpay3gint.transbank.cl/webpayserver/wswebpay/OneClickPaymentService?wsdl';

    /** @const PRODUCTION_WSDL URL del WSDL de producción */
    const PRODUCTION_WSDL = 'https://webpay3g.transbank.cl/webpayserver/wswebpay/OneClickPaymentService?wsdl';

    /** @var array $classmap Listado de asociaciones de tipos del WSDL a clases */
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
     * Realiza la inscripción del tarjetahabiente e información de su tarjeta de crédito
     *
     * Retorna como respuesta un token de inscripción y una URL, que corresponde a la URL de inscripción de One Click.
     *
     * @param oneClickInscriptionInput $oneClickInscriptionInput Detalles de la inscripción
     * @return mixed
     * @throws \Freshwork\Transbank\Exceptions\InvalidCertificateException
     * @throws \SoapFault
     */
    public function initInscription(oneClickInscriptionInput $oneClickInscriptionInput)
    {
        $initInscription = new InitInscription();
        $initInscription->arg0 = $oneClickInscriptionInput;
        return $this->callSoapMethod('initInscription', $initInscription);
    }

    /**
     * Finaliza el proceso de inscripción del tarjetahabiente en Oneclick
     *
     * Retorna el identificador del usuario en Oneclick, el cual será utilizado para realizar las transacciones de pago.
     *
     * @param OneClickFinishInscriptionInput $finishInscriptionInput Detalles para la finalización de inscripción
     * @return mixed
     * @throws \Freshwork\Transbank\Exceptions\InvalidCertificateException
     * @throws \SoapFault
     */
    public function finishInscription(OneClickFinishInscriptionInput $finishInscriptionInput)
    {
        $finishInscription = new FinishInscription();
        $finishInscription->arg0 = $finishInscriptionInput;

        return $this->callSoapMethod('finishInscription', $finishInscription);
    }

    /**
     * Reliza transacciones de pago.
     *
     * Retorna un objeto que contiene el resultado de la autorización y debe ser ejecutado cada vez que el
     * usuario selecciona pagar con Oneclick.
     *
     * @param OneClickPayInput $authorizeInput Detalles de la transacción a autorizar
     * @return mixed
     * @throws \Freshwork\Transbank\Exceptions\InvalidCertificateException
     * @throws \SoapFault
     */
    public function authorize(OneClickPayInput $authorizeInput)
    {
        $authorize = new Authorize();
        $authorize->arg0 = $authorizeInput;
        return $this->callSoapMethod('authorize', $authorize);
    }

    /**
     * Reversa una transacción de venta autorizada con anterioridad
     *
     * Retorna un objeto que contiene un identificador único de la transacción de reversa
     *
     * @param OneClickReverseInput $codeReverseOneClickInput Detalles de la reversa a realizar
     * @return mixed
     * @throws \Freshwork\Transbank\Exceptions\InvalidCertificateException
     * @throws \SoapFault
     */
    public function codeReverseOneClick(OneClickReverseInput $codeReverseOneClickInput)
    {
        $codeReverseOneClick = new CodeReverseOneClick();
        $codeReverseOneClick->arg0 = $codeReverseOneClickInput;

        return $this->callSoapMethod('codeReverseOneClick', $codeReverseOneClick);
    }

    /**
     * Elimina una inscripción de usuario en Transbank
     *
     * @param OneClickRemoveUserInput $removeUserInput Detalles del usuario a eliminar
     * @return mixed
     * @throws \Freshwork\Transbank\Exceptions\InvalidCertificateException
     * @throws \SoapFault
     */
    public function removeUser(OneClickRemoveUserInput $removeUserInput)
    {
        $removeUser = new RemoveUser();
        $removeUser->arg0 = $removeUserInput;
        return $this->callSoapMethod('removeUser', $removeUser);
    }
}
