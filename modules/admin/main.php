<?php
if (!defined('SOUTHLAND')) { exit(1);}
import ('general.php');

/**
 * 管理员系统管理控制器
 * @author Harrie
 * @version 1.0
 * @created 2010-06-28
 */
class main extends general
{
	public function __construct(){ // 公用
		parent::__construct(); // 这是必须的
		$this->tpl_title = T("Dashboard");
		$this->navigation_current = 'main';
	}
	
	public function index(){ // 这里是首页
		$this->contents = "后台模块首页内容";
		$this->tREADME = nl2br(file_get_contents(APP_PATH.'/README'));
		$this->display("admin/main_index.html");
	}
	
	public function recentactions(){ // ajax: recent actions
		$this->display("admin/main_page.html");
	}

	public function recentpageview(){ // ajax: recent pageview
		$objLog = spClass("logModel");
		$this->tList = $objLog->findSQL('SELECT '.$GLOBALS['spConfig']['db']['prefix'].'log.*,'.$GLOBALS['spConfig']['db']['prefix'].'navigation.name FROM `'.$GLOBALS['spConfig']['db']['prefix'].'log` left join '.$GLOBALS['spConfig']['db']['prefix'].'navigation on '.$GLOBALS['spConfig']['db']['prefix'].'log.navigationid = '.$GLOBALS['spConfig']['db']['prefix'].'navigation.nid order by '.$GLOBALS['spConfig']['db']['prefix'].'log.id desc limit 15');
		$this->display("admin/main_recentpageview.html");
	}
	
	public function language(){
		$mylang = $this->spArgs("lang","cn"); // 默认是英语
		$this->setLang($mylang);
		$this->mylang = $mylang;
		echo "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"><script>function sptips(){history.go(-1);}</script></head><body onload=\"sptips()\"></body></html>";
		exit;
	}
}
