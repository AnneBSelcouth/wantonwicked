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
        $_SESSION['Flash'][$section][] = array(
            'message' => $message,
            'key' => 'flash',
            'element' => 'Flash/default',
            'params' => []
        );
    }

    public static function GetFlashMessage($section = 'flash')
    {
        $messages = [];
        if (isset($_SESSION['Flash'][$section]) && count($_SESSION['Flash'][$section])) {
            foreach($_SESSION['Flash'][$section] as $data) {
                $messages[] = $data['message'];
            }
            unset($_SESSION['Flash'][$section]);
        }
        return implode(' ', $messages);
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
