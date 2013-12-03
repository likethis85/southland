<?php
// 定义当前目录
define("APP_PATH",dirname(__FILE__));

if( true == @file_exists(APP_PATH.'/config.php') )exit();
$defaults = array(
	
	"DB_HOST" => "localhost",
	"DB_USER" => "root",
	"DB_PASSWORD" => "pass",
	"DB_DBNAME" => "meon",
	"DB_PREFIX" => "sc_",
		
		
	"SITENAME" => "SpeedCMS演示网站",
	"SITEINTRO" => "这是一个新的SpeedCMS网站",
	"COPYRIGHT" => "Copyright (C) SpeedCMS.",
	"SUBDOMAIN" => 0,
	"PATH_INFO" => 0,
		
		
	"USERNAME" => "admin",
	"NICKNAME" => "admin",
	"SEX" => 0,
	"EMAIL" => "admin@horn.com",
	"PASSWORD" => "888888"
);
	
	
function ins_checkdblink($configs){
	global $dblink,$err;
	$dblink = mysql_connect($configs['DB_HOST'], $configs['DB_USER'], $configs['DB_PASSWORD']);
	if(false == $dblink){$err = '无法链接网站数据库，请检查网站数据库设置！';return false;}
	if(! mysql_select_db($configs['DB_DBNAME'], $dblink)){$err = '无法选择网站数据库，请确定网站数据库名称正确！'; return false;}
	ins_query("SET NAMES UTF8");
	return true;
}

function ins_query($sql,$prefix = ""){
	global $dblink,$err;
	$sqlarr = explode(";", $sql);
	foreach($sqlarr as $single){
		if( !empty($single) && strlen($single) > 5 ){
			$single = str_replace("\n",'',$single);
			$single = str_replace("#DBPREFIX#",$prefix,$single );
			if( !mysql_query($single, $dblink) ){$err = "数据库执行错误：".mysql_error().'</br>sql='.$single;return false;}
		}
	}
}

function ins_registeruser($configs, $prefix = "")	{
	global $dblink,$err,$adminsql;
	$password = md5($configs["PASSWORD"]);

	$ctime = date('Y-m-d H:i:s');
	$adminsql = "INSERT INTO `{$prefix}user` ( `uname`, `upass`, `nick`, `email`, `enabled`, `addtime`) VALUES ( '{$configs[USERNAME]}', '{$password}', '{$configs[NICKNAME]}', '{$configs[EMAIL]}', 1, '{$ctime}');";
	return true;

}

function ins_writeconfig($configs){
	$configex = file_get_contents(APP_PATH."/config_sample.php");
	foreach( $configs as $skey => $value ){
		$skey = "#".$skey."#";
		$configex = str_replace($skey, $value, $configex);
	}
	file_put_contents (APP_PATH."/config.php" ,$configex);
}


$sql = "

DROP TABLE IF EXISTS #DBPREFIX#log
;
DROP TABLE IF EXISTS #DBPREFIX#user
;
DROP TABLE IF EXISTS #DBPREFIX#userrole
;
DROP TABLE IF EXISTS #DBPREFIX#occupy
;
DROP TABLE IF EXISTS #DBPREFIX#project 
;
DROP TABLE IF EXISTS #DBPREFIX#forum
;
DROP TABLE IF EXISTS #DBPREFIX#task
;
DROP TABLE IF EXISTS #DBPREFIX#issue
;
DROP TABLE IF EXISTS #DBPREFIX#wiki
;
DROP TABLE IF EXISTS #DBPREFIX#keywords
;
DROP TABLE IF EXISTS #DBPREFIX#keywords_ref
;
DROP TABLE IF EXISTS #DBPREFIX#comment
;
DROP TABLE IF EXISTS #DBPREFIX#message
;
DROP TABLE IF EXISTS #DBPREFIX#attachment
;
DROP TABLE IF EXISTS #DBPREFIX#timeline
;
DROP TABLE IF EXISTS #DBPREFIX#subscriber
;

