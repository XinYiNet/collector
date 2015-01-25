<?php
/**
 * Author Vision
 * Email  521287718@qq.com
 * Date: 2015-01-23 08:33
 */

class Response {
    public static $code = response_enum::OK;
    public static $msg  = 'success';
    public static $data = array();


    public static function  error($code){
        self::$code = $code;
        self::$msg = response_enum::$msg[$code];
        self::make();
    }

    public static function make(){
        $response = array(
            'code'  =>  self::$code,
            'msg'   =>  self::$msg,
            'data' => self::$data
        );
        $content = json_encode($response);
        header('Content-Type:text/json;charset=utf-8');
        echo $content;
        exit;
    }

}