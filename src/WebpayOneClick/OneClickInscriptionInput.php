<?php
/**
 * Clase OneClickInscriptionInput
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayOneClick
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank\WebpayOneClick;

/**
 * Clase OneClickInscriptionInput
 *
 * @package Freshwork\Transbank\WebpayOneClick
 */
class OneClickInscriptionInput
{
    /** @var string $email Correo electrónico del cliente registrado en el comercio */
    public $email;

    /** @var string $responseURL URL a la que será enviado el cliente finalizado el proceso de inscripción */
    public $responseURL;

    /** @var string $username Identificador único del cliente en el comercio */
    public $username;
}
