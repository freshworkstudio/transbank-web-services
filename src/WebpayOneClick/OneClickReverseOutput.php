<?php
/**
 * Clase OneClickReverseOutput
 *
 * @package Freshwork\Transbank
 * @subpackage WebpayOneClick
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank\WebpayOneClick;

/**
 * Clase OneClickReverseOutput
 *
 * @package Freshwork\Transbank\WebpayOneClick
 */
class OneClickReverseOutput
{
    /** @var int $reverseCode Identificador único de la transacción de reversa */
    public $reverseCode;

    /** @var bool $reversed Indica si tuvo éxito la reversa */
    public $reversed;
}
