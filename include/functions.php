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


//jiequstr函数定义开始
function jqstr($mubiaostr,$ksstr,$jsstr){
	if($mubiaostr=='') return false;
	if($ksstr==''){
		$jiequks=0;return false;
	}else{
		$chucuo1=0;
		$arr1=explode('(*)',$ksstr);
		$len1=count($arr1);
		$chaxunwz=0;
		$feikongnum1=0;
		for($i=0;$i<$len1;$i++){
			if($arr1[$i]=='')continue;
			$feikongnum1++;
			if(($wz=strpos($mubiaostr,$arr1[$i],$chaxunwz))!==false)
				$chaxunwz=$wz+strlen($arr1[$i]);
			else {
				$chucuo1=1;
				return false;
			}
		}
		if($chucuo1==1)$jiequks=0;

		else $jiequks=$chaxunwz;
	}
	if($jsstr==''){
		return false;
	}else{
		$chucuo2=0;
		$arr2=explode('(*)',$jsstr);
		$len2=count($arr2);
		$chaxunwz=$jiequks;
		$feikongnum2=0;
		for($i=0;$i<$len2;$i++){
			if($arr2[$i]=='')continue;
			$feikongnum2++;
			if(($wz=strpos($mubiaostr,$arr2[$i],$chaxunwz))!==false){
				$chaxunwz=$wz+strlen($arr2[$i]);
				if($feikongnum2==1)$enddian=$wz;
	
			}else {
				$chucuo2=1;
				return false;
			}

		}

		if($chucuo2==1)$jiequjs=strlen($mubiaostr);

		else $jiequjs=$enddian;

	}
	$jiequstr=substr($mubiaostr,$jiequks,$jiequjs-$jiequks);
	return $jiequstr;
}


