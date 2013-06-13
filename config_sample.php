<?php
if (!defined('SOUTHLAND')) { exit(1);}
// 定义当前目录
define("APP_PATH",dirname(__FILE__));
// 定义框架目录
define("SP_PATH",APP_PATH."/SpeedPHP");
// 默认时区设置
@date_default_timezone_set('PRC');
// 皮肤名称存放在template/skin/default
define("__SKIN_NAME",'think');

// 通用的全局配置
$spConfig = array(
	"db" => array(
			'host' => '#DB_HOST#',
			'login' => '#DB_USER#',
			'password' => '#DB_PASSWORD#',
			'database' => '#DB_DBNAME#',
			'prefix' => '#DB_PREFIX#'
	),
	'lang' => array( 
		'en' => 'default', // 默认语言，这里英文为默认语言
		'cn' => APP_PATH."/lang/cn.php", // 中文
		'fr' => array("GoogleTranslate","en2fr"),  // 法语
	),
	'view' => array(
		'enabled' => TRUE, // 开启视图
		'config' =>array(
			'template_dir' => APP_PATH.'/template', // 模板目录
			'compile_dir' => APP_PATH.'/tmp', // 编译目录
			'cache_dir' => APP_PATH.'/tmp', // 缓存目录
			'left_delimiter' => '<{',  // smarty左限定符
			'right_delimiter' => '}>', // smarty右限定符
		),
		'debugging' => FALSE,
	),
	'controller_path' => APP_PATH.'/modules/'.WORKSPACE, //controller 的目录
	'model_path' => APP_PATH.'/lib', // 定义model类的路径
	'url' => array( // URL设置
		'url_path_info' => FALSE, // 是否使用path_info方式的URL
	),
);

// 载入SpeedPHP框架
require(SP_PATH."/SpeedPHP.php");
import("general.php");
