<?php
/**
 * Clase RedirectorHelper
 *
 * @package Freshwork\Transbank
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank;

/**
 * Clase RedirectorHelper
 *
 * @package Freshwork\Transbank
 */
class RedirectorHelper
{
    /** @var string $title Titulo de la página generada */
    public static $title = 'Redireccionando a Webpay...';

    /**
     * Redirigir a Webpay
     *
     * @param string $url URL entregada por Webpay donde realizar la redirección
     * @param string $token Token entregado por Webpay
     * @param string $field_name Nombre del campo del Token
     * @return string
     */
    public static function redirectHTML($url, $token = '', $field_name = 'TBK_TOKEN')
    {
        if (!$token) {
            $token = $_POST['token_ws'];
        }
        return self::addHtmlWrapper(self::getForm($url, $token, $field_name));
    }

    /**
     * Redirigir al voucher de éxito de Webpay
     *
     * @param string $url URL entregada por Webpay donde realizar la redirección
     * @param string $token Token entregado por Webpay
     * @param string $field_name Nombre del campo del Token
     * @return string
     */
    public static function redirectBackNormal($url, $token = '', $field_name = 'token_ws')
    {
        return self::redirectHTML($url, $token, $field_name);
    }

    /**
     * Genera esqueleto HTML que envolverá al formulario
     *
     * @param string $form_html HTML del formulario
     * @return string
     */
    public static function addHtmlWrapper($form_html)
    {
        return
        '<html>
            <head>
                <title>' . self::$title . '</title>
            </head>
            <body>
                ' . $form_html . '
            </body>
        
        </html>';
    }

    /**
     * Genera HTML del formulario
     *
     * @param string $url URL entregada por Webpay donde realizar la redirección
     * @param string $token Token entregado por Webpay
     * @param string $field_name Nombre del campo del Token
     * @return string
     */
    public static function getForm($url, $token, $field_name)
    {
        $rand = uniqid();

        return '
        <form action="' . $url . '" id="webpay-form-' . $rand . '" method="POST">
            <input type="hidden" name="' . $field_name . '" value="' . $token . '" />
        </form>

        <script>document.getElementById("webpay-form-' . $rand . '").submit();</script>';
    }
}
