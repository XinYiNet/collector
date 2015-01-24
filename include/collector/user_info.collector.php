<?php
/**
 * Author Vision
 * Email  521287718@qq.com
 * Date: 2015-01-23 10:25
 */

/**
 * Class user_info_collector
 */
class user_info_collector extends base_collector {
	
	private $get_user_id_api = 'www.dasoke.com/ajax/get/rate/';
	private $get_user_profile_api = 'member1.taobao.com/member/user_profile.jhtml?user_id=';


    /**
     * @param $user_name
     * @param $result
     * @return mixed
     */
    protected  function start(){
		//先根据用户名获取month_id和rate_url
		$this->curl->get($this->get_user_id_api, array(
			'username'	=>	$this->collector->user_name
		));
		if(!$this->curl->response){
			throw(new Excption('服务器错误'));
		}
		
		//获取month_id和rate_url
		if(preg_match('/http\:\/\/rate\.taobao\.com\/user\-rate\-(\w+)\.htm/', $this->curl->response, $matches)){
			$this->collector->rate_url = $matches[0];
			$this->collector->month_id = $matches[1];
		}
		
		//再请求rate_url
		$this->curl->get($this->collector->rate_url);
		//删除换行符
		$this->curl->response= str_replace(array("\r\n", "\r", "\n"), "", $this->curl->response);
		//编码转换为UTF-8
		$this->curl->response = set_encode($this->curl->response, 'gbk', 'utf-8');
		
		//获取长ID和profile_url
		if(preg_match('/http\:\/\/member1\.taobao\.com\/member\/user-profile-([0-9a-z]{32})\.htm/', $this->curl->response, $matches)){
			$this->collector->profile_url = $matches[0];
			$this->collector->long_id 	  = $matches[1];
		}
		
		//获取认证情况
		if(preg_match('/<img alt=".*?" border="0" align="absmiddle" src="(http\:\/\/pics\.taobaocdn\.com.*?)" title="(.*?)">/', $this->curl->response, $matches)){
			$this->collector->auth_img_src   = $matches[1];		//
			$this->collector->auth_title 	 = $matches[2];
		}
		
		
		
		//获取是否是卖家
		if(preg_match('/<input type="hidden" name="shopIdHidden" id="J_ShopIdHidden" value="(\d+)" \/>/', $this->curl->response, $matches)){
			$this->collector->is_seller = 1;
			$this->collector->shop = new stdClass();
			$this->collector->shop->shop_id   = $matches[1];
			
			//获取卖家好评率
			if(preg_match('/<em style="color:gray;">.*?\b([0-9\.]+)%<\/em>/', $this->curl->response, $matches)){
				$this->collector->rate = $matches[1];
			}
			//获取创店时间
			if(preg_match('/<input type="hidden" name="shopStartDate" id="J_showShopStartDate" value="([0-9\-]+)" \/>/', $this->curl->response, $matches)){
				$this->collector->shop->start_date   = $matches[1];
			}
			//获取开店天数
			if(preg_match('/<input type="hidden" name="shopBetween" id="J_showShopBetween" value="(\d+)" \/>/', $this->curl->response, $matches)){
				$this->collector->shop->start_days   = $matches[1];
			}
			//获取店铺URL和店铺名
			if(preg_match('/<a class="shop\-name" href="(.*?)">\s+<span title="(.*?)">.*?<\/span>/', $this->curl->response, $matches)){
				$this->collector->shop->shop_name = $matches[2];
				$this->collector->shop->shop_url  = $matches[1];
			}
			//获取店铺收藏链接
			if(preg_match('/href="(http:\/\/favorite.taobao.com\/popup\/add_collection.htm.*?)"/', $this->curl->response, $matches)){
				$this->collector->shop->favorite_url = $matches[1];
			}
			
		}else{
			//获取买家好评率
			if(preg_match('/<em class="gray">.*?\b([0-9\.]+)%<\/em>/', $this->curl->response, $matches)){
				$this->collector->rate = $matches[1];
			}
			$this->collector->is_buyer = 1;
			
		}
		
		
		//获取信用记录
		if(preg_match_all('/<td class="rate\w{2,6}">(.*?)<\/td>/', $this->curl->response, $matches)){
			$credits = strip_array_tag($matches[1]);
			$this->collector->credit = $this->process_credit($credits);
		}
		
		echo $this->curl->response;
		return $this;
		
		
    }
	

	private function process_credit(array $credits){
	
			//总好评数 = 最近半年好评 + 半年前好评
			$credit['total_rate_ok'] = $credits[6]     +  $credits[9];
			//总中评数 = 最近半年中评 + 半年前中评
			$credit['total_rate_normal'] = $credits[7] +  $credits[10];
			//总差评数 = 最近半年差评 + 半年前差评
			$credit['total_rate_bad'] = $credits[8] +  $credits[11];
			//总信用值 = 总好评数 - 总差评数
			$credit['total_rate']	= $credit['total_rate_ok'] - $credit['total_rate_bad'];
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