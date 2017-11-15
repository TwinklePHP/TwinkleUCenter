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
     * @param string $separator
     * @return mixed
     */
    public static function ucWords($string, $separator = '-')
    {
        static $ucWords = [];
        $key = md5($string);

        if (!isset($ucWords[$key])) {
            $string = str_replace($separator, ' ', $string);   // abc def ghi
            $string = ucwords($string);                 // Abc Def Ghi
            $string = str_replace(' ', '', $string);    // AbcDefGhi
            $ucWords[$key] = $string;
        }

        return $ucWords[$key];
    }
}