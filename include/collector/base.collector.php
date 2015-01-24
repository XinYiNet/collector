<?php
/**
 * Author Vision
 * Email  521287718@qq.com
 * Date: 2015-01-23 08:47
 */

/**
 * Class base_collector
 */
abstract class base_collector{
	protected $collector = null;
	protected $curl = null;
	
	public function __construct(){
		$this->curl = new Curl();
	}
    /**
     * @param $user_name
     * @param $collector
     * @return mixed
     */
    abstract protected function start();

    /**
     * @param $user_name
     * @param $collector
     * @return mixed
     */
    final public function __invoke(collector &$collector){
	   $this->collector = &$collector;
	   try{
			return $this->start();
	   }catch(Exception $e){
			echo $e->getMessge();
	   }
    }


}