<?php
namespace Freshwork\Transbank\WebpayStandard;

class InitTransactionInput
{

    /** @var string */
    public $wSTransactionType;

    /** @var string */
    public $commerceId;

    /** @var string */
    public $buyOrder;

    /** @var string */
    public $sessionId;

    /** @var string anyURI */
    public $returnURL;

    /** @var string anyURI */
    public $finalURL;

    /** @var wsTransactionDetail */
    public $transactionDetails;

    /** @var DetailInput */
    public $wPMDetail;
}
