<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 8/18/13
 * Time: 11:07 PM
 * To change this template use File | Settings | File Templates.
 */

namespace classes\core\helpers;


class SessionHelper
{
    public static function SetFlashMessage($message, $section = 'flash')
    {
        $_SESSION['Message'][$section] = array(
            'message' => $message,
            'element' => 'default',
            'params' => array()
        );
    }

    public static function GetFlashMessage($section = 'flash')
    {
        $message = "";
        if (isset($_SESSION['Message'][$section])) {
            $message = $_SESSION['Message'][$section]['message'];
            unset($_SESSION['Message'][$section]);
            if (count($_SESSION['Message']) == 0) {
                unset($_SESSION['Message']);
            }
        }
        return $message;
    }

    public static function Read($index, $default = null)
    {
        if(isset($_SESSION[$index])) {
            return $_SESSION[$index];
        }
        return $default;
    }

    public static function Write($index, $value)
    {
        $_SESSION[$index] = $value;
    }

    public static function Has($index) {
        return isset($_SESSION[$index]);
    }
}