<?php
/**
 * Author Vision
 * Email  521287718@qq.com
 * Date: 2015-01-23 08:33
 */
class response_enum {
    const OK    =   1000;   //成功

    //请求响应为空
    const EMPTY_RESPONSE = 1001;
    //HTTP请求错误
    const HTTP_FAIL       = 1002;
    //淘宝账号格式不正确
    const ACCOUNT_INCORRECT = 2001;
    //账号不存在
    const ACCOUNT_NOT_EXIST = 2002;
    public static $msg = array(
        self::OK => 'success'
    );



}