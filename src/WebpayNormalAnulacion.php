<?php
namespace Freshwork\Transbank;

class nullify
{
    public $nullificationInput; //nullificationInput
}

class nullificationInput
{
    public $commerceId; //long
    public $buyOrder; //string
    public $authorizedAmount; //decimal
    public $authorizationCode; //string
    public $nullifyAmount; //decimal
}

class baseBean
{
}

class nullifyResponse
{
    public $return; //nullificationOutput
}

class nullificationOutput
{
    public $authorizationCode; //string
    public $authorizationDate; //dateTime
    public $balance; //decimal
    public $nullifiedAmount; //decimal
    public $token; //string
}

class capture
{
    public $captureInput; //captureInput
}

class captureInput
{
    public $commerceId; //long
    public $buyOrder; //string
    public $authorizationCode; //string
    public $captureAmount; //decimal
}

class captureResponse
{
    public $return; //captureOutput
}

class captureOutput
{
    public $authorizationCode; //string
    public $authorizationDate; //dateTime
    public $capturedAmount; //decimal
    public $token; //string
}

class WebpayNormalAnulacion
{
    public $soapClient;
    private static $classmap = array(
        'nullify' => 'nullify',
        'nullificationInput' => 'nullificationInput',
        'baseBean' => 'baseBean',
        'nullifyResponse' => 'nullifyResponse',
        'nullificationOutput' => 'nullificationOutput',
        'capture' => 'capture',
        'captureInput' => 'captureInput',
        'captureResponse' => 'captureResponse',
        'captureOutput' => 'captureOutput'
    );

    const INTEGRATION_WSDL = 'https://tbk.orangepeople.cl/WSWebpayTransaction/cxf/WSCommerceIntegrationService?wsdl';

    public function __construct($url = self::INTEGRATION_WSDL)
    {
        $this->soapClient = new TransbankSoap($url, array(
            "classmap" => self::$classmap,
            "trace" => true,
            "exceptions" => true
        ));
    }

    public function nullify($nullify)
    {
        $nullifyResponse = $this->soapClient->nullify($nullify);
        return $nullifyResponse;
    }

    public function capture($capture)
    {
        $captureResponse = $this->soapClient->capture($capture);
        return $captureResponse;
    }
}
