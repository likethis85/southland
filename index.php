<?php
define('SOUTHLAND', true);
define("APP_PATH",dirname(__FILE__));
define("WORKSPACE", basename(__FILE__,".php"));
if( true != @file_exists(APP_PATH.'/config.php') ){require(APP_PATH.'/install.php');exit;}
// 网站主体模块程序入口文件

// 载入配置与定义文件
require("config.php");

spRun();
