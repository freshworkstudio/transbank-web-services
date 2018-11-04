<?php
/**
 * Clase NullificationOutput
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayCaptureNullify
 * @author Luis Urrutia <luis@urrutia.me>
 * @version 1.1.0
 */

namespace Freshwork\Transbank\WebpayCaptureNullify;

/**
 * Clase NullificationOutput
 *
 * @package Freshwork\Transbank\WebpayCaptureNullify
 */
class NullificationOutput
{
    /** @var string $authorizationCode Código de autorización de la anulación */
    public $authorizationCode;

    /** @var string $authorizationDate Fecha y hora de la autorización */
    public $authorizationDate;

    /** @var int|float $balance Saldo actualizado de la transacción */
    public $balance;

    /** @var int|float $nullifiedAmount Monto anulado */
    public $nullifiedAmount;

    /** @var string $token Token de la transacción */
    public $token;
}
