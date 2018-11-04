<?php
/**
 * Clase DetailInput
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayStandard
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank\WebpayStandard;

/**
 * Clase DetailInput
 *
 * @package Freshwork\Transbank\WebpayStandard
 */
class DetailInput
{
    /** @var string $serviceId Identificador del servicio */
    public $serviceId;

    /** @var string $cardHolderId RUT del cliente */
    public $cardHolderId;

    /** @var string $cardHolderName Nombres del cliente */
    public $cardHolderName;

    /** @var string $cardHolderLastName1 Apellido paterno del cliente */
    public $cardHolderLastName1;

    /** @var string $cardHolderLastName2 Apellido materno del cliente */
    public $cardHolderLastName2;

    /** @var string $cardHolderMail Correo electrónico del cliente */
    public $cardHolderMail;

    /** @var string $cellPhoneNumber Teléfono del cliente */
    public $cellPhoneNumber;

    /** @var string $expirationDate Fecha de expiración de la suscripción */
    public $expirationDate;

    /** @var string $commerceMail Correo electrónico del comercio */
    public $commerceMail;

    /** @var string $ufFlag Indica si el cobro es realizado en UF */
    public $ufFlag;
}
