<?php
/**
 * Author Vision
 * Email  521287718@qq.com
 * Date: 2015-01-23 10:25
 */

/**
 * Class user_info_collector
 */
class base_info_collector extends base_collector {


    private $get_user_id_api = 'www.dasoke.com/ajax/get/rate/';

    private $get_user_profile_api = 'member1.taobao.com/member/user_profile.jhtml?user_id=';


    /**
     * @return $this|mixed
     * @throws ErrorException
     * @throws Exception
     */
    protected  function start(){
        //先根据用户名获取month_id和rate_url
        $this->curl->get($this->get_user_id_api, array(
            'username'	=>	$this->collector->user_name
        ));
        if(!$this->curl->response){
            Response::error(response_enum::HTTP_FAIL);
        }

        //获取month_id和rate_url
        if(preg_match('/http\:\/\/rate\.taobao\.com\/user\-rate\-(\w+)\.htm/', $this->curl->response, $matches)){
            $this->collector->rate_url = $matches[0];
            $this->collector->month_id = $matches[1];
        }

        //再请求rate_url
        $this->curl->get($this->collector->rate_url);

        if(!$this->curl->response){
            Response::error(1);
        }

        //删除换行符
        $this->curl->response= str_replace(array("\r\n", "\r", "\n"), "", $this->curl->response);
        //编码转换为UTF-8
        $this->curl->response = set_encode($this->curl->response, 'gbk', 'utf-8');


        //获取认证情况
        if(preg_match('/<\s*img\s+alt="(.*?认证)"\s+[^>]*?src=\s*(\'|\")(.*?)\\2[^>]*?\/?\s*title="\\1">/i', $this->curl->response, $matches)){
            $this->collector->auth_img        = $matches[3];		//认证标志图片
            $this->collector->auth_title 	 = $matches[1];     //认证信息
            $this->collector->auth             = collector_enum::$auth_title[$matches[1]];
        }

        //判断是否天猫商家
        if(preg_match('/<input type="hidden" name="isB2C" id="isB2C" value="(\w+)" \/>/', $this->curl->response, $matches)  && $matches[1] == 'true'){

				 $this->collector->is_tmall   =   collector_enum::IS_TMALL;
                 $this->collector->is_seller  =   collector_enum::IS_SELLER;
				 
				 //获取user_id 
				if(preg_match('/<input type="hidden" id="dsr-userid" value="(\d+)"\/>/', $this->curl->response, $matches)){
					$this->collector->user_id = $matches[1];
				}
                //所在地区
                if(preg_match('/<li>所在地区：(.*?)<\/li>/', $this->curl->response, $matches)) {
                    $this->collector->address = trim($matches[1]);
                }
                //获取店铺ID
                if(preg_match('/<input type="hidden" name="shopIdHidden" id="J_ShopIdHidden" value="(\d+)" \/>/', $this->curl->response, $matches)){
                    $this->collector->shop['shop_id']   =   $matches[1];
                }
                //获取店铺URL和店铺名以及long_id
                if(preg_match('/<a href="(http:\/\/[0-9a-z\.\/\-]+view_shop-(\w{32}).htm)" target="_blank">(.*?)<\/a>/', $this->curl->response, $matches)){
                    $this->collector->long_id               = $matches[2];
                    $this->collector->shop['shop_name']    = $matches[3];
                    $this->collector->shop['shop_url']     = $matches[1];
                }
        //是否卖家
        }elseif($this->collector->is_seller || preg_match('/<input type="hidden" name="shopIdHidden" id="J_ShopIdHidden" value="(\d+)" \/>/', $this->curl->response, $matches)){

            $this->collector->is_seller = collector_enum::IS_SELLER;
            $this->collector->shop['shop_id']   =   $matches[1];
            //获取user_id
            if(preg_match('/view_shop.htm?user_number_id=(\d+)\b/', $this->curl->response, $matches)){
                $this->collector->user_id = $matches[1];
            }
            //获取卖家好评率
            if(preg_match('/<em style="color:gray;">.*?\b([0-9\.]+)%<\/em>/', $this->curl->response, $matches)){
                $this->collector->rank['seller_rate'] = $matches[1];
            }
            //获取店铺URL和店铺名
            if(preg_match('/<a class="shop\-name" href="(http:\/\/.*?)"\s*>(.*?)<\/a>/', $this->curl->response, $matches)){
                $this->collector->shop['shop_name'] = trim(strip_tags($matches[2]));
                $this->collector->shop['shop_url']  = $matches[1];
            }
        //买家信息
        }else{

            //获取买家好评率
            if(preg_match('/<em class="gray">\b([0-9\.]+)%<\/em>/', $this->curl->response, $matches)){
                $this->collector->rate = $matches[1];
            }
            $this->collector->is_buyer = collector_enum::IS_BUYER;

        }
        //卖家和天猫
        if($this->collector->is_seller === collector_enum::IS_SELLER){

            //获取创店时间
            if(preg_match('/<input type="hidden" name="shopStartDate" id="J_showShopStartDate" value="([0-9\-]+)" \/>/', $this->curl->response, $matches)){
                $this->collector->shop['start_date']   = $matches[1];
                $this->collector->shop['start_timestamp'] = strtotime($matches[1]);
            }

            //获取店铺收藏链接
            if(preg_match('/href="(http:\/\/favorite.taobao.com\/popup\/add_collection.htm.*?)"/', $this->curl->response, $matches)){
                $this->collector->shop['favorite_url'] = $matches[1];
            }
        }
        //不是天猫店
        if($this->collector->is_tmall !== collector_enum::IS_TMALL){
            //获取信用记录
            if(preg_match_all('/<td class="rate\w{2,6}">(.*?)<\/td>/', $this->curl->response, $matches)){
                $credits = strip_array_tag($matches[1]);
                $this->collector->rank = $this->process_credit($credits);
            }
            //获取所在地区
            if(preg_match('/<dt>所在地区：<\/dt>\s*<dd>(.*?)<\/dd>/', $this->curl->response, $matches)){
                $address = strip_tags($matches[1]);
                $this->collector->address =trim($address);
            }
            //获取长ID和profile_url
            if(preg_match('/http\:\/\/member1\.taobao\.com\/member\/user-profile-([0-9a-z]{32})\.htm/', $this->curl->response, $matches)){
                $this->collector->profile_url = $matches[0];
                $this->collector->long_id 	  = $matches[1];
            }


        }
        return $this;
    }


