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
     * @return mixed
     */
    abstract protected function start();


    /**
     * @param collector $collector
     */
    final public function __invoke(collector &$collector){
	   $this->collector = &$collector;
        return $this->start();
    }


}