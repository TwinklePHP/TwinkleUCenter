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
     * @param string $string
     * @param string $separator 分割符
     * @param bool $lcFirst 是否首字母小写
     * @return string
     */
    public static function ucWords($string, $separator = '-',$lcFirst = false)
    {
        $string = str_replace($separator, ' ', $string);
        $string = ucwords($string);
        $string = str_replace(' ', '', $string);
        $lcFirst && $string = lcfirst($string);

        return $string;

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