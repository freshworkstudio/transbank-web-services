<?php
/**
 * Clase CaptureOutput
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayCaptureNullify
 * @author Luis Urrutia <luis@urrutia.me>
 * @version 1.1.0
 */

namespace Freshwork\Transbank\WebpayCaptureNullify;

/**
 * Clase CaptureOutput
 *
 * @package Freshwork\Transbank\WebpayCaptureNullify
 */
class CaptureOutput
{
    /** @var string $authorizationCode Código de autorización de la captura diferida  */
    public $authorizationCode;

    /** @var string $authorizationDate Fecha y hora de la autorización */
    public $authorizationDate;

    /** @var int|float $capturedAmount Monto capturado */
    public $capturedAmount;

    /** @var string $token Token de la transacción */
    public $token;
}
