<?php

namespace Freshwork\Transbank;


use Freshwork\Transbank\WebpayOneClick\initInscriptionResponse;
use Freshwork\Transbank\WebpayOneClick\oneClickInscriptionOutput;

/**
 * Class RedirectorHelper
 * @package Freshwork\Transbank
 */
class RedirectorHelper
{
    /**
     * @var string
     */
    public $title = 'Redireccionando a Webpay...';

    /**
     * Get the basic form a script to redirect the user for one click
     * @param oneClickInscriptionOutput $initInscriptionResponse
     * @return string
     */
    public function getOneClickRedirectHtml(oneClickInscriptionOutput $initInscriptionResponse)
    {
        $response = $initInscriptionResponse;
        return $this->addHtmlWrapper($this->getForm($response->urlWebpay, $response->token));
    }

    /**
     * Add Base HTML tags
     *
     * @param $formHtml
     * @return string
     */
    public function addHtmlWrapper($formHtml)
    {
        return
'<html>
    <head>
        <title>' . $this->title . '</title>
    </head>
    <body>
        ' . $formHtml . '
    </body>

</html>';
    }

    /**
     * @param string $urlWebpay
     * @param string $token
     * @return string
     */
    public function getForm($urlWebpay, $token)
    {
        $rand = uniqid();

        return '
        <form action="' . $urlWebpay . '" id="webpay-form-' . $rand . '" method="POST">
            <input type="hidden" name="TBK_TOKEN" value="' . $token . '" />
        </form>

        <script>document.getElementById("webpay-form-' . $rand . '").submit();</script>';
    }
}