<?php
/**
 * Author Vision
 * Email  521287718@qq.com
 * Date: 2015-01-23 08:57
 */

final class user_name_vo extends base_vo {
    public $user_name;
	public $user_name_gbk;

	public function user_name(){
		$this->user_name_gbk = urlencode(iconv('utf-8', 'gbk', $this->user_name));
	}
	
}