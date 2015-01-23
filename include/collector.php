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

    /**
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
     * @param user_name_vo $user_name_vo
     * @return array
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