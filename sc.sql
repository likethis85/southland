-- MySQL dump 10.11
--
-- Host: localhost    Database: speedcms
-- ------------------------------------------------------
-- Server version	5.0.95

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `sc_acl`
--

DROP TABLE IF EXISTS `sc_acl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sc_acl` (
  `aclid` int(11) NOT NULL auto_increment,
  `name` varchar(200) collate utf8_unicode_ci NOT NULL,
  `workspace` varchar(64) collate utf8_unicode_ci default NULL,
  `controller` varchar(50) collate utf8_unicode_ci NOT NULL,
  `action` varchar(50) collate utf8_unicode_ci NOT NULL,
  `acl_name` varchar(50) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`aclid`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_acl`
--

LOCK TABLES `sc_acl` WRITE;
/*!40000 ALTER TABLE `sc_acl` DISABLE KEYS */;
INSERT INTO `sc_acl` VALUES (24,'Login',NULL,'user','login','ANONYMOUS'),(25,'Signon',NULL,'user','signon','ANONYMOUS'),(26,'FirstPage',NULL,'main','index','ANONYMOUS');
/*!40000 ALTER TABLE `sc_acl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_comment`
--

DROP TABLE IF EXISTS `sc_comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sc_comment` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `prj` int(11) NOT NULL default '0',
  `owner` varchar(32) NOT NULL,
  `rid` int(11) NOT NULL default '0',
  `content` text,
  `visible` tinyint(4) NOT NULL default '1',
  `scorer` tinyint(4) NOT NULL default '0',
  `addtime` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_comment`
--

LOCK TABLES `sc_comment` WRITE;
/*!40000 ALTER TABLE `sc_comment` DISABLE KEYS */;
INSERT INTO `sc_comment` VALUES (6,11,5,'task',1,'<p>另外一种方法是，ZC发现meeting开起来后就去选一个TA，如果所选中的TA掉线了，那就再找一个新的，直到MMR通知ZC，TA已经加会了才停止选择。</p>\r\n',1,0,'2013-03-21 10:37:07'),(7,11,5,'task',1,'<p>用新的方法，ZC在邀请一个TA的时候，可以一直等待对方应答直到TA掉线。</p>\r\n\r\n<p>需要处理的异常情况是：</p>\r\n\r\n<ol>\r\n	<li>邀请TA的时候，没有满足条件的TA供选择，则需要MMR设定一个定时器，定时去邀请TA，直到满足条件的TA被找到。</li>\r\n</ol>\r\n',1,0,'2013-03-21 11:05:05'),(8,11,5,'task',2,'<p>对于item 1， 在roster manager中为所有与会人员创建链表，记录加入和离开的tick，待关会的时候做统计，该功能只在top mmr处理。</p>\r\n',1,0,'2013-03-22 10:27:24'),(9,11,5,'task',1,'<p>如果TA在响应邀请之后，由于某种原因导致无法加会，TA通知ZC重新执行选择逻辑</p>\r\n',1,0,'2013-03-28 03:36:16');
/*!40000 ALTER TABLE `sc_comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_forum`
--

DROP TABLE IF EXISTS `sc_forum`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sc_forum` (
  `id` int(11) NOT NULL auto_increment,
  `prj` int(11) NOT NULL default '0',
  `author` int(1) NOT NULL,
  `commentable` tinyint(4) NOT NULL default '1',
  `subject` varchar(256) NOT NULL,
  `content` text NOT NULL,
  `addtime` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_forum`
--

LOCK TABLES `sc_forum` WRITE;
/*!40000 ALTER TABLE `sc_forum` DISABLE KEYS */;
/*!40000 ALTER TABLE `sc_forum` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_issue`
--

DROP TABLE IF EXISTS `sc_issue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sc_issue` (
  `id` int(11) NOT NULL auto_increment,
  `prj` int(11) NOT NULL,
  `brief` varchar(256) NOT NULL,
  `detail` text,
  `owner` int(11) NOT NULL,
  `assigner` int(11) NOT NULL,
  `reporter` int(11) NOT NULL,
  `addtime` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_issue`
--

LOCK TABLES `sc_issue` WRITE;
/*!40000 ALTER TABLE `sc_issue` DISABLE KEYS */;
/*!40000 ALTER TABLE `sc_issue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_log`
--

DROP TABLE IF EXISTS `sc_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sc_log` (
  `id` int(11) NOT NULL auto_increment,
  `sessionid` varchar(32) NOT NULL default '',
  `ip` varchar(16) NOT NULL default '',
  `uri` varchar(100) NOT NULL default '',
  `referer` varchar(255) NOT NULL default '',
  `host` varchar(50) NOT NULL,
  `domain` varchar(40) NOT NULL,
  `navigationid` int(11) NOT NULL default '0',
  `module` int(11) NOT NULL,
  `module_action` varchar(50) NOT NULL,
  `module_xid` int(11) NOT NULL COMMENT '记录newsid，新闻id等id',
  `addtime` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `sessionid` (`sessionid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_log`
--

LOCK TABLES `sc_log` WRITE;
/*!40000 ALTER TABLE `sc_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `sc_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_module`
--

DROP TABLE IF EXISTS `sc_module`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sc_module` (
  `mid` int(11) NOT NULL auto_increment COMMENT 'module id',
  `mname_en` varchar(255) NOT NULL,
  `mname_cn` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL,
  `menabled` int(11) NOT NULL default '1',
  PRIMARY KEY  (`mid`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_module`
--

LOCK TABLES `sc_module` WRITE;
/*!40000 ALTER TABLE `sc_module` DISABLE KEYS */;
INSERT INTO `sc_module` VALUES (1,'News','新闻','news',1),(2,'Order Form','预定表单','order',1),(3,'Contact Us','联系表单','contact',1),(4,'Forum','论坛','forum',1),(5,'Task','任务','task',1),(6,'Issue','问题','issue',1),(7,'Project','项目','project',1);
/*!40000 ALTER TABLE `sc_module` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_navigation`
--

DROP TABLE IF EXISTS `sc_navigation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sc_navigation` (
  `nid` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `content` text NOT NULL,
  `parentid` int(10) unsigned NOT NULL default '0',
  `position` tinyint(4) NOT NULL,
  `type` tinyint(4) NOT NULL default '0',
  `weight` int(10) NOT NULL default '0',
  `enabled` tinyint(1) NOT NULL default '1',
  `expanded` tinyint(4) NOT NULL,
  `module` int(11) NOT NULL,
  `is_del` tinyint(4) NOT NULL default '0',
  `addtime` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`nid`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_navigation`
--

LOCK TABLES `sc_navigation` WRITE;
/*!40000 ALTER TABLE `sc_navigation` DISABLE KEYS */;
INSERT INTO `sc_navigation` VALUES (8,'ProjectDesc','ProjectDesc','',0,1,0,-49,1,1,7,0,'2010-07-20 19:50:54'),(9,'Task','Task','',0,1,0,-49,1,1,5,0,'2010-07-20 19:50:54'),(10,'Wiki','Wiki','',0,1,0,-49,0,1,1,0,'2010-07-20 19:50:54'),(11,'Topic','Topic','',0,1,0,-49,1,0,4,0,'2010-08-04 18:15:20'),(12,'BugTracker','BugTracker','',0,1,0,-48,1,0,6,0,'2010-07-20 19:50:54'),(13,'Source','Source','',0,1,0,-47,1,0,0,0,'2010-07-29 23:24:53'),(14,'Download','Download','',0,1,0,-47,0,0,0,0,'2010-07-29 23:24:53'),(15,'Manage','Manage','',0,1,0,-47,0,0,0,0,'2010-07-29 23:24:53'),(16,'关于我们','关于我们','',0,2,0,-49,1,0,0,0,'2010-07-20 19:27:11'),(17,'联系我们','联系我们','',0,2,0,-50,1,0,0,0,'2010-07-20 19:29:38'),(18,'Sitemap','','',0,0,0,0,0,0,0,0,'2010-07-17 04:55:03');
/*!40000 ALTER TABLE `sc_navigation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_news`
--

DROP TABLE IF EXISTS `sc_news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sc_news` (
  `newsid` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `enabled` tinyint(4) NOT NULL default '1',
  `views` int(11) NOT NULL default '0',
  `addtime` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`newsid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_news`
--

LOCK TABLES `sc_news` WRITE;
/*!40000 ALTER TABLE `sc_news` DISABLE KEYS */;
/*!40000 ALTER TABLE `sc_news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_orderform`
--

DROP TABLE IF EXISTS `sc_orderform`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sc_orderform` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `tel` varchar(100) NOT NULL,
  `mealdate` date NOT NULL,
  `timearrive` varchar(255) NOT NULL,
  `encountertype` varchar(255) NOT NULL,
  `mealorder` text NOT NULL,
  `extranotes` text NOT NULL,
  `is_delete` tinyint(4) NOT NULL default '0',
  `addtime` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_orderform`
--

LOCK TABLES `sc_orderform` WRITE;
/*!40000 ALTER TABLE `sc_orderform` DISABLE KEYS */;
/*!40000 ALTER TABLE `sc_orderform` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_project`
--

DROP TABLE IF EXISTS `sc_project`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sc_project` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `description` text,
  `addtime` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `status` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_project`
--

LOCK TABLES `sc_project` WRITE;
/*!40000 ALTER TABLE `sc_project` DISABLE KEYS */;
INSERT INTO `sc_project` VALUES (5,11,'zoom 1.5','<p>4月底发布，主要是PSTN的功能实现</p>\r\n','2013-03-22 02:20:40',0);
/*!40000 ALTER TABLE `sc_project` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_settings`
--

DROP TABLE IF EXISTS `sc_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sc_settings` (
  `skey` varchar(255) NOT NULL default '',
  `stype` enum('string','array') NOT NULL default 'string',
  `svalue` text NOT NULL,
  PRIMARY KEY  (`skey`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_settings`
--

LOCK TABLES `sc_settings` WRITE;
/*!40000 ALTER TABLE `sc_settings` DISABLE KEYS */;
INSERT INTO `sc_settings` VALUES ('title_en','string','SpeedCMS demo website'),('title_cn','string','SpeedCMS演示网站'),('metadescription_en','string','SpeedCMS is base on speedphp'),('metadescription_cn','string','SpeedCMS网站基于speedphp'),('metakeywords_en','string','yancreate,speedcms,speedphp'),('metakeywords_cn','string','岩创网络,speedcms,speedphp'),('copyrightshow','string','1');
/*!40000 ALTER TABLE `sc_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_task`
--

DROP TABLE IF EXISTS `sc_task`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sc_task` (
  `id` int(11) NOT NULL auto_increment,
  `pid` int(11) NOT NULL default '0',
  `assigner` int(11) NOT NULL default '0',
  `prj` int(11) NOT NULL default '0',
  `owner` int(11) NOT NULL,
  `priority` tinyint(4) NOT NULL default '1',
  `status` tinyint(4) unsigned default NULL,
  `subject` varchar(256) NOT NULL,
  `detail` text,
  `addtime` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `category` int(10) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_task`
--

LOCK TABLES `sc_task` WRITE;
/*!40000 ALTER TABLE `sc_task` DISABLE KEYS */;
INSERT INTO `sc_task` VALUES (1,0,0,5,11,1,128,'ZC对TA的选择逻辑','<ol>\r\n	<li>MMR请求ZC选择一个可用TA供Meeting使用</li>\r\n	<li>ZC收到请求后，选择一个可用的TA，并发送请求给该TA</li>\r\n	<li>TA完成自身业务逻辑后，响应ZC请求</li>\r\n	<li>ZC收到来自TA的正确响应后，通知MMR</li>\r\n	<li>MMR收到ZC的响应，停止请求动作，TA选择完成</li>\r\n</ol>\r\n','2013-03-29 06:08:55',1),(2,0,0,5,11,1,128,'关会时候，添加新的统计信息','<ol>\r\n	<li>Meeting attendee list : like &ldquo;bill.lu;flyer.li&rdquo;</li>\r\n	<li>Sessions in meeting: which session is created in this meeting.</li>\r\n	<li>Accurate meeting minute. We discussed it before.</li>\r\n</ol>\r\n','2013-03-28 01:22:32',1),(3,0,0,5,11,2,255,'无host功能的用户过滤','<p>对于H323, PSTN, PSTN-GW，MMR等虚拟用户将无法指派Host权限，一次，在一个非JBH的meeting中，将出现无Host的情况。</p>\r\n','2013-03-28 01:22:28',1),(4,0,0,5,11,2,NULL,'对roster info进行封装，以方便应用层对基于session的特性操作','<p>比如conf中的roster info有user name,address,pstn等属性，而其它session中没有，提供conf的roster info封装，应用层就可以方便，统一的从基础数据中获得特性。</p>\r\n','2013-03-28 05:53:06',1),(5,0,0,5,11,2,NULL,'TA用户加会后，roster的广播问题','<ol>\r\n	<li>为了兼容老client，TA用户不在conf中广播，所以，界面上不会显示该用户</li>\r\n	<li>Audio session 需要广播该用户</li>\r\n</ol>\r\n','2013-03-28 09:44:24',1),(6,0,0,5,11,2,NULL,'PSTN的用户广播','<ol>\r\n	<li>未绑定用户，当普通用户处理</li>\r\n	<li>绑定用户，不在所有session中广播该用户，并且不算人数</li>\r\n	<li>绑定用户解除绑定，广播该用户</li>\r\n</ol>\r\n','2013-03-28 09:48:06',1),(7,0,0,5,11,1,NULL,'JBH会议，通知web host没有与会','<p>目前只是打了一个trace，没有实际的通知，需要client定义该动作</p>\r\n','2013-03-29 03:25:12',1),(8,0,0,5,11,2,128,'用户主动关会后，正常断连接的离会当做正常离会来处理','<p>目前client发送关会请求后，就直接断连接离会了，server在收到close后，只是通知所有用户离会，但是发送关会命令的用户由于是直接离会，server会当做failover处理，这种情况下meeting会延迟2分钟关闭，解决方案是对于主动断连接的离会，在close状态下当做正常离会处理。</p>\r\n','2013-03-29 06:08:43',2);
/*!40000 ALTER TABLE `sc_task` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_user`
--

DROP TABLE IF EXISTS `sc_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sc_user` (
  `uid` int(11) NOT NULL auto_increment,
  `uname` varchar(20) NOT NULL,
  `upass` varchar(32) NOT NULL,
  `acl` varchar(10) NOT NULL default 'WEBUSER',
  `nick` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `street` varchar(255) default NULL,
  `city` varchar(255) default NULL,
  `country` varchar(255) default NULL,
  `state` varchar(255) default NULL,
  `zip` varchar(11) default NULL,
  `tel` varchar(50) default NULL,
  `enabled` tinyint(4) NOT NULL default '0',
  `addtime` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_user`
--

LOCK TABLES `sc_user` WRITE;
/*!40000 ALTER TABLE `sc_user` DISABLE KEYS */;
INSERT INTO `sc_user` VALUES (1,'issac','21218cca77804d2ba1922c33e0151105','WEBUSER','issac hong','issac.hong@zoom.us',NULL,NULL,NULL,NULL,NULL,NULL,1,'2013-03-20 06:58:39'),(2,'issach80','21218cca77804d2ba1922c33e0151105','WEBUSER','issach80','issach80@gmail.com',NULL,NULL,NULL,NULL,NULL,NULL,1,'2013-03-20 06:58:39'),(3,'admin','21218cca77804d2ba1922c33e0151105','WEBMASTER','admin','admin@horn.com','','','','','','',1,'2013-03-20 06:58:39'),(11,'eon','1a1dc91c907325c69271ddf0c944bc72','WEBUSER','eon','eon.hong@gmail.com',NULL,NULL,NULL,NULL,NULL,NULL,1,'2013-03-20 07:51:51'),(10,'1','c4ca4238a0b923820dcc509a6f75849b','WEBUSER','1','issach80@gmail.com',NULL,NULL,NULL,NULL,NULL,NULL,1,'2013-03-20 07:49:37');
/*!40000 ALTER TABLE `sc_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_usergroup`
--

DROP TABLE IF EXISTS `sc_usergroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sc_usergroup` (
  `gid` int(11) NOT NULL auto_increment,
  `acl` varchar(255) NOT NULL,
  `gname_cn` varchar(255) NOT NULL,
  `gdescription_cn` varchar(255) NOT NULL,
  `gname_en` varchar(255) NOT NULL,
  `gdescription_en` varchar(255) NOT NULL,
  PRIMARY KEY  (`gid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_usergroup`
--

LOCK TABLES `sc_usergroup` WRITE;
/*!40000 ALTER TABLE `sc_usergroup` DISABLE KEYS */;
INSERT INTO `sc_usergroup` VALUES (1,'EDITOR','编辑','编辑人员能编辑一些内容和导航','Editor','Editor could edit content and edit navigation'),(2,'WEBMASTER','网站管理员','网站管理员能管理用户，编辑内容和导航','Webmaster','Webmaster could manage user, manage content and manage navigation');
/*!40000 ALTER TABLE `sc_usergroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_userorg`
--

DROP TABLE IF EXISTS `sc_userorg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sc_userorg` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL COMMENT 'project id the recorder belongs to',
  `uid` int(10) unsigned NOT NULL COMMENT 'user id',
  `sid` int(10) unsigned NOT NULL COMMENT 'id for scope',
  `scope` tinyint(3) unsigned NOT NULL COMMENT 'for project,task,issues,etc',
  `role` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_userorg`
--

LOCK TABLES `sc_userorg` WRITE;
/*!40000 ALTER TABLE `sc_userorg` DISABLE KEYS */;
/*!40000 ALTER TABLE `sc_userorg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_wiki`
--

DROP TABLE IF EXISTS `sc_wiki`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sc_wiki` (
  `id` int(11) NOT NULL auto_increment,
  `firstauthor` varchar(64) NOT NULL,
  `historyauthor` varchar(64) NOT NULL,
  `lastauthor` varchar(64) NOT NULL,
  `category` varchar(32) NOT NULL,
  `brief` varchar(256) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_wiki`
--

LOCK TABLES `sc_wiki` WRITE;
/*!40000 ALTER TABLE `sc_wiki` DISABLE KEYS */;
/*!40000 ALTER TABLE `sc_wiki` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-03-31 18:52:18
