<?php
/**
 * Class OneClickInscriptionInput
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayOneClick
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank\WebpayOneClick;

/**
 * Class OneClickInscriptionInput
 *
 * @package Freshwork\Transbank\WebpayOneClick
 */
class OneClickInscriptionInput
{
    /** @var string $email Customer's e-mail */
    public $email;

    /** @var string $responseURL URL of the commerce to which Webpay will redirect subsequent to the subscription */
    public $responseURL;

    /** @var string $username Customer username */
    public $username;
}
