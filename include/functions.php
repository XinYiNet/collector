<?php
/**
 * Author Vision
 * Email  521287718@qq.com
 * Date: 2015-01-23 08:35
 */



/**
 * 自动加载
 * @param string $class_name
 * @throws Exception
 */
function __autoload($class_name){

    $class_name = str_ireplace('_collector','.collector',$class_name);
    $class_name = str_ireplace('_vo','.vo',$class_name);
    $class_name = str_ireplace('_enum','.enum',$class_name);

    //基础封装类
    if(file_exists(__INCLUDE__.'class/'.$class_name.'.class.php')){

        include_once(__INCLUDE__.'class/'.$class_name.'.class.php');

    //采集器类
    }elseif(file_exists(__INCLUDE__.'collector/'.$class_name.'.php')){

        include_once(__INCLUDE__.'collector/'.$class_name.'.php');

    //VO类
    }elseif(file_exists(__INCLUDE__.'vo/'.$class_name.'.php')){

        include_once(__INCLUDE__.'vo/'.$class_name.'.php');
    //枚举
    }elseif(file_exists(__INCLUDE__.'enum/'.$class_name.'.php')) {

        include_once(__INCLUDE__ . 'enum/' . $class_name . '.php');
    }else {

        throw(new Exception($class_name . '类不存在'));
    }
}


/**
 * 将字符串转换编码
 * @param $string
 * @param string $encode
 * @param string $decode
 * @return string
 */
function set_encode($string , $encode = 'utf-8', $decode = 'gbk'){
	return  iconv($encode, $decode, $string);
	
}


/**
 * 删除一个数组中的所有元素的html标签
 * @param array $arr
 * @return array
 */
function strip_array_tag(array $arr){
	foreach($arr as $key => $val){
		$arr[$key] = trim(strip_tags($val));
	}
	return $arr;
}

