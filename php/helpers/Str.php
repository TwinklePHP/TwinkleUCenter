<?php
/**
 * Created by PhpStorm.
 * User: huanjin
 * Date: 2017/11/15
 * Time: 21:37
 */

namespace app\helpers;


class Str
{
    /**
     * 字符转换
     *
     * @param $string
     * @param string $separator 分割符
     * @return mixed
     */
    public static function ucWords($string, $separator = '-')
    {
        static $ucWords = [];
        $key = md5($string);

        if (!isset($ucWords[$key])) {
            $string = str_replace($separator, ' ', $string);
            $string = ucwords($string);
            $string = str_replace(' ', '', $string);
            $ucWords[$key] = $string;
        }

        return $ucWords[$key];
    }

    /**
     * 字符转换
     *
     * @param $string
     * @param string $separator
     * @return mixed|string
     */
    public static function revertUcWords($string, $separator = '-')
    {
        static $ucWords = [];
        $key = $string . $separator;

        if (isset($ucWords[$key])) {
            return $ucWords[$key];
        } elseif (!ctype_lower($string)) {
            $string = preg_replace_callback(
                '/([A-Z])/',
                function ($matches) use ($separator) {
                    return $separator . strtolower($matches[0]);
                },
                $string
            );
            $string = trim($string, $separator);
        }

        return $ucWords[$key] = $string;
    }
}