<?php
/**
 * Clase OneClickRemoveUserInput
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayOneClick
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank\WebpayOneClick;

/**
 * Clase OneClickRemoveUserInput
 *
 * @package Freshwork\Transbank\WebpayOneClick
 */
class OneClickRemoveUserInput
{
    /** @var string $tbkUser Identificador único de la inscripción del cliente	 */
    public $tbkUser;

    /** @var string $username Identificador único del cliente en el comercio */
    public $username;
}
