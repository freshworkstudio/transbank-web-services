<?php
/**
 * Class WebpayOneClickWebService
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayOneClick
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank\WebpayOneClick;

use Freshwork\Transbank\TransbankWebService;

/**
 * Class WebpayOneClickWebService
 *
 * @package Freshwork\Transbank\WebpayOneClick
 */
class WebpayOneClickWebService extends TransbankWebService
{
    /** @const INTEGRATION_WSDL Development WSDL URL */
    const INTEGRATION_WSDL = 'https://webpay3gint.transbank.cl/webpayserver/wswebpay/OneClickPaymentService?wsdl';

    /** @const PRODUCTION_WSDL Production WSDL URL */
    const PRODUCTION_WSDL = 'https://webpay3g.transbank.cl/webpayserver/wswebpay/OneClickPaymentService?wsdl';

    /** @var array $classmap Association of WSDL types to classes */
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
     * Inscribe a customer and his/her credit card
     *
     * @param oneClickInscriptionInput $oneClickInscriptionInput Inscription information
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
     * Finish the inscription of the customer on OneClick
     *
     * @param OneClickFinishInscriptionInput $finishInscriptionInput Finish inscription details
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
     * Authorize a payment
     *
     * @param OneClickPayInput $authorizeInput Payment details to authorize
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
     * Reverse a previous payment
     *
     * @param OneClickReverseInput $codeReverseOneClickInput Reverse information
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
     * Deletes a customer inscription
     *
     * @param OneClickRemoveUserInput $removeUserInput Customer details to remove
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
