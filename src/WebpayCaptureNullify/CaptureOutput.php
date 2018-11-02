<?php
namespace Freshwork\Transbank\WebpayCaptureNullify;

class CaptureOutput
{
    public $authorizationCode; // string
    public $authorizationDate; // dateTime
    public $capturedAmount; // decimal
    public $token; // string
}
