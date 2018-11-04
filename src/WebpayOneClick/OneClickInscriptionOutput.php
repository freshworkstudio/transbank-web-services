<?php
/**
 * Clase OneClickInscriptionOutput
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayOneClick
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank\WebpayOneClick;

/**
 * Clase OneClickInscriptionOutput
 *
 * @package Freshwork\Transbank\WebpayOneClick
 */
class OneClickInscriptionOutput
{
    /** @var string $token Identificador único del proceso de inscripción */
    public $token;

    /** @var string $urlWebpay URL de Webpay para iniciar la inscripción */
    public $urlWebpay;
}