    /**
     * 处理用户信誉详情
     * @param array $credits
     * @return array
     */
    private function process_credit(array $credits){

        //总好评数 = 最近半年好评 + 半年前好评
        $credit['total_rate_ok'] = $credits[6]     +  $credits[9];
        //总中评数 = 最近半年中评 + 半年前中评
        $credit['total_rate_normal'] = $credits[7] +  $credits[10];
        //总差评数 = 最近半年差评 + 半年前差评
        $credit['total_rate_bad'] = $credits[8] +  $credits[11];
        //总信用值 = 总好评数 - 总差评数
        if($this->collector->is_seller == collector_enum::IS_SELLER){
            $credit['seller_rank']	= $credit['total_rate_ok'] - $credit['total_rate_bad'];
        }else{
            $credit['buyer_rank']	= $credit['total_rate_ok'] - $credit['total_rate_bad'];
        }

        //最近一周
        $credit['detail']['week'] = array(
            'rate_ok'	  =>	$credits[0],
            'rate_normal' =>	$credits[1],
            'rate_bad'	  =>	$credits[2],
        );
        //最近一个月
        $credit['detail']['month'] = array(
            'rate_ok'	  =>	$credits[3],
            'rate_normal' =>	$credits[4],
            'rate_bad'	  =>	$credits[5],
        );
        //最近半年
        $credit['detail']['half_year'] = array(
            'rate_ok'	  =>	$credits[6],
            'rate_normal' =>	$credits[7],
            'rate_bad'	  =>	$credits[8],
        );
        //半年以前
        $credit['detail']['ago'] = array(
            'rate_ok'	  =>	$credits[9],
            'rate_normal' =>	$credits[10],
            'rate_bad'	  =>	$credits[11],
        );

        return $credit;
    }
}