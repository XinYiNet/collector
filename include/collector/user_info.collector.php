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

		$this->curl->get($this->get_user_id_api, array(
			'username'	=>	$this->collector->user_name
		));
		
		$this->get_month_id()->get_profile()->get_credit_info();
    }
	
	//获取month_id
	private function get_month_id(){
		$html = $this->curl->response;
		if(preg_match('/http\:\/\/rate\.taobao\.com\/user\-rate\-(\w+)\.htm/', $html, $matches)){
			$this->collector->rate_url = $matches[0];
			$this->collector->month_id = $matches[1];
		}
		return $this;
	}
	
	//获取基本信息
	private function get_profile(){
		$this->curl->get($this->collector->rate_url);
		$this->curl->response = set_encode($this->curl->response, 'gbk', 'utf-8');
		//获取长ID
		if(preg_match('/http\:\/\/member1\.taobao\.com\/member\/user-profile-([0-9a-z]{32})\.htm/', $this->curl->response, $matches)){
			$this->collector->profile_url = $matches[0];
			$this->collector->long_id 	  = $matches[1];
		}
		//获取认证情况
		if(preg_match('/<img alt="(.*?)" border="0" align="absmiddle" src="(.*?)".*?>/', $this->curl->response, $matches)){
			$this->collector->auth_img_tag   = $matches[0];
			$this->collector->auth_img_src   = $matches[2];
			$this->collector->auth_title 	 = $matches[1];
		}
		//获取是否是卖家
		if(preg_match('/<input type="hidden" name="shopIdHidden" id="J_ShopIdHidden" value="(\d+)" \/>/', $this->curl->response, $matches)){
			$this->collector->is_seller = 1;
			$this->collector->shop_id   = $matches[1];
		}
		
		return $this;
	}
	
	private function get_credit_info(){
	
		$html = $this->curl->response;
		//获取好评率
		if(preg_match('/<em class="gray">.*?\b([0-9\.]+)%<\/em>/', $this->curl->response, $matches)){
			$this->collector->rate = $matches[1];
		}
		//获取好评率
		if(preg_match('/<li rel="(.*?)">最近一周<\/li>/', $this->curl->response, $matches)){
			//$this->collector->credit['detail']['week'] = $matches[1];
			//var_dump(json_decode($matches[1]));
		}
		print_r($matches);
	}
}