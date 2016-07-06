<?php
namespace Freshwork\Transbank\WebpayStandard;

class wsInitTransactionInput {

    /** @var string wsTransactionType */
    var $wSTransactionType;

    /** @var string */
    var $commerceId;

    /** @var string */
    var $buyOrder;

    /** @var string */
    var $sessionId;

    /** @var string anyURI */
    var $returnURL;

    /** @var string anyURI */
    var $finalURL;

    /** @var wsTransactionDetail */
    var $transactionDetails;

    /** @var wpmDetailInput */
    var $wPMDetail;
}