<?php
/**
 * Author Vision
 * Email  521287718@qq.com
 * Date: 2015-01-23 08:33
 */

class Time{
    private static $start_time;
    private static $end_time;

    public static function start(){
        self::$start_time = microtime();
        return self::$start_time;
    }

    public static function end(){
        self::$end_time = microtime();
        return self::$end_time;
    }

    public static function diff(){
        $tmp_start = explode(' ', self::$start_time);
        $tmp_end   = explode(' ', self::$end_time);
        $totaltime = $tmp_end[0] - $tmp_start[0] + $tmp_end[1] - $tmp_start[1];
        return sprintf("%s",$totaltime);
    }
}