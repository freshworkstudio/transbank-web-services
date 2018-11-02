<?php
namespace Freshwork\Transbank\WebpayCaptureNullify;

class CaptureInput
{
    public $commerceId; // long
    public $buyOrder; // string
    public $authorizationCode; // string
    public $captureAmount; // decimal
}
