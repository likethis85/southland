<?php
define('SOUTHLAND', true);
define('WORKSPACE', basename(__FILE__,".php"));
// 后台模块程序入口文件

// 载入配置与定义文件
require("config.php");

spRun();
