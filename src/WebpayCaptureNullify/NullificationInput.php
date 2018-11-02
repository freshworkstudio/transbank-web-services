<?php
namespace Freshwork\Transbank\WebpayCaptureNullify;

class NullificationInput
{
    public $commerceId; // long
    public $buyOrder; // string
    public $authorizedAmount; // decimal
    public $authorizationCode; // string
    public $nullifyAmount; // decimal
}
