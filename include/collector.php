<?php
/**
 * Author Vision
 * Email  521287718@qq.com
 * Date: 2015-01-23 08:29
 */

/**
 * Class collector
 */
final class collector {


    private  static $hooks = array();
	public $user_name;

    public function __construct(){
         new collector_vo($this, base_vo::BOUND_MERGE);
    }

    /**
     * 注册一个采集器
     * @param string $class_name
     * @return true
     */
    public static function register_collector($class_name){
        if(class_exists($class_name) && !in_array($class_name, self::$hooks)){
            self::$hooks[] = $class_name;
        }
        return true;
    }

    /**
     * 魔术方法-循环执行采集器
     * @param $user_name
     * @return $this
     */
    public function __invoke($user_name){
		$this->user_name = $user_name;
		
        foreach(self::$hooks as $class_name){
            if(class_exists($class_name)){
                $object = new $class_name;
                $object($this);
            }
        }
        return $this;
    }
}