<?php
/**
 * Author Vision
 * Email  521287718@qq.com
 * Date: 2015-01-23 10:25
 */

/**
 * Class user_name_gbk_collector
 */
class user_name_gbk_collector extends base_collector {


    /**
     * 生成一个gbk编码的用户名
     * @return mixed|void
     */
    protected  function start(){
		$this->collector->user_name_gbk =  urlencode(set_encode($this->collector->user_name));
    }
}