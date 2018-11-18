<?php
/**
 * Class RedirectorHelper
 *
 * @package Freshwork\Transbank
 * @author Gonzalo De Spirito <gonzunigad@gmail.com>
 * @version 0.1 (06/07/2016)
 */

namespace Freshwork\Transbank;

/**
 * Class RedirectorHelper
 *
 * @package Freshwork\Transbank
 */
class RedirectorHelper
{
    /** @var string $title Title of the generated page */
    public static $title = 'Redireccionando a Webpay...';

    /**
     * Redirect to Webpay
     *
     * @param string $url Webpay URL
     * @param string $token Webpay token
     * @param string $field_name Webpay token field name
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
     * Redirect to Webpay voucher
     *
     * @param string $url Webpay URL
     * @param string $token Webpay token
     * @param string $field_name Webpay token field name
     * @return string
     */
    public static function redirectBackNormal($url, $token = '', $field_name = 'token_ws')
    {
        return self::redirectHTML($url, $token, $field_name);
    }

    /**
     * Create HTML base to wrap the form
     *
     * @param string $form_html Form HTML
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
     * Create a HTML form
     *
     * @param string $url Webpay URL
     * @param string $token Webpay token
     * @param string $field_name Webpay token field name
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
