<?php

/**
 * Author Vision
 * Email  521287718@qq.com
 * Date: 2015-01-23 08:57
 */
class collector_vo extends base_vo
{

    //用户名
    public $user_name;
    //用户名GBK编码
    public $user_name_gbk;
    //user_id
    public $user_id;
    //month_id
    public $month_id;
    //long_id
    public $long_id;
	//是否卖家
    public $is_seller ;
    //是否买家
    public $is_buyer ;
    //是否天猫商家
    public $is_tmall ;
    //认证情况
    public $auth;
    //认证情况文字
    public $auth_title;
    //认证图片
    public $auth_img;
    //信用评价的url
    public $rate_url;
    //个人档案URL
    public $profile_url;
    //所在地区
    public $address;
    //买家信用
    public $buyer_rank;
    //买家好评率
    public $buyer_rate;
    //注册时间
    public $register_date;
    //上次登录时间
    public $last_login_time;

    //信用情况
    public $rank = array(
        //总好评数
        'rate_ok'       =>  null,
        //总中评数
        'rate_normal'   =>  null,
        //总差评数
        'rate_bad'      =>  null,
        //最近详情
        'detail'        =>  array(
            //最近一周
            'week'  =>  array(
                'rate_ok'       =>  null,
                'rate_normal'   =>  null,
                'rate_bad'      =>  null,
            ),
            //最近一个月
            'month'  =>  array(
                'rate_ok'       =>  null,
                'rate_normal'   =>  null,
                'rate_bad'      =>  null,
            ),
            //最近半年
            'half_year'  =>  array(
                'rate_ok'       =>  null,
                'rate_normal'   =>  null,
                'rate_bad'      =>  null,
            ),
            //半年以前
            'ago'  =>  array(
                'rate_ok'       =>  null,
                'rate_normal'   =>  null,
                'rate_bad'      =>  null,
            )
        )
    );

    //以下是淘宝卖家信息

    //卖家信用
    public $seller_rank = null;
    //卖家好评率
    public $seller_rate = null;

    //店铺信息
    public $shop = array(
        //店铺名
        'shop_name'     => NULL,
        //店铺ID
        'shop_id'       => NULL,
        //开店时间
        'start_date'    => NULL,
        //开店天数
        'start_days'    => NULL,
        //店铺收藏地址
        'favorite_url'  =>  '',

    );


}