<?php
/**
 * Author Vision
 * Email  521287718@qq.com
 * Date: 2015-01-24 12:47
 */

class collector_enum {

    //角色类型
    const IS_BUYER  =   1;  //买家标示
    const IS_SELLER =   1;  //卖家标示
    const IS_TMALL  =   1;  //天猫商城标示

    //认证情况
    const AUTH_ALIPAY_PERSON    =   1;  //支付宝个人认证
    const AUTH_ALIPAY_COMPANY   =   2;  //支付宝企业认证


    public static $auth_title  =  array(
        '支付宝个人认证'   =>  self::AUTH_ALIPAY_PERSON  ,
        '支付宝企业认证'   =>  self::AUTH_ALIPAY_COMPANY
    );

}