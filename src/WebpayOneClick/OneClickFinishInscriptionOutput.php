<?php
/**
 * Clase OneClickFinishInscriptionOutput
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayOneClick
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank\WebpayOneClick;

/**
 * Clase OneClickFinishInscriptionOutput
 *
 * @package Freshwork\Transbank\WebpayOneClick
 */
class OneClickFinishInscriptionOutput
{

    /** @var string $authCode Código que identifica la autorización de la inscripción */
    public $authCode;

    /** @var string $creditCardType Marca de la tarjeta de crédito que fue inscrita por el cliente */
    public $creditCardType;

    /** @var string $last4CardDigits Últimos 4 dígitos y/o BIN de la tarjeta de crédito utilizada */
    public $last4CardDigits;

    /** @var int $responseCode Código de respuesta del proceso de inscripción */
    public $responseCode;

    /** @var string $tbkUser Identificador único de la inscripción del cliente */
    public $tbkUser;
}
