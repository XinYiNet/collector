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
$collector($user_name);
print_r($collector);

echo json_encode($collector);
exit;