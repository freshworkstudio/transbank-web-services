<?php
namespace Freshwork\Transbank\WebpayCaptureNullify;

class NullificationOutput
{
    public $authorizationCode; // string
    public $authorizationDate; // dateTime
    public $balance; // decimal
    public $nullifiedAmount; // decimal
    public $token; // string
}
