<?php
header('Content-Type:text/html;charset=UTF-8');
define('__ROOT__',dirname(__FILE__).'/');
define('__INCLUDE__',dirname(__FILE__).'/include/');


require_once(__INCLUDE__.'functions.php');
require_once(__INCLUDE__.'collector.php');
collector::register_collector('user_name_gbk_collector');
collector::register_collector('user_info_collector');
$collector = new collector();

$user_name = $_GET['user_name'] ? $_GET['user_name'] : '新依网络';
$start_time	=	microtime();
$collector($user_name);
$end_time   =   microtime();

print_r($collector);
echo json_encode($collector);
echo '<br>采集用时：'.$end_time - $start_time .'毫秒</br>';
exit;