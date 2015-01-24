<?php
/**
 * Author Vision
 * Email  521287718@qq.com
 * Date: 2015-01-23 08:58
 */

abstract class base_vo {

    const BOUND_MERGE   =   TRUE;
    const BOUN_ONLY     =   FALSE;

    final public function __construct( &$args , $merge = self::BOUN_ONLY){

        switch($merge){
            case self::BOUN_ONLY    :
                $this->vo_bound($args);
                break;
            case self::BOUND_MERGE  :
                $this->merge_vo($args);
                break;
        }


    }


    private   function merge_vo(&$args){
        foreach($this as $key=>$val){
            $args->$key = $val;
        }

    }

    private function vo_bound(array $args){
        foreach($this as $key=>$val){

            if(isset($args[$key]) ){

                $this->$key = $args[$key];

            }
        }
    }

}