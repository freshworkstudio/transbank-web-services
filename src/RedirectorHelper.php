<?php
namespace Freshwork\Transbank;

/**
 * Class RedirectorHelper
 * @package Freshwork\Transbank
 */
class RedirectorHelper
{
    /**
     * @var string
     */
    public static $title = 'Redireccionando a Webpay...';

    /**
     * Get the basic form a script to redirect the user for one click
     * @param string $url
     * @param string $token
     * @param string $field_name
     * @return string
     */
    public static function redirectHTML($url, $token = '', $field_name = 'TBK_TOKEN')
    {
        if (!$token) {
            $token = $_POST['token_ws'];
        }
        return self::addHtmlWrapper(self::getForm($url, $token, $field_name));
    }

    public static function redirectBackNormal($url, $token = '', $field_name = 'token_ws')
    {
        return self::redirectHTML($url, $token, $field_name);
    }

    /**
     * Add Base HTML tags
     *
     * @param $formHtml
     * @return string
     */
    public static function addHtmlWrapper($formHtml)
    {
        return
'<html>
    <head>
        <title>' . self::$title . '</title>
    </head>
    <body>
        ' . $formHtml . '
    </body>

</html>';
    }

    /**
     * @param string $urlWebpay
     * @param string $token
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