CREATE TABLE IF NOT EXISTS `#DBPREFIX#log` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `sessionid` varchar(32) NOT NULL DEFAULT '',
  `ip` varchar(16) NOT NULL DEFAULT '',
  `uri` varchar(100) NOT NULL DEFAULT '',
  `referer` varchar(255) NOT NULL DEFAULT '',
  `host` varchar(50) NOT NULL,
  `domain` varchar(40) NOT NULL,
  `navigationid` int(11) NOT NULL DEFAULT '0',
  `module` int(11) NOT NULL,
  `module_action` varchar(50) NOT NULL,
  `module_xid` int(11) NOT NULL COMMENT '记录newsid，新闻id等id',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `sessionid` (`sessionid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `#DBPREFIX#occupy` (
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `uid` int unsigned NOT NULL COMMENT 'user id the resource occupied by',
    `reason` text,
    `bytes` int unsigned NOT NULL,
    PRIMARY KEY(`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#DBPREFIX#project` (
  `id`          int unsigned NOT NULL AUTO_INCREMENT,
  `solution`    int unsigned NOT NULL DEFAULT 0 COMMENT 'Top concept for project orgnization',
  `status`      tinyint unsigned NOT NULL DEFAULT 0,
  `title`       varchar(256) NOT NULL,
  `description` text,
  `acl`         tinyint unsigned NOT NULL DEFAULT 1 COMMENT '0 for public, 1 for protected, 2 for private',
  `addtime`     timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `droptime`    timestamp DEFAULT 0 COMMENT 'NULL to indicate the project in processing',
  PRIMARY KEY(`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `#DBPREFIX#task` (
  `id`       int unsigned NOT NULL AUTO_INCREMENT,
  `pid`      int NOT NULL DEFAULT 0 COMMENT 'parent id of the task',
  `prj`      int NOT NULL DEFAULT 0 COMMENT 'project id of the task belong to',
  `acl`      tinyint unsigned NOT NULL DEFAULT 1 COMMENT '0 for public, 1 for protected, 2 for private',
  `category` tinyint unsigned NOT NULL default 0,
  `priority` tinyint unsigned NOT NULL DEFAULT 1,
  `status`   tinyint unsigned NOT NULL DEFAULT 0,
  `subject`  varchar(256) NOT NULL,
  `detail`   text,
  `addtime`  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `droptime`    timestamp DEFAULT 0 COMMENT 'NULL to indicate the project in processing',
  PRIMARY KEY(`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#DBPREFIX#issue` (
  `id`       int unsigned NOT NULL AUTO_INCREMENT,
  `prj`      int unsigned NOT NULL,
  `tid`      int unsigned NOT NULL default 0,
  `acl`      tinyint unsigned NOT NULL DEFAULT 1 COMMENT '0 for public, 1 for protected, 2 for private',
  `priority` tinyint unsigned NOT NULL DEFAULT 1,
  `status`   tinyint unsigned NOT NULL default 0,
  `brief`    varchar(256) NOT NULL,
  `detail`   text,
  `addtime`  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `droptime` timestamp DEFAULT 0 COMMENT 'NULL to indicate the project in processing',
  PRIMARY KEY(`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `#DBPREFIX#wiki` (
  `id`  int unsigned NOT NULL AUTO_INCREMENT,
  `uid` int unsigned NOT NULL,
  `prj` int unsigned NOT NULL,
  `acl` tinyint unsigned NOT NULL DEFAULT 1 COMMENT '0 for public, 1 for protected, 2 for private',
  `subject` varchar(256) NOT NULL,
  `summary` text NOT NULL,
  `content` mediumtext NOT NULL,
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `droptime` timestamp DEFAULT 0 COMMENT 'NULL to indicate the project in processing',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#DBPREFIX#keywords` (
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `ref_count` int unsigned NOT NULL DEFAULT 0,
    `content`   varchar(256) NOT NULL,
    PRIMARY KEY(`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#DBPREFIX#keywords_ref` (
    `id`  int unsigned NOT NULL AUTO_INCREMENT,
    `prj` int unsigned NOT NULL DEFAULT 0,
    `scope` tinyint unsigned NOT NULL DEFAULT 0,
    `sid` int unsigned NOT NULL DEFAULT 0,
    `ref` int unsigned NOT NULL,
    PRIMARY KEY(`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#DBPREFIX#forum` (
  `id`          int unsigned NOT NULL AUTO_INCREMENT,
  `prj`         int unsigned NOT NULL DEFAULT 0,
  `author`      int unsigned NOT NULL,
  `acl`         tinyint unsigned NOT NULL DEFAULT 1 COMMENT '0 for public, 1 for protected, 2 for private',
  `commentable` tinyint unsigned NOT NULL default '1',
  `subject`     varchar(256) NOT NULL,
  `content`     text NOT NULL,
  `addtime`     timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `droptime`    timestamp DEFAULT 0 COMMENT 'NULL to indicate the project in processing',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `#DBPREFIX#message` (
    `id`     int unsigned NOT NULL AUTO_INCREMENT,
    `sender` int unsigned NOT NULL DEFAULT 0 COMMENT '0 indicates system',
    `receiver` int unsigned NOT NULL,
    `msgtype`  tinyint unsigned NOT NULL default 0,
    `subject`  varchar(256) NOT NULL,
    `msgbody`  text,
    `status`   tinyint unsigned NOT NULL default 0,
    PRIMARY KEY(`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `#DBPREFIX#comment` (
  `id`      int unsigned NOT NULL AUTO_INCREMENT,
  `uid`     int unsigned NOT NULL COMMENT 'user who create the comment',
  `prj`     int unsigned NOT NULL default 0,
  `scope`   tinyint unsigned NOT NULL,
  `sid`     int unsigned NOT NULL default 0,
  `content` mediumtext,
  `visible` tinyint unsigned NOT NULL default 1,
  `scorer`  smallint NOT NULL default 0,
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `droptime`    timestamp DEFAULT 0 COMMENT 'NULL to indicate the project in processing',
  PRIMARY KEY(`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `#DBPREFIX#attachment` (
    `id`    int unsigned NOT NULL AUTO_INCREMENT,
    `uid`   int unsigned NOT NULL,
    `prj`   int unsigned NOT NULL DEFAULT 0,
    `scope` tinyint unsigned NOT NULL,
    `sid`   int unsigned NOT NULL,
    `oname` varchar(256),
    `path`  varchar(256),
    PRIMARY KEY(`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `#DBPREFIX#timeline` (
  `id`    int unsigned NOT NULL AUTO_INCREMENT,
  `uid`   int unsigned NOT NULL,
  `prj`   int unsigned NOT NULL DEFAULT 0,
  `scope` tinyint unsigned NOT NULL,
  `sid`   int unsigned NOT NULL,
  `brief` varchar(256) NOT NULL,
  `content` text DEFAULT NULL,
  `stime`       timestamp NOT NULL DEFAULT 0 COMMENT 'event start time',
  `etime`       timestamp NOT NULL DEFAULT 0 COMMENT 'event end time',
  `addtime`     timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `droptime`    timestamp DEFAULT 0 COMMENT 'NULL to indicate in processing',
  PRIMARY KEY(`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `#DBPREFIX#subscriber` (
  `uid` int unsigned NOT NULL,
  `utype` tinyint unsigned NOT NULL DEFAULT 0 COMMENT 'site user,group user,etc',
  `scope` tinyint unsigned NOT NULL,
  `sid`   int unsigned NOT NULL,
  `depth` tinyint NOT NULL DEFAULT 1,
  `writable` tinyint NOT NULL DEFAULT 0,
  `addtime`     timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `droptime`    timestamp DEFAULT 0 COMMENT 'NULL to indicate in processing',
  PRIMARY KEY(`uid`,`utype`,`scope`,`sid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `#DBPREFIX#user` (
  `id`    int unsigned NOT NULL AUTO_INCREMENT,
  `uname` varchar(20) NOT NULL,
  `upass` varchar(32) NOT NULL,
  `nick`  varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL default 'avatar.png',
  `qq`     varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `enabled` tinyint(4) NOT NULL DEFAULT '0',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY(`id`),
  PRIMARY KEY (`uname`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
INSERT INTO `#DBPREFIX#user`(`id`,`uname`,`upass`,`nick`,`email`,`enabled`) VALUES
(1,'eon','1a1dc91c907325c69271ddf0c944bc72','eon hong','eon.hong@gmail.com',1),
(2,'issach80','21218cca77804d2ba1922c33e0151105','issach80','issach80@gmail.com',1);


CREATE TABLE IF NOT EXISTS `#DBPREFIX#userrole` (
    `id`    int unsigned NOT NULL AUTO_INCREMENT,
    `prj`   int unsigned NOT NULL COMMENT 'project id the recorder belongs to',
    `uid`   int unsigned NOT NULL COMMENT 'user id',
    `title`  varchar(64) COMMENT 'screen name for the role',
    `scope` tinyint unsigned NOT NULL COMMENT 'for project,task,issues,etc',
    `sid`   int unsigned NOT NULL COMMENT 'id for scope',
    `role`  tinyint unsigned NOT NULL default 0,
    `addtime`     timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `droptime`    timestamp DEFAULT 0 COMMENT 'NULL to indicate in processing',
    KEY(`id`),
    PRIMARY KEY(`uid`,`sid`,`scope`,`role`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

";


if( empty($_GET["step"]) || 1 == $_GET["step"]  ){
	// 第一步，检查更新
	require(APP_PATH.'/template/install/step1.html');
}elseif( 2 == $_GET["step"] ){
	// 第二步，填写资料
	$tips = $defaults;
	require(APP_PATH.'/template/install/step2.html');
}else{
	// 第三步，验证资料，写入资料，完成安装
	$dblink = null;$err=null;$adminsql = null;
	while(1){
		// 检查本地数据库设置
		ins_checkdblink($_POST);if( null != $err )break;
		// 增加管理员用户
		ins_registeruser($_POST,$_POST["DB_PREFIX"]);if( null != $err )break;
		// 本地数据库入库
		$sql .= $adminsql;
		ins_query($sql,$_POST["DB_PREFIX"]);if( null != $err )break;
		// 改写本地配置文件
		ins_writeconfig($_POST);if( null != $err )break;
		break;
	}
	if( null != $err ){ // 有错误则覆盖
		$tips = array_merge($defaults, $_POST); // 显示原值或新值
		require(APP_PATH.'/template/install/step2.html');
	}else{
		require(APP_PATH.'/template/install/step3.html');
	}
}
