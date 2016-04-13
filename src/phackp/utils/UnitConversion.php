<?php
/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 13/04/2016
 * Time: 14:54
 */

namespace yuxblank\phackp\utils;


class UnitConversion
{

    public static function byteConvert($bytes)
    {
        if ($bytes === 0)
            return "0.00 B";

        $s = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        $e = floor(log($bytes, 1024));
        return round($bytes/pow(1024, $e), 2).$s[$e];
    }

}