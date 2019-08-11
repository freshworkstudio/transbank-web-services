<?php
/**
 * Class WebpayOneClick
 *
 * @package Freshwork\Transbank
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank;

use Freshwork\Transbank\WebpayOneClick\OneClickFinishInscriptionInput;
use Freshwork\Transbank\WebpayOneClick\OneClickInscriptionInput;
use Freshwork\Transbank\WebpayOneClick\OneClickPayInput;
use Freshwork\Transbank\WebpayOneClick\OneClickRemoveUserInput;
use Freshwork\Transbank\WebpayOneClick\OneClickReverseInput;
use Freshwork\Transbank\WebpayOneClick\WebpayOneClickWebService;

/**
 * Class WebpayOneClick
 * @package Freshwork\Transbank
 */
class WebpayOneClick extends TransbankService
{
    /**
     * @var WebpayOneClickWebService SOAP client involved in OneClick communication
     */
    protected $service;

    /**
     * WebpayOneClick constructor
     *
     * @param WebpayOneClickWebService $service SOAP Client instance
     */
    public function __construct(WebpayOneClickWebService $service)
    {
        $this->service = $service;
    }

    /**
     *  Inscribe a customer and his/her credit card
     *
     * @param string $username Customer username
     * @param string $email Customer's email
     * @param string $responseURL URL of the commerce to which Webpay will redirect subsequent to the subscription
     *
     * @return WebpayOneClick\OneClickInscriptionOutput
     * @throws Exceptions\InvalidCertificateException
     * @throws \SoapFault
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
     * Finish the inscription of the customer on OneClick
     *
     * @param string $token Subscription token
     *
     * @return WebpayOneClick\OneClickFinishInscriptionOutput
     * @throws Exceptions\InvalidCertificateException
     * @throws \SoapFault
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
     * Authorize a payment
     *
     * @param int $amount Amount
     * @param string $buyOrder Order identifier
     * @param string $username Customer username
     * @param string $userToken Customer token
     *
     * @return WebpayOneClick\OneClickPayOutput
     * @throws Exceptions\InvalidCertificateException
     * @throws \SoapFault
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
     * Reverse a previous payment
     *
     * @param string $buyOrder Order identifier
     * @return WebpayOneClick\OneClickReverseOutput
     * @throws Exceptions\InvalidCertificateException
     * @throws \SoapFault
     */
    public function codeReverseOneClick($buyOrder)
    {
        $codeReverseOneClickInput = new OneClickReverseInput();
        $codeReverseOneClickInput->buyorder = $buyOrder;

        return $this->service->codeReverseOneClick($codeReverseOneClickInput)->return;
    }

    /**
     * Deletes a customer inscription
     *
     * @param string $userToken Customer's token
     * @param string $username Customer username
     * @return bool
     * @throws Exceptions\InvalidCertificateException
     * @throws \SoapFault
     */
    public function removeUser($userToken, $username)
    {
        $removeUserInput = new OneClickRemoveUserInput();
        $removeUserInput->tbkUser = $userToken;
        $removeUserInput->username = $username;

        return $this->service->removeUser($removeUserInput)->return;
    }
}
