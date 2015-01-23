<?php
/**
 * Author Vision
 * Email  521287718@qq.com
 * Date: 2015-01-23 08:58
 */

abstract class base_vo {

    final public function __construct(array $args){

        foreach($this as $key=>$val){

            if(isset($args[$key]) ){

                $this->$key = $args[$key];

                if(method_exists($this, $key)){
                    $this->$key();
                }

            }
        }
    }
    
}