<?php
define('SOUTHLAND', true);
// 用户模块程序入口文件

// 载入配置与定义文件
require("config.php");

// 当前模块附加的配置
$spConfig['controller_path'] = APP_PATH.'/modules/'.basename(__FILE__,".php");

// 载入SpeedPHP框架
require(SP_PATH."/SpeedPHP.php");
spRun();
