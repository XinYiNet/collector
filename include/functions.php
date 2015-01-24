<?php
/**
 * Author Vision
 * Email  521287718@qq.com
 * Date: 2015-01-23 08:35
 */

/**
 * @param string $class_name
 * @throws Exception
 */
function __autoload($class_name){
    $class_name = str_ireplace('_collector','.collector',$class_name);
    $class_name = str_ireplace('_vo','.vo',$class_name);

    if(file_exists(__INCLUDE__.'class/'.$class_name.'.class.php')){

        include_once(__INCLUDE__.'class/'.$class_name.'.class.php');

    }elseif(file_exists(__INCLUDE__.'collector/'.$class_name.'.php')){

        include_once(__INCLUDE__.'collector/'.$class_name.'.php');

    }elseif(file_exists(__INCLUDE__.'vo/'.$class_name.'.php')){

        include_once(__INCLUDE__.'vo/'.$class_name.'.php');

    }else {

        throw(new Exception($class_name . '类不存在'));
    }
}

function set_encode($string , $encode = 'utf-8', $decode = 'gbk'){
	return  iconv($encode, $decode, $string);
	
}

function strip_array_tag(array $arr){
	foreach($arr as $key => $val){
		$arr[$key] = trim(strip_tags($val));
	}
	return $arr;
}

