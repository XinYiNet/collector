<?php

define('__ROOT__',dirname(__FILE__).'/');
define('__INCLUDE__',dirname(__FILE__).'/include/');


require_once(__INCLUDE__.'functions.php');
require_once(__INCLUDE__.'collector.php');
collector::register_collector('user_name_gbk_collector');
collector::register_collector('user_info_collector');
$collector = new collector();

$user_name = $_GET['user_name'] ? $_GET['user_name'] : '新依网络';
Time::start();
$result = $collector($user_name);
Time::end();
Response::make();
